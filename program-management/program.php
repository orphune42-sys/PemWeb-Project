<?php
$programs = [
    [
        'id' => 1,
        'name' => 'Program Beasiswa Talenta Digital Indonesia untuk Pemimpin Teknologi Masa Depan',
        'organizer' => 'Kementerian Komunikasi dan Informatika Republik Indonesia',
        'type' => 'Beasiswa',
        'field' => 'Teknologi',
        'deadline' => '30 Okt 2026',
        'registrants' => 1284
    ],
    [
        'id' => 2,
        'name' => 'Kompetisi Nasional Perencanaan Bisnis Berkelanjutan dan Inovasi Sosial Mahasiswa',
        'organizer' => 'Universitas Gadjah Mada',
        'type' => 'Lomba',
        'field' => 'Kewirausahaan',
        'deadline' => '12 Nov 2026',
        'registrants' => 846
    ],
    [
        'id' => 3,
        'name' => 'Program Pengembangan Kepemimpinan Muda Nusantara Angkatan ke-12',
        'organizer' => 'Yayasan Pemimpin Indonesia',
        'type' => 'Program',
        'field' => 'Kepemimpinan',
        'deadline' => '24 Nov 2026',
        'registrants' => 589
    ],
    [
        'id' => 4,
        'name' => 'Lomba Karya Tulis Ilmiah Nasional: Transisi Energi untuk Indonesia Emas',
        'organizer' => 'Institut Teknologi Bandung',
        'type' => 'Lomba',
        'field' => 'Lingkungan',
        'deadline' => '05 Des 2026',
        'registrants' => 392
    ],
    [
        'id' => 5,
        'name' => 'Hackathon Solusi Kota Cerdas untuk Pelayanan Publik yang Inklusif',
        'organizer' => 'Jakarta Smart City',
        'type' => 'Lomba',
        'field' => 'Teknologi',
        'deadline' => '18 Sep 2026',
        'registrants' => 619
    ],
    [
        'id' => 6,
        'name' => 'Program Magang Bersertifikat Industri Kreatif Digital Indonesia',
        'organizer' => 'Asosiasi Industri Kreatif Indonesia',
        'type' => 'Magang',
        'field' => 'Industri Kreatif',
        'deadline' => '31 Agu 2026',
        'registrants' => 274
    ]
];

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Management - FindYourPath</title>
    <link rel="stylesheet" href="../sidebarAdmin/style.css">
    <link rel="stylesheet" href="program.css">
</head>

