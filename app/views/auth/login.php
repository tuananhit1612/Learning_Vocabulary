<?php require 'app/views/layouts/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-4">🔐 Đăng nhập</h3>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="/Learning_Vocab/auth/handleLogin">
                <div class="mb-3">
                    <label class="form-label">👤 Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🔒 Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">🔓 Đăng nhập</button>
            </form>

            <div class="text-center mt-3">
                <small>❓ Chưa có tài khoản? <a href="/Learning_Vocab/auth/register">Đăng ký ngay</a></small>
            </div>
        </div>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
