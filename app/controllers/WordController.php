<?php
require_once 'app/models/Word.php';
require_once 'app/models/User.php';
require_once 'vendor/autoload.php';

class WordController {
    private $model;
    private $modelUser;

    public function __construct() {
        $this->model = new Word();
        $this->modelUser = new User();
    }

    public function checkLogin(){
        if (!isset($_SESSION['user_id'])) {
            header("Location: /Learning_Vocab/auth/login");
            exit();
        }
    }

    public function index() {
        $this->checkLogin();
        $userId = $_SESSION['user_id'];
        $words = $this->model->getAllWords($userId); // ✅ lấy theo user
        require 'app/views/word/index.php';
    }

    public function add() {
        $this->checkLogin();
        require 'app/views/word/add.php';
    }

    private function readPdfText($filePath) {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }

    public function extractFromPdf() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "⚠️ Truy cập sai phương thức.";
            exit();
        }

        if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== 0) {
            die('Lỗi khi tải file PDF');
        }

        if (!isset($_POST['num_words'])) {
            die("❌ Không có số lượng từ cần trích.");
        }

        $pdfPath = $_FILES['pdf_file']['tmp_name'];
        $text = $this->readPdfText($pdfPath);
        $numWords = $_POST['num_words'] ?? 10;

        $prompt = "Bạn là một trợ lý học từ vựng tiếng Anh.

        Dưới đây là nội dung một bài học hoặc bài kiểm tra. Hãy bỏ qua phần mở đầu, ví dụ như tiêu đề, đề bài, phần giới thiệu,... và tập trung lấy từ bảng từ vựng thường xuất hiện ở cuối.

        🎯 Yêu cầu
        - Trích ra {$numWords} từ vựng quan trọng nhất.
        - Ưu tiên từ có trong bảng từ vựng (thường ở cuối bài)
        - Dịch chuẩn từ tiếng Anh sang tiếng Việt
        - Không lấy câu hỏi, chỉ từ/cụm từ.

        📋 Định dạng: mỗi dòng
        từ tiếng Anh | nghĩa tiếng Việt

        Ví dụ:
        book | sách
        strategy | chiến lược";

        $fullPrompt = $prompt . "\n\n" . $text;
        $aiResponseText = $this->callGeminiAPI($fullPrompt);
        $aiResponse = $this->parseVocabularyFromText($aiResponseText);
        require 'app/views/word/upload.php';
    }

    public function upload() {
        $this->checkLogin();
        require 'app/views/word/upload.php';
    }

    private function callGeminiAPI($promptText) {
        $userId = $_SESSION['user_id'];
        $user = $this->modelUser->getUserById($userId);
        $apiKey = $user['api_key'];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

        $postData = [
            "contents" => [[
                "parts" => [[ "text" => $promptText ]]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return "";

        $json = json_decode($response, true);
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? '';
    }

    private function parseVocabularyFromText($text) {
        $lines = explode("\n", trim($text));
        $result = [];

        foreach ($lines as $line) {
            $line = preg_replace('/^\d+\.\s*/', '', $line);
            $parts = explode('|', $line);

            if (count($parts) === 2) {
                $eng = trim($parts[0]);
                $vie = trim($parts[1]);
                if ($eng && $vie) {
                    $result[] = [$eng, $vie];
                }
            }
        }

        return $result;
    }

    public function store() {
        $this->checkLogin();
        $userId = $_SESSION['user_id'];

        if (isset($_POST['english_word']) && isset($_POST['vietnamese_word'])) {
            $english = $_POST['english_word'];
            $vietnamese = $_POST['vietnamese_word'];
            $this->model->addWord($userId, $english, $vietnamese);
        }

        header("Location: /Learning_Vocab/word");
        exit();
    }

    public function saveExtracted() {
        $this->checkLogin();
        $userId = $_SESSION['user_id'];

        if (!isset($_POST['words'])) {
            die("❌ Không có từ nào được gửi.");
        }

        foreach ($_POST['words'] as $item) {
            $eng = trim($item['english']);
            $vie = trim($item['vietnamese']);
            if ($eng && $vie && !$this->model->isWordExists($eng, $userId)) {
                $this->model->addWord($userId, $eng, $vie);
            }
        }

        header("Location: /Learning_Vocab/word");
        exit();
    }

    public function edit($id = null) {
        $this->checkLogin();
        if ($id) {
            $word = $this->model->getWordById($id);
            if ($word && $word['user_id'] == $_SESSION['user_id']) {
                require 'app/views/word/edit.php';
            } else {
                echo "Không tìm thấy từ hoặc bạn không có quyền.";
            }
        } else {
            echo "Thiếu ID cần sửa.";
        }
    }

    public function update() {
        $this->checkLogin();
        if (isset($_POST['id'], $_POST['english_word'], $_POST['vietnamese_word'])) {
            $id = $_POST['id'];
            $english = $_POST['english_word'];
            $vietnamese = $_POST['vietnamese_word'];
            $this->model->updateWord($id, $english, $vietnamese);
        }
        header("Location: /Learning_Vocab/word");
        exit();
    }

    public function delete($id = null) {
        $this->checkLogin();
        if ($id) {
            $word = $this->model->getWordById($id);
            if ($word && $word['user_id'] == $_SESSION['user_id']) {
                $this->model->deleteWord($id);
            }
        }
        header("Location: /Learning_Vocab/word");
        exit();
    }
}
