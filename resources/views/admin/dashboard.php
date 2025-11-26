<?php include __DIR__ . '/../partials/header.php'; ?>

<?php
$statMeta = [
    'users'      => ['label' => 'Người dùng', 'icon' => 'fa-users', 'class' => 'text-primary'],
    'courses'    => ['label' => 'Khóa học', 'icon' => 'fa-graduation-cap', 'class' => 'text-success'],
    'materials'  => ['label' => 'Tài liệu', 'icon' => 'fa-book', 'class' => 'text-warning'],
    'slideshows' => ['label' => 'Slideshow', 'icon' => 'fa-images', 'class' => 'text-danger'],
];
?>

<div class="py-4">
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
      <p class="text-muted mb-1">Xin chào, <?php echo htmlspecialchars($admin['user_name'] ?? 'Admin'); ?></p>
      <h2 class="fw-bold">Bảng điều khiển quản trị</h2>
      <p class="text-muted mb-0">Theo dõi nhanh tình trạng người dùng, khóa học và nội dung.</p>
    </div>
    <a class="btn btn-outline-primary mt-3 mt-md-0" href="<?php echo $base; ?>/index.php?route=home">
      <i class="fa fa-home me-2"></i>Quay về trang chính
    </a>
  </div>

  <div class="row g-3">
    <?php foreach ($stats as $key => $value): 
      $meta = $statMeta[$key] ?? ['label' => ucfirst($key), 'icon' => 'fa-circle', 'class' => 'text-secondary'];
    ?>
      <div class="col-6 col-lg-3">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-semibold"><?php echo htmlspecialchars($meta['label']); ?></span>
              <i class="fa <?php echo htmlspecialchars($meta['icon']); ?> <?php echo htmlspecialchars($meta['class']); ?>"></i>
            </div>
            <h3 class="fw-bold mb-0"><?php echo number_format($value); ?></h3>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mt-5">
    <h4 class="fw-bold mb-3">Menu quản lý</h4>
    <div class="row g-4">
      <?php foreach ($menu as $section): ?>
        <div class="col-md-6 col-xl-3">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                  <i class="fa <?php echo htmlspecialchars($section['icon']); ?> text-primary"></i>
                </div>
                <div>
                  <h5 class="card-title mb-0"><?php echo htmlspecialchars($section['title']); ?></h5>
                  <small class="text-muted">Truy cập tính năng</small>
                </div>
              </div>
              <p class="text-muted flex-grow-1"><?php echo htmlspecialchars($section['description']); ?></p>
              <a class="btn btn-outline-primary mt-2" href="<?php echo htmlspecialchars($section['link']); ?>">
                <i class="fa fa-arrow-right me-2"></i>Mở quản lý
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>