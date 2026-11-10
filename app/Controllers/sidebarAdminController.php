<?php
require_once __DIR__ . '/../Models/sidebarAdmin.php';

class SidebarAdminController
{
    private SidebarAdmin $sidebarModel;

    public function __construct()
    {
        $this->sidebarModel = new SidebarAdmin();
    }

    public function render(string $activeMenu = '', string $baseUrl = ''): void
    {
        $menuItems = $this->sidebarModel->getMenuItems($baseUrl);
        $logoUrl = $baseUrl . '/public/images/LOGO.png';
        $logoutUrl = $baseUrl . '/logout.php';

        require __DIR__ . '/../Views/layouts/sidebarAdmin.php';
    }
}