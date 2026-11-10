<?php
session_start();
$projectRoot = dirname(__DIR__);
require_once $projectRoot . '/koneksi.php';
require_once $projectRoot . '/app/Controllers/AuthController.php';
require_once $projectRoot . '/app/Controllers/ExploreProgramController.php';
require_once $projectRoot . '/app/Controllers/ScheduleManagementController.php';
require_once $projectRoot . '/app/Controllers/AdminProfileController.php';
require_once $projectRoot . '/app/Controllers/programManagementController.php';

$baseUrl = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        (new AuthController($pdo, $baseUrl))->login();
        break;
    case 'register':
        (new AuthController($pdo, $baseUrl))->register();
        break;
    case 'explore-program':
        (new ExploreProgramController())->index();
        break;
    case 'schedule':
        (new ScheduleManagementController($baseUrl))->index();
        break;
    case 'profile-admin':
        (new AdminProfileController($pdo, $baseUrl))->index();
        break;
    case 'program-management':
        (new programManagementController())->index();
        break;
    default:
        http_response_code(404);
        echo 'Halaman tidak ditemukan.';
}
