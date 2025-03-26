<h2>✏️ Sửa từ vựng</h2>
<form method="POST" action="/Learning_Vocab/word/update">

    <input type="hidden" name="id" value="<?= $word['id'] ?>">

    <label>Tiếng Anh:</label><br>
    <input type="text" name="english_word" value="<?= htmlspecialchars($word['english_word']) ?>" required><br><br>

    <label>Tiếng Việt:</label><br>
    <input type="text" name="vietnamese_word" value="<?= htmlspecialchars($word['vietnamese_word']) ?>" required><br><br>

    <button type="submit">💾 Cập nhật</button>
</form>
<a href="/Learning_Vocab/word/index">⬅ Quay lại</a>
