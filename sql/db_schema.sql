CREATE DATABASE IF NOT EXISTS flyhighenglish CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE flyhighenglish;

CREATE TABLE course (
  course_id int(50) NOT NULL,
  course_name varchar(255) NOT NULL,
  start_date date DEFAULT NULL,
  end_date date DEFAULT NULL,
  skill varchar(255) DEFAULT NULL,
  status enum('upcoming','active','ended','') NOT NULL,
  teachea_id int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `materials`
--

CREATE TABLE materials (
  materials_id int(50) NOT NULL,
  title varchar(255) NOT NULL,
  file_path varchar(255) NOT NULL,
  type enum('document','video') NOT NULL,
  upload_date datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE questions (
  question_id int(50) NOT NULL,
  content text NOT NULL,
  option_a varchar(255) NOT NULL,
  option_b varchar(255) NOT NULL,
  option_c varchar(255) NOT NULL,
  option_d varchar(255) NOT NULL,
  correct_answer char(1) NOT NULL,
  difficulty enum('easy','medium','hard','') NOT NULL,
  topic varchar(100) DEFAULT NULL,
  test_id int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `registrations`
--

CREATE TABLE registrations (
  reg_id int(50) NOT NULL,
  course_id int(50) NOT NULL,
  name varchar(100) NOT NULL,
  email varchar(255) NOT NULL,
  reg_date datetime NOT NULL DEFAULT current_timestamp(),
  status enum('new','contacted','enrolled','cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slideshows`
--

CREATE TABLE slideshows (
  slide_id int(50) NOT NULL,
  title varchar(255) NOT NULL,
  description text DEFAULT NULL,
  image varchar(255) NOT NULL,
  link varchar(255) DEFAULT NULL,
  course_id int(50) DEFAULT NULL,
  status enum('visible','hidden') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `teacher`
--

CREATE TABLE teacher (
  teacher_id int(50) NOT NULL,
  teacher_name varchar(100) NOT NULL,
  email varchar(255) DEFAULT NULL,
  phone varchar(10) DEFAULT NULL,
  degree varchar(255) DEFAULT NULL,
  avatar varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `test`
--

CREATE TABLE test (
  test_id int(50) NOT NULL,
  test_name varchar(50) NOT NULL,
  description text DEFAULT NULL,
  created_at datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `test_answers`
--

CREATE TABLE test_answers (
  answer_id int(50) NOT NULL,
  result_id int(50) NOT NULL,
  question_id int(50) NOT NULL,
  selected_answer char(1) NOT NULL,
  is_correct tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `test_results`
--

CREATE TABLE test_results (
  result_id int(50) NOT NULL,
  user_id int(50) NOT NULL,
  test_id int(50) NOT NULL,
  score int(255) NOT NULL,
  date_test datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE user (
  user_id int(50) NOT NULL,
  user_name varchar(100) NOT NULL,
  email varchar(255) NOT NULL,
  phone varchar(10) NOT NULL,
  password varchar(100) NOT NULL,
  role enum('admin','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `course`
--
ALTER TABLE course
  ADD PRIMARY KEY (course_id),
  ADD KEY teachea_id (teachea_id);

--
-- Chỉ mục cho bảng `materials`
--
ALTER TABLE materials
  ADD PRIMARY KEY (materials_id);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE questions
  ADD PRIMARY KEY (question_id),
  ADD KEY test_id (test_id);

--
-- Chỉ mục cho bảng `registrations`
--
ALTER TABLE registrations
  ADD PRIMARY KEY (reg_id),
  ADD KEY INDEX (course_id);

--
-- Chỉ mục cho bảng `slideshows`
--
ALTER TABLE slideshows
  ADD PRIMARY KEY (slide_id),
  ADD KEY INDEX (course_id);

--
-- Chỉ mục cho bảng `teacher`
--
ALTER TABLE teacher
  ADD PRIMARY KEY (teacher_id);

--
-- Chỉ mục cho bảng `test`
--
ALTER TABLE test
  ADD PRIMARY KEY (test_id),
  ADD KEY INDEX (test_name);

--
-- Chỉ mục cho bảng `test_answers`
--
ALTER TABLE test_answers
  ADD KEY question_id (question_id),
  ADD KEY result_id (result_id);

--
-- Chỉ mục cho bảng `test_results`
--
ALTER TABLE test_results
  ADD PRIMARY KEY (result_id),
  ADD KEY user_id (user_id),
  ADD KEY test_id (test_id);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE user
  ADD PRIMARY KEY (user_id);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `course`
--
ALTER TABLE course
  MODIFY course_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `materials`
--
ALTER TABLE materials
  MODIFY materials_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE questions
  MODIFY question_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `registrations`
--
ALTER TABLE registrations
  MODIFY reg_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `slideshows`
--
ALTER TABLE slideshows
  MODIFY slide_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `teacher`
--
ALTER TABLE teacher
  MODIFY teacher_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `test`
--
ALTER TABLE test
  MODIFY test_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `test_results`
--
ALTER TABLE test_results
  MODIFY result_id int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE user
  MODIFY user_id int(50) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `course`
--
ALTER TABLE course
  ADD CONSTRAINT course_ibfk_1 FOREIGN KEY (teachea_id) REFERENCES teacher (teacher_id);

--
-- Các ràng buộc cho bảng `questions`
--
ALTER TABLE questions
  ADD CONSTRAINT questions_ibfk_1 FOREIGN KEY (test_id) REFERENCES test (test_id);

--
-- Các ràng buộc cho bảng `registrations`
--
ALTER TABLE registrations
  ADD CONSTRAINT registrations_ibfk_1 FOREIGN KEY (course_id) REFERENCES course (course_id);

--
-- Các ràng buộc cho bảng `slideshows`
--
ALTER TABLE slideshows
  ADD CONSTRAINT slideshows_ibfk_1 FOREIGN KEY (course_id) REFERENCES course (course_id);

--
-- Các ràng buộc cho bảng `test_answers`
--
ALTER TABLE test_answers
  ADD CONSTRAINT test_answers_ibfk_1 FOREIGN KEY (question_id) REFERENCES questions (question_id),
  ADD CONSTRAINT test_answers_ibfk_2 FOREIGN KEY (result_id) REFERENCES test_results (result_id);

--
-- Các ràng buộc cho bảng `test_results`
--
ALTER TABLE test_results
  ADD CONSTRAINT test_results_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (user_id),
  ADD CONSTRAINT test_results_ibfk_2 FOREIGN KEY (test_id) REFERENCES test (test_id);
COMMIT;
