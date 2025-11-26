<?php

// Hiển thị lỗi khi dev (tuỳ chọn)
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

// Autoload helpers
require_once __DIR__ . '/../app/helpers/Database.php';
require_once __DIR__ . '/../app/helpers/view.php';

// START SESSION & REQUEST METHOD
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Simple Router
$route = $_GET['route'] ?? 'home';

switch ($route) {
    // ========== HOME ==========    // ========== HOME ==========
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // ========== COURSES ==========    // ========== COURSES ==========
    /*case 'courses':
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        (new CourseController())->index();
        break;

    case 'course_detail':    case 'course_detail':
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        (new CourseController())->detail();
        break;*/

    // ========== REGISTER ==========    // ========== REGISTER ==========
    case 'register':
        require_once __DIR__ . '/../app/controllers/UserController.php'; 
        $controller = new UserController();
        if ($method === 'POST') {
            // xử lý form
            $controller->register();
        } else {
            // hiển thị form
            $controller->showRegistrationForm();
        }
        break;

     // ========== LOGIN ==========
    case 'login':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        if ($method === 'POST') {
            $controller->login();
        } else {
            $controller->showLoginForm();
        }
        break;

    // ========== GOOGLE AUTH ==========
    case 'google-login':
        require_once __DIR__ . '/google-login.php';
        break;

    case 'google-callback':
        require_once __DIR__ . '/google-callback.php';
        break;

    case 'google-auth':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        $controller->handleGoogleAuth();
        break;

    // ========== PROFILE ==========
    case 'profile':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        
        // Kiểm tra nếu có action update_avatar
        if (isset($_GET['action']) && $_GET['action'] === 'update_avatar' && $method === 'POST') {
            $controller->updateAvatar();
        } else {
            $controller->showProfile();
        }
        break;

    // ========== LOGOUT ==========
    case 'logout':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        $controller->logout();
        break;


    // ========== 404 ==========
    default:
        http_response_code(404);
        echo "404 - Not Found";
        break;
}