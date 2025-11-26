-- Tạo bảng documents để quản lý tài liệu học tiếng Anh
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề tài liệu',
  `description` text DEFAULT NULL COMMENT 'Mô tả tài liệu',
  `file_name` varchar(255) NOT NULL COMMENT 'Tên file gốc',
  `file_path` varchar(255) NOT NULL COMMENT 'Đường dẫn file trên server',
  `file_type` varchar(10) NOT NULL COMMENT 'Loại file (pdf, doc, docx, ...)',
  `file_size` int(11) NOT NULL COMMENT 'Kích thước file (bytes)',
  `downloads` int(11) DEFAULT 0 COMMENT 'Số lượt tải xuống',
  `uploaded_by` int(11) NOT NULL COMMENT 'ID người upload',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`uploaded_by`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm foreign key nếu bảng users tồn tại
-- ALTER TABLE `documents` 
-- ADD CONSTRAINT `fk_documents_user` 
-- FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`user_id`) 
-- ON DELETE CASCADE ON UPDATE CASCADE;
