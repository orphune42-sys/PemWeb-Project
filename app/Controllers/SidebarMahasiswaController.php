<?php
require_once __DIR__ . '/../Models/SidebarMahasiswa.php';

class SidebarMahasiswaController
{
    private SidebarMahasiswa $sidebarModel;

    public function __construct()
    {
        $this->sidebarModel = new SidebarMahasiswa();
    }

    public function render(string $activeMenu = '', string $baseUrl = ''): void
    {
        $menuItems = $this->sidebarModel->getMenuItems($baseUrl);
        $logoUrl = $baseUrl . '/public/images/LOGO.png';
        $logoutUrl = $baseUrl . '/logout.php';

        require __DIR__ . '/../Views/layouts/sidebarMahasiswa.php';
    }
}