<body>

    <?php 
    $activeMenu = 'program';
    $basePath = '../';
    include "../sidebarAdmin/sidebar.php"; ?>

    <main class="program-main">

        <header class="program-header">
            <h1>Program Management</h1>

            <div class="program-account">
                <button class="program-notification" type="button" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="program-account-button">
                    <span class="program-avatar">A</span>

                    <span class="program-account-text">
                        <strong>Admin</strong>
                        <small>Administrator</small>
                    </span>
                </div>
            </div>
        </header>

        <section class="program-content">

            <div class="program-welcome">
                <h2>Hi, Admin!</h2>
                <p>Kelola seluruh program lomba dan beasiswa yang tersedia di FindYourPath.</p>
            </div>

            <div class="program-statistics">

                <div class="program-stat-card">
                    <div class="program-stat-top">
                        <span>Total Program</span>
                        <span class="program-stat-icon blue">▣</span>
                    </div>
                    <strong>48</strong>
                    <small>Semua program</small>
                </div>

                <div class="program-stat-card">
                    <div class="program-stat-top">
                        <span>Program Aktif</span>
                        <span class="program-stat-icon green">✓</span>
                    </div>
                    <strong>31</strong>
                    <small>Sedang berlangsung</small>
                </div>

                <div class="program-stat-card">
                    <div class="program-stat-top">
                        <span>Akan Datang</span>
                        <span class="program-stat-icon orange">▣</span>
                    </div>
                    <strong>9</strong>
                    <small>Belum dibuka</small>
                </div>

                <div class="program-stat-card">
                    <div class="program-stat-top">
                        <span>Selesai</span>
                        <span class="program-stat-icon gray">▣</span>
                    </div>
                    <strong>8</strong>
                    <small>Telah ditutup</small>
                </div>

            </div>

            <div class="program-toolbar">

                <div class="program-search">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9.58 17.5C13.95 17.5 17.5 13.95 17.5 9.58C17.5 5.21 13.95 1.67 9.58 1.67C5.21 1.67 1.67 5.21 1.67 9.58C1.67 13.95 5.21 17.5 9.58 17.5Z" stroke="#606060" stroke-width="1.5" />
                        <path d="M18.33 18.33L16.67 16.67" stroke="#606060" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <input type="text" id="searchProgram" placeholder="Cari program...">
                </div>

                <select id="programFilter">
                    <option value="all">Semua Program</option>
                    <option value="active">Program Aktif</option>
                    <option value="upcoming">Akan Datang</option>
                    <option value="finished">Selesai</option>
                </select>

                <select id="typeFilter">
                    <option value="all">Semua Jenis</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Lomba">Lomba</option>
                    <option value="Program">Program</option>
                    <option value="Magang">Magang</option>
                </select>

                <button class="add-program-button" id="addProgramButton" type="button">
                    <span>+</span>
                    Tambah Program
                </button>

            </div>

            <section class="program-table-card">

                <div class="program-table-heading">
                    <div>
                        <div class="program-table-title">
                            <h3>Daftar Program</h3>
                            <span>48</span>
                        </div>
                        <p>Terbaru diperbarui</p>
                    </div>
                </div>

                <div class="program-table-wrapper">

                    <table>
                        <thead>
                            <tr>
                                <th>PROGRAM</th>
                                <th>JENIS</th>
                                <th>BIDANG</th>
                                <th>TENGGAT</th>
                                <th>PENDAFTAR</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>

                        <tbody id="programTableBody">
                            <?php foreach ($programs as $program): ?>
                                <tr data-id="<?= e($program['id']) ?>">
                                    <td>
                                        <div class="program-name">
                                            <strong><?= e($program['name']) ?></strong>
                                            <small><?= e($program['organizer']) ?></small>
                                        </div>
                                    </td>

                                    <td><?= e($program['type']) ?></td>

                                    <td>
                                        <span class="field-tag">
                                            <?= e($program['field']) ?>
                                        </span>
                                    </td>

                                    <td><?= e($program['deadline']) ?></td>

                                    <td><?= number_format($program['registrants'], 0, ',', '.') ?></td>

                                    <td>
                                        <div class="program-actions">
                                            <button class="edit-program" type="button" data-id="<?= e($program['id']) ?>">✎</button>
                                            <button class="delete-program" type="button" data-id="<?= e($program['id']) ?>">⌫</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>

                <div class="program-pagination">

                    <span id="programResult">
                        Menampilkan 1–6 dari 48 program
                    </span>

                    <div class="pagination-buttons">
                        <button type="button" class="page-arrow" disabled>‹</button>
                        <button type="button" class="page-number active">1</button>
                        <button type="button" class="page-number">2</button>
                        <button type="button" class="page-number">3</button>
                        <span>•••</span>
                        <button type="button" class="page-arrow">›</button>
                    </div>

                </div>

            </section>

        </section>

    </main>

    <div class="program-modal" id="programModal">

        <div class="program-modal-box">

            <div class="program-modal-header">
                <h3 id="modalTitle">Tambah Program</h3>
                <button type="button" id="closeModal">×</button>
            </div>

            <form id="programForm">

                <div class="program-form-group">
                    <label>Nama Program</label>
                    <input type="text" id="programName" required>
                </div>

                <div class="program-form-group">
                    <label>Penyelenggara</label>
                    <input type="text" id="programOrganizer" required>
                </div>

                <div class="program-form-row">

                    <div class="program-form-group">
                        <label>Jenis</label>
                        <select id="programType" required>
                            <option value="Beasiswa">Beasiswa</option>
                            <option value="Lomba">Lomba</option>
                            <option value="Program">Program</option>
                            <option value="Magang">Magang</option>
                        </select>
                    </div>

                    <div class="program-form-group">
                        <label>Bidang</label>
                        <input type="text" id="programField" required>
                    </div>

                </div>

                <div class="program-form-row">

                    <div class="program-form-group">
                        <label>Tenggat</label>
                        <input type="text" id="programDeadline" placeholder="30 Okt 2026" required>
                    </div>

                    <div class="program-form-group">
                        <label>Pendaftar</label>
                        <input type="number" id="programRegistrants" min="0" value="0" required>
                    </div>

                </div>

                <div class="program-modal-actions">
                    <button type="button" id="cancelModal">Batal</button>
                    <button type="submit">Simpan Program</button>
                </div>

            </form>

        </div>

    </div>

    <script src="program.js"></script>

</body>

</html>