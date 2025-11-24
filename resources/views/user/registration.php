<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">
      <div class="card shadow-sm my-4">
        <div class="card-body">
          <h3 class="card-title mb-3 text-center">Đăng ký thành viên</h3>

          <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($_SESSION['success_message']); ?>
              <?php unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($data['errors']['global'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($data['errors']['global']); ?>
            </div>
          <?php endif; ?>

          <form action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>?route=register" method="POST" novalidate>
            <div class="mb-3 form-floating">
              <input
                type="text"
                name="user_name"
                id="user_name"
                class="form-control <?php echo !empty($data['errors']['user_name']) ? 'is-invalid' : ''; ?>"
                placeholder="Họ và tên"
                value="<?php echo htmlspecialchars($data['old']['user_name'] ?? ''); ?>"
                required>
              <label for="user_name">Họ và tên</label>
              <?php if (!empty($data['errors']['user_name'])): ?>
                <div class="invalid-feedback d-block">
                  <?php echo htmlspecialchars($data['errors']['user_name']); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="email"
                name="email"
                id="email"
                class="form-control <?php echo !empty($data['errors']['email']) ? 'is-invalid' : ''; ?>"
                placeholder="Email"
                value="<?php echo htmlspecialchars($data['old']['email'] ?? ''); ?>"
                required>
              <label for="email">Email</label>
              <?php if (!empty($data['errors']['email'])): ?>
                <div class="invalid-feedback d-block">
                  <?php echo htmlspecialchars($data['errors']['email']); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="tel"
                name="phone"
                id="phone"
                class="form-control <?php echo !empty($data['errors']['phone']) ? 'is-invalid' : ''; ?>"
                placeholder="Số điện thoại"
                value="<?php echo htmlspecialchars($data['old']['phone'] ?? ''); ?>">
              <label for="phone">Số điện thoại (tùy chọn)</label>
              <?php if (!empty($data['errors']['phone'])): ?>
                <div class="invalid-feedback d-block">
                  <?php echo htmlspecialchars($data['errors']['phone']); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="password"
                name="password"
                id="password"
                class="form-control <?php echo !empty($data['errors']['password']) ? 'is-invalid' : ''; ?>"
                placeholder="Mật khẩu"
                required>
              <label for="password">Mật khẩu</label>
              <?php if (!empty($data['errors']['password'])): ?>
                <div class="invalid-feedback d-block">
                  <?php echo htmlspecialchars($data['errors']['password']); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="password"
                name="password_confirm"
                id="password_confirm"
                class="form-control <?php echo !empty($data['errors']['password_confirm']) ? 'is-invalid' : ''; ?>"
                placeholder="Xác nhận mật khẩu"
                required>
              <label for="password_confirm">Xác nhận mật khẩu</label>
              <?php if (!empty($data['errors']['password_confirm'])): ?>
                <div class="invalid-feedback d-block">
                  <?php echo htmlspecialchars($data['errors']['password_confirm']); ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
            </div>

            <div class="divider my-4">
              <span class="divider-text">hoặc</span>
            </div>

            <div class="d-grid">
              <a href="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>?route=google-login" class="btn btn-outline-danger btn-lg">
                <svg width="18" height="18" viewBox="0 0 18 18" style="margin-right: 8px; vertical-align: middle;">
                  <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                  <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.965-2.184l-2.908-2.258c-.806.54-1.837.86-3.057.86-2.35 0-4.34-1.587-5.053-3.72H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                  <path fill="#FBBC05" d="M3.947 10.698c-.18-.54-.282-1.117-.282-1.698s.102-1.158.282-1.698V4.97H.957C.348 6.175 0 7.55 0 9s.348 2.825.957 4.03l2.99-2.332z"/>
                  <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.582C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.97L3.947 7.302C4.66 5.167 6.65 3.58 9 3.58z"/>
                </svg>
                Đăng ký bằng Google
              </a>
            </div>

            <div class="text-center mt-3">
              <small>Đã có tài khoản? <a href="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>?route=login">Đăng nhập</a></small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>