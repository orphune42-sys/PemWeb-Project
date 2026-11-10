
<?php

require_once __DIR__ . '/../Models/DashboardAdmin.php';

class DashboardAdminController
{
    public function index()
    {
        $basePath = '../../';
        $activeMenu = 'dashboard';

        $dashboardData = DashboardAdmin::getDashboardData();

        $totalProgram = $dashboardData['totalProgram'];
        $totalLomba = $dashboardData['totalLomba'];
        $totalBeasiswa = $dashboardData['totalBeasiswa'];
        $totalPendaftar = $dashboardData['totalPendaftar'];

        $recentRegistrations = $dashboardData['recentRegistrations'];
        $monthlyTrends = $dashboardData['monthlyTrends'];

        $pctBeasiswa = $totalProgram > 0
            ? round(($totalBeasiswa / $totalProgram) * 100)
            : 0;

        $pctLomba = $totalProgram > 0
            ? round(($totalLomba / $totalProgram) * 100)
            : 0;

        require __DIR__ . '/../Views/dashboard-admin/dashboardAdmin.php';
    }
}

$controller = new DashboardAdminController();
$controller->index();

?>
