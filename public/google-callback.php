<?php
session_start();

require_once __DIR__ . '/../app/helpers/Database.php';
require_once __DIR__ . '/../app/models/User.php';

$config = require __DIR__ . '/../config/config.php';
$googleConfig = $config['google'];

$clientID = $googleConfig['client_id'];
$clientSecret = $googleConfig['client_secret'];
$redirectURI = $googleConfig['redirect_uri'];

// ---- Nhận code từ Google ----
if (!isset($_GET['code'])) {
    $_SESSION['error_message'] = "Không nhận được mã xác thực (code).";
    header("Location: " . $_SERVER['SCRIPT_NAME'] . "?route=login");
    exit;
}

// ---- Gửi mã để lấy access token ----
$tokenURL = "https://oauth2.googleapis.com/token";

$data = [
    'code' => $_GET['code'],
    'client_id' => $clientID,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectURI,
    'grant_type' => 'authorization_code'
];

$curl = curl_init($tokenURL);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$tokenInfo = json_decode($response, true);

// Khi lỗi, tokenInfo sẽ không có access_token
if (!isset($tokenInfo['access_token'])) {
    $_SESSION['error_message'] = "Lấy token thất bại!";
    header("Location: " . $_SERVER['SCRIPT_NAME'] . "?route=login");
    exit;
}

// ---- Lấy thông tin người dùng từ Google ----
$userInfoURL = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $tokenInfo['access_token'];

$userData = json_decode(file_get_contents($userInfoURL), true);

if (!isset($userData['email'])) {
    $_SESSION['error_message'] = "Không thể lấy thông tin từ Google.";
    header("Location: " . $_SERVER['SCRIPT_NAME'] . "?route=login");
    exit;
}

// ---- Kiểm tra và xử lý đăng nhập/đăng ký ----
$userModel = new User();
$existingUser = $userModel->findUserByEmail($userData['email']);

if ($existingUser) {
    // User đã tồn tại, đăng nhập
    unset($existingUser['password']);
    $_SESSION['user'] = $existingUser;
    $_SESSION['success_message'] = 'Đăng nhập thành công với Google.';
} else {
    // User chưa tồn tại, đăng ký mới
    $registerData = [
        'user_name' => $userData['name'] ?? 'Người dùng Google',
        'email' => $userData['email'],
        'phone' => '',
        'password' => '', // Không cần password cho Google user
        'avatar' => $userData['picture'] ?? null,
        'role' => 'student'
    ];

    $result = $userModel->registerWithGoogle($registerData);
    
    if ($result === true) {
        // Đăng nhập ngay sau khi đăng ký
        $newUser = $userModel->findUserByEmail($userData['email']);
        if ($newUser) {
            unset($newUser['password']);
            $_SESSION['user'] = $newUser;
            $_SESSION['success_message'] = 'Đăng ký thành công với Google!';
        } else {
            $_SESSION['error_message'] = 'Đăng ký thành công nhưng không thể đăng nhập.';
        }
    } else {
        $_SESSION['error_message'] = is_string($result) ? $result : 'Đã xảy ra lỗi khi đăng ký.';
    }
}

// ---- Chuyển về trang chủ ----
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
header("Location: " . $base . "/index.php?route=home");
exit;
