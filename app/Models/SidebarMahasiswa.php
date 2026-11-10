<?php
class SidebarMahasiswa
{
    public function getMenuItems(string $baseUrl): array
    {
        return [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => $baseUrl . '/dashboard-user/user.php'
            ],
            [
                'key' => 'explore-program',
                'title' => 'Explore Program',
                'url' => $baseUrl . '/public/index.php?page=explore-program'
            ],
            [
                'key' => 'fine-opportunities',
                'title' => 'Find Opportunities',
                'url' => $baseUrl . '/pendaftaran_program/form_pendaftaran.php'
            ],
            [
                'key' => 'profile',
                'title' => 'Profile',
                'url' => $baseUrl . '/profile_user/profile_user.php'
            ],
        ];
    }
}
