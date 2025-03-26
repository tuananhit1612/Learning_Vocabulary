<?php
require_once 'app/models/Word.php';

class PracticeController {
    private $model;

    public function __construct() {
        $this->model = new Word();
    }

    public function checkLogin(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: /Learning_Vocab/auth/login");
            exit();
        }
    }

    public function start() {
        $this->checkLogin();
        require 'app/views/practice/start.php';
    }

    public function quiz() {
        $this->checkLogin();

        if (isset($_POST['num_questions'])) {
            $userId = $_SESSION['user_id'];
            $limit = (int) $_POST['num_questions'];
            $mode = $_POST['mode'] ?? 'multiple';

            $words = $this->model->getRandomWords($limit, $userId);
            $questions = [];

            foreach ($words as $word) {
                if ($mode === 'multiple') {
                    $correct = $word['vietnamese_word'];
                    $choices = [$correct];
                    $others = $this->model->getWrongChoices($word['id'], 3, $userId);
                    foreach ($others as $w) {
                        $choices[] = $w['vietnamese_word'];
                    }
                    shuffle($choices);
                    $questions[] = [
                        'type' => 'multiple',
                        'direction' => 'en_to_vi',
                        'question' => $word['english_word'],
                        'correct' => $correct,
                        'choices' => $choices
                    ];
                } else {
                    $questions[] = [
                        'type' => 'typing',
                        'direction' => 'vi_to_en',
                        'question' => $word['vietnamese_word'],
                        'correct' => $word['english_word'],
                    ];
                }
            }

            $_SESSION['quiz'] = $questions;
            $_SESSION['quiz_results'] = [];
            header("Location: /Learning_Vocab/practice/play/0");
            exit();
        } else {
            echo "Vui lòng chọn số câu hỏi.";
        }
    }

    public function record() {
        $this->checkLogin();
        $data = json_decode(file_get_contents("php://input"), true);

        if ($data && isset($data['word'], $data['correct'], $data['chosen'])) {
            $userId = $_SESSION['user_id'];
            $isCorrect = strtolower(trim($data['correct'])) === strtolower(trim($data['chosen']));
            $wordId = $this->model->getWordIdByEnglish($data['word'], $userId); // 🔁 userId
            if ($wordId) {
                $this->model->updateProgress($userId, $wordId, $isCorrect ? 'learned' : 'review');
            }

            $_SESSION['quiz_results'][] = [
                'question' => $data['question'] ?? $data['word'],
                'word' => $data['word'],
                'correct' => $data['correct'],
                'selected' => $data['chosen']
            ];
        }

        http_response_code(204);
    }

    public function play($index = 0) {
        $this->checkLogin();

        if (!isset($_SESSION['quiz'])) {
            echo "Bạn chưa bắt đầu quiz.";
            return;
        }

        $questions = $_SESSION['quiz'];
        $index = (int) $index;

        if (!isset($questions[$index])) {
            header("Location: /Learning_Vocab/practice/result");
            exit();
        }

        $question = $questions[$index];
        require 'app/views/practice/play.php';
    }

    public function result() {
        $this->checkLogin();

        $results = $_SESSION['quiz_results'] ?? [];
        $total = count($results);
        $correct = count(array_filter($results, fn($r) => strtolower(trim($r['selected'])) === strtolower(trim($r['correct']))));

        unset($_SESSION['quiz']);
        unset($_SESSION['quiz_results']);

        require 'app/views/practice/result.php';
    }

    public function submit() {
        $this->checkLogin();

        if (!isset($_POST['questions'])) {
            echo "Không có dữ liệu bài làm.";
            return;
        }

        $userId = $_SESSION['user_id'];
        $results = [];
        $correctCount = 0;

        foreach ($_POST['questions'] as $q) {
            $word = $q['word'];
            $correct = $q['correct'];
            $chosen = $q['answer'] ?? null;

            $isCorrect = $chosen === $correct;
            if ($isCorrect) $correctCount++;

            $results[] = [
                'word' => $word,
                'correct' => $correct,
                'chosen' => $chosen,
                'is_correct' => $isCorrect
            ];

            $wordId = $this->model->getWordIdByEnglish($word, $userId);
            if ($wordId) {
                $this->model->updateProgress($userId, $wordId, $isCorrect ? 'learned' : 'review');
            }
        }

        require 'app/views/practice/result.php';
    }
}
