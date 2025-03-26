<?php require 'app/views/layouts/header.php'; ?>

<?php
    $current = $index;
    $total = count($_SESSION['quiz']);
    $question = $_SESSION['quiz'][$index];

    $type = $question['type'] ?? 'multiple';           // multiple / typing
    $direction = $question['direction'] ?? 'en_to_vi'; // en_to_vi / vi_to_en
    $questionText = htmlspecialchars($question['question']);
    $correctAnswer = $question['correct'];
?>

<div class="container my-5">
    <!-- Thanh tiến trình -->
    <div class="mb-4">
        <div class="d-flex justify-content-between">
            <span class="badge bg-success rounded-pill px-3"><?= $current + 1 ?></span>
            <small><?= $total ?></small>
        </div>
        <div class="progress">
            <div class="progress-bar bg-info" role="progressbar"
                style="width: <?= ($current + 1) / $total * 100 ?>%"
                aria-valuenow="<?= $current + 1 ?>" aria-valuemin="0" aria-valuemax="<?= $total ?>">
            </div>
        </div>
    </div>

    <!-- Câu hỏi -->
    <div class="card shadow p-4">
        <h5 class="text-muted mb-2">Câu hỏi</h5>
        <h2 class="fw-bold text-primary mb-4">
            <?= $direction === 'en_to_vi' ? "👉 Từ: $questionText" : "👉 Nghĩa: $questionText" ?>
        </h2>

        <div class="alert d-none" id="feedback"></div>

        <?php if ($type === 'multiple'): ?>
            <div class="row row-cols-1 row-cols-md-2 g-3" id="choices">
                <?php foreach ($question['choices'] as $choice): ?>
                    <div class="col">
                        <button 
                            class="btn btn-outline-secondary w-100 py-3 choice-btn" 
                            data-answer="<?= htmlspecialchars($choice) ?>"
                        >
                            <?= htmlspecialchars($choice) ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <form id="typingForm">
                <div class="mb-3">
                    <input type="text" name="userAnswer" class="form-control form-control-lg"
                        placeholder="Nhập câu trả lời bằng <?= $direction === 'vi_to_en' ? 'tiếng Anh' : 'tiếng Việt' ?>..."
                        required>
                </div>
                <button class="btn btn-primary w-100">Gửi đáp án</button>
            </form>
        <?php endif; ?>

        <div class="text-end mt-4">
            <a href="/Learning_Vocab/practice/play/<?= $current + 1 ?>" class="btn btn-success d-none" id="nextBtn">Tiếp tục</a>
        </div>
    </div>
</div>

<script>
    const feedback = document.getElementById('feedback');
    const nextBtn = document.getElementById('nextBtn');
    const correctAnswer = <?= json_encode($question['correct']) ?>;
    const questionWord = <?= json_encode($question['direction'] === 'vi_to_en' ? $question['correct'] : $question['question']) ?>;
    const questionText = <?= json_encode($question['question']) ?>;

    <?php if ($type === 'multiple'): ?>
        // MULTIPLE CHOICE
        const buttons = document.querySelectorAll('.choice-btn');
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const userAnswer = btn.dataset.answer;

                buttons.forEach(b => {
                    b.disabled = true;
                    if (b.dataset.answer === correctAnswer) {
                        b.classList.remove('btn-outline-secondary');
                        b.classList.add('btn-success');
                    }
                    if (b.dataset.answer === userAnswer && userAnswer !== correctAnswer) {
                        b.classList.remove('btn-outline-secondary');
                        b.classList.add('btn-danger');
                    }
                });

                feedback.classList.remove('d-none');
                if (userAnswer === correctAnswer) {
                    feedback.textContent = "✅ Chính xác!";
                    feedback.className = "alert alert-success";
                } else {
                    feedback.innerHTML = `❌ Chưa đúng. Đáp án là: <strong>${correctAnswer}</strong>`;
                    feedback.className = "alert alert-danger";
                }

                nextBtn.classList.remove('d-none');

                fetch("/Learning_Vocab/practice/record", {
                    method: "POST",
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        word: questionText,
                        correct: correctAnswer,
                        chosen: userAnswer
                    })
                });
            });
        });

    <?php else: ?>
        // TYPING MODE
        document.getElementById("typingForm").onsubmit = function(e) {
            e.preventDefault();
            const input = this.userAnswer.value.trim();
            const isCorrect = input.toLowerCase() === correctAnswer.toLowerCase();

            feedback.classList.remove('d-none');
            if (isCorrect) {
                feedback.textContent = "✅ Chính xác!";
                feedback.className = "alert alert-success";
            } else {
                feedback.innerHTML = `❌ Chưa đúng. Đáp án là: <strong>${correctAnswer}</strong>`;
                feedback.className = "alert alert-danger";
            }

            this.querySelector("input").disabled = true;
            this.querySelector("button").disabled = true;
            nextBtn.classList.remove('d-none');

            fetch("/Learning_Vocab/practice/record", {
                method: "POST",
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    question: <?= json_encode($question['question']) ?>,
                    word: questionWord,
                    correct: correctAnswer,
                    chosen: input
                })
            });




        }
    <?php endif; ?>
</script>

<?php require 'app/views/layouts/footer.php'; ?>
