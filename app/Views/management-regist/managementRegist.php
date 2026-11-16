<?php
/**
 * @var array $registrations
 * @var int $totalRegistrations
 * @var int $pageNum
 * @var int $totalPages
 * @var string $activeMenu
 * @var string $baseUrl
 */


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management - FindYourPath</title>
    <link rel="stylesheet" href="css/managementRegist.css?v=2">
    <link rel="stylesheet" href="css/sidebarAdmin.css">
</head>

<body>

    <?php
    require_once __DIR__ . '/../../Controllers/sidebarAdminController.php';
    (new SidebarAdminController())->render($activeMenu, $baseUrl);
    ?>

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
                        <button type="button" class="btn btn--outline" id="exportBtn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Ekspor CSV
                        </button>

                        <button type="button" class="btn btn--sidebar-teal" id="openAddModalBtn">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah
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
                            <?php if (empty($registrations)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 32px 16px; color: #7a9bac;">
                                        Belum ada data pendaftaran saat ini.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($registrations as $row): ?>
                                    <tr>
                                        <td class="user-cell">
                                            <div class="avatar avatar--teal">
                                                <?= strtoupper(substr($row['display_nama'], 0, 2)); ?>
                                            </div>
                                            <div class="user-info">
                                                <span class="user-name"><?= htmlspecialchars($row['display_nama']); ?></span>
                                                <span class="user-email"><?= htmlspecialchars($row['display_email']); ?></span>
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
                                            <div class="action-dropdown-wrap">
                                                <button type="button" class="action-btn edit-menu-trigger" title="Edit">
                                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>

                                                <div class="action-dropdown-menu">
                                                    <button type="button"
                                                        class="dropdown-item dropdown-item--edit"
                                                        data-id="<?= $row['id']; ?>"
                                                        data-nama="<?= htmlspecialchars($row['display_nama'], ENT_QUOTES); ?>"
                                                        data-email="<?= htmlspecialchars($row['display_email'], ENT_QUOTES); ?>"
                                                        data-jenis="<?= htmlspecialchars($row['jenis_program'], ENT_QUOTES); ?>"
                                                        data-tanggal="<?= date('d M Y', strtotime($row['created_at'])); ?>">
                                                        Perbarui Data
                                                    </button>

                                                    <form method="POST" action="index.php?page=management-regist" class="delete-form" onsubmit="return confirm('Yakin ingin menghapus data pendaftar <?= htmlspecialchars($row['display_nama'], ENT_QUOTES); ?>?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                        <button type="submit" class="dropdown-item dropdown-item--delete">
                                                            Hapus Data
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-bar" style="justify-content: center; margin-top: 20px;">
                    <div class="pagination-pages">
                        <?php if ($pageNum > 1): ?>
                            <a href="index.php?page=management-regist&p=<?= $pageNum - 1; ?>" class="page-btn page-btn--nav" style="text-decoration:none;">&lsaquo;</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="index.php?page=management-regist&p=<?= $i; ?>" class="page-btn <?= $i === $pageNum ? 'page-btn--active' : ''; ?>" style="text-decoration:none;">
                                <?= $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($pageNum < $totalPages): ?>
                            <a href="index.php?page=management-regist&p=<?= $pageNum + 1; ?>" class="page-btn page-btn--nav" style="text-decoration:none;">&rsaquo;</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <div class="modal-overlay" id="registModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Registration</h3>
                <button type="button" class="modal-close" id="closeModalBtn" aria-label="Tutup">&times;</button>
            </div>

            <form method="POST" action="index.php?page=management-regist" id="registForm">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="formId" value="">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputNama" class="form-label">Nama Pendaftar</label>
                        <input type="text" id="inputNama" name="nama_mahasiswa" class="form-control" placeholder="Masukkan nama lengkap..." required>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="contoh@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="inputJenis" class="form-label">Jenis Program</label>
                        <select id="inputJenis" name="jenis_program" class="form-control" required>
                            <option value="Beasiswa">Beasiswa</option>
                            <option value="Lomba">Lomba</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="inputTanggal" class="form-label">Tanggal Pendaftaran</label>
                        <input type="text" id="inputTanggal" class="form-control form-control--readonly" readonly>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn--outline" id="cancelModalBtn">Batal</button>
                    <button type="submit" class="btn btn--sidebar-teal" id="submitModalBtn">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/managementRegist.js?v=2"></script>
    <script src="js/sidebarAdmin.js"></script>

</body>

</html>