<?php include __DIR__ . '/../partials/header.php'; ?>
<h2>Danh sách khóa học</h2>
<table class="table">
  <thead><tr><th>Tên</th><th>Ngày bắt đầu</th><th>Ngày kết thúc</th><th>Trạng thái</th><th></th></tr></thead>
  <tbody>
    <?php foreach($courses as $c): ?>
      <tr>
        <td><?php echo htmlspecialchars($c['title']); ?></td>
        <td><?php echo htmlspecialchars($c['start_date']); ?></td>
        <td><?php echo htmlspecialchars($c['end_date']); ?></td>
        <td><?php echo htmlspecialchars($c['status']); ?></td>
        <td><a href="/public/index.php?route=course_detail&id=<?php echo $c['id']; ?>">Xem</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>