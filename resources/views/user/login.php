<?php
 include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm my-4">
        <div class="card-body">
          <h3 class="card-title mb-3 text-center">Đăng nhập</h3>

          <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($errors) || !empty($data['errors'])): ?>
            <div class="alert alert-danger">
              <?php
                $errs = $errors ?? $data['errors'] ?? [];
                foreach ($errs as $e) echo '<div>'.htmlspecialchars($e).'</div>';
              ?>
            </div>
          <?php endif; ?>

          <form action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>?route=login" method="POST" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control"
                     value="<?php echo htmlspecialchars($data['old']['email'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Mật khẩu</label>
              <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="d-grid mt-3">
              <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
            </div>

            <div class="text-center mt-3">
              <small>Chưa có tài khoản? <a href="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>?route=register">Đăng ký</a></small>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>