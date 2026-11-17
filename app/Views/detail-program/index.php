<?php
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($program['judul']) ?> | FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet"
        href="<?= e($baseUrl) ?>/public/css/detail_program.css">
</head>

<body>
<header class="topbar">
    <a href="<?= e($baseUrl) ?>/public/index.php?page=explore-program"
       class="back-link">
        <span aria-hidden="true">←</span>
        Kembali ke Explore Program
    </a>

    <a href="<?= e($baseUrl) ?>/public/index.php?page=explore-program"
       class="brand">
        <img
            src="<?= e($baseUrl) ?>/assets/LOGO.png"
            alt="FindYourPath — Student Career & Achievement"
            class="detail-brand-logo"
        >
    </a>
</header>

<main class="page-container">
    <section class="program-banner">
        <?php if (!empty($program['gambar'])): ?>
            <img
                src="<?= e($program['gambar']) ?>"
                alt="Banner <?= e($program['judul']) ?>"
                class="banner-image"
            >
        <?php endif; ?>

        <div class="banner-overlay"></div>
        <div class="banner-content">
            <span class="category-badge <?= e($categoryClass) ?>">
                <?= e($program['kategori']) ?>
            </span>

            <h1><?= e($program['judul']) ?></h1>
            <p class="banner-organizer">
                <?= e($program['penyelenggara']) ?>
            </p>
        </div>
    </section>

    <section class="quick-info">
        <div class="quick-info-item">
            <span class="info-icon">⌖</span>
            <div>
                <span class="info-label">Lokasi</span>
                <strong><?= e($program['lokasi']) ?></strong>
            </div>
        </div>

        <div class="quick-info-item">
            <span class="info-icon">▦</span>
            <div>
                <span class="info-label">Mulai pendaftaran</span>
                <strong><?= e($program['tanggal_mulai']) ?></strong>
            </div>
        </div>

        <div class="quick-info-item deadline-info">
            <span class="info-icon">◷</span>
            <div>
                <span class="info-label">Batas pendaftaran</span>
                <strong><?= e($program['deadline']) ?></strong>
            </div>
        </div>

    </section>

    <div class="detail-layout">
        <div class="detail-main">
            <section class="content-card">
                <h2>Tentang Program</h2>
                <p class="description">
                    <?= e($program['deskripsi']) ?>
                </p>
            </section>

            <section class="content-card">
                <h2>Persyaratan Peserta</h2>
                <?php if (!empty($program['persyaratan'])): ?>
                    <ul class="detail-list">
                        <?php foreach ($program['persyaratan'] as $item): ?>
                            <li>
                                <span class="list-check">✓</span>
                                <span><?= e($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="description">
                        Belum ada persyaratan yang ditambahkan.
                    </p>
                <?php endif; ?>
            </section>

            <section class="content-card">
                <h2>Benefit Program</h2>
                <?php if (!empty($program['benefit'])): ?>
                    <ul class="detail-list">
                        <?php foreach ($program['benefit'] as $item): ?>
                            <li>
                                <span class="list-check">✓</span>
                                <span><?= e($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="description">
                        Belum ada informasi benefit program.
                    </p>
                <?php endif; ?>
            </section>

            <section class="content-card">
                <h2>Hadiah atau Pendanaan</h2>
                <p class="description">
                    <?= e($program['hadiah']) ?>
                </p>
            </section>

            <section class="content-card">
                <h2>Dokumen yang Dibutuhkan</h2>
                <?php if (!empty($program['dokumen'])): ?>
                    <ul class="detail-list">
                        <?php foreach ($program['dokumen'] as $item): ?>
                            <li>
                                <span class="document-icon">▤</span>
                                <span><?= e($item) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="description">
                        Tidak ada dokumen unggahan yang ditentukan.
                    </p>
                <?php endif; ?>
            </section>

        </div>

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

                <div class="status-row">
                    <span>Status program</span>

                    <span class="status-badge <?= e($statusClass) ?>">
                        <?= e($program['status']) ?>
                    </span>
                </div>

                <div class="deadline-box">
                    <span class="deadline-icon">◷</span>
                    <div>
                        <span class="info-label">Batas pendaftaran</span>
                        <strong><?= e($program['deadline']) ?></strong>
                    </div>
                </div>

                <?php if ($isOpen): ?>
                    <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $program['id'] ?>"                        class="register-button"
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

    <footer class="page-footer">
        <p>
            FindYourPath — Temukan peluang, kembangkan potensimu.
        </p>
    </footer>

</main>

<script src="<?= e($baseUrl) ?>/public/js/detail_program.js"></script>

</body>
</html>
