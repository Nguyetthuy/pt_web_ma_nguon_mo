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