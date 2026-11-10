<?php
class exploreProgram
{
    public function getAll(): array
    {
        $programs = [
    [
        'id' => 1,
        'type' => 'Lomba',
        'title' => 'UI/UX Design Competition 2025',
        'organizer' => 'Universitas Sriwijaya',
        'location' => 'Online',
        'date' => '12 Mar 2025',
        'category' => 'Teknologi',
        'desc' => 'Kompetisi desain UI/UX untuk mahasiswa dan siswa SMA/K nasional. Tunjukkan ide kreatifmu dalam meran...',
        'image' => 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=500&auto=format&fit=crop'
    ],
    [
        'id' => 2,
        'type' => 'Beasiswa',
        'title' => 'Beasiswa Unggulan 2025',
        'organizer' => 'Kementerian Keuangan RI',
        'location' => 'Online',
        'date' => '20 Apr 2025',
        'category' => 'Pendidikan',
        'desc' => 'Program beasiswa penuh untuk jenjang pendidikan sarjana (S1), magister (S2), dan doktor (S3) di dalam...',
        'image' => 'https://images.unsplash.com/photo-1562774053-701939374585?w=500&auto=format&fit=crop'
    ],
    [
        'id' => 3,
        'type' => 'Lomba',
        'title' => 'Tech For Impact Hackathon',
        'organizer' => 'Telkom University',
        'location' => 'Offline (Bandung)',
        'date' => '25 Mei 2025',
        'category' => 'Teknologi',
        'desc' => 'Wadahkan solusi inovasi teknologi yang berdampak bagi masyarakat. Terbuka untuk seluruh mahasiswa a...',
        'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=500&auto=format&fit=crop'
    ],
    [
        'id' => 4,
        'type' => 'Beasiswa',
        'title' => 'IPB Prestasi Scholarship',
        'organizer' => 'Institut Pertanian Bogor',
        'location' => 'Online',
        'date' => '10 Jun 2025',
        'category' => 'Pendidikan',
        'desc' => 'Beasiswa bagi mahasiswa berprestasi tingkat nasional maupun internasional di IPB.',
        'image' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=500&auto=format&fit=crop'
    ],
    [
        'id' => 5,
        'type' => 'Lomba',
        'title' => 'National Business Plan Competition',
        'organizer' => 'Universitas Gadjah Mada',
        'location' => 'Online',
        'date' => '05 Agt 2025',
        'category' => 'Bisnis',
        'desc' => 'Kompetisi bisnis plan nasional untuk mahasiswa dengan ide inovatif dan berkelanjutan.',
        'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=500&auto=format&fit=crop'
    ],
    [
        'id' => 6,
        'type' => 'Beasiswa',
        'title' => 'Djarum Beasiswa Plus',
        'organizer' => 'Djarum Foundation',
        'location' => 'Online',
        'date' => '15 Sep 2025',
        'category' => 'Pendidikan',
        'desc' => 'Program beasiswa untuk mahasiswa D3, D4, dan S1 dengan peluang pengembangan karakter.',
        'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=500&auto=format&fit=crop'
    ]
];
        return $programs;
    }
}
