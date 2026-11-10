<?php
class SidebarAdmin {
    public function getMenuItems(string $baseUrl): array {
        return [
            [
                'key'   => 'dashboard',
                'title' => 'Dashboard',
                'url'   => $baseUrl . '/dashboard-admin/dashboardAdmin.php'
            ],
            [
                'key'   => 'program',
                'title' => 'Program',
                'url'   => $baseUrl . '/program-management/program.php'
            ],
            [
                'key'   => 'schedule',
                'title' => 'Schedule',
                'url'   => $baseUrl . '/public/index.php?page=schedule'
            ],
            [
                'key'   => 'registration',
                'title' => 'Registration',
                'url'   => $baseUrl . '/management-regist/managementRegist.php'
            ],
            [
                'key'   => 'profile',
                'title' => 'Profile',
                'url'   => $baseUrl . '/public/index.php?page=profile-admin'
            ],
        ];
    }
}