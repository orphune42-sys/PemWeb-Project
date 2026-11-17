<?php
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

$actionUrl = $baseUrl .
    '/public/index.php?page=pendaftaran&id=' .
    (int) $pendaftaran['program_id'] .
    '&aksi=edit&pendaftaran_id=' .
    (int) $pendaftaran['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pendaftaran - FindYourPath</title>
    <link rel="stylesheet"
          href="<?= e($baseUrl) ?>/public/css/form_pendaftaran.css?v=5">
</head>
<body>

<div class="form-container edit-registration-page">
    <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $pendaftaran['program_id'] ?>"
       class="back-link">
        &larr; Kembali ke Riwayat
    </a>

    <h1>Edit Pendaftaran</h1>
    <p>
        Program:
        <strong><?= e($program['judul'] ?? '-') ?></strong>
    </p>

    <?php if ($message !== ''): ?>
        <div class="<?= $success ? 'success-message' : 'error-message' ?>">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <?php if (!$canEdit): ?>
        <p>
            Pendaftaran ini tidak dapat diedit karena statusnya
            sudah bukan Menunggu Verifikasi.
        </p>
        <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $pendaftaran['program_id'] ?>">
            Kembali ke Riwayat Pendaftaran
        </a>
    <?php elseif ($success): ?>

        <p>Perubahan data berhasil disimpan.</p>
        <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $pendaftaran['program_id'] ?>">
            Kembali ke Riwayat Pendaftaran
        </a>
    <?php else: ?>
        <form action="<?= e($actionUrl) ?>" method="POST">
            <input type="hidden"
                   name="pendaftaran_id"
                   value="<?= (int) $pendaftaran['id'] ?>">
            <input type="hidden"
                   name="csrf_token"
                   value="<?= e($csrfToken) ?>">

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text"
                       value="<?= e($pendaftaran['nama']) ?>"
                       readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       value="<?= e($pendaftaran['email']) ?>"
                       readonly>
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input type="text"
                       id="telepon"
                       name="telepon"
                       value="<?= e($pendaftaran['telepon']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="universitas">Universitas</label>
                <input type="text"
                       id="universitas"
                       name="universitas"
                       value="<?= e($pendaftaran['universitas']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="prodi">Program Studi</label>
                <input type="text"
                       id="prodi"
                       name="prodi"
                       value="<?= e($pendaftaran['prodi']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number"
                       id="semester"
                       name="semester"
                       min="1"
                       max="14"
                       value="<?= e($pendaftaran['semester']) ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="alasan">Alasan Mengikuti Program</label>
                <textarea id="alasan"
                          name="alasan"
                          required><?= e($pendaftaran['alasan']) ?></textarea>
            </div>

            <div class="edit-form-actions">
                <button type="submit">
                    Simpan Perubahan
                </button>

                <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $pendaftaran['program_id'] ?>">
                    Batal
                </a>
            </div>
        </form>
    <?php endif; ?>

</div>
</body>
</html>