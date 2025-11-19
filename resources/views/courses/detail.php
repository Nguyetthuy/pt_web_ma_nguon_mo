<?php include __DIR__ . '/../partials/header.php'; ?>
<h2><?php echo htmlspecialchars($course['title']); ?></h2>
<p><strong>Thời gian:</strong> <?php echo htmlspecialchars($course['start_date']); ?> - <?php echo htmlspecialchars($course['end_date']); ?></p>
<p><strong>Kỹ năng:</strong> <?php echo htmlspecialchars($course['skills']); ?></p>
<p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($course['status']); ?></p>

<h4 class="mt-4">Đăng ký tham gia</h4>
<form method="post" action="/public/index.php?route=register">
  <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
  <div class="mb-2"><input class="form-control" name="name" placeholder="Họ và tên"></div>
  <div class="mb-2"><input class="form-control" name="email" placeholder="Email"></div>
  <div class="mb-2"><input class="form-control" name="phone" placeholder="Số điện thoại"></div>
  <button class="btn btn-success">Đăng ký</button>
</form>

<h4 class="mt-4">Bài test trình độ (mẫu)</h4>
<p>Test gồm các câu hỏi trắc nghiệm (mẫu)</p>
<a class="btn btn-outline-primary" href="#">Làm bài test</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>