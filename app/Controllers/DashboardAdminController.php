<?php

require_once __DIR__ . '/../Models/DashboardAdmin.php';
require_once __DIR__ . '/sidebarAdminController.php';

class DashboardAdminController
{
    private $pdo;
    private string $baseUrl;
    private $model;

    public function __construct($pdo, string $baseUrl = '')
    {
        $this->pdo     = $pdo;
        $this->baseUrl = $baseUrl;
        $this->model   = new DashboardAdmin($pdo);
    }

    public function index()
    {
        // Proteksi akses khusus Admin
        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));
        if ($role !== 'admin') {
            header("Location: " . $this->baseUrl . "/public/index.php?page=login");
            exit;
        }

        // Ambil seluruh metrik real dari database
        $totalProgram        = $this->model->getTotalPrograms();
        $programGrowth       = $this->model->getProgramGrowthPercentage();

        $distribusi          = $this->model->getProgramDistributionByType();
        $jenisPrograms       = $distribusi['items'];
        $totalJenisProgram   = $distribusi['grandTotal'];

        // Ambil 2 jenis program terbanyak untuk 2 kartu dinamis di atas
        $topJenis1 = $jenisPrograms[0] ?? ['label' => 'Lomba', 'total' => 0, 'growth' => 0];
        $topJenis2 = $jenisPrograms[1] ?? ['label' => 'Beasiswa', 'total' => 0, 'growth' => 0];

        $totalPendaftar      = $this->model->getTotalRegistrations();
        $pendaftarGrowth     = $this->model->getRegistrationGrowthPercentage();

        $monthlyTrends       = $this->model->getMonthlyRegistrationTrends();
        $recentRegistrations = $this->model->getRecentRegistrations(5);

        // Variabel pendukung View & SidebarController
        $baseUrl    = $this->baseUrl;
        $activeMenu = 'dashboard';
        $adminName  = $_SESSION['user']['name'] ?? 'Admin';

        require_once __DIR__ . '/../Views/dashboard-admin/dashboardAdmin.php';
    }
}