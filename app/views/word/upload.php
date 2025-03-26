<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4 fw-bold">📄 Trích xuất từ vựng từ file PDF bằng AI</h2>

    <!-- Form gửi file PDF -->
    <form method="POST" action="/Learning_Vocab/word/extractFromPdf" enctype="multipart/form-data" class="card p-4 shadow-sm mb-5">
        <div class="mb-3">
            <label class="form-label">📎 Chọn file PDF:</label>
            <input type="file" name="pdf_file" accept="application/pdf" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">🔢 Số lượng từ vựng cần trích:</label>
            <input type="number" name="num_words" min="1" max="100" value="10" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">📤 Gửi đến AI</button>
    </form>

    <!-- Nếu có kết quả từ AI -->
    <?php if (isset($aiResponse) && count($aiResponse)): ?>
        <div class="card p-4 shadow-sm">
            <h3 class="fw-bold mb-4">📑 Danh sách từ vựng đã trích từ AI</h3>

            <form method="POST" action="/Learning_Vocab/word/saveExtracted">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>🇬🇧 Tiếng Anh</th>
                                <th>🇻🇳 Tiếng Việt</th>
                                <th class="text-center">🛠️ Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="vocab-table">
                            <?php foreach ($aiResponse as $index => $pair): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="words[<?= $index ?>][english]" class="form-control" value="<?= htmlspecialchars($pair[0]) ?>" required>
                                    </td>
                                    <td>
                                        <input type="text" name="words[<?= $index ?>][vietnamese]" class="form-control" value="<?= htmlspecialchars($pair[1]) ?>" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow(this)">🗑️ Xoá</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">💾 Lưu các từ còn lại</button>
                </div>
            </form>
        </div>

        <script>
            function removeRow(btn) {
                btn.closest('tr').remove();
            }
        </script>
    <?php endif; ?>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
