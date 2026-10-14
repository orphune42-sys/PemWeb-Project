<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$nama = 'Naura';

$activities = [
    ['title' => 'UI/UX Design Competition', 'type' => 'UI/UX', 'deadline' => 'Deadline: 12 Feb 2025', 'status' => 'Ongoing', 'status_class' => 'ongoing', 'icon' => '♧', 'color' => 'blue'],
    ['title' => 'Beasiswa Unggulan 2025', 'type' => 'Beasiswa', 'deadline' => 'Deadline: 28 Feb 2025', 'status' => 'Submitted', 'status_class' => 'submitted', 'icon' => '♧', 'color' => 'sky'],
    ['title' => 'Digital Innovation Challenge', 'type' => 'Lomba', 'deadline' => 'Deadline: 5 Mar 2025', 'status' => 'Menang', 'status_class' => 'won', 'icon' => '♧', 'color' => 'mint'],
    ['title' => 'Hackathon Nasional 2025', 'type' => 'Lomba', 'deadline' => 'Deadline: 20 Mar 2025', 'status' => 'Daftar', 'status_class' => 'register', 'icon' => '♧', 'color' => 'lavender']
];

$schedules = [
    ['title' => 'Submission Deadline', 'desc' => 'UI/UX Design Competition · Kumpulkan karya', 'date' => '12 Feb 2025', 'line' => 'blue-line', 'group' => 'Hari Ini'],
    ['title' => 'Pengumuman Focus', 'desc' => 'Beasiswa Unggulan · Cek pengumuman seleksi', 'date' => '14 Feb 2025', 'line' => 'blue-line', 'group' => 'Hari Ini'],
    ['title' => 'Pengumpulan Karya', 'desc' => 'Digital Innovation Challenge · Submit proposal', 'date' => 'Aug 08–28', 'line' => 'orange-line', 'group' => 'Mendatang'],
    ['title' => 'Hackathon Nasional', 'desc' => 'Hackathon Nasional · Registrasi tim', 'date' => '20 Mar 2025', 'line' => 'green-line', 'group' => 'Mendatang']
];

$reminders = [
    ['title' => 'Submission Deadline', 'desc' => 'UI/UX Design Competition', 'time' => 'Hari ini · 23:59', 'class' => 'blue-reminder', 'dot' => 'blue'],
    ['title' => 'Pengumpulan Focus', 'desc' => 'Beasiswa Unggulan 2025', 'time' => 'Besok · 17:00', 'class' => 'orange-reminder', 'dot' => 'orange'],
    ['title' => 'Tips Hari Ini 💡', 'desc' => 'Jangan lupa perbarui portofoliomu sebelum mendaftar lomba!', 'time' => '', 'class' => 'green-reminder', 'dot' => 'green']
];

