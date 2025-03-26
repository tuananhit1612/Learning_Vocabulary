<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4">🎯 Bài luyện tập</h2>

    <form method="POST" action="/Learning_Vocab/practice/submit">
        <?php foreach ($questions as $index => $q): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📝 Câu <?= $index + 1 ?></h5>
                    <p class="card-text fs-5 fw-semibold">Từ: <span class="text-primary"><?= htmlspecialchars($q['word']) ?></span></p>

                    <input type="hidden" name="questions[<?= $index ?>][word]" value="<?= htmlspecialchars($q['word']) ?>">
                    <input type="hidden" name="questions[<?= $index ?>][correct]" value="<?= htmlspecialchars($q['correct_answer']) ?>">

                    <div class="list-group">
                        <?php foreach ($q['choices'] as $choice): ?>
                            <label class="list-group-item list-group-item-action">
                                <input type="radio" name="questions[<?= $index ?>][answer]" value="<?= htmlspecialchars($choice) ?>" class="form-check-input me-2" required>
                                <?= htmlspecialchars($choice) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">✅ Nộp bài</button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="/Learning_Vocab/practice/start" class="btn btn-link">🔁 Làm lại từ đầu</a>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
