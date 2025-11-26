<?php

require_once __DIR__ . '/../helpers/Database.php';
require_once __DIR__ . '/../helpers/view.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AdminController
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function dashboard(): void
    {
        $this->ensureAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUserActions();
        }

        $section = $_GET['section'] ?? 'overview';

        switch ($section) {
            case 'users':
                $this->showUserManagement();
                return;
            default:
                $this->showOverview();
                return;
        }
    }

    private function ensureAdmin(): void
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để truy cập trang quản trị.';
            header('Location: ' . ($_SERVER['SCRIPT_NAME'] ?? '/index.php') . '?route=login');
            exit;
        }

        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang quản trị.';
            header('Location: ' . ($_SERVER['SCRIPT_NAME'] ?? '/index.php') . '?route=home');
            exit;
        }
    }

    private function countTable(string $table): int
    {
        try {
            $result = $this->db->runQuery("SELECT COUNT(*) AS total FROM `$table`");
            return (int)($result[0]['total'] ?? 0);
        } catch (Throwable $e) {
            error_log('AdminController::countTable error: ' . $e->getMessage());
            return 0;
        }
    }

    private function showOverview(): void
    {
        $stats = [
            'users'      => $this->countTable('user'),
            'courses'    => $this->countTable('course'),
            'materials'  => $this->countTable('materials'),
            'slideshows' => $this->countTable('slideshows'),
        ];

        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $menu = [
            [
                'title'       => 'Quản lý người dùng',
                'description' => 'Quản trị tài khoản, phân quyền và trạng thái hoạt động.',
                'icon'        => 'fa-users',
                'link'        => $script . '?route=admin&section=users',
            ],
            [
                'title'       => 'Quản lý khóa học',
                'description' => 'Cập nhật nội dung, lịch học và giảng viên phụ trách.',
                'icon'        => 'fa-chalkboard-teacher',
                'link'        => $script . '?route=admin&section=courses',
            ],
            [
                'title'       => 'Quản lý tài liệu',
                'description' => 'Tải lên, phân loại và cấp quyền cho tài liệu học tập.',
                'icon'        => 'fa-book',
                'link'        => $script . '?route=admin&section=materials',
            ],
            [
                'title'       => 'Quản lý slideshow',
                'description' => 'Điều chỉnh banner, trạng thái hiển thị và thứ tự trình chiếu.',
                'icon'        => 'fa-images',
                'link'        => $script . '?route=admin&section=slideshows',
            ],
        ];

        view('admin/dashboard', [
            'stats' => $stats,
            'menu'  => $menu,
            'admin' => $_SESSION['user'],
        ]);
    }

    private function showUserManagement(): void
    {
        $users = $this->db->runQuery("
            SELECT user_id, user_name, email, phone, role, avatar
            FROM `user`
            ORDER BY role DESC, user_name ASC
        ");

        view('admin/users', [
            'users' => $users,
            'admin' => $_SESSION['user'],
        ]);
    }

    private function handleUserActions(): void
    {
        $action = $_POST['action'] ?? '';
        if ($action === '') {
            return;
        }

        try {
            switch ($action) {
                case 'create':
                    $this->createUser($_POST);
                    $_SESSION['success_message'] = 'Đã thêm người dùng mới thành công.';
                    break;
                case 'update':
                    $this->updateUser($_POST);
                    $_SESSION['success_message'] = 'Cập nhật thông tin người dùng thành công.';
                    break;
                case 'delete':
                    $this->deleteUser($_POST);
                    $_SESSION['success_message'] = 'Đã xóa người dùng khỏi hệ thống.';
                    break;
                default:
                    $_SESSION['error_message'] = 'Hành động không hợp lệ.';
                    break;
            }
        } catch (Throwable $e) {
            $_SESSION['error_message'] = $e->getMessage();
        }

        $this->redirectToUsers();
    }

    private function redirectToUsers(): void
    {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        header('Location: ' . $script . '?route=admin&section=users');
        exit;
    }

    private function createUser(array $data): void
    {
        $name = trim($data['user_name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $role = $this->sanitizeRole($data['role'] ?? 'student');
        $password = $data['password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Tên, email và mật khẩu không được để trống.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email không hợp lệ.');
        }
        if ($this->emailExists($email)) {
            throw new InvalidArgumentException('Email này đã tồn tại trong hệ thống.');
        }

        $sql = "INSERT INTO `user` (user_name, email, phone, password, role)
                VALUES (:user_name, :email, :phone, :password, :role)";
        $params = [
            ':user_name' => $name,
            ':email'     => $email,
            ':phone'     => $phone !== '' ? $phone : null,
            ':password'  => password_hash($password, PASSWORD_DEFAULT),
            ':role'      => $role,
        ];

        $this->db->execute($sql, $params);
    }

    private function updateUser(array $data): void
    {
        $userId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
        if ($userId <= 0) {
            throw new InvalidArgumentException('Người dùng không hợp lệ.');
        }

        $current = $this->findUserById($userId);
        if (!$current) {
            throw new RuntimeException('Không tìm thấy người dùng để cập nhật.');
        }

        $name = trim($data['user_name'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $role = $this->sanitizeRole($data['role'] ?? $current['role']);
        $password = $data['password'] ?? '';

        if ($name === '' || $email === '') {
            throw new InvalidArgumentException('Tên và email không được để trống.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email không hợp lệ.');
        }
        if ($email !== $current['email'] && $this->emailExists($email)) {
            throw new InvalidArgumentException('Email này đã được sử dụng.');
        }

        $fields = [
            'user_name' => $name,
            'email'     => $email,
            'phone'     => $phone !== '' ? $phone : null,
            'role'      => $role,
        ];

        $setParts = [];
        $params = [':user_id' => $userId];
        foreach ($fields as $column => $value) {
            $setParts[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        if ($password !== '') {
            $setParts[] = "password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE `user` SET " . implode(', ', $setParts) . " WHERE user_id = :user_id";
        $this->db->execute($sql, $params);
    }

    private function deleteUser(array $data): void
    {
        $userId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
        if ($userId <= 0) {
            throw new InvalidArgumentException('Người dùng không hợp lệ.');
        }

        if ($userId === (int)($_SESSION['user']['user_id'] ?? 0)) {
            throw new RuntimeException('Không thể xóa tài khoản đang đăng nhập.');
        }

        $sql = "DELETE FROM `user` WHERE user_id = :user_id";
        $this->db->execute($sql, [':user_id' => $userId]);
    }

    private function findUserById(int $id): ?array
    {
        $result = $this->db->runQuery("SELECT * FROM `user` WHERE user_id = :id LIMIT 1", [':id' => $id]);
        return $result[0] ?? null;
    }

    private function emailExists(string $email): bool
    {
        $result = $this->db->runQuery("SELECT user_id FROM `user` WHERE email = :email LIMIT 1", [':email' => $email]);
        return !empty($result);
    }

    private function sanitizeRole(string $role): string
    {
        return in_array($role, ['admin', 'student'], true) ? $role : 'student';
    }
}

