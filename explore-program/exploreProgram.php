<?php
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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Program - FindYourPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../sidebarMahasiswa/style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <?php
            $activeMenu = 'explore-program';
            $basePath = '../';
            include '../sidebarMahasiswa/sidebar.php';
            ?>

            <div class="sidebar-footer">
                <a href="../login/login.php" class="logout-link">Logout</a>
            </div>
        </aside>
        <main class="main-content">
            <header class="top-header">
                <button type="button" class="hamburger-btn" id="sidebarToggleBtn" aria-label="Buka Menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>

                <h1 class="page-title">Explore Program</h1>
                <div class="header-user">
                    <button type="button" class="btn-icon" aria-label="Notifikasi">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>
                    <div class="user-profile">
                        <div class="avatar">H</div>
                        <div class="user-info">
                            <span class="user-name">Hafshah</span>
                            <span class="user-role">Mahasiswa</span>
                        </div>
                    </div>
                </div>
            </header>

            <section class="hero-banner">
                <div class="banner-content">
                    <span class="badge-tag">Explore Program</span>
                    <h2>Temukan Lomba & Beasiswa Terbaik untuk Masa Depanmu!</h2>
                    <p>Jelajahi berbagai program kompetisi dan beasiswa dari dalam dan luar negeri, serta wujudkan potensi terbaikmu.</p>
                </div>
            </section>

            <section class="filter-section">
                <div class="search-box">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari program berdasarkan kata kunci...">
                </div>

                <div class="select-group">
                    <div class="select-wrapper">
                        <label>Jenis</label>
                        <select id="typeFilter">
                            <option value="Semua">Semua</option>
                            <option value="Lomba">Lomba</option>
                            <option value="Beasiswa">Beasiswa</option>
                        </select>
                    </div>

                    <div class="select-wrapper">
                        <label>Bidang Minat</label>
                        <select id="categoryFilter">
                            <option value="Semua">Semua</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Bisnis">Bisnis</option>
                        </select>
                    </div>

                    <!-- <button class="btn-search" id="btnSearch">Cari</button> -->
                </div>
            </section>

            <section class="program-grid" id="programGrid">
                <?php foreach ($programs as $prog): ?>
                    <div class="program-card"
                        data-title="<?= strtolower(htmlspecialchars($prog['title'])) ?>"
                        data-type="<?= strtolower($prog['type']) ?>"
                        data-category="<?= strtolower($prog['category']) ?>">
                        <div class="card-image">
                            <img src="<?= htmlspecialchars($prog['image']) ?>" alt="<?= htmlspecialchars($prog['title']) ?>">
                            <span class="type-badge <?= strtolower($prog['type']) ?>"><?= htmlspecialchars($prog['type']) ?></span>
                            <button class="bookmark-btn" title="Simpan Bookmark">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?= htmlspecialchars($prog['title']) ?></h3>
                            <p class="card-organizer"><?= htmlspecialchars($prog['organizer']) ?></p>

                            <div class="card-meta">
                                <span class="meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <?= htmlspecialchars($prog['location']) ?>
                                </span>
                                <span class="meta-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?= htmlspecialchars($prog['date']) ?>
                                </span>
                            </div>

                            <p class="card-desc"><?= htmlspecialchars($prog['desc']) ?></p>

                            <a href="#" class="detail-link">Lihat Detail &rarr;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>

</html>