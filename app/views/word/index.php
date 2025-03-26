<?php require 'app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📚 Danh sách từ vựng</h2>
        <a href="/Learning_Vocab/word/add" class="btn btn-success">
            ➕ Thêm từ mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">🇬🇧 Tiếng Anh</th>
                    <th scope="col">🇻🇳 Tiếng Việt</th>
                    <th scope="col" class="text-center">⚙️ Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($words as $word): ?>
                    <tr>
                        <td><?= $word['id'] ?></td>
                        <td><?= htmlspecialchars($word['english_word']) ?></td>
                        <td><?= htmlspecialchars($word['vietnamese_word']) ?></td>
                        <td class="text-center">
                            <a href="/Learning_Vocab/word/edit/<?= $word['id'] ?>" class="btn btn-sm btn-warning me-2">
                                ✏️ Sửa
                            </a>
                            <a href="/Learning_Vocab/word/delete/<?= $word['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn chắc chắn muốn xoá từ này?')">
                                🗑️ Xoá
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
