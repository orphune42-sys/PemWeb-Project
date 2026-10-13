<?php
$basePath = '../';
$activeMenu = 'registration';

$allRegistrations = [
    ['id' => 1, 'nama_mahasiswa' => 'Sarah Cantika', 'email' => 'sarahcantika@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Approved', 'created_at' => '2026-10-13'],
    ['id' => 2, 'nama_mahasiswa' => 'Mike Rossa', 'email' => 'mikeross@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Pending', 'created_at' => '2026-10-12'],
    ['id' => 3, 'nama_mahasiswa' => 'Alex Kenandra', 'email' => 'alex.ken09@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Rejected', 'created_at' => '2026-10-11'],
    ['id' => 4, 'nama_mahasiswa' => 'Jenna Alifia', 'email' => 'jenna.altr@email.com', 'jenis_program' => 'Lomba', 'status' => 'Approved', 'created_at' => '2026-10-10'],
    ['id' => 5, 'nama_mahasiswa' => 'David Pratama', 'email' => 'david.234@email.com', 'jenis_program' => 'Lomba', 'status' => 'Pending', 'created_at' => '2026-10-09'],
    ['id' => 6, 'nama_mahasiswa' => 'Raina Teresa', 'email' => 'rainneterrs@email.com', 'jenis_program' => 'Lomba', 'status' => 'Approved', 'created_at' => '2026-10-08'],
    ['id' => 7, 'nama_mahasiswa' => 'Kania Andriana', 'email' => 'kan.09@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Approved', 'created_at' => '2026-10-07'],
    ['id' => 8, 'nama_mahasiswa' => 'Bintang Pratama', 'email' => 'bintang.pratama@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Pending', 'created_at' => '2026-10-05'],
    ['id' => 9, 'nama_mahasiswa' => 'Nabila Putri', 'email' => 'nabila.ptr@email.com', 'jenis_program' => 'Lomba', 'status' => 'Approved', 'created_at' => '2026-10-03'],
    ['id' => 10, 'nama_mahasiswa' => 'Rizky Ramadhan', 'email' => 'rizky.rmd@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Approved', 'created_at' => '2026-09-28'],
    ['id' => 11, 'nama_mahasiswa' => 'Clarissa Stephanie', 'email' => 'clarissa.steph@email.com', 'jenis_program' => 'Lomba', 'status' => 'Rejected', 'created_at' => '2026-09-25'],
    ['id' => 12, 'nama_mahasiswa' => 'Farhan Hidayat', 'email' => 'farhan.hidayat@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Pending', 'created_at' => '2026-09-20'],
    ['id' => 13, 'nama_mahasiswa' => 'Amanda Wijaya', 'email' => 'amanda.wj@email.com', 'jenis_program' => 'Lomba', 'status' => 'Approved', 'created_at' => '2026-09-15'],
    ['id' => 14, 'nama_mahasiswa' => 'Dion Kusuma', 'email' => 'dion.ksm@email.com', 'jenis_program' => 'Beasiswa', 'status' => 'Rejected', 'created_at' => '2026-09-10'],
];

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$totalRegistrations = count($allRegistrations);
$totalPages = ceil($totalRegistrations / $limit) ?: 1;
$offset = ($page - 1) * $limit;

$registrations = array_slice($allRegistrations, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management - FindYourPath</title>
    <link rel="stylesheet" href="../management-regist/style.css">
    <link rel="stylesheet" href="../sidebarAdmin/style.css">
</head>

<body>

    <?php include '../sidebarAdmin/sidebar.php'; ?>

    <div class="main-wrapper">
        <header class="header">
            <h1 class="header-title">Registration Management</h1>
            <div class="header-right">
                <button class="header-notif" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>

                </button>
                <div class="header-user">
                    <div class="header-avatar">A</div>
                    <div class="header-userinfo">
                        <span class="header-username">Admin</span>
                        <span class="header-userrole">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <h2 class="greeting-title">Hi, Admin!</h2>
            <p class="greeting-sub">Pantau pendaftaran mahasiswa pada seluruh program dalam satu tempat.</p>

            <div class="panel">
                <h3 class="panel-title">Daftar Pendaftaran</h3>

                <div class="filter-bar">
                    <div class="search-wrap">
                        <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari nama atau email...">
                    </div>

                    <select class="filter-select" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="Disetujui">Disetujui</option>
                        <option value="Tertunda">Tertunda</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>

                    <select class="filter-select" id="dateFilter">
                        <option value="">Semua Waktu</option>
                        <option value="7">7 Hari Terakhir</option>
                        <option value="30">30 Hari Terakhir</option>
                        <option value="90">90 Hari Terakhir</option>
                    </select>

                    <select class="filter-select" id="programFilter">
                        <option value="">Semua Program</option>
                        <option value="Beasiswa">Beasiswa</option>
                        <option value="Lomba">Lomba</option>
                    </select>

                    <div class="filter-actions">
                        <button class="btn btn--outline" id="exportBtn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Ekspor CSV
                        </button>
                    </div>
                </div>

                <div class="table-meta">
                    <div class="table-meta-left">
                        <span class="table-list-title">Registration List</span>
                        <button class="refresh-btn" aria-label="Refresh" onclick="location.reload();">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="table-count">Showing <?= count($registrations); ?> of <?= $totalRegistrations; ?> registrants</span>
                </div>

                <div class="table-wrap">
                    <table class="reg-table" id="regTable">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Pendaftar</th>
                                <th style="width: 20%;">Tanggal Pendaftaran</th>
                                <th style="width: 20%;">Jenis</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 15%;">Lainnya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registrations as $row): ?>
                                <tr>
                                    <td class="user-cell">
                                        <div class="avatar avatar--teal">
                                            <?= strtoupper(substr($row['nama_mahasiswa'], 0, 2)); ?>
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name"><?= htmlspecialchars($row['nama_mahasiswa']); ?></span>
                                            <span class="user-email"><?= htmlspecialchars($row['email']); ?></span>
                                        </div>
                                    </td>
                                    <td class="reg-date"><?= date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td class="reg-role"><?= htmlspecialchars($row['jenis_program']); ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge--pending';
                                        $statusText = 'Tertunda';
                                        if ($row['status'] == 'Approved') {
                                            $badgeClass = 'badge--approved';
                                            $statusText = 'Disetujui';
                                        }
                                        if ($row['status'] == 'Rejected') {
                                            $badgeClass = 'badge--rejected';
                                            $statusText = 'Ditolak';
                                        }
                                        ?>
                                        <span class="badge <?= $badgeClass; ?>"><?= $statusText; ?></span>
                                    </td>
                                    <td class="actions-cell">
                                        <button class="action-btn" title="Edit">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button class="action-btn" title="Lihat">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-bar" style="justify-content: center; margin-top: 20px;">
                    <div class="pagination-pages">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1; ?>" class="page-btn page-btn--nav" style="text-decoration:none;">&lsaquo;</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i; ?>" class="page-btn <?= $i === $page ? 'page-btn--active' : ''; ?>" style="text-decoration:none;">
                                <?= $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?= $page + 1; ?>" class="page-btn page-btn--nav" style="text-decoration:none;">&rsaquo;</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>

<script src="script.js?v=1"></script>

<script src="../sidebarAdmin/script.js"></script>

</body>

</html>