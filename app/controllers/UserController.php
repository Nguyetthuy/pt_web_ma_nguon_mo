<?php
// Cần tải Model và Helper (view.php)
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/view.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Hiển thị form đăng ký
    public function showRegistrationForm($data = []) {
        // ensure $data contains errors/old keys
        $data += ['errors' => [], 'old' => []];
        view('user/registration', $data);
    }

    // Xử lý dữ liệu POST từ form
    public function register() {
        // nếu không phải POST hướng về form (dùng SCRIPT_NAME để tránh 404)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=register');
            exit;
        }

        // Lấy và làm sạch dữ liệu (NOTE: tên input form dùng password_confirm)
        $data = [
            'user_name' => trim($_POST['user_name'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'phone'     => trim($_POST['phone'] ?? ''),
            'password'  => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'avatar'    => trim($_POST['avatar'] ?? ''),
            'errors'    => [],
            'old'       => []
        ];

        // lưu giá trị cũ để trả lại view
        $data['old'] = [
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'phone' => $data['phone']
        ];

        // Validation
        if ($data['user_name'] === '') { $data['errors']['user_name'] = 'Tên người dùng không được rỗng.'; }
        if ($data['email'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) { $data['errors']['email'] = 'Email không hợp lệ.'; }
        // nếu bạn muốn phone optional thì comment dòng dưới
        if ($data['phone'] !== '' && strlen(preg_replace('/\D/', '', $data['phone'])) != 10) { $data['errors']['phone'] = 'Số điện thoại phải có 10 chữ số.'; }
        if (strlen($data['password']) < 6) { $data['errors']['password'] = 'Mật khẩu phải từ 6 ký tự trở lên.'; }
        if ($data['password'] !== $data['password_confirm']) { $data['errors']['password_confirm'] = 'Xác nhận mật khẩu không khớp.'; }

        if (!empty($data['errors'])) {
            view('user/registration', $data);
            return;
        }

        // gọi model
        $ok = $this->userModel->register($data);
        if ($ok === true) {
            $_SESSION['success_message'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=login');
            exit;
        } else {
            // model trả về false hoặc chuỗi lỗi
            $data['errors']['general'] = is_string($ok) ? $ok : 'Đã xảy ra lỗi khi đăng ký.';
            view('user/registration', $data);
        }
    }

    // Xác thực user bằng email + password
    public function authenticate(string $email, string $password)
    {
        $user = $this->findUserByEmail($email);
        if (!$user) {
            return false;
        }
        // nếu password trong DB chưa hash, thay logic tùy DB bạn dùng
        if (isset($user['password']) && password_verify($password, $user['password'])) {
            // trả về dữ liệu user (không trả về password)
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function showLoginForm($data = [])
    {
        $data += ['errors' => [], 'old' => []];
        view('user/login', $data);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $data = ['errors' => [], 'old' => ['email' => $email]];

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['errors'][] = 'Email không hợp lệ.';
        }
        if ($password === '') {
            $data['errors'][] = 'Mật khẩu không được để trống.';
        }

        if (!empty($data['errors'])) {
            view('user/login', $data);
            return;
        }

        $user = $this->userModel->authenticate($email, $password);
        if ($user) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user'] = $user;
            $_SESSION['success_message'] = 'Đăng nhập thành công.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=home');
            exit;
        } else {
            $data['errors'][] = 'Email hoặc mật khẩu không đúng.';
            view('user/login', $data);
        }
    }
}