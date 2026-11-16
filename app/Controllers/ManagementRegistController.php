<?php
require_once __DIR__ . '/../Models/ManagementRegist.php';

class ManagementRegistController
{
    private $model;
    private string $baseUrl;

    public function __construct($pdo, string $baseUrl = '')
    {
        $this->model = new ManagementRegist($pdo);
        $this->baseUrl = $baseUrl;
    }

    public function index()
    {
        // ... (kode POST dan pagination Anda tetap sama persis) ...
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                $nama  = trim($_POST['nama_mahasiswa'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $jenis = trim($_POST['jenis_program'] ?? 'Beasiswa');

                if ($nama !== '' && $email !== '') {
                    $this->model->create($nama, $email, $jenis);
                }
            } elseif ($action === 'update') {
                $id    = (int)($_POST['id'] ?? 0);
                $nama  = trim($_POST['nama_mahasiswa'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $jenis = trim($_POST['jenis_program'] ?? 'Beasiswa');

                if ($id > 0 && $nama !== '' && $email !== '') {
                    $this->model->update($id, $nama, $email, $jenis);
                }
            } elseif ($action === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($id > 0) {
                    $this->model->delete($id);
                }
            }

            header("Location: index.php?page=management-regist");
            exit;
        }

        $limit = 10;
        $pageNum = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($pageNum < 1) $pageNum = 1;

        $totalRegistrations = $this->model->countAll();
        $totalPages = ceil($totalRegistrations / $limit) ?: 1;
        if ($pageNum > $totalPages) $pageNum = $totalPages;
        $offset = ($pageNum - 1) * $limit;

        $registrations = $this->model->getPaginated($limit, $offset);

        // Variabel pendukung View & SidebarController
        $activeMenu = 'registration';
        $baseUrl = $this->baseUrl;

        require_once __DIR__ . '/../Views/management-regist/managementRegist.php';
    }
}