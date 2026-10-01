<?php
$dataFile = __DIR__ . '/../data/schedules.json';
$schedules = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
if (!is_array($schedules)) {
    $schedules = [];
}

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Lomba');
        $status = trim($_POST['status'] ?? 'Aktif');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        if (empty($eventDate)) {
            $eventDate = !empty($startDate) ? $startDate : date('Y-m-d');
        }
        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!empty($title)) {
            $newSchedule = [
                'id' => 'sch_' . bin2hex(random_bytes(5)),
                'title' => $title,
                'category' => $category,
                'status' => $status,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'event_date' => $eventDate,
                'location' => $location,
                'description' => $description
            ];
            $schedules[] = $newSchedule;
            file_put_contents($dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
            $message = 'Program baru berhasil ditambahkan!';
            $messageType = 'success';
        } else {
            $message = 'Judul program wajib diisi!';
            $messageType = 'error';
        }
    } elseif ($action === 'update') {
        $id = trim($_POST['id'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Lomba');
        $status = trim($_POST['status'] ?? 'Aktif');
        $startDate = trim($_POST['start_date'] ?? '');
        $endDate = trim($_POST['end_date'] ?? '');
        $eventDate = trim($_POST['event_date'] ?? '');
        if (empty($eventDate)) {
            $eventDate = !empty($startDate) ? $startDate : date('Y-m-d');
        }
        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $found = false;
        foreach ($schedules as &$sch) {
            if (($sch['id'] ?? '') === $id) {
                $sch['title'] = $title;
                $sch['category'] = $category;
                $sch['status'] = $status;
                $sch['start_date'] = $startDate;
                $sch['end_date'] = $endDate;
                $sch['event_date'] = $eventDate;
                $sch['location'] = $location;
                $sch['description'] = $description;
                $found = true;
                break;
            }
        }
        unset($sch);

        if ($found) {
            file_put_contents($dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
            $message = 'Program berhasil diperbarui!';
            $messageType = 'success';
        } else {
            $message = 'Data program tidak ditemukan!';
            $messageType = 'error';
        }
    } elseif ($action === 'delete') {
        $id = trim($_POST['id'] ?? '');
        $initialCount = count($schedules);
        $schedules = array_values(array_filter($schedules, function ($sch) use ($id) {
            return ($sch['id'] ?? '') !== $id;
        }));

        if (count($schedules) < $initialCount) {
            file_put_contents($dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
            $message = 'Program berhasil dihapus!';
            $messageType = 'success';
        } else {
            $message = 'Gagal menghapus program, data tidak ditemukan!';
            $messageType = 'error';
        }
    }
}

function formatTanggalIndo($dateStr) {
    if (empty($dateStr)) return '-';
    $timestamp = strtotime($dateStr);
    if (!$timestamp) return $dateStr;
    $bulanIndo = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];
    $d = date('j', $timestamp);
    $m = $bulanIndo[(int)date('n', $timestamp)] ?? date('M', $timestamp);
    $y = date('Y', $timestamp);
    return "$d $m $y";
}

function formatPeriodeIndo($start, $end) {
    if (empty($start) && empty($end)) return '-';
    if (!empty($start) && !empty($end)) {
        return formatTanggalIndo($start) . ' - ' . formatTanggalIndo($end);
    }
    return formatTanggalIndo($start ?: $end);
}

$totalPrograms = count($schedules);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Management - FindYourPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../sidebarAdmin/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="layout-wrapper">
        <?php 
            $activeMenu = 'schedule'; 
            $basePath = '../';
            include __DIR__ . '/../sidebarAdmin/sidebar.php'; 
        ?>

        <div class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button type="button" class="mobile-toggle-btn" id="sidebarToggleBtn" aria-label="Menu">
                        <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                    <h1 class="page-title">Schedule Management</h1>
                </div>

                <div class="header-right">
                    <button type="button" class="btn-icon" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>

                    <div class="user-profile-badge">
                        <div class="user-avatar-circle">
                            <span>A</span>
                        </div>
                        <div class="user-meta">
                            <span class="user-name">Admin Account</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <?php 
            if (!empty($message)): ?>
                <div class="toast-alert show <?= $messageType ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <section class="welcome-section">
                <h2 class="welcome-title">Hi, Admin!</h2>
                <p class="welcome-subtitle">Kelola dan pantau jadwal program, kompetisi, beasiswa dalam satu tempat.</p>
            </section>

            <section class="toolbar-section">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" class="search-icon">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari program, kompetisi, beasiswa...">
                </div>

                <div class="filter-dropdown-box">
                    <select id="filterCategory" class="filter-select">
                        <option value="">Kategori</option>
                        <option value="Beasiswa">Beasiswa</option>
                        <option value="Lomba">Kompetisi</option>

                    </select>
                </div>

                <div class="filter-date-box">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" class="calendar-input-icon">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <input type="text" id="filterDateRange" placeholder="Rentang tanggal" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'">
                </div>

                <button type="button" class="btn-primary-action" id="btnOpenAddModal">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Program</span>
                </button>
            </section>

            <section class="content-card">
                <div class="card-header-bar">
                    <div class="card-title-group">
                        <h3 class="card-title">Jadwal Program</h3>
                        <span class="count-badge" id="programCountBadge"><?= $totalPrograms ?></span>
                    </div>
                    <span class="card-meta-text">Terbaru diperbarui</span>
                </div>

                <div class="table-responsive">
                    <table class="program-table" id="programTable">
                        <thead>
                            <tr>
                                <th class="th-program">PROGRAM</th>
                                <th class="th-jenis">JENIS</th>
                                <th class="th-status">STATUS</th>
                                <th class="th-periode">PERIODE PENDAFTARAN</th>
                                <th class="th-lokasi">LOKASI</th>
                                <th class="th-aksi">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="programTableBody">
                            <?php if (empty($schedules)): ?>
                                <tr>
                                    <td colspan="6" class="empty-cell">Belum ada jadwal program tersedia. Klik tombol "+ Tambah Program" untuk membuat data baru.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($schedules as $item): ?>
                                    <?php 
                                        $statusClass = 'status-aktif';
                                        if (($item['status'] ?? '') === 'Akan Datang') {
                                            $statusClass = 'status-akan-datang';
                                        } elseif (($item['status'] ?? '') === 'Selesai') {
                                            $statusClass = 'status-selesai';
                                        }
                                    ?>
                                    <tr class="program-row" 
                                        data-id="<?= htmlspecialchars($item['id'] ?? '') ?>"
                                        data-title="<?= htmlspecialchars($item['title'] ?? '') ?>"
                                        data-category="<?= htmlspecialchars($item['category'] ?? '') ?>"
                                        data-status="<?= htmlspecialchars($item['status'] ?? '') ?>"
                                        data-start="<?= htmlspecialchars($item['start_date'] ?? '') ?>"
                                        data-end="<?= htmlspecialchars($item['end_date'] ?? '') ?>"
                                        data-event="<?= htmlspecialchars($item['event_date'] ?? '') ?>"
                                        data-location="<?= htmlspecialchars($item['location'] ?? '') ?>"
                                        data-description="<?= htmlspecialchars($item['description'] ?? '') ?>">
                                        
                                        <td class="td-program">
                                            <span class="program-title-text"><?= htmlspecialchars($item['title'] ?? '') ?></span>
                                        </td>
                                        <td class="td-jenis">
                                            <?= htmlspecialchars($item['category'] ?? '') ?>
                                        </td>
                                        <td class="td-status">
                                            <span class="badge-status <?= $statusClass ?>">
                                                <?= htmlspecialchars($item['status'] ?? 'Aktif') ?>
                                            </span>
                                        </td>
                                        <td class="td-periode">
                                            <?= htmlspecialchars(formatPeriodeIndo($item['start_date'] ?? '', $item['end_date'] ?? '')) ?>
                                        </td>
                                        <td class="td-lokasi">
                                            <span class="location-text"><?= htmlspecialchars($item['location'] ?? '') ?></span>
                                        </td>
                                        <td class="td-aksi">
                                            <div class="action-buttons-group">
                                                <button type="button" class="btn-action-edit" title="Edit Program">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                                        <path d="M12 20h9"></path>
                                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn-action-delete" title="Hapus Program">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-pagination-footer">
                    <div class="pagination-info">
                        Menampilkan <span id="pageRangeText">1-<?= min(6, $totalPrograms) ?></span> dari <span id="pageTotalText"><?= $totalPrograms ?></span> program
                    </div>
                    <div class="pagination-controls" id="paginationControls">
                    </div>
                </div>
            </section>

            <section class="content-card calendar-card">
                <div class="card-header-bar calendar-header-bar">
                    <div class="card-title-group">
                        <h3 class="card-title">Kalender</h3>
                        <span class="count-badge" id="calendarCountBadge"><?= $totalPrograms ?></span>
                    </div>

                    <div class="calendar-nav-bar">
                        <button type="button" class="btn-cal-nav" id="btnPrevMonth" title="Bulan Sebelumnya" aria-label="Bulan Sebelumnya">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <span class="calendar-month-heading" id="calMonthTitle">Oktober 2026</span>
                        <button type="button" class="btn-cal-nav" id="btnNextMonth" title="Bulan Selanjutnya" aria-label="Bulan Selanjutnya">
                            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="calendar-wrapper">
                    <div class="calendar-weekdays-row">
                        <div class="weekday-col">Mon</div>
                        <div class="weekday-col">Tue</div>
                        <div class="weekday-col">Wed</div>
                        <div class="weekday-col">Thu</div>
                        <div class="weekday-col">Fri</div>
                        <div class="weekday-col">Sat</div>
                        <div class="weekday-col">Sun</div>
                    </div>

                    <div class="calendar-grid" id="calendarGrid">
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="modal-overlay" id="programModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Tambah Program</h3>
                <button type="button" class="modal-close-btn" id="btnCloseModal">&times;</button>
            </div>
            <form method="POST" action="schedule.php" id="programForm">
                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="id" id="formId" value="">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputTitle">Nama Program / Kompetisi / Beasiswa <span class="required">*</span></label>
                        <input type="text" id="inputTitle" name="title" required placeholder="Contoh: Kompetisi Nasional AI 2026">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputCategory">Jenis / Kategori <span class="required">*</span></label>
                            <select id="inputCategory" name="category" required>
                                <option value="Lomba">Lomba</option>
                                <option value="Beasiswa">Beasiswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="inputStatus">Status <span class="required">*</span></label>
                            <select id="inputStatus" name="status" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Akan Datang">Akan Datang</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputStartDate">Mulai Pendaftaran</label>
                            <input type="date" id="inputStartDate" name="start_date">
                        </div>
                        <div class="form-group">
                            <label for="inputEndDate">Selesai Pendaftaran</label>
                            <input type="date" id="inputEndDate" name="end_date">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputEventDate">Tanggal Pelaksanaan / Kalender <span class="required">*</span></label>
                            <input type="date" id="inputEventDate" name="event_date" required>
                            <small class="form-helper">Tanggal ini akan ditandai pada Kalender Lomba.</small>
                        </div>
                        <div class="form-group">
                            <label for="inputLocation">Lokasi / Penyelenggara <span class="required">*</span></label>
                            <input type="text" id="inputLocation" name="location" required placeholder="Contoh: UGM / Online / ITB">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputDescription">Deskripsi Singkat</label>
                        <textarea id="inputDescription" name="description" rows="3" placeholder="Keterangan singkat mengenai program atau ketentuan lomba..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="btnCancelModal">Batal</button>
                    <button type="submit" class="btn-save" id="btnSubmitForm">Simpan Program</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteForm" method="POST" action="schedule.php" style="display: none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteId" value="">
    </form>

    <script>
        const initialSchedules = <?= json_encode($schedules, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="../sidebarAdmin/script.js"></script>
    <script src="script.js"></script>
</body>
</html>
