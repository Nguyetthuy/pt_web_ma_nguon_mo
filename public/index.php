<?php

// Autoload helpers
require_once __DIR__ . '/../app/helpers/Database.php';
require_once __DIR__ . '/../app/helpers/view.php';

// Simple Router
$route = $_GET['route'] ?? 'home';

switch ($route) {

    // ========== HOME ==========
    case 'home':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        (new HomeController())->index();
        break;

    // ========== COURSES ==========
    case 'courses':
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        (new CourseController())->index();
        break;

    case 'course_detail':
        require_once __DIR__ . '/../app/controllers/CourseController.php';
        (new CourseController())->detail();
        break;

    /*// ========== REGISTRATION ==========
    case 'register':
        require_once __DIR__ . '/../app/controllers/RegistrationController.php';
        (new RegistrationController())->store();
        break; */

   /* // ========== ADMIN DASHBOARD ==========
    case 'admin':
        require_once __DIR__ . '/../app/controllers/AdminController.php';
        (new AdminController())->dashboard();
        break; */

   /* // ========== AI CHAT ==========
    case 'ai_chat':
        require_once __DIR__ . '/../app/controllers/AIController.php';
        (new AIController())->chat();
        break; */

    // ========== 404 ==========
    default:
        http_response_code(404);
        echo "404 - Not Found";
        break;
}