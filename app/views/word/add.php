<?php require 'app/views/layouts/header.php'; ?>

<h2>➕ Thêm từ vựng thủ công hoặc bằng file PDF</h2>

<!-- Thêm từ thủ công -->
<form method="POST" action="/Learning_Vocab/word/store">
    <label>Tiếng Anh:</label><br>
    <input type="text" name="english_word" required><br><br>

    <label>Tiếng Việt:</label><br>
    <input type="text" name="vietnamese_word" required><br><br>

    <button type="submit">💾 Lưu</button>
</form>
<?php require 'app/views/layouts/footer.php'; ?>
