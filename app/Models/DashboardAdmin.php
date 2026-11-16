
<?php

class DashboardAdmin
{
    public static function getDashboardData()
    {
        $totalProgram = 48;
        $totalLomba = 28;
        $totalBeasiswa = 12;
        $totalPendaftar = 148;

        $recentRegistrations = [
            [
                'name' => 'Sarah Cantika',
                'program' => 'Beasiswa Unggulan 2026',
                'date' => '13 Okt 2026, 09:41',
                'status' => 'Disetujui',
                'avatar' => 'SC',
                'badge' => 'status-badge--approved',
                'color' => 'student-avatar--teal'
            ],
            [
                'name' => 'Mike Rossa',
                'program' => 'National UI/UX Competition',
                'date' => '12 Okt 2026, 14:20',
                'status' => 'Tertunda',
                'avatar' => 'MR',
                'badge' => 'status-badge--pending',
                'color' => 'student-avatar--navy'
            ],
            [
                'name' => 'Alex Kenandra',
                'program' => 'Hackathon Web Dev 2026',
                'date' => '11 Okt 2026, 17:10',
                'status' => 'Ditolak',
                'avatar' => 'AK',
                'badge' => 'status-badge--rejected',
                'color' => 'student-avatar--red'
            ],
            [
                'name' => 'Jenna Alifia',
                'program' => 'Lomba Karya Tulis Ilmiah',
                'date' => '10 Okt 2026, 11:05',
                'status' => 'Disetujui',
                'avatar' => 'JL',
                'badge' => 'status-badge--approved',
                'color' => 'student-avatar--green'
            ],
            [
                'name' => 'David Pratama',
                'program' => 'Beasiswa Prestasi BUMN',
                'date' => '09 Okt 2026, 10:05',
                'status' => 'Tertunda',
                'avatar' => 'DP',
                'badge' => 'status-badge--pending',
                'color' => 'student-avatar--teal'
            ]
        ];

        $monthlyTrends = [
            'Jun' => 110,
            'Jul' => 160,
            'Agu' => 240,
            'Sep' => 320,
            'Okt' => 420
        ];

        return [
            'totalProgram' => $totalProgram,
            'totalLomba' => $totalLomba,
            'totalBeasiswa' => $totalBeasiswa,
            'totalPendaftar' => $totalPendaftar,
            'recentRegistrations' => $recentRegistrations,
            'monthlyTrends' => $monthlyTrends
        ];
    }
}
?>
