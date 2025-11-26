# Hướng dẫn cài đặt tính năng Quản lý Tài liệu

## 1. Tạo bảng database

Chạy file SQL sau trong phpMyAdmin hoặc MySQL:

```sql
pt_web_ma_nguon_mo/sql/create_documents_table.sql
```

Hoặc copy và chạy trực tiếp:

```sql
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
```

## 2. Kiểm tra quyền thư mục

Đảm bảo thư mục `public/uploads/documents/` có quyền ghi (write permission):

```bash
chmod 777 public/uploads/documents/
```

## 3. Các tính năng đã tạo

### Model
- `app/models/Document.php` - Xử lý database cho tài liệu

### Controller
- `app/controllers/DocumentController.php` - Xử lý logic:
  - `index()` - Hiển thị danh sách tài liệu
  - `create()` - Hiển thị form upload
  - `store()` - Xử lý upload file
  - `download()` - Tải xuống tài liệu
  - `delete()` - Xóa tài liệu

### Views
- `resources/views/documents/index.php` - Danh sách tài liệu
- `resources/views/documents/create.php` - Form upload tài liệu

### Routes đã thêm vào `public/index.php`
- `?route=documents` - Danh sách tài liệu
- `?route=documents_create` - Form upload
- `?route=documents_store` - Xử lý upload
- `?route=documents_download&id=X` - Tải xuống
- `?route=documents_delete&id=X` - Xóa tài liệu

## 4. Sử dụng

### Xem danh sách tài liệu
Truy cập: `http://localhost/flyhighenglish/public/index.php?route=documents`

### Upload tài liệu
1. Đăng nhập vào hệ thống
2. Vào trang Tài liệu
3. Click "Upload tài liệu"
4. Điền thông tin và chọn file
5. Click "Upload tài liệu"

### Tải xuống tài liệu
Click nút "Tải xuống" trên card tài liệu

### Xóa tài liệu
- Chỉ người upload mới có quyền xóa
- Click icon thùng rác trên card tài liệu

## 5. Loại file được hỗ trợ

- PDF (.pdf)
- Word (.doc, .docx)
- PowerPoint (.ppt, .pptx)
- Excel (.xls, .xlsx)
- Text (.txt)
- Archive (.zip, .rar)

Kích thước tối đa: 10MB

## 6. Bảo mật

- Chỉ người đăng nhập mới upload được tài liệu
- Chỉ người upload mới xóa được tài liệu của mình
- File được validate loại và kích thước
- Tên file được rename để tránh trùng lặp

## 7. Giao diện

- Responsive design với Bootstrap 5
- Icon Font Awesome cho các loại file
- Card layout hiện đại
- Thông báo success/error
