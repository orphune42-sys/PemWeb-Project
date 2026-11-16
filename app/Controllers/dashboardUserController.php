<?php

require_once __DIR__ . '/../Models/dashboardUserModel.php';

class dashboardUserController
{
    private $model;
    private $baseUrl;

    public function __construct($pdo = null, $baseUrl = '')
    {
        $this->model = new dashboardUserModel($pdo);
        $this->baseUrl = $baseUrl;
    }

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;

        $data = $this->model->getDashboardData($userId);

        $user = $data['user'];
        $programs = $data['programs'];
        $registrations = $data['registrations'];
        $notifications = $data['notifications'];

        $baseUrl = $this->baseUrl;

        require __DIR__ . '/../Views/dashboard-user/dashboardUser.php';
    }
}
