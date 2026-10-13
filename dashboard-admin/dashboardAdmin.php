<?php
$basePath = '../';
$activeMenu = 'dashboard';

$programs = [
    ['id' => 1,  'title' => 'Beasiswa Unggulan 2026',  'category' => 'Beasiswa'],
    ['id' => 2,  'title' => 'Beasiswa Prestasi BUMN',  'category' => 'Beasiswa'],
    ['id' => 3,  'title' => 'Beasiswa Talenta Muda',   'category' => 'Beasiswa'],
    ['id' => 4,  'title' => 'Beasiswa Pemuda Bangsa',  'category' => 'Beasiswa'],
    ['id' => 5,  'title' => 'Beasiswa Karya Indonesia', 'category' => 'Beasiswa'],
    ['id' => 6,  'title' => 'Beasiswa Indonesia Maju', 'category' => 'Beasiswa'],
    ['id' => 7,  'title' => 'Beasiswa Riset Akademik', 'category' => 'Beasiswa'],
    ['id' => 8,  'title' => 'National UI/UX Competition', 'category' => 'Lomba'],
    ['id' => 9,  'title' => 'Hackathon Web Dev 2026',    'category' => 'Lomba'],
    ['id' => 10, 'title' => 'Lomba Karya Tulis Ilmiah',  'category' => 'Lomba'],
    ['id' => 11, 'title' => 'Business Plan Challenge',   'category' => 'Lomba'],
    ['id' => 12, 'title' => 'Cyber Security Contest',   'category' => 'Lomba'],
    ['id' => 13, 'title' => 'Lomba AI & Data Science',   'category' => 'Lomba'],
];

$totalProgram  = count($programs);
$totalLomba    = count(array_filter($programs, fn($p) => $p['category'] === 'Lomba'));
$totalBeasiswa = count(array_filter($programs, fn($p) => $p['category'] === 'Beasiswa'));

$pctBeasiswa = $totalProgram > 0 ? round(($totalBeasiswa / $totalProgram) * 100) : 0;
$pctLomba    = $totalProgram > 0 ? round(($totalLomba / $totalProgram) * 100) : 0;

$recentRegistrations = [
    [
        'name'    => 'Sarah Cantika',
        'program' => 'Beasiswa Unggulan 2026',
        'date'    => '13 Okt 2026, 09:41',
        'status'  => 'Disetujui',
        'avatar'  => 'SC',
        'badge'   => 'status-badge--approved',
        'color'   => 'student-avatar--teal'
    ],
    [
        'name'    => 'Mike Rossa',
        'program' => 'National UI/UX Competition',
        'date'    => '12 Okt 2026, 14:20',
        'status'  => 'Tertunda',
        'avatar'  => 'MR',
        'badge'   => 'status-badge--pending',
        'color'   => 'student-avatar--navy'
    ],
    [
        'name'    => 'Alex Kenandra',
        'program' => 'Hackathon Web Dev 2026',
        'date'    => '11 Okt 2026, 17:10',
        'status'  => 'Ditolak',
        'avatar'  => 'AK',
        'badge'   => 'status-badge--rejected',
        'color'   => 'student-avatar--red'
    ],
    [
        'name'    => 'Jenna Alifia',
        'program' => 'Lomba Karya Tulis Ilmiah',
        'date'    => '10 Okt 2026, 11:05',
        'status'  => 'Disetujui',
        'avatar'  => 'JL',
        'badge'   => 'status-badge--approved',
        'color'   => 'student-avatar--green'
    ],
    [
        'name'    => 'David Pratama',
        'program' => 'Beasiswa Prestasi BUMN',
        'date'    => '09 Okt 2026, 10:05',
        'status'  => 'Tertunda',
        'avatar'  => 'DP',
        'badge'   => 'status-badge--pending',
        'color'   => 'student-avatar--teal'
    ],
];

$totalPendaftar = 148;

