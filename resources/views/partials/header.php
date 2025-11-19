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
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>/index.php?route=admin">Liên hệ</a></li>
        <li class="nav-item">
            <a class="btn btn-warning btn-sm ms-lg-2 mb-2 mb-lg-0" href="<?php echo $base; ?>/index.php?route=register">
               <i class="fas fa-user-plus"></i> Đăng ký
            </a>
        </li>
        <li class="nav-item">
            <a class="btn btn-light btn-sm ms-lg-2" href="<?php echo $base; ?>/index.php?route=login">
               <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">