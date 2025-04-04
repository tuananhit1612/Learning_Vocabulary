<?php require 'app/views/layouts/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5 w-100" style="max-width: 500px;">
        <h2 class="text-center mb-4">🧠 Luyện tập từ vựng</h2>

        <form method="POST" action="/Learning_Vocab/practice/quiz">
            <!-- Chọn chế độ -->
            <div class="mb-3">
                <label class="form-label">🎮 Chọn chế độ luyện tập:</label>
                <select name="mode" class="form-select" required>
                    <option value="multiple">📝 Trắc nghiệm (mặc định)</option>
                    <option value="typing">⌨️ Gõ từ tiếng Anh</option>
                </select>
            </div>

            <!-- Nhập số lượng câu hỏi -->
            <div class="mb-3">
                <label class="form-label">📌 Số lượng câu hỏi:</label>
                <input type="number" name="num_questions" min="10" max="50" class="form-control" placeholder="Ví dụ: 10" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                🚀 Bắt đầu luyện tập
            </button>
        </form>


        <div class="text-center mt-3">
            <a href="/Learning_Vocab/word" class="btn btn-link">⬅ Quay lại danh sách từ</a>
        </div>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
