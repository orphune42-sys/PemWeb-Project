
<?php

require_once __DIR__ . '/../Models/ManagementRegist.php';

class ManagementRegistController
{
    public function index()
    {
        $basePath = '../../';
        $activeMenu = 'registration';

        $allRegistrations = ManagementRegist::getAllRegistrations();

        $limit = 10;

        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        if ($page < 1) {
            $page = 1;
        }

        $totalRegistrations = count($allRegistrations);

        $totalPages = (int) ceil($totalRegistrations / $limit);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $limit;

        $registrations = array_slice(
            $allRegistrations,
            $offset,
            $limit
        );

        require __DIR__ . '/../Views/management-regist/managementRegist.php';
    }
}

$controller = new ManagementRegistController();

$controller->index();
