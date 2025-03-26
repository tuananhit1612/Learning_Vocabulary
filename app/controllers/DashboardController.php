<?php
require_once 'app/models/Word.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new Word();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /Learning_Vocab/auth/login");
            exit();
        }

        $userId = $_SESSION['user_id'];

        $stats = $this->model->getLearningStats($userId);

        require 'app/views/dashboard/index.php';
    }
}
