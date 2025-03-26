<?php
require_once 'app/config/database.php';

class Word {
    private $conn;
    private $table = "words";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getPracticeWords($userId, $limit) {
        $query = "
            SELECT * FROM words
            WHERE user_id = :uid
            ORDER BY 
                FIELD(id, 
                    SELECT word_id FROM user_word_progress 
                    WHERE user_id = :uid AND status = 'unlearned'
                ) DESC,
                FIELD(id, 
                    SELECT word_id FROM user_word_progress 
                    WHERE user_id = :uid AND status = 'review'
                ) DESC,
                FIELD(id, 
                    SELECT word_id FROM user_word_progress 
                    WHERE user_id = :uid AND status = 'learned'
                ) DESC
            LIMIT :limit
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomWords($limit, $userId) {
        $query = "SELECT * FROM words WHERE user_id = :uid ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPracticeQuestions($userId, $limit = 10) {
        $query = "
            SELECT w.*, uw.status
            FROM words w
            LEFT JOIN user_word_progress uw 
                ON w.id = uw.word_id AND uw.user_id = :uid
            WHERE w.user_id = :uid
            ORDER BY 
                CASE
                    WHEN uw.status IS NULL THEN 0
                    WHEN uw.status = 'review' THEN 1
                    ELSE 2
                END,
                RAND()
            LIMIT " . (int)$limit;

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uid' => $userId]);
        $words = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $questions = [];

        foreach ($words as $word) {
            $correct = $word['vietnamese_word'];

            $fakeQuery = "SELECT vietnamese_word FROM words WHERE id != ? AND user_id = ? ORDER BY RAND() LIMIT 3";
            $fakeStmt = $this->conn->prepare($fakeQuery);
            $fakeStmt->execute([$word['id'], $userId]);
            $fakeAnswers = $fakeStmt->fetchAll(PDO::FETCH_COLUMN);

            $choices = array_merge($fakeAnswers, [$correct]);
            shuffle($choices);

            $questions[] = [
                'word' => $word['english_word'],
                'correct' => $correct,
                'choices' => $choices
            ];
        }

        return $questions;
    }

    public function isWordExists($english, $userId) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE english_word = :eng AND user_id = :uid";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':eng' => $english, ':uid' => $userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function getWrongChoices($excludeId, $limit, $userId) {
        $query = "SELECT * FROM words WHERE id != :id AND user_id = :uid ORDER BY RAND() LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWordIdByEnglish($english, $userId) {
        $query = "SELECT id FROM words WHERE english_word = :eng AND user_id = :uid LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':eng' => $english, ':uid' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : null;
    }

    public function updateProgress($userId, $wordId, $status) {
        $query = "SELECT * FROM user_word_progress WHERE user_id = :uid AND word_id = :wid LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uid' => $userId, ':wid' => $wordId]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            $query = "UPDATE user_word_progress SET status = :status WHERE user_id = :uid AND word_id = :wid";
        } else {
            $query = "INSERT INTO user_word_progress (user_id, word_id, status) VALUES (:uid, :wid, :status)";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':uid' => $userId,
            ':wid' => $wordId,
            ':status' => $status
        ]);
    }

    public function getLearningStats($userId) {
        $queryTotal = "SELECT COUNT(*) AS total FROM words WHERE user_id = :uid";
        $stmt = $this->conn->prepare($queryTotal);
        $stmt->execute([':uid' => $userId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $queryLearned = "SELECT COUNT(*) AS learned FROM user_word_progress WHERE user_id = :uid AND status = 'learned'";
        $stmt1 = $this->conn->prepare($queryLearned);
        $stmt1->execute([':uid' => $userId]);
        $learned = $stmt1->fetch(PDO::FETCH_ASSOC)['learned'];

        $queryReview = "SELECT COUNT(*) AS review FROM user_word_progress WHERE user_id = :uid AND status = 'review'";
        $stmt2 = $this->conn->prepare($queryReview);
        $stmt2->execute([':uid' => $userId]);
        $review = $stmt2->fetch(PDO::FETCH_ASSOC)['review'];

        $unlearned = $total - $learned - $review;

        return [
            'total' => $total,
            'learned' => $learned,
            'review' => $review,
            'unlearned' => $unlearned
        ];
    }

    public function getAllWords($userId) {
        $query = "SELECT * FROM words WHERE user_id = :uid ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWordById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addWord($userId, $english, $vietnamese) {
        $query = "INSERT INTO words (user_id, english_word, vietnamese_word) VALUES (:uid, :eng, :vie)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':uid' => $userId,
            ':eng' => $english,
            ':vie' => $vietnamese
        ]);
    }

    public function updateWord($id, $english, $vietnamese) {
        $query = "UPDATE " . $this->table . " SET english_word = :eng, vietnamese_word = :vie WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":eng", $english);
        $stmt->bindParam(":vie", $vietnamese);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function deleteWord($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
