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
        <?php
        $activeMenu = 'explore-program';
        $basePath = '../';
        include '../sidebarMahasiswa/sidebar.php';
        ?>
        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <button type="button" class="hamburger-btn" id="sidebarToggleBtn" aria-label="Buka Menu">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="header-title">Explore Program</h1>
                </div>

                <div class="header-right">
                    <button type="button" class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="header-user">
                        <div class="header-avatar">N</div>
                        <div class="header-userinfo">
                            <span class="header-username">Naura</span>
                            <span class="header-userrole">Mahasiswa</span>
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

                            <a href="../detail_program/detail_program.php?id=<?= urlencode($prog['id']) ?>" class="detail-link">
                                Lihat Detail &rarr;
                            </a>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>

</html>