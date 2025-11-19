<?php include __DIR__ . '/../partials/header.php'; ?>
<h2>Admin Dashboard (mẫu)</h2>
<div class="row">
  <div class="col-md-6">
    <h4>Quản lý khóa học</h4>
    <a class="btn btn-primary mb-2" href="#">Thêm khóa học</a>
    <ul class="list-group">
      <?php foreach($courses as $c): ?>
        <li class="list-group-item">
          <?php echo htmlspecialchars($c['title']); ?> - <?php echo htmlspecialchars($c['status']); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="col-md-6">
    <h4>Quản lý giảng viên</h4>
    <a class="btn btn-primary mb-2" href="#">Thêm giảng viên</a>
    <ul class="list-group">
      <?php foreach($teachers as $t): ?>
        <li class="list-group-item"><?php echo htmlspecialchars($t['name']); ?> (<?php echo htmlspecialchars($t['degree']); ?>)</li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>