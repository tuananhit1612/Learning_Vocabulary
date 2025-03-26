<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">📊 Kết quả luyện tập</h2>
        <p class="fs-5">Bạn đã trả lời đúng <strong class="text-success"><?= $correct ?>/<?= $total ?></strong> câu!</p>
    </div>

    <?php foreach ($results as $i => $r): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">🧠 Câu <?= $i + 1 ?>: <strong><?= htmlspecialchars($r['question']) ?></strong></h5>


                <?php if (strtolower(trim($r['selected'])) === strtolower(trim($r['correct']))): ?>

                    <div class="alert alert-success">
                        ✅ Chính xác! Bạn đã chọn: <strong><?= htmlspecialchars($r['selected']) ?></strong>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        ❌ Sai rồi. Bạn chọn: <strong><?= htmlspecialchars($r['selected']) ?></strong><br>
                        ✅ Đáp án đúng: <strong><?= htmlspecialchars($r['correct']) ?></strong>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="text-center mt-4">
        <a href="/Learning_Vocab/practice/start" class="btn btn-primary btn-lg me-2">🔁 Luyện lại</a>
        <a href="/Learning_Vocab/word" class="btn btn-outline-secondary btn-lg">📚 Quay về danh sách từ</a>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
