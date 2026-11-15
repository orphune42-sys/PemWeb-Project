<?php
class SidebarMahasiswa
{
    public function getMenuItems(string $baseUrl): array
    {
        $indexUrl = $baseUrl . '/public';

        return [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => $indexUrl . '?page=dashboard-user'
            ],
            [
                'key' => 'explore-program',
                'title' => 'Explore Program',
                'url' => $indexUrl . '?page=explore-program'
            ],
            [
                'key' => 'find-opportunities',
                'title' => 'Find Opportunities',
                'url' => $indexUrl . '?page=opportunity'
            ],
            [
                'key' => 'profile',
                'title' => 'Profile',
                'url' => $indexUrl . '?page=profile-user'
            ],
        ];
    }
}
