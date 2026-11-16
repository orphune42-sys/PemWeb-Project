<?php
class SidebarAdmin
{
    public function getMenuItems(string $baseUrl): array
    {
        return [
            [
                'key'   => 'dashboard',
                'title' => 'Dashboard',
                'url'   => $baseUrl . '/public/index.php?page=dashboard-admin'
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
                'url'   => $baseUrl . '/public/index.php?page=management-regist'
            ],
            [
                'key'   => 'profile',
                'title' => 'Profile',
                'url'   => $baseUrl . '/public/index.php?page=profile-admin'
            ],
        ];
    }
}
