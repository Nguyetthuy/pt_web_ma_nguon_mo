<?php include __DIR__ . '/partials/header.php'; ?>
<div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php foreach($slides as $i => $s): ?>
      <div class="carousel-item <?php echo $i===0 ? 'active' : ''; ?>">
        <?php if ($s['media_type'] === 'image'): ?>
          <img src="/public/uploads/<?php echo htmlspecialchars($s['media_path']); ?>" class="d-block w-100" style="height:350px;object-fit:cover;">
        <?php else: ?>
          <video class="d-block w-100" style="height:350px;object-fit:cover;" controls>
            <source src="/public/uploads/<?php echo htmlspecialchars($s['media_path']); ?>" type="video/mp4">
          </video>
        <?php endif; ?>
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
          <h5><?php echo htmlspecialchars($s['title']); ?></h5>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
<h2 class="mt-4">Khóa học nổi bật</h2>
<div class="row">
  <?php foreach($courses as $c): ?>
    <div class="col-md-4">
      <div class="card mb-3">
        <img src="/public/assets/img/course-placeholder.jpg" class="card-img-top" style="height:200px;object-fit:cover;">
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($c['title']); ?></h5>
          <p class="card-text"><?php echo htmlspecialchars($c['skills']); ?></p>
          <p class="text-muted"><?php echo htmlspecialchars($c['start_date']) ?> - <?php echo htmlspecialchars($c['end_date']); ?></p>
          <a href="/public/index.php?route=course_detail&id=<?php echo $c['id']; ?>" class="btn btn-primary">Xem chi tiết</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>