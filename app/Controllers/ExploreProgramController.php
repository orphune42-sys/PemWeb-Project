<?php
require_once __DIR__ . '/../Models/ExploreProgram.php';

class ExploreProgramController
{
    private ExploreProgram $model;
    private string $baseUrl;

    public function __construct(PDO $pdo, string $baseUrl = '')
    {
        $this->model = new ExploreProgram($pdo);
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    private function userId(): int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? [];

        if (
            empty($user['id']) ||
            !in_array(
                strtolower($user['role'] ?? ''),
                ['mahasiswa', 'user']
            )
        ) {
            http_response_code(403);
            exit('Akses hanya untuk mahasiswa.');
        }

        return (int) $user['id'];
    }

    private function redirect(): void
    {
        header(
            'Location: ' . $this->baseUrl .
                '/public/?page=explore-program'
        );
        exit;
    }

    private function verifyCsrf(): void
    {
        if (
            !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            http_response_code(403);
            exit('Token keamanan tidak valid.');
        }
    }

    public function index(): void
    {
        $programModel = new exploreProgram();
        $programs = $programModel->getAll();
        $baseUrl = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
        require __DIR__ . '/../Views/explore/exploreProgram.php';
    }
}
