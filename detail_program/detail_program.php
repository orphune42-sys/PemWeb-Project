
<?php
session_start();

$defaultPrograms = [
    "1" => [
        "id" => 1,
        "kategori" => "Lomba",
        "judul" => "UI/UX Design Competition 2025",
        "penyelenggara" => "Universitas Sriwijaya",
        "lokasi" => "Online",
        "deadline" => "12 Maret 2025",
        "tanggal_mulai" => "1 Maret 2025",
        "gambar" => "https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?auto=format&fit=crop&w=1400&q=85",
        "deskripsi" => "Kompetisi desain UI/UX yang ditujukan bagi mahasiswa dan siswa SMA/SMK nasional. Peserta ditantang untuk menciptakan solusi digital yang inovatif, mudah digunakan, dan mampu menjawab permasalahan pengguna.",
        "persyaratan" => [
            "Mahasiswa aktif atau siswa SMA/SMK.",
            "Mengikuti ketentuan pendaftaran yang berlaku.",
            "Mengumpulkan karya sesuai tema kompetisi.",
            "Karya merupakan hasil sendiri dan belum pernah dilombakan."
        ],
        "benefit" => [
            "Pengalaman dan portofolio desain.",
            "Sertifikat peserta dan penghargaan bagi pemenang.",
            "Kesempatan mengembangkan kemampuan UI/UX."
        ],
        "hadiah" => "Hadiah dan penghargaan mengikuti ketentuan penyelenggara.",
        "dokumen" => [
            "Kartu identitas pelajar atau mahasiswa.",
            "Berkas karya sesuai ketentuan lomba."
        ],
        "status" => "Dibuka"
    ],
    "2" => [
        "id" => 2,
        "kategori" => "Beasiswa",
        "judul" => "Beasiswa Unggulan 2025",
        "penyelenggara" => "Kementerian Keuangan RI",
        "lokasi" => "Online",
        "deadline" => "20 April 2025",
        "tanggal_mulai" => "1 April 2025",
        "gambar" => "https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1400&q=85",
        "deskripsi" => "Program beasiswa untuk mendukung pendidikan dan pengembangan potensi mahasiswa. Calon peserta perlu memeriksa persyaratan, jenjang pendidikan, serta ketentuan resmi dari penyelenggara sebelum mendaftar.",
        "persyaratan" => [
            "Memenuhi ketentuan jenjang pendidikan program.",
            "Memiliki dokumen akademik yang dipersyaratkan.",
            "Melengkapi formulir pendaftaran.",
            "Memenuhi seluruh ketentuan resmi penyelenggara."
        ],
        "benefit" => [
            "Dukungan pendidikan sesuai ketentuan program.",
            "Kesempatan mengembangkan prestasi akademik.",
            "Pengalaman mengikuti seleksi beasiswa."
        ],
        "hadiah" => "Bentuk dan nominal bantuan mengikuti ketentuan resmi program.",
        "dokumen" => [
            "Kartu identitas.",
            "Transkrip nilai atau dokumen akademik.",
            "Dokumen pendukung sesuai ketentuan."
        ],
        "status" => "Dibuka"
    ]
];

if (!isset($_SESSION["programs"])) {
    $_SESSION["programs"] = $defaultPrograms;
}

$programs = $_SESSION["programs"];

$id = $_GET["id"] ?? "1";
$program = $programs[$id] ?? $programs["1"];

$savedPrograms = $_SESSION["saved_programs"] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "toggle_bookmark") {
        if (in_array($program["id"], $savedPrograms)) {
            $savedPrograms = array_values(array_diff(
                $savedPrograms,
                [$program["id"]]
            ));
        } else {
            $savedPrograms[] = $program["id"];
        }

        $_SESSION["saved_programs"] = $savedPrograms;

        header("Location: detail_program.php?id=" . urlencode($id));
        exit;
    }

    if ($action === "register") {
        if ($program["status"] !== "Dibuka") {
            $_SESSION["program_message"] =
                "Pendaftaran program ini sedang ditutup.";
        } else {
            $_SESSION["program_message"] =
                "Fitur pendaftaran akan dihubungkan ke halaman pendaftaran.";
        }

        header("Location: detail_program.php?id=" . urlencode($id));
        exit;
    }
}

$message = $_SESSION["program_message"] ?? "";
unset($_SESSION["program_message"]);

$isSaved = in_array($program["id"], $savedPrograms);

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="detail_program.css">
</head>
<body>

<!-- HEADER -->
<header class="topbar">
    <a href="../explore-program/exploreProgram.php" class="back-link">
        <span aria-hidden="true">←</span> Kembali ke Explore Program
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

    <?php if ($message !== ""): ?>
        <div class="notice" role="status">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

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

        <form method="POST" class="bookmark-form">
            <input type="hidden" name="action" value="toggle_bookmark">

            <button
                type="submit"
                class="bookmark-button <?= $isSaved ? "is-saved" : "" ?>"
                aria-label="<?= $isSaved ? "Hapus dari program tersimpan" : "Simpan program" ?>"
                title="<?= $isSaved ? "Hapus dari tersimpan" : "Simpan program" ?>"
            >
                <?= $isSaved ? "♥" : "♡" ?>
            </button>
        </form>
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

            <section class="content-card">
                <h2>Tentang Program</h2>
                <p class="description">
                    <?= e($program["deskripsi"]) ?>
                </p>
            </section>

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

            <section class="content-card">
                <h2>Hadiah atau Pendanaan</h2>
                <p class="description">
                    <?= e($program["hadiah"]) ?>
                </p>
            </section>

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

        <!-- PANEL DAFTAR -->
        <aside class="registration-panel">
            <div class="registration-card">

                <span class="panel-eyebrow">INFORMASI PENDAFTARAN</span>
                <h2>Siap mengikuti program ini?</h2>

                <p class="panel-description">
                    Periksa persyaratan dan siapkan dokumen yang dibutuhkan
                    sebelum melanjutkan pendaftaran.
                </p>

                <div class="status-row">
                    <span>Status program</span>

                    <span class="status-badge <?= strtolower($program["status"]) === "dibuka" ? "status-open" : "status-closed" ?>">
                        <?= e($program["status"]) ?>
                    </span>
                </div>

                <div class="deadline-box">
                    <span class="deadline-icon">◷</span>
                    <div>
                        <span class="info-label">Batas pendaftaran</span>
                        <strong><?= e($program["deadline"]) ?></strong>
                    </div>
                </div>
                    <a
                        href="../pendaftaran_program/form_pendaftaran.php?id=<?= urlencode($program['id']) ?>"
                        class="register-button"
                    >
                        Daftar Sekarang →
                    </a>
                <p class="panel-note">
                    Pastikan kamu memenuhi seluruh ketentuan yang ditetapkan
                    penyelenggara.
                </p>

            </div>
        </aside>

    </div>

    <footer class="page-footer">
        <p>FindYourPath — Temukan peluang, kembangkan potensimu.</p>
    </footer>

</main>

<script src="detail_program.js"></script>
</body>
</html>