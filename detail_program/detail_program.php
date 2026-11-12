<?php
session_start();
require_once __DIR__ . '/../koneksi.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $id = 1;
}

$stmt = $pdo->prepare(
    "SELECT * FROM program WHERE id = :id"
);

$stmt->execute(['id' => $id]);
$program = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$program) {
    http_response_code(404);
    exit("Program tidak ditemukan.");
}
$stmtDetail = $pdo->prepare(
    "SELECT jenis, isi
     FROM program_detail
     WHERE program_id = :id"
);

$stmtDetail->execute(['id' => $id]);

$details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

$program['persyaratan'] = [];
$program['benefit'] = [];
$program['dokumen'] = [];

foreach ($details as $detail) {
    $jenis = $detail['jenis'];

    if (isset($program[$jenis])) {
        $program[$jenis][] = $detail['isi'];
    }
}

function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($program["judul"]) ?> | FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="detail_program.css">
</head>

<body>

<!-- HEADER -->
<header class="topbar">

    <a href="../explore-program/exploreProgram.php" class="back-link">
        <span aria-hidden="true">←</span>
        Kembali ke Explore Program
    </a>

    <a href="../explore-program/exploreProgram.php" class="brand">
        <img
            src="../assets/LOGO.png"
            alt="FindYourPath — Student Career & Achievement"
            class="detail-brand-logo"
        >
    </a>

</header>

<main class="page-container">

    <!-- BANNER PROGRAM -->
    <section class="program-banner">

        <img
            src="<?= e($program["gambar"]) ?>"
            alt="Banner <?= e($program["judul"]) ?>"
            class="banner-image"
        >

        <div class="banner-overlay"></div>

        <div class="banner-content">

            <span class="category-badge <?= $program["kategori"] === "Beasiswa" ? "scholarship" : "competition" ?>">
                <?= e($program["kategori"]) ?>
            </span>

            <h1><?= e($program["judul"]) ?></h1>

            <p class="banner-organizer">
                <?= e($program["penyelenggara"]) ?>
            </p>

        </div>

    </section>

    <!-- INFORMASI SINGKAT -->
    <section class="quick-info">

        <div class="quick-info-item">

            <span class="info-icon">⌖</span>

            <div>
                <span class="info-label">Lokasi</span>
                <strong><?= e($program["lokasi"]) ?></strong>
            </div>

        </div>

        <div class="quick-info-item">

            <span class="info-icon">▦</span>

            <div>
                <span class="info-label">Mulai pendaftaran</span>
                <strong><?= e($program["tanggal_mulai"]) ?></strong>
            </div>

        </div>

        <div class="quick-info-item deadline-info">

            <span class="info-icon">◷</span>

            <div>
                <span class="info-label">Batas pendaftaran</span>
                <strong><?= e($program["deadline"]) ?></strong>
            </div>

        </div>

    </section>

    <!-- KONTEN UTAMA -->
    <div class="detail-layout">

        <div class="detail-main">

            <!-- DESKRIPSI -->
            <section class="content-card">

                <h2>Tentang Program</h2>

                <p class="description">
                    <?= e($program["deskripsi"]) ?>
                </p>

            </section>

            <!-- PERSYARATAN -->
            <section class="content-card">

                <h2>Persyaratan Peserta</h2>

                <ul class="detail-list">

                    <?php foreach ($program["persyaratan"] as $item): ?>

                        <li>
                            <span class="list-check">✓</span>
                            <span><?= e($item) ?></span>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </section>

            <!-- BENEFIT -->
            <section class="content-card">

                <h2>Benefit Program</h2>

                <ul class="detail-list">

                    <?php foreach ($program["benefit"] as $item): ?>

                        <li>
                            <span class="list-check">✓</span>
                            <span><?= e($item) ?></span>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </section>

            <!-- HADIAH -->
            <section class="content-card">

                <h2>Hadiah atau Pendanaan</h2>

                <p class="description">
                    <?= e($program["hadiah"]) ?>
                </p>

            </section>

            <!-- DOKUMEN -->
            <section class="content-card">

                <h2>Dokumen yang Dibutuhkan</h2>

                <ul class="detail-list">

                    <?php foreach ($program["dokumen"] as $item): ?>

                        <li>
                            <span class="document-icon">▤</span>
                            <span><?= e($item) ?></span>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </section>

        </div>

        <!-- PANEL PENDAFTARAN -->
        <aside class="registration-panel">

            <div class="registration-card">

                <span class="panel-eyebrow">
                    INFORMASI PENDAFTARAN
                </span>

                <h2>Siap mengikuti program ini?</h2>

                <p class="panel-description">
                    Periksa persyaratan dan siapkan dokumen yang dibutuhkan
                    sebelum melanjutkan pendaftaran.
                </p>

                <!-- STATUS PROGRAM -->
                <div class="status-row">

                    <span>Status program</span>

                    <span class="status-badge <?= strtolower($program["status"]) === "dibuka" ? "status-open" : "status-closed" ?>">
                        <?= e($program["status"]) ?>
                    </span>

                </div>

                <!-- DEADLINE -->
                <div class="deadline-box">

                    <span class="deadline-icon">◷</span>

                    <div>
                        <span class="info-label">Batas pendaftaran</span>
                        <strong><?= e($program["deadline"]) ?></strong>
                    </div>

                </div>

                <!-- TOMBOL DAFTAR -->
                <?php if ($program["status"] === "Dibuka"): ?>

                    <a
                        href="../pendaftaran_program/form_pendaftaran.php?id=<?= urlencode($program['id']) ?>"
                        class="register-button"
                    >
                        Daftar Sekarang →
                    </a>

                <?php else: ?>

                    <button
                        type="button"
                        class="register-button"
                        disabled
                    >
                        Pendaftaran Ditutup
                    </button>

                <?php endif; ?>

                <p class="panel-note">
                    Pastikan kamu memenuhi seluruh ketentuan yang ditetapkan
                    penyelenggara.
                </p>

            </div>

        </aside>

    </div>

    <!-- FOOTER -->
    <footer class="page-footer">

        <p>
            FindYourPath — Temukan peluang, kembangkan potensimu.
        </p>

    </footer>

</main>

<script src="detail_program.js"></script>

</body>
</html>
