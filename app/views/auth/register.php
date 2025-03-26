<?php require 'app/views/layouts/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm p-4">
            <h3 class="text-center mb-4">📝 Đăng ký tài khoản</h3>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="/Learning_Vocab/auth/handleRegister">
                <div class="mb-3">
                    <label class="form-label">👤 Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🔒 Mật khẩu</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🔒 Xác nhận mật khẩu</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">🔑 API Key (Gemini)</label>
                    <input type="text" name="api_key" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">🚀 Đăng ký</button>
            </form>

            <div class="text-center mt-3">
                <small>Đã có tài khoản? <a href="/Learning_Vocab/auth/login">Đăng nhập</a></small>
            </div>
        </div>
    </div>
</div>

<?php require 'app/views/layouts/footer.php'; ?>
