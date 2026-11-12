<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

// Mengambil ID program dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
}

// Fungsi keamanan output HTML
function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$message = '';
$success = false;
$registrationId = '';
$program = null;
$documents = [];

// Mengambil data program dari database
if ($id) {
    $stmt = $pdo->prepare(
        "SELECT * FROM program WHERE id = :id"
    );
    $stmt->execute(['id' => $id]);
    $program = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mengambil persyaratan dokumen khusus program ini
    if ($program) {
        $stmtDocs = $pdo->prepare(
            "SELECT id, isi
             FROM program_detail
             WHERE program_id = :id
             AND jenis = 'dokumen'
             ORDER BY id ASC"
        );
        $stmtDocs->execute(['id' => $id]);
        $documents = $stmtDocs->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Memproses pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $program) {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $universitas = trim($_POST['universitas'] ?? '');
    $prodi = trim($_POST['prodi'] ?? '');
    $semester = trim($_POST['semester'] ?? '');
    $alasan = trim($_POST['alasan'] ?? '');

    if ($program['status'] !== 'Dibuka') {
        $message = 'Maaf, pendaftaran program ini sedang ditutup.';
    } elseif (
        $nama === '' || $email === '' || $telepon === '' ||
        $universitas === '' || $prodi === '' ||
        $semester === '' || $alasan === ''
    ) {
        $message = 'Semua kolom wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
    } else {
        $maxSize = 200 * 1024 * 1024;

        $allowedMimes = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png'
        ];

        $uploadedFiles = [];
        $uploadDirectory = __DIR__ . '/../uploads/pendaftaran/';

        try {
            // Pastikan semua dokumen wajib sudah dipilih
            foreach ($documents as $doc) {
                $docId = (int) $doc['id'];

                if (
                    !isset($_FILES['dokumen']['error'][$docId]) ||
                    $_FILES['dokumen']['error'][$docId] === UPLOAD_ERR_NO_FILE
                ) {
                    throw new Exception(
                        'Dokumen "' . $doc['isi'] . '" wajib diunggah.'
                    );
                }

                if ($_FILES['dokumen']['error'][$docId] !== UPLOAD_ERR_OK) {
                    throw new Exception(
                        'Terjadi kesalahan saat mengunggah dokumen "' .
                        $doc['isi'] . '".'
                    );
                }

                $fileSize = $_FILES['dokumen']['size'][$docId];

                if ($fileSize > $maxSize) {
                    throw new Exception(
                        'Ukuran dokumen "' . $doc['isi'] .
                        '" melebihi batas 5 MB.'
                    );
                }

                $tmpName = $_FILES['dokumen']['tmp_name'][$docId];

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($tmpName);

                if (!isset($allowedMimes[$mime])) {
                    throw new Exception(
                        'Format dokumen "' . $doc['isi'] .
                        '" harus PDF, JPG, atau PNG.'
                    );
                }

                $extension = $allowedMimes[$mime];

                $randomName = bin2hex(random_bytes(16)) .
                    '.' . $extension;

                $relativePath = 'uploads/pendaftaran/' . $randomName;

                $uploadedFiles[] = [
                    'doc_id' => $docId,
                    'tmp_name' => $tmpName,
                    'file_name' => $randomName,
                    'path' => $relativePath,
                    'mime' => $mime,
                    'size' => $fileSize
                ];
            }

            // Membuat folder jika belum tersedia
            if (!is_dir($uploadDirectory)) {
                if (!mkdir($uploadDirectory, 0755, true)) {
                    throw new Exception(
                        'Folder penyimpanan dokumen gagal dibuat.'
                    );
                }
            }

            // Menyimpan data pendaftaran dan dokumen
            $pdo->beginTransaction();

            $registrationId = 'REG-' .
                strtoupper(bin2hex(random_bytes(6)));

            $stmtInsert = $pdo->prepare(
                "INSERT INTO pendaftaran (
                    kode_pendaftaran,
                    program_id,
                    nama,
                    email,
                    telepon,
                    universitas,
                    prodi,
                    semester,
                    alasan,
                    status
                ) VALUES (
                    :kode,
                    :program_id,
                    :nama,
                    :email,
                    :telepon,
                    :universitas,
                    :prodi,
                    :semester,
                    :alasan,
                    'Menunggu Verifikasi'
                )"
            );

            $stmtInsert->execute([
                'kode' => $registrationId,
                'program_id' => $id,
                'nama' => $nama,
                'email' => $email,
                'telepon' => $telepon,
                'universitas' => $universitas,
                'prodi' => $prodi,
                'semester' => $semester,
                'alasan' => $alasan
            ]);

            $pendaftaranId = (int) $pdo->lastInsertId();

            $stmtDocument = $pdo->prepare(
                "INSERT INTO pendaftaran_dokumen (
                    pendaftaran_id,
                    program_detail_id,
                    nama_file,
                    path_file,
                    tipe_file,
                    ukuran_file
                ) VALUES (
                    :pendaftaran_id,
                    :program_detail_id,
                    :nama_file,
                    :path_file,
                    :tipe_file,
                    :ukuran_file
                )"
            );

            foreach ($uploadedFiles as $file) {
                $destination = $uploadDirectory . $file['file_name'];

                if (!move_uploaded_file(
                    $file['tmp_name'],
                    $destination
                )) {
                    throw new Exception(
                        'Gagal menyimpan salah satu dokumen.'
                    );
                }

                $stmtDocument->execute([
                    'pendaftaran_id' => $pendaftaranId,
                    'program_detail_id' => $file['doc_id'],
                    'nama_file' => $file['file_name'],
                    'path_file' => $file['path'],
                    'tipe_file' => $file['mime'],
                    'ukuran_file' => $file['size']
                ]);
            }

            $pdo->commit();
            $success = true;

        } catch (Throwable $error) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            // Menghapus file yang telanjur dipindahkan jika gagal
            foreach ($uploadedFiles as $file) {
                $savedPath = $uploadDirectory . $file['file_name'];

                if (is_file($savedPath)) {
                    unlink($savedPath);
                }
            }

            $message = $error instanceof PDOException
                ? 'Pendaftaran gagal disimpan. Periksa kembali database.'
                : $error->getMessage();
        }
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

    <a href="../detail_program/detail_program.php?id=<?= urlencode((string) ($program['id'] ?? $id)) ?>"
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
        <p>Data pendaftaran dan dokumenmu berhasil disimpan.</p>

        <div class="program-summary">
            <span>ID Pendaftaran</span>
            <h2><?= e($registrationId) ?></h2>
            <p>Program: <?= e($program['judul']) ?></p>
            <p>Status: <strong>Menunggu Verifikasi</strong></p>
        </div>

        <a href="../detail_program/detail_program.php?id=<?= urlencode((string) $program['id']) ?>"
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
        <span class="category"><?= e($program['kategori']) ?></span>
        <h2><?= e($program['judul']) ?></h2>
        <p><?= e($program['penyelenggara']) ?></p>
        <p>Deadline: <?= e($program['deadline']) ?></p>
    </section>

    <?php if ($message !== ''): ?>
        <div class="alert" role="alert"><?= e($message) ?></div>
    <?php endif; ?>

    <?php if ($program['status'] !== 'Dibuka'): ?>

        <section class="form-card">
            <h2>Pendaftaran Ditutup</h2>
            <p>Program ini sedang tidak menerima pendaftaran.</p>
        </section>

    <?php else: ?>

        <form method="POST"
              id="registrationForm"
              enctype="multipart/form-data"
              class="form-card">

            <input type="hidden" name="id" value="<?= (int) $id ?>">

            <h2>Informasi Pribadi</h2>

            <div class="form-group">
                <label for="nama">Nama Lengkap *</label>
                <input type="text" id="nama" name="nama"
                    value="<?= e($_POST['nama'] ?? '') ?>"
                    maxlength="100" required>
            </div>

            <div class="form-group">
                <label for="email">Email Aktif *</label>
                <input type="email" id="email" name="email"
                    value="<?= e($_POST['email'] ?? '') ?>"
                    maxlength="150" required>
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon *</label>
                <input type="tel" id="telepon" name="telepon"
                    value="<?= e($_POST['telepon'] ?? '') ?>"
                    maxlength="20" required>
            </div>

            <div class="form-group">
                <label for="universitas">Universitas / Institusi *</label>
                <input type="text" id="universitas" name="universitas"
                    value="<?= e($_POST['universitas'] ?? '') ?>"
                    maxlength="150" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="prodi">Program Studi *</label>
                    <input type="text" id="prodi" name="prodi"
                        value="<?= e($_POST['prodi'] ?? '') ?>"
                        maxlength="100" required>
                </div>

                <div class="form-group">
                    <label for="semester">Semester *</label>
                    <select id="semester" name="semester" required>
                        <option value="">Pilih semester</option>
                        <?php for ($i = 1; $i <= 14; $i++): ?>
                            <option value="<?= $i ?>"
                                <?= (string) ($_POST['semester'] ?? '') === (string) $i ? 'selected' : '' ?>>
                                Semester <?= $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="alasan">Alasan Mengikuti Program *</label>
                <textarea id="alasan" name="alasan" rows="5"
                    maxlength="1000" required><?= e($_POST['alasan'] ?? '') ?></textarea>
            </div>

            <h2>Dokumen Persyaratan</h2>
            <p class="form-note">
                Unggah seluruh dokumen yang diminta oleh program ini.
                Format PDF, JPG, atau PNG dengan ukuran maksimal 100 MB per file.
            </p>

            <?php if (empty($documents)): ?>

                <p>Program ini tidak memiliki persyaratan dokumen unggahan.</p>

            <?php else: ?>

                <?php foreach ($documents as $doc): ?>
                    <div class="form-group">
                        <label for="dokumen_<?= (int) $doc['id'] ?>">
                            <?= e($doc['isi']) ?> *
                        </label>

                        <input
                            type="file"
                            id="dokumen_<?= (int) $doc['id'] ?>"
                            name="dokumen[<?= (int) $doc['id'] ?>]"
                            accept=".pdf,.jpg,.jpeg,.png"
                            required
                        >
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <p class="form-note">
                * Semua kolom dan dokumen yang diminta wajib diisi.
            </p>

            <div class="form-actions">
                <a href="../detail_program/detail_program.php?id=<?= urlencode((string) $program['id']) ?>"
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

<script src="form_pendaftaran.js"></script>
</body>
</html>
