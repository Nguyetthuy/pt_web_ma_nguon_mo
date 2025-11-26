<?php
// tính base URL đến thư mục public (ví dụ: /flyhightenglish/public)
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FlyHighEnglish</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="<?php echo $base; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="<?php echo $base; ?>/index.php?route=home">FlyHighEnglish</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php?route=home">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php?route=courses">Khóa học</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php?route=home#contact">Liên hệ</a></li>
        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php?route=admin">Quản trị</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['user'])): ?>
          <!-- Người dùng đã đăng nhập - Hiển thị avatar -->
          <li class="nav-item dropdown ms-lg-2">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php 
                $avatar = $_SESSION['user']['avatar'] ?? null;
                if ($avatar): 
              ?>
                <img src="<?php echo htmlspecialchars($avatar); ?>" 
                     alt="Avatar" 
                     class="rounded-circle me-2" 
                     style="width: 32px; height: 32px; object-fit: cover; border: 2px solid white;">
              <?php else: ?>
                <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center bg-light text-primary" 
                     style="width: 32px; height: 32px; border: 2px solid white;">
                  <i class="fas fa-user" style="font-size: 14px;"></i>
                </div>
              <?php endif; ?>
              <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['user']['user_name'] ?? 'User'); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="<?php echo $base; ?>/index.php?route=profile">
                <i class="fas fa-user"></i> Thông tin cá nhân
              </a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?php echo $base; ?>/index.php?route=logout">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
              </a></li>
            </ul>
          </li>
        <?php else: ?>
          <!-- Người dùng chưa đăng nhập - Hiển thị nút đăng nhập -->
          <li class="nav-item">
            <a class="btn btn-light btn-sm ms-lg-2" href="<?php echo $base; ?>/index.php?route=login">
               <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">