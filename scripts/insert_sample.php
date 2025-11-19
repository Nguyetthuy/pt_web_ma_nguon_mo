<?php
require_once __DIR__ . '/../app/helpers/functions.php';
$pdo = db_connect();
$pdo->exec("INSERT INTO courses (title, description, skills, start_date, end_date, status) VALUES
  ('Giao tiếp cơ bản', 'Khóa học dành cho người mới bắt đầu.', 'Speaking, Listening', '2025-12-01', '2026-02-01', 'upcoming'),
  ('IELTS Intensive', 'Luyện thi IELTS nâng cao.', 'Reading, Writing, Listening, Speaking', '2025-11-20', '2026-01-20', 'open')
");
$pdo->exec("INSERT INTO teachers (name, age, phone, email, degree) VALUES ('Nguyen Van A', 30, '0123456789', 'a@example.com', 'MA TESOL')");
echo "Inserted sample data";
