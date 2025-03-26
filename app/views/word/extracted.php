<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="card p-4 shadow-sm">
        <h2 class="fw-bold mb-4">📄 Xem lại danh sách từ trước khi lưu</h2>

        <form method="POST" action="/Learning_Vocab/word/saveExtracted">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="vocab-table">
                    <thead class="table-light">
                        <tr>
                            <th>🇬🇧 Tiếng Anh</th>
                            <th>🇻🇳 Tiếng Việt</th>
                            <th class="text-center">🛠️ Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
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
</div>

<script>
    function removeRow(btn) {
        btn.closest('tr').remove();
    }
</script>

<?php require 'app/views/layouts/footer.php'; ?>