$opportunities = [
    ['title' => 'UI/UX Design Competition 2025', 'type' => 'Lomba', 'deadline' => 'Deadline: 12 Feb 2025', 'image' => 'https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=700&q=85'],
    ['title' => 'Beasiswa Unggulan 2025', 'type' => 'Beasiswa', 'deadline' => 'Deadline: 28 Feb 2025', 'image' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=700&q=85'],
    ['title' => 'Green Future Challenge', 'type' => 'Challenge', 'deadline' => 'Deadline: 5 Mar 2025', 'image' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?auto=format&fit=crop&w=700&q=80']
];

$boards = [
    ['title' => 'UI/UX Design Competition', 'status' => 'In Progress · Deadline 12 Feb', 'dot' => 'blue'],
    ['title' => 'Beasiswa Unggulan 2025', 'status' => 'Review · Menunggu pengumuman', 'dot' => 'orange'],
    ['title' => 'Hackathon Nasional 2025', 'status' => 'Selesai · Juara 2 🏆', 'dot' => 'green'],
    ['title' => 'Digital Innovation Challenge', 'status' => 'Planning · Mulai 15 Feb', 'dot' => 'blue'],
    ['title' => 'Green Future Challenge', 'status' => 'Wishlist · Daftar segera', 'dot' => 'green']
];

function pageTitle($page)
{
    $titles = ['dashboard' => 'Dashboard', 'explore' => 'Explore Program', 'profile' => 'Profile', 'applications' => 'Applications', 'opportunities' => 'Find Opportunities'];
    return isset($titles[$page]) ? $titles[$page] : 'Dashboard';
}

function isActive($current, $target)
{
    return $current === $target ? 'active' : '';
}

function matchesSearch($title, $search)
{
    return $search === '' || stripos($title, $search) !== false;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(pageTitle($page)); ?> - FindYourPath</title>
    <link rel="stylesheet" href="../sidebarMahasiswa/style.css">
    <link rel="stylesheet" href="user.css">
        

</head>

<body>
    <div class="app">
        <?php
        $activeMenu = 'dashboard';
        $basePath = '../';
        include '../sidebarMahasiswa/sidebar.php';
        ?>

        <main class="main">
            <header class="topbar">
                <form class="search-box" action="mahasiswa.php" method="get">
                    <input type="hidden" name="page" value="<?= htmlspecialchars($page); ?>">
                    <span class="search-icon" aria-hidden="true"><svg viewBox="0 0 24 24">
                            <circle cx="10.8" cy="10.8" r="7.3"></circle>
                            <path d="m16.2 16.2 4.3 4.3"></path>
                        </svg></span>
                    <input type="search" name="q" value="<?= htmlspecialchars($search); ?>" placeholder="Cari lomba, beasiswa, atau lainnya..." aria-label="Cari program">
                    <?php if ($search !== ''): ?><a class="clear-search" href="mahasiswa.php?page=<?= urlencode($page); ?>" aria-label="Hapus pencarian">×</a><?php endif; ?>
                </form>
                <div class="top-actions">
                    <button class="icon-button" id="notificationBtn" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24">
                            <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                            <path d="M10 21h4"></path>
                        </svg></button>
                    <button class="user-menu" id="profileMenuBtn" type="button">
                        <span class="avatar">N</span><span class="user-info"><strong><?= htmlspecialchars($nama); ?></strong><small>Mahasiswa</small></span>
                    </button>
                    <div class="notification-panel" id="notificationPanel">
                        <strong class="panel-title">Notifikasi</strong>
                        <p><span class="small-dot blue"></span> Deadline UI/UX Design Competition hari ini.</p>
                        <p><span class="small-dot orange"></span> Jangan lupa cek pengumuman beasiswa.</p>
                    </div>
                    <div class="profile-panel" id="profilePanel">
                        <a href="mahasiswa.php?page=profile">Lihat Profile</a><a href="mahasiswa.php?page=applications">Applications</a><a href="mahasiswa.php?page=logout" id="profileLogout">Logout</a>
                    </div>
                </div>
            </header>

            <section class="content">
                <?php if ($page === 'logout'): ?>
                    <div class="simple-page">
                        <div class="simple-icon">✓</div>
                        <h1>Berhasil Logout</h1>
                        <p>Session mahasiswa telah diakhiri.</p><a class="primary-button" href="mahasiswa.php?page=dashboard">Kembali ke Dashboard</a>
                    </div>
                <?php elseif ($page === 'dashboard'): ?>
                    <div class="welcome">
                        <h1>Selamat Datang, <?= htmlspecialchars($nama); ?>! <span>👋🏻</span></h1>
                        <p>Terus semangat, banyak peluang terbuka untukmu.</p>
                    </div>
                    <?php if ($search !== ''): ?><div class="search-result-info">Hasil pencarian untuk <strong>“<?= htmlspecialchars($search); ?>”</strong></div><?php endif; ?>
                    <div class="dashboard-grid">
                        <section class="card activity-card">
                            <div class="card-header">
                                <h2>Ringkasan Aktivitas</h2><a href="mahasiswa.php?page=applications">Lihat Semua</a>
                            </div>
                            <div class="activity-list">
                                <?php foreach ($activities as $item): if (!matchesSearch($item['title'], $search)) continue; ?>
                                    <button class="activity-row clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><span class="activity-icon <?= $item['color']; ?>"><?= $item['icon']; ?></span><span class="row-main"><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['type'] . ' · ' . $item['deadline']); ?></small></span><span class="status <?= $item['status_class']; ?>"><?= htmlspecialchars($item['status']); ?></span></button>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <section class="card schedule-card">
                            <div class="card-header">
                                <h2>Jadwal &amp; Pengingat</h2><a href="mahasiswa.php?page=applications">Lihat Semua</a>
                            </div>
                            <?php $lastGroup = '';
                            foreach ($schedules as $item): if ($lastGroup !== $item['group']): ?><div class="schedule-label"><?= htmlspecialchars($item['group']); ?></div><?php $lastGroup = $item['group'];
                                                                                                                                                                                                endif; ?>
                                <button class="schedule-item <?= $item['line']; ?> clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><span class="schedule-copy"><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['desc']); ?></small></span><time><?= htmlspecialchars($item['date']); ?></time></button>
                            <?php endforeach; ?>
                        </section>

                        <section class="card reminder-card" id="reminders">
                            <div class="card-header">
                                <h2>Today's Reminder</h2>
                            </div>
                            <div class="reminder-list">
                                <?php foreach ($reminders as $item): ?><button class="reminder <?= $item['class']; ?> clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><strong><span class="small-dot <?= $item['dot']; ?>"></span><?= htmlspecialchars($item['title']); ?></strong><span><?= htmlspecialchars($item['desc']); ?></span><?php if ($item['time'] !== ''): ?><em><?= htmlspecialchars($item['time']); ?></em><?php endif; ?></button><?php endforeach; ?>
                            </div>
                        </section>

                        <section class="card opportunities-card">
                            <div class="card-header">
                                <h2>Peluang/Kesempatan Lainnya</h2><a href="mahasiswa.php?page=opportunities">Lihat Semua</a>
                            </div>
                            <div class="opportunity-grid">
                                <?php foreach ($opportunities as $item): if (!matchesSearch($item['title'], $search)) continue; ?><button class="opportunity-card clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><img src="<?= htmlspecialchars($item['image']); ?>" alt="Ilustrasi <?= htmlspecialchars($item['title']); ?>"><span class="opportunity-body"><span class="type-badge"><?= htmlspecialchars($item['type']); ?></span><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['deadline']); ?></small></span></button><?php endforeach; ?>
                            </div>
                        </section>

                        <section class="card board-card">
                            <div class="card-header">
                                <h2>My Board</h2>
                            </div>
                            <div class="board-list">
                                <?php foreach ($boards as $item): if (!matchesSearch($item['title'], $search)) continue; ?><button class="board-item clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><span class="board-dot <?= $item['dot']; ?>"></span><span><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['status']); ?></small></span></button><?php endforeach; ?>
                            </div>
                            <div class="board-tip"><span>💡</span>
                                <p>Tips: Perbarui portofoliomu sebelum mendaftar lomba agar lebih menarik perhatian juri!</p>
                            </div>
                        </section>
                    </div>
                <?php elseif ($page === 'profile'): ?>
                    <div class="page-heading">
                        <h1>Profile</h1>
                        <p>Informasi profil mahasiswa.</p>
                    </div>
                    <section class="card simple-page">
                        <div class="large-avatar">N</div>
                        <h2><?= htmlspecialchars($nama); ?></h2>
                        <p>Mahasiswa</p>
                        <p>Lengkapi informasi profilmu untuk mempermudah proses pendaftaran program.</p>
                    </section>
                <?php elseif ($page === 'applications'): ?>
                    <div class="page-heading">
                        <h1>Applications</h1>
                        <p>Ringkasan program yang sedang kamu ikuti.</p>
                    </div>
                    <section class="card page-list-card"><?php foreach ($activities as $item): if (!matchesSearch($item['title'], $search)) continue; ?><div class="full-list-row"><span><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['type'] . ' · ' . $item['deadline']); ?></small></span><span class="status <?= $item['status_class']; ?>"><?= htmlspecialchars($item['status']); ?></span></div><?php endforeach; ?></section>
                <?php elseif ($page === 'explore' || $page === 'opportunities'): ?>
                    <div class="page-heading">
                        <h1><?= htmlspecialchars(pageTitle($page)); ?></h1>
                        <p>Temukan lomba, beasiswa, dan kesempatan menarik lainnya.</p>
                    </div>
                    <div class="explore-grid"><?php foreach ($opportunities as $item): if (!matchesSearch($item['title'], $search)) continue; ?><button class="opportunity-card clickable-row" type="button" data-title="<?= htmlspecialchars($item['title']); ?>"><img src="<?= htmlspecialchars($item['image']); ?>" alt="Ilustrasi <?= htmlspecialchars($item['title']); ?>"><span class="opportunity-body"><span class="type-badge"><?= htmlspecialchars($item['type']); ?></span><strong><?= htmlspecialchars($item['title']); ?></strong><small><?= htmlspecialchars($item['deadline']); ?></small></span></button><?php endforeach; ?></div>
                <?php endif; ?>
            </section>
        </main>
    </div>
    <div class="toast" id="toast" role="status" aria-live="polite"></div>
    <script src="user.js"></script>
</body>

</html>