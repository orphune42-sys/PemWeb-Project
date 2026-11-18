<?php
session_start();
$projectRoot = dirname(__DIR__);
require_once $projectRoot . '/koneksi.php';
require_once $projectRoot . '/app/Controllers/AuthController.php';
require_once $projectRoot . '/app/Controllers/ExploreProgramController.php';
require_once $projectRoot . '/app/Controllers/ScheduleManagementController.php';
require_once $projectRoot . '/app/Controllers/AdminProfileController.php';
require_once $projectRoot . '/app/Controllers/programManagementController.php';
require_once $projectRoot . '/app/Controllers/OpportunityController.php';
require_once $projectRoot . '/app/Controllers/MahasiswaProfileController.php';
require_once $projectRoot . '/app/Controllers/ManagementRegistController.php';

$baseUrl = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
$page = $_GET['page'] ?? 'login';

$publicPages = ['login', 'register'];
$studentPages = ['explore-program', 'opportunity'];
$isLoggedIn = isset($_SESSION['user']);
$userRole = strtolower(trim($_SESSION['user']['role'] ?? ''));

if (!$isLoggedIn && !in_array($page, $publicPages, true)) {
    header("Location: " . $baseUrl . "/public/index.php?page=login");
    exit;
}
if (in_array($page, $studentPages, true) && $isLoggedIn) {

    if ($userRole !== 'mahasiswa') {
        http_response_code(403);
        echo "403 - Anda tidak memiliki akses ke halaman ini.";
        exit;
    }
}

switch ($page) {
    case 'login':
        (new AuthController($pdo, $baseUrl))->login();
        break;
    case 'register':
        (new AuthController($pdo, $baseUrl))->register();
        break;
    case 'explore-program':
        (new ExploreProgramController($pdo, $baseUrl))->index();
        break;
    case 'schedule':
        (new ScheduleManagementController($baseUrl))->index();
        break;
    case 'profile-admin':
        (new AdminProfileController($pdo, $baseUrl))->index();
        break;
    case 'profile-mahasiswa':
        (new MahasiswaProfileController($pdo, $baseUrl, $projectRoot))->index();
        break;
    case 'program-management':
        (new programManagementController())->index();
        break;
    case 'opportunity':
        $opportunityController = new OpportunityController($pdo, $baseUrl);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $opportunityController->handleRequest();
        } else {
            $opportunityController->index();
        }
        break;
    case 'management-regist':
        (new ManagementRegistController($pdo, $baseUrl))->index();
        break;
    case 'logout':
        (new AuthController($pdo, $baseUrl))->logout();
        break;
    default:
        http_response_code(404);
        echo 'Halaman tidak ditemukan.';
}
