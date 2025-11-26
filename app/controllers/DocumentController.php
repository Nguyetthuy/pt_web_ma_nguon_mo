<?php

require_once __DIR__ . '/../models/Document.php';
require_once __DIR__ . '/../helpers/view.php';

class DocumentController
{
    /**
     * Hiển thị danh sách tài liệu
     */
    public function index()
    {
        $documents = Document::all();
        
        view('documents/index', [
            'documents' => $documents
        ]);
    }

    /**
     * Hiển thị form upload tài liệu
     */
    public function create()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?route=login');
            exit;
        }
        
        view('documents/create');
    }

    /**
     * Xử lý upload tài liệu
     */
    public function store()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Bạn cần đăng nhập để upload tài liệu';
            header('Location: index.php?route=login');
            exit;
        }

        // Validate dữ liệu
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($title)) {
            $_SESSION['error'] = 'Vui lòng nhập tiêu đề tài liệu';
            header('Location: index.php?route=documents_create');
            exit;
        }

        // Xử lý upload file
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Vui lòng chọn file để upload';
            header('Location: index.php?route=documents_create');
            exit;
        }

        $file = $_FILES['document'];
        $allowedTypes = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt', 'zip', 'rar'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        // Kiểm tra kích thước
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'File quá lớn. Kích thước tối đa là 10MB';
            header('Location: index.php?route=documents_create');
            exit;
        }

        // Kiểm tra loại file
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedTypes)) {
            $_SESSION['error'] = 'Loại file không được hỗ trợ';
            header('Location: index.php?route=documents_create');
            exit;
        }

        // Tạo tên file unique
        $fileName = time() . '_' . uniqid() . '.' . $fileExt;
        $uploadDir = __DIR__ . '/../../public/uploads/documents/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . $fileName;

        // Di chuyển file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $_SESSION['error'] = 'Có lỗi khi upload file';
            header('Location: index.php?route=documents_create');
            exit;
        }

        // Lưu vào database
        $data = [
            'title' => $title,
            'description' => $description,
            'file_name' => $file['name'],
            'file_path' => 'documents/' . $fileName,
            'file_type' => $fileExt,
            'file_size' => $file['size'],
            'uploaded_by' => $_SESSION['user']['user_id']
        ];

        Document::create($data);

        $_SESSION['success'] = 'Upload tài liệu thành công';
        header('Location: index.php?route=documents');
        exit;
    }

    /**
     * Tải xuống tài liệu
     */
    public function download()
    {
        if (!isset($_GET['id'])) {
            die('Thiếu ID tài liệu');
        }

        $id = $_GET['id'];
        $document = Document::find($id);

        if (!$document) {
            die('Không tìm thấy tài liệu');
        }

        $filePath = __DIR__ . '/../../public/uploads/' . $document['file_path'];

        if (!file_exists($filePath)) {
            die('File không tồn tại');
        }

        // Tăng lượt tải
        Document::incrementDownloads($id);

        // Download file
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $document['file_name'] . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * Xóa tài liệu
     */
    public function delete()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?route=login');
            exit;
        }

        if (!isset($_GET['id'])) {
            die('Thiếu ID tài liệu');
        }

        $id = $_GET['id'];
        $document = Document::find($id);

        if (!$document) {
            $_SESSION['error'] = 'Không tìm thấy tài liệu';
            header('Location: index.php?route=documents');
            exit;
        }

        // Kiểm tra quyền xóa (chỉ người upload hoặc admin)
        if ($document['uploaded_by'] != $_SESSION['user']['user_id']) {
            $_SESSION['error'] = 'Bạn không có quyền xóa tài liệu này';
            header('Location: index.php?route=documents');
            exit;
        }

        // Xóa file
        $filePath = __DIR__ . '/../../public/uploads/' . $document['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Xóa khỏi database
        Document::delete($id);

        $_SESSION['success'] = 'Xóa tài liệu thành công';
        header('Location: index.php?route=documents');
        exit;
    }
}