$monthlyTrends = [
    'Jun' => 110,
    'Jul' => 160,
    'Agu' => 240,
    'Sep' => 320,
    'Okt' => 420
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - FindYourPath</title>

    <link rel="stylesheet" href="../dashboard-admin/style.css">
    <link rel="stylesheet" href="../sidebarAdmin/style.css">
</head>

<body>

    <?php include '../sidebarAdmin/sidebar.php'; ?>

    <div class="main-wrapper">

        <header class="header">
            <div class="search-box">
                <span class="search-icon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" placeholder="Cari lomba, beasiswa, atau lainnya..." class="search-input">
            </div>
            <div class="header-right">
                <button class="header-notif">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
                <div class="header-user">
                    <div class="header-avatar">A</div>
                    <div class="header-userinfo">
                        <div class="header-username">Admin</div>
                        <div class="header-userrole">Administrator</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <h2 class="greeting-title">Hi, Admin!</h2>
            <p class="greeting-sub">Kelola program, lomba, beasiswa dengan mudah dalam satu platform!</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <h3 class="stat-card-title">Total Program</h3>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.8458 16.1779C17.5332 16.4904 17.1093 16.666 16.6672 16.666H3.33282C2.89075 16.666 2.4668 16.4904 2.15421 16.1779C1.84162 15.8653 1.66602 15.4414 1.66602 14.9994V4.16659C1.66602 3.72458 1.84162 3.30068 2.15421 2.98813C2.4668 2.67559 2.89075 2.5 3.33282 2.5H6.60808C6.88402 2.50005 7.15564 2.56859 7.39856 2.69949C7.64147 2.83038 7.84809 3.01953 7.99986 3.24996L8.67491 4.24992C8.82822 4.48272 9.03748 4.67335 9.28354 4.80437C9.5296 4.9354 9.80459 5.00262 10.0834 4.99988H16.6672C17.1093 4.99988 17.5332 5.17547 17.8458 5.48801C18.1584 5.80056 18.334 6.22446 18.334 6.66647V14.9994C18.334 15.4414 18.1584 15.8653 17.8458 16.1779Z" stroke="#4EACD4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value"><?= $totalProgram; ?></div>
                    <div class="stat-card-trend">
                        <span class="trend-up">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.8338 7.58324V4.0838H9.33352M12.8338 4.0838L7.87507 9.04134L4.95817 6.12514L1.1662 9.9162" stroke="#22C55E" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            12%
                        </span>
                        dari bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <h3 class="stat-card-title">Total Lomba</h3>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.33322 12.217V13.5721C8.33007 13.8576 8.25361 14.1376 8.11118 14.3851C7.96875 14.6326 7.76512 14.8394 7.51982 14.9855C6.99906 15.3713 6.57544 15.8731 6.28262 16.4512C5.9898 17.0293 5.83586 17.6678 5.83302 18.3158M11.6668 12.217V13.5721C11.67 13.8576 11.7464 14.1376 11.8888 14.3851C12.0313 14.6326 12.2349 14.8394 12.4802 14.9855C13.001 15.3713 13.4246 15.8731 13.7174 16.4512C14.0102 17.0293 14.1642 17.6678 14.167 18.3158M15.0004 7.49982H16.2505C16.8031 7.49982 17.333 7.2803 17.7238 6.88957C18.1145 6.49884 18.334 5.96889 18.334 5.41632C18.334 4.86374 18.1145 4.33379 17.7238 3.94306C17.333 3.55233 16.8031 3.33282 16.2505 3.33282H15.0004M15.0004 7.49982C15.0004 8.826 14.4736 10.0979 13.5358 11.0356C12.5981 11.9734 11.3262 12.5002 10 12.5002C8.67383 12.5002 7.40196 11.9734 6.4642 11.0356C5.52644 10.0979 4.99962 8.826 4.99962 7.49982M15.0004 7.49982V2.49942C15.0004 2.27838 14.9126 2.06641 14.7563 1.91011C14.6 1.75382 14.388 1.66602 14.167 1.66602H5.83302C5.61198 1.66602 5.40001 1.75382 5.24371 1.91011C5.08742 2.06641 4.99962 2.27838 4.99962 2.49942V7.49982M4.99962 7.49982H3.74952C3.19694 7.49982 2.66699 7.2803 2.27626 6.88957C1.88553 6.49884 1.66602 5.96889 1.66602 5.41632C1.66602 4.86374 1.88553 4.33379 2.27626 3.94306C2.66699 3.55233 3.19694 3.33282 3.74952 3.33282H4.99962M3.33282 18.334H16.6672" stroke="#4EACD4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value"><?= $totalLomba; ?></div>
                    <div class="stat-card-trend">
                        <span class="trend-up">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.8338 7.58324V4.0838H9.33352M12.8338 4.0838L7.87507 9.04134L4.95817 6.12514L1.1662 9.9162" stroke="#22C55E" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            8%
                        </span>
                        dari bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <h3 class="stat-card-title">Total Beasiswa</h3>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3325 8.33316V13.3337M5.00022 10.4167V13.3337C5.00022 13.9968 5.52696 14.6328 6.46457 15.1017C7.40218 15.5706 8.67385 15.834 9.99982 15.834C11.3258 15.834 12.5975 15.5706 13.5351 15.1017C14.4727 14.6328 14.9994 13.9968 14.9994 13.3337V10.4167M17.849 9.10188C17.9982 9.03606 18.1248 8.92792 18.2131 8.79085C18.3014 8.65378 18.3476 8.4938 18.3459 8.33074C18.3442 8.16767 18.2948 8.00869 18.2036 7.87347C18.1125 7.73826 17.9837 7.63276 17.8332 7.57004L10.6913 4.31633C10.4741 4.21728 10.2383 4.16602 9.99965 4.16602C9.76102 4.16602 9.52516 4.21728 9.30804 4.31633L2.16695 7.5667C2.0186 7.63169 1.8924 7.7385 1.80378 7.87408C1.71516 8.00967 1.66797 8.16814 1.66797 8.33012C1.66797 8.49211 1.71516 8.65058 1.80378 8.78616C1.8924 8.92174 2.0186 9.02856 2.16695 9.09354L9.30804 12.3506C9.52516 12.4496 9.76102 12.5009 9.99965 12.5009C10.2383 12.5009 10.4741 12.4496 10.6913 12.3506L17.849 9.10188Z" stroke="#4EACD4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value"><?= $totalBeasiswa; ?></div>
                    <div class="stat-card-trend">
                        <span class="trend-up">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.8338 7.58324V4.0838H9.33352M12.8338 4.0838L7.87507 9.04134L4.95817 6.12514L1.1662 9.9162" stroke="#22C55E" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            15%
                        </span>
                        dari bulan lalu
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <h3 class="stat-card-title">Total Pendaftar</h3>
                        <div class="stat-card-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3336 17.5V15.8333C13.3336 14.9493 12.9824 14.1014 12.3572 13.4763C11.7321 12.8512 10.8841 12.5 10 12.5H4.99962C4.11549 12.5 3.26758 12.8512 2.6424 13.4763C2.01723 14.1014 1.66602 14.9493 1.66602 15.8333V17.5M13.3336 2.60661C14.0485 2.79192 14.6816 3.20933 15.1335 3.79333C15.5854 4.37733 15.8306 5.09485 15.8306 5.83327C15.8306 6.5717 15.5854 7.28922 15.1335 7.87322C14.6816 8.45722 14.0485 8.87463 13.3336 9.05994M18.334 17.4999V15.8332C18.3335 15.0947 18.0876 14.3772 17.6351 13.7935C17.1826 13.2098 16.549 12.7929 15.8338 12.6082M10.8334 5.83333C10.8334 7.67428 9.34091 9.16667 7.49982 9.16667C5.65872 9.16667 4.16622 7.67428 4.16622 5.83333C4.16622 3.99238 5.65872 2.5 7.49982 2.5C9.34091 2.5 10.8334 3.99238 10.8334 5.83333Z" stroke="#4EACD4" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value"><?= number_format($totalPendaftar, 0, ',', '.'); ?></div>
                    <div class="stat-card-trend">
                        <span class="trend-up">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.8338 7.58324V4.0838H9.33352M12.8338 4.0838L7.87507 9.04134L4.95817 6.12514L1.1662 9.9162" stroke="#22C55E" stroke-width="2" stroke-linecap="round" />
                            </svg>
                            23%
                        </span>
                        dari bulan lalu
                    </div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3 class="chart-title">Tren Pendaftaran Mahasiswa</h3>
                    <p class="chart-subtitle">Grafik pendaftaran mahasiswa 5 bulan terakhir</p>
                    <div class="chart-area" id="chartArea">
                        <div class="chart-y-axis">
                            <span>500</span><span>375</span><span>250</span><span>125</span><span>0</span>
                        </div>
                        <div class="chart-grid-line chart-grid-line--bottom"></div>
                        <div class="chart-grid-line chart-grid-line--q1"></div>
                        <div class="chart-grid-line chart-grid-line--q2"></div>
                        <div class="chart-grid-line chart-grid-line--q3"></div>

                        <div class="chart-svg-container" id="chartSvgContainer"></div>

                        <div class="chart-x-axis">
                            <?php foreach ($monthlyTrends as $month => $val): ?>
                                <span><?= $month; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <h3 class="chart-title">Kategori Program</h3>
                    <p class="chart-subtitle">Distribusi program berdasarkan kategori</p>
                    <div class="category-content">
                        <div class="donut-chart" style="background: conic-gradient(#4EACD4 0% <?= $pctBeasiswa; ?>%, #60A5FA <?= $pctBeasiswa; ?>% 100%);">
                            <div class="donut-inner">
                                <span class="donut-value"><?= $totalProgram; ?></span>
                                <span class="donut-label">Total</span>
                            </div>
                        </div>
                        <div class="legend">
                            <div class="legend-item">
                                <span class="legend-dot legend-dot--blue"></span>
                                <span class="legend-name">Beasiswa</span>
                                <b class="legend-count"><?= $totalBeasiswa; ?></b>
                                <span class="legend-pct"><?= $pctBeasiswa; ?>%</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot legend-dot--blue-light"></span>
                                <span class="legend-name">Lomba</span>
                                <b class="legend-count"><?= $totalLomba; ?></b>
                                <span class="legend-pct"><?= $pctLomba; ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-card-header">
                    <div>
                        <h3 class="table-card-title">Pendaftaran Terbaru</h3>
                        <p class="table-card-subtitle">Daftar pendaftaran program terbaru dari mahasiswa</p>
                    </div>
                    <a href="../management-regist/managementRegist.php" class="view-all-link">Lihat Semua &rarr;</a>
                </div>
                <table class="registrations-table" id="dashboardTable">
                    <thead>
                        <tr class="table-header-row">
                            <th>Nama Mahasiswa</th>
                            <th>Program</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentRegistrations as $row): ?>
                            <tr class="table-row">
                                <td class="student-cell">
                                    <div class="student-avatar <?= $row['color']; ?>"><?= $row['avatar']; ?></div>
                                    <span class="student-name"><?= htmlspecialchars($row['name']); ?></span>
                                </td>
                                <td>
                                    <span class="program-badge program-badge--blue">
                                        <?= htmlspecialchars($row['program']); ?>
                                    </span>
                                </td>
                                <td class="date-cell"><?= htmlspecialchars($row['date']); ?></td>
                                <td>
                                    <span class="status-badge <?= $row['badge']; ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const monthlyTrendData = <?= json_encode($monthlyTrends); ?>;
    </script>
    <script src="script.js?v=2"></script>
    <script src="../sidebarAdmin/script.js"></script>
</body>

</html>