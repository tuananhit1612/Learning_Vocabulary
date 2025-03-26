<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">👋 Chào mừng đến với website học từ vựng tiếng Anh!</h2>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
            $model = new Word();
            $stats = $model->getLearningStats($_SESSION['user_id']);
        ?>
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <div class="card shadow text-center">
                    <div class="card-header bg-primary text-white fw-bold">
                        📊 Thống kê học tập
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3 text-start">
                            <li class="list-group-item"><strong>Tổng số từ:</strong> <?= $stats['total'] ?></li>
                            <li class="list-group-item"><strong>✅ Đã học:</strong> <?= $stats['learned'] ?></li>
                            <li class="list-group-item"><strong>🔁 Cần học lại:</strong> <?= $stats['review'] ?></li>
                            <li class="list-group-item"><strong>❌ Chưa học:</strong> <?= $stats['unlearned'] ?></li>
                        </ul>
                        <a href="/Learning_Vocab/practice/start" class="btn btn-success">🚀 Luyện tập ngay</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center">
            <p class="fs-5">🎯 Hãy <a href="/Learning_Vocab/auth/login" class="fw-bold text-decoration-underline">đăng nhập</a> để bắt đầu học từ vựng nhé!</p>
        </div>
    <?php endif; ?>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
