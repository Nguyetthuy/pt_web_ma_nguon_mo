<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm my-4">
        <div class="card-body">
          <h3 class="card-title mb-4 text-center">Thông tin cá nhân</h3>

          <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
              <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
              <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            </div>
          <?php endif; ?>
          
          <div class="text-center mb-4">
            <div class="d-inline-block position-relative">
              <label for="avatar_upload" style="cursor: pointer; display: inline-block; position: relative;">
                <?php if (!empty($user['avatar'])): ?>
                  <img src="<?php echo htmlspecialchars($user['avatar']); ?>" 
                       alt="Avatar" 
                       class="rounded-circle mb-2 profile-avatar-img" 
                       style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #0d6efd;">
                <?php else: ?>
                  <div class="rounded-circle mb-2 mx-auto d-inline-flex align-items-center justify-content-center bg-primary text-white profile-avatar-img" 
                       style="width: 120px; height: 120px; border: 4px solid #0d6efd;">
                    <i class="fas fa-user" style="font-size: 48px;"></i>
                  </div>
                <?php endif; ?>
                <div class="badge bg-secondary position-absolute rounded-circle p-2" 
                     style="bottom: 10px; right: 10px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                  <i class="fas fa-camera" style="font-size: 14px;"></i>
                </div>
              </label>
            </div>

            <h4 class="mb-1 mt-3"><?php echo htmlspecialchars($user['user_name'] ?? 'Chưa có tên'); ?></h4>
            <p class="text-muted mb-3"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

            <form action="<?php echo $base; ?>/index.php?route=profile&action=update_avatar" method="POST" enctype="multipart/form-data" id="avatar_form">
              <input type="file" name="avatar" id="avatar_upload" class="form-control" accept="image/*" style="display: none;">
              <button type="submit" class="btn btn-sm btn-outline-primary" id="upload_button" style="display: none;">
                <i class="fas fa-cloud-upload-alt"></i> Tải lên
              </button>
            </form>
          </div>

          <script>
            document.getElementById('avatar_upload').addEventListener('change', function() {
              if (this.files.length > 0) {
                // Tự động submit form ngay sau khi chọn file
                document.getElementById('avatar_form').submit();
                // Hoặc hiển thị nút tải lên nếu muốn:
                // document.getElementById('upload_button').style.display = 'inline-block';
              }
            });
          </script>

          <div class="row mb-3">
            <div class="col-sm-4">
              <strong>Họ và tên:</strong>
            </div>
            <div class="col-sm-8">
              <?php echo htmlspecialchars($user['user_name'] ?? 'Chưa có thông tin'); ?>
            </div>
          </div>

          <hr>

          <div class="row mb-3">
            <div class="col-sm-4">
              <strong>Email:</strong>
            </div>
            <div class="col-sm-8">
              <?php echo htmlspecialchars($user['email'] ?? 'Chưa có thông tin'); ?>
            </div>
          </div>

          <hr>

          <div class="row mb-3">
            <div class="col-sm-4">
              <strong>Số điện thoại:</strong>
            </div>
            <div class="col-sm-8">
              <?php echo htmlspecialchars($user['phone'] ?? 'Chưa có thông tin'); ?>
            </div>
          </div>

          <hr>

          <div class="row mb-3">
            <div class="col-sm-4">
              <strong>Vai trò:</strong>
            </div>
            <div class="col-sm-8">
              <span class="badge bg-primary">
                <?php 
                  $role = $user['role'] ?? 'student';
                  echo $role === 'student' ? 'Học viên' : ucfirst($role);
                ?>
              </span>
            </div>
          </div>

          <hr>

          <div class="d-grid gap-2 mt-4">
            <a href="<?php echo $base; ?>/index.php?route=home" class="btn btn-primary">
              <i class="fas fa-home"></i> Về trang chủ
            </a>
            <a href="<?php echo $base; ?>/index.php?route=logout" class="btn btn-outline-danger">
              <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>

