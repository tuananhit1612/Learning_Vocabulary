<?php
require_once 'app/models/User.php';

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new User();
    }

    public function login() {
        require 'app/views/auth/login.php';
    }

    public function handleLogin() {
        if (isset($_POST['username'], $_POST['password'])) {
            $user = $this->model->getByUsername($_POST['username']);

            if ($user && $_POST['password'] === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: /Learning_Vocab/word");
                exit();
            } else {
                $error = "Sai tài khoản hoặc mật khẩu!";
                require 'app/views/auth/login.php';
            }
        }
    }

        public function logout() {
            session_destroy();
            header("Location: /Learning_Vocab/auth/login");
            exit();
        }
        public function register() {
        require 'app/views/auth/register.php';
    }

    public function handleRegister() {
        if (isset($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['api_key'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $confirm = $_POST['confirm_password'];
            $apiKey = trim($_POST['api_key']);

            if ($password !== $confirm) {
                $error = "Xác nhận mật khẩu không khớp.";
                require 'app/views/auth/register.php';
                return;
            }

            if ($this->model->getByUsername($username)) {
                $error = "Tên đăng nhập đã tồn tại.";
                require 'app/views/auth/register.php';
                return;
            }
            if (!$this->isValidApiKey($apiKey)) {
                $error = "❌ API Key không hợp lệ hoặc đã hết quota.";
                require 'app/views/auth/register.php';
                return;
            }
            $this->model->createUser($username, $password, $apiKey);
            $user = $this->model->getByUsername($username);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: /Learning_Vocab/word");
            exit();
        }
    }
    private function isValidApiKey($key) {
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$key";

        $postData = [
            "contents" => [[
                "parts" => [["text" => "Hi"]]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_POST, true);

        $res = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status === 200;
    }


}
