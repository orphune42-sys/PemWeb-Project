<?php

require_once __DIR__ . '/../Models/Opportunity.php';

class OpportunityController
{
    private Opportunity $model;
    private string $baseUrl;

    public function __construct(PDO $pdo, string $baseUrl = '')
    {
        $this->model = new Opportunity($pdo);
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    private function userId(): int
    {
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
                '/public/index.php?page=opportunity'
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
        $userId = $this->userId();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $goals = $this->model->getAll('goals', $userId);
        $projects = $this->model->getAll('projects', $userId);
        $achievements = $this->model->getAll('achievements', $userId);
        $careerPlans = $this->model->getAll('career', $userId);

        $baseUrl = $this->baseUrl;

        require __DIR__ . '/../Views/opportunity/findOpportunity.php';
    }

    public function handleRequest(): void
    {
        $userId = $this->userId();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect();
        }

        $this->verifyCsrf();

        $action = $_POST['action'] ?? '';
        $type = $_POST['type'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        $allowed = ['goals', 'projects', 'achievements', 'career'];

        if (!in_array($type, $allowed, true)) {
            exit('Jenis data tidak valid.');
        }

        $schemas = [
            'goals' => [
                'title',
                'category',
                'current_level',
                'target_level',
                'progress',
                'target_date',
                'description'
            ],
            'projects' => [
                'project_name',
                'category',
                'technologies',
                'project_url',
                'description',
                'status'
            ],
            'achievements' => [
                'title',
                'category',
                'organizer',
                'achievement_date',
                'description',
                'certificate_file'
            ],
            'career' => [
                'career_title',
                'description',
                'target_date',
                'status'
            ]
        ];

        if ($action === 'delete') {
            if ($id <= 0) {
                exit('ID tidak valid.');
            }

            $this->model->delete($type, $id, $userId);
            $this->redirect();
        }

        if (!in_array($action, ['create', 'update'], true)) {
            exit('Aksi tidak valid.');
        }

        $data = [];

        foreach ($schemas[$type] as $field) {
            $value = trim($_POST[$field] ?? '');

            if ($value === '') {
                $value = null;
            }

            $data[$field] = $value;
        }

        $required = [
            'goals' => ['title', 'category'],
            'projects' => ['project_name'],
            'achievements' => ['title'],
            'career' => ['career_title']
        ];

        foreach ($required[$type] as $field) {
            if (empty($data[$field])) {
                exit('Kolom wajib belum diisi.');
            }
        }

        $validOptions = [
            'category' => ['Goals', 'Skills'],
            'status' => ['planned', 'in_progress', 'completed']
        ];

        foreach ($validOptions as $field => $options) {
            if ($type === 'goals' && !in_array($data['category'] ?? '', ['Goals', 'Skills'], true)) {
                exit('Jenis data goals tidak valid.');
            }

            if (
                isset($data['status']) &&
                $data['status'] !== null &&
                !in_array($data['status'], ['planned', 'in_progress', 'completed'], true)
            ) {
                exit('Pilihan status tidak valid.');
            }
        }

        $data['user_id'] = $userId;

        if ($action === 'create') {
            $this->model->create($type, $data);
        } else {
            if ($id <= 0) {
                exit('ID tidak valid.');
            }

            unset($data['user_id']);

            $this->model->update($type, $id, $userId, $data);
        }

        $this->redirect();
    }
}
