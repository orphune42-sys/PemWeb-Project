<?php
$baseUrl = $baseUrl ?? '';

$user = $user ?? null;
$programs = $programs ?? [];
$registrations = $registrations ?? [];
$notifications = $notifications ?? [];

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
} ?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard User - FindYourPath</title>

    <link rel="stylesheet" href="<?= e($baseUrl) ?>/css/dashboardUser.css">
</head>

<body>
    <main class="dashboard-user">
        <header class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="header-actions">
                <button type="button" id="notificationBtn">
                    Notifikasi
                </button>
                <div id="notificationPanel" class="dropdown-panel">
                    <h3>Notifikasi</h3>
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $notification): ?>
                            <div class="notification-item">
                                <p>
                                    Pendaftaran
                                    <?= e($notification['nama_program'] ?? $notification['jenis_program'] ?? 'Program') ?>
                                </p>
                                <p>
                                    Status:
                                    <?= e($notification['status'] ?? '-') ?>
                                </p>
                                <small>
                                    <?= e($notification['created_at'] ?? '') ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Belum ada notifikasi.</p>
                    <?php endif; ?>
                </div>

                <button type="button" id="profileMenuBtn">
                    Profil
                </button>

                <div id="profilePanel" class="dropdown-panel">
                    <p>
                        <?= e($user['name'] ?? 'Pengguna') ?>
                    </p>
                    <a href="<?= e($baseUrl) ?>/?page=profile-user">
                        Lihat Profil
                    </a>
                    <a href="<?= e($baseUrl) ?>/?page=logout"
                        id="profileLogout">
                        Logout
                    </a>
                </div>
            </div>
        </header>

        <section class="dashboard-content">
            <h2>
                Selamat Datang,
                <?= e($user['name'] ?? 'Pengguna') ?>!
            </h2>
            <p>
                Temukan berbagai peluang lomba, beasiswa, dan program
                pengembangan diri untuk mendukung perjalanan kariermu.
            </p>
            <?php if (!empty($user)): ?>
                <div class="user-information">
                    <h3>Informasi Pengguna</h3>
                    <p>
                        <strong>Nama:</strong>
                        <?= e($user['name'] ?? '-') ?>
                    </p>
                    <p>
                        <strong>NIM:</strong>
                        <?= e($user['nim'] ?? '-') ?>
                    </p>
                    <p>
                        <strong>Universitas:</strong>
                        <?= e($user['university'] ?? '-') ?>
                    </p>
                    <p>
                        <strong>Program Studi:</strong>
                        <?= e($user['major'] ?? '-') ?>
                    </p>
                </div>

            <?php endif; ?>

            <section class="program-search">
                <h2>Cari Program</h2>
                <form class="search-box" method="GET"
                    action="<?= e($baseUrl) ?>/">
                    <input
                        type="hidden"
                        name="page"
                        value="dashboard-user">
                    <input
                        type="text"
                        name="q"
                        placeholder="Cari program..."
                        value="<?= e($_GET['q'] ?? '') ?>">
                    <button type="submit">
                        Cari
                    </button>
                </form>
            </section>

            <section class="program-section">
                <h2>Program Tersedia</h2>
                <?php if (!empty($programs)): ?>
                    <div class="program-list">
                        <?php foreach ($programs as $program): ?>
                            <div
                                class="clickable-row"
                                data-title="<?= e($program['nama_program'] ?? 'Program') ?>">
                                <h3>
                                    <?= e($program['nama_program'] ?? '-') ?>
                                </h3>
                                <p>
                                    <strong>Kategori:</strong>
                                    <?= e($program['jenis_kategori'] ?? '-') ?>
                                </p>
                                <p>
                                    <strong>Status:</strong>
                                    <?= e($program['status'] ?? '-') ?>
                                </p>
                                <p>
                                    <strong>Tanggal Pelaksanaan:</strong>
                                    <?= e($program['tanggal_pelaksanaan'] ?? '-') ?>
                                </p>
                                <p>
                                    <strong>Lokasi:</strong>
                                    <?= e($program['lokasi'] ?? '-') ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Belum ada program yang tersedia.</p>
                <?php endif; ?>
            </section>

            <section class="registration-section">
                <h2>Riwayat Pendaftaran</h2>
                <?php if (!empty($registrations)): ?>
                    <div class="registration-list">
                        <?php foreach ($registrations as $registration): ?>
                            <div class="registration-item">
                                <h3>
                                    <?= e($registration['nama_program'] ?? $registration['jenis_program'] ?? 'Program') ?>
                                </h3>
                                <p>
                                    <strong>Status:</strong>
                                    <?= e($registration['status'] ?? '-') ?>
                                </p>
                                <p>
                                    <strong>Tanggal Pendaftaran:</strong>
                                    <?= e($registration['created_at'] ?? '-') ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Kamu belum memiliki riwayat pendaftaran.</p>
                <?php endif; ?>
            </section>
        </section>

        <a href="<?= e($baseUrl) ?>/?page=logout" id="logoutBtn">
            Logout
        </a>
    </main>
    <div id="toast" class="toast"></div>
    <script src="<?= e($baseUrl) ?>/js/dashboardUser.js"></script>

</body>

</html>