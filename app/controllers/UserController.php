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
        $user = $this->userModel->findUserByEmail($email);
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

    // Xử lý đăng ký/đăng nhập bằng Google
    public function handleGoogleAuth() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $credential = $input['credential'] ?? '';

        if (empty($credential)) {
            echo json_encode(['success' => false, 'message' => 'Không nhận được thông tin từ Google.']);
            exit;
        }

        // Verify Google token và lấy thông tin user
        $googleUser = $this->verifyGoogleToken($credential);
        
        if (!$googleUser) {
            echo json_encode(['success' => false, 'message' => 'Không thể xác thực với Google.']);
            exit;
        }

        // Kiểm tra xem user đã tồn tại chưa
        $existingUser = $this->userModel->findUserByEmail($googleUser['email']);
        
        if ($existingUser) {
            // User đã tồn tại, đăng nhập
            if (session_status() === PHP_SESSION_NONE) session_start();
            unset($existingUser['password']);
            $_SESSION['user'] = $existingUser;
            $_SESSION['success_message'] = 'Đăng nhập thành công với Google.';
            echo json_encode([
                'success' => true, 
                'message' => 'Đăng nhập thành công.',
                'redirect' => $_SERVER['SCRIPT_NAME'] . '?route=home'
            ]);
        } else {
            // User chưa tồn tại, đăng ký mới
            $registerData = [
                'user_name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'phone' => '',
                'password' => '', // Không cần password cho Google user
                'avatar' => $googleUser['picture'] ?? null,
                'role' => 'student',
                'google_id' => $googleUser['sub'] ?? null
            ];

            $result = $this->userModel->registerWithGoogle($registerData);
            
            if ($result === true) {
                // Đăng nhập ngay sau khi đăng ký
                $newUser = $this->userModel->findUserByEmail($googleUser['email']);
                if ($newUser) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    unset($newUser['password']);
                    $_SESSION['user'] = $newUser;
                    $_SESSION['success_message'] = 'Đăng ký thành công với Google!';
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Đăng ký thành công.',
                        'redirect' => $_SERVER['SCRIPT_NAME'] . '?route=home'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Đăng ký thành công nhưng không thể đăng nhập.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => is_string($result) ? $result : 'Đã xảy ra lỗi khi đăng ký.']);
            }
        }
        exit;
    }

    // Verify Google ID token
    private function verifyGoogleToken($credential) {
        // Sử dụng Google API để verify token
        // Cần cài đặt: composer require google/apiclient
        // Hoặc sử dụng cURL để gọi Google API
        
        $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($credential);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            error_log('Google token verification failed: HTTP ' . $httpCode);
            return false;
        }
        
        $data = json_decode($response, true);
        
        if (!$data || isset($data['error'])) {
            error_log('Google token verification error: ' . ($data['error'] ?? 'Unknown'));
            return false;
        }
        
        // Verify audience (client ID) - Uncomment và thay YOUR_GOOGLE_CLIENT_ID để bảo mật hơn
        // $config = require __DIR__ . '/../../config/config.php';
        // $expectedClientId = $config['google_client_id'] ?? '';
        // if ($data['aud'] !== $expectedClientId) {
        //     error_log('Google token audience mismatch');
        //     return false;
        // }
        
        return [
            'sub' => $data['sub'] ?? '',
            'email' => $data['email'] ?? '',
            'name' => $data['name'] ?? '',
            'picture' => $data['picture'] ?? null,
            'email_verified' => $data['email_verified'] ?? false
        ];
    }

    // Hiển thị trang thông tin cá nhân
    public function showProfile() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để xem thông tin cá nhân.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=login');
            exit;
        }

        // Lấy thông tin user mới nhất từ database
        $user = $this->userModel->findUserByEmail($_SESSION['user']['email']);
        if ($user) {
            unset($user['password']);
            // Đảm bảo user_id có trong session
            if (isset($user['user_id'])) {
                $_SESSION['user']['user_id'] = $user['user_id'];
            }
            $data = ['user' => $user];
        } else {
            $data = ['user' => $_SESSION['user']];
        }

        view('user/profile', $data);
    }

    // Đăng xuất
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa session
        unset($_SESSION['user']);
        session_destroy();

        $_SESSION['success_message'] = 'Đăng xuất thành công.';
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=home');
        exit;
    }

    // Cập nhật avatar
    public function updateAvatar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để cập nhật avatar.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=login');
            exit;
        }

        // Kiểm tra method POST và có file upload
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['avatar'])) {
            $_SESSION['error_message'] = 'Không nhận được file upload.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?route=profile');
            exit;
        }

        $file = $_FILES['avatar'];
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

        // 1. Kiểm tra lỗi file upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'Lỗi tải file lên. Mã lỗi: ' . $file['error'];
            header('Location: ' . $base . '/index.php?route=profile');
            exit;
        }

        // 2. Kiểm tra kích thước và loại file
        $max_size = 5 * 1024 * 1024; // 5 MB
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if ($file['size'] > $max_size) {
            $_SESSION['error_message'] = 'Kích thước file quá lớn (tối đa 5MB).';
            header('Location: ' . $base . '/index.php?route=profile');
            exit;
        }
        
        if (!in_array($file['type'], $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error_message'] = 'Chỉ chấp nhận định dạng JPG, PNG hoặc GIF.';
            header('Location: ' . $base . '/index.php?route=profile');
            exit;
        }

        // 3. Chuẩn bị đường dẫn và tên file mới
        $upload_dir = __DIR__ . '/../../public/uploads/avatars/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Lấy user_id từ session, nếu không có thì lấy từ database
        $user_id = $_SESSION['user']['user_id'] ?? null;
        if (!$user_id) {
            // Lấy user từ database để có user_id
            $user = $this->userModel->findUserByEmail($_SESSION['user']['email']);
            if ($user && isset($user['user_id'])) {
                $user_id = $user['user_id'];
                // Cập nhật session với user_id
                $_SESSION['user']['user_id'] = $user_id;
            } else {
                $_SESSION['error_message'] = 'Không thể xác định user_id.';
                header('Location: ' . $base . '/index.php?route=profile');
                exit;
            }
        }
        
        // Tạo tên file bằng user_id
        $new_file_name = $user_id . '.' . $file_extension;
        $destination = $upload_dir . $new_file_name;
        
        // Xóa file avatar cũ nếu tồn tại (để tránh tích lũy file)
        $old_files = glob($upload_dir . $user_id . '.*');
        foreach ($old_files as $old_file) {
            if (is_file($old_file)) {
                @unlink($old_file);
            }
        }
        
        // 4. Di chuyển file vào thư mục
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // 5. Cập nhật đường dẫn vào Database
            $public_path = $base . '/uploads/avatars/' . $new_file_name;
            
            // Cập nhật database
            $result = $this->userModel->updateAvatar($_SESSION['user']['email'], $public_path);
            
            if ($result) {
                // Cập nhật Session để hiển thị ngay
                $_SESSION['user']['avatar'] = $public_path;
                $_SESSION['success_message'] = 'Ảnh đại diện đã được cập nhật thành công!';
            } else {
                // Xóa file nếu không cập nhật được DB
                @unlink($destination);
                $_SESSION['error_message'] = 'Không thể cập nhật database.';
            }
        } else {
            $_SESSION['error_message'] = 'Không thể lưu file. Vui lòng kiểm tra quyền thư mục.';
        }
        
        // Chuyển hướng về trang profile
        header('Location: ' . $base . '/index.php?route=profile');
        exit;
    }
}