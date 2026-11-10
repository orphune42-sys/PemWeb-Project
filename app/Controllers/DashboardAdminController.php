
<?php

require_once __DIR__ . '/../Models/DashboardAdmin.php';

class DashboardAdminController
{
    public function index()
    {
        $totalUser = DashboardAdmin::getTotalUser();
        $totalAdmin = DashboardAdmin::getTotalAdmin();
        $totalMahasiswa = DashboardAdmin::getTotalMahasiswa();
        $dataUser = DashboardAdmin::getDataUser();

        require __DIR__ . '/../Views/dashboard-admin/dashboardAdmin.php';
    }
}

$controller = new DashboardAdminController();
$controller->index();

?>
