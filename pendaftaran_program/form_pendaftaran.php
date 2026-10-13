
<?php
session_start();

// Mengambil data program dari session yang sama dengan detail_program.php
$programs = $_SESSION["programs"] ?? [];

$id = (string) ($_GET["id"] ?? "");
$program = $programs[$id] ?? null;

$message = "";
$success = false;
$registrationId = "";

// Fungsi untuk mengamankan output HTML
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

// Memproses form ketika dikirim
if ($_SERVER["REQUEST_METHOD"] === "POST" && $program) {
    $nama = trim($_POST["nama"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telepon = trim($_POST["telepon"] ?? "");
    $universitas = trim($_POST["universitas"] ?? "");
    $prodi = trim($_POST["prodi"] ?? "");
    $semester = trim($_POST["semester"] ?? "");
    $alasan = trim($_POST["alasan"] ?? "");

    if ($program["status"] !== "Dibuka") {
        $message = "Maaf, pendaftaran program ini sedang ditutup.";
    } elseif (
        $nama === "" || $email === "" || $telepon === "" ||
        $universitas === "" || $prodi === "" ||
        $semester === "" || $alasan === ""
    ) {
        $message = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
    } else {
        if (!isset($_SESSION["registrations"])) {
            $_SESSION["registrations"] = [];
        }

        $registrationId = uniqid("REG-");

        $_SESSION["registrations"][$registrationId] = [
            "id" => $registrationId,
            "program_id" => $program["id"],
            "program_judul" => $program["judul"],
            "nama" => $nama,
            "email" => $email,
            "telepon" => $telepon,
            "universitas" => $universitas,
            "prodi" => $prodi,
            "semester" => $semester,
            "alasan" => $alasan,
            "status" => "Menunggu Verifikasi",
            "tanggal_daftar" => date("d-m-Y H:i:s")
        ];

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran | FindYourPath</title>
    <link rel="stylesheet" href="form_pendaftaran.css">
</head>
<body>

<header class="topbar">
    <a href="../explore-program/exploreProgram.php" class="brand">
        FindYourPath
    </a>

    <a href="../detail_program/detail_program.php?id=<?= urlencode((string) ($program["id"] ?? $id)) ?>"
       class="back-link">
        ← Kembali ke Detail
    </a>
</header>

<main class="container">

<?php if (!$program): ?>

    <section class="form-card">
        <h1>Program Tidak Ditemukan</h1>
        <p>Silakan buka program melalui halaman Explore Program.</p>
        <a href="../explore-program/exploreProgram.php" class="primary-button">
            Kembali ke Explore Program
        </a>
    </section>

<?php elseif ($success): ?>

    <section class="success-card">
        <div class="success-icon">✓</div>
        <h1>Pendaftaran Berhasil!</h1>
        <p>Data pendaftaranmu berhasil disimpan.</p>

        <div class="program-summary">
            <span>ID Pendaftaran</span>
            <h2><?= e($registrationId) ?></h2>
            <p>Program: <?= e($program["judul"]) ?></p>
            <p>Status: <strong>Menunggu Verifikasi</strong></p>
        </div>

        <a href="../detail_program/detail_program.php?id=<?= urlencode((string) $program["id"]) ?>"
           class="primary-button">
            Kembali ke Detail Program
        </a>
    </section>

<?php else: ?>

    <div class="page-heading">
        <p class="eyebrow">PENDAFTARAN PROGRAM</p>
        <h1>Lengkapi Data Dirimu</h1>
        <p>Isi data dengan benar sebelum mengirim pendaftaran.</p>
    </div>

    <section class="program-summary">
        <span class="category"><?= e($program["kategori"]) ?></span>
        <h2><?= e($program["judul"]) ?></h2>
        <p><?= e($program["penyelenggara"]) ?></p>
        <p>Deadline: <?= e($program["deadline"]) ?></p>
    </section>

    <?php if ($message !== ""): ?>
        <div class="alert" role="alert"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($program["status"] !== "Dibuka"): ?>

        <section class="form-card">
            <h2>Pendaftaran Ditutup</h2>
            <p>Program ini sedang tidak menerima pendaftaran.</p>
        </section>

    <?php else: ?>

        <form method="POST" class="form-card">
            <h2>Informasi Pribadi</h2>

            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama"
                    value="<?= e($_POST["nama"] ?? "") ?>"
                    maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="email">Email Aktif *</label>
                <input type="email" id="email" name="email"
                    value="<?= e($_POST["email"] ?? "") ?>"
                    maxlength="150" required>
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon *</label>
                <input type="tel" id="telepon" name="telepon"
                    value="<?= e($_POST["telepon"] ?? "") ?>"
                    maxlength="20" required>
            </div>

            <div class="form-group">
                <label for="universitas">Universitas / Institusi *</label>
                <input type="text" id="universitas" name="universitas"
                    value="<?= e($_POST["universitas"] ?? "") ?>"
                    maxlength="150" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="prodi">Program Studi *</label>
                    <input type="text" id="prodi" name="prodi"
                        value="<?= e($_POST["prodi"] ?? "") ?>"
                        maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="semester">Semester *</label>
                    <select id="semester" name="semester" required>
                        <option value="">Pilih semester</option>
                        <?php for ($i = 1; $i <= 14; $i++): ?>
                            <option value="<?= $i ?>"
                                <?= (string) ($_POST["semester"] ?? "") === (string) $i ? "selected" : "" ?>>
                                Semester <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="alasan">Alasan Mengikuti Program *</label>
                <textarea id="alasan" name="alasan" rows="5"
                    maxlength="1000" required><?= e($_POST["alasan"] ?? "") ?></textarea>
            </div>

            <p class="form-note">
                * Semua kolom wajib diisi.
            </p>

            <div class="form-actions">
                <a href="../detail_program/detail_program.php?id=<?= urlencode((string) $program["id"]) ?>"
                   class="secondary-button">
                    Batal
                </a>
                <button type="submit" class="primary-button">
                    Kirim Pendaftaran →
                </button>
            </div>
        </form>

    <?php endif; ?>

<?php endif; ?>

</main>


</body>
</html>
