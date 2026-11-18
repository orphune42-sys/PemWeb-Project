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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - FindYourPath</title>

    <link rel="stylesheet"
          href="<?= e($baseUrl) ?>/public/css/form_pendaftaran.css?v=5">
</head>
<body>

<div class="container">

    <a href="<?= e($baseUrl) ?>/public/index.php?page=detail-program&id=<?= (int) $id ?>">
        &larr; Kembali ke Detail Program
    </a>
    <h1>Form Pendaftaran</h1>

    <?php if (!$program): ?>
        <div class="message error">
            Program tidak ditemukan.
        </div>
    <?php elseif ($success): ?>

        <div class="message success">
            <h2>Pendaftaran Berhasil!</h2>
            <p>Kode Pendaftaran:
                <strong><?= e($registrationId) ?></strong>
            </p>
            <p>Pendaftaran kamu berhasil dikirim dan sedang menunggu verifikasi.</p>
        </div>

        <a href="<?= e($baseUrl) ?>/public/index.php?page=explore-program">
            Kembali ke Explore Program
        </a>

        <?php elseif ($existingRegistration): ?>
            <div class="message warning">
                <h2>Kamu Sudah Terdaftar</h2>
                <p>Kamu sudah pernah mendaftar pada program ini.</p>
                <p>Kode Pendaftaran:
                    <strong><?= e($existingRegistration['kode_pendaftaran']) ?></strong>
                </p>
            </div>
    <?php else: ?>

        <?php if (!empty($message)): ?>
            <div class="message <?= !empty($cancelSuccess) ? 'success' : 'error' ?>">
                <?= e($message) ?>
            </div>
        <?php endif; ?>

        <div class="program-info">
            <h2><?= e($program['judul']) ?></h2>
            <p>Penyelenggara: <?= e($program['penyelenggara']) ?></p>
            <p>Kategori: <?= e($program['kategori']) ?></p>
        </div>

        <form
            id="registrationForm"
            class="form-card"
            novalidate
            action="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $id ?>"
            method="POST"
            enctype="multipart/form-data"
        >

            <input type="hidden" name="id" value="<?= (int) $id ?>">
            <input
                type="hidden"
                name="csrf_token"
                value="<?= e($_SESSION['csrf_token'] ?? '') ?>"
            >
            <h3>Data Mahasiswa</h3>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" value="<?= e($namaAkun) ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?= e($emailAkun) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input
                    type="text"
                    id="telepon"
                    name="telepon"
                    value="<?= e($_POST['telepon'] ?? '') ?>"
                    required
                >
                <small class="field-error" <?= empty($fieldErrors['telepon']) ? 'hidden' : '' ?>>
                    <?= e($fieldErrors['telepon'] ?? '') ?>
                </small>
                <small class="field-error client-field-error" hidden></small>
            </div>

            <div class="form-group">
                <label for="universitas">Universitas</label>
                <input
                    type="text"
                    id="universitas"
                    name="universitas"
                    value="<?= e($_POST['universitas'] ?? '') ?>"
                    required
                >
                <small class="field-error" <?= empty($fieldErrors['universitas']) ? 'hidden' : '' ?>>
                    <?= e($fieldErrors['universitas'] ?? '') ?>
                </small>
                <small class="field-error client-field-error" hidden></small>
            </div>

            <div class="form-group">
                <label for="prodi">Program Studi</label>
                <input
                    type="text"
                    id="prodi"
                    name="prodi"
                    value="<?= e($_POST['prodi'] ?? '') ?>"
                    required
                >
                <small class="field-error" <?= empty($fieldErrors['prodi']) ? 'hidden' : '' ?>>
                    <?= e($fieldErrors['prodi'] ?? '') ?>
                </small>
                <small class="field-error client-field-error" hidden></small>
            </div>

            <div class="form-group">
                <label for="semester">Semester</label>
                <input
                    type="number"
                    id="semester"
                    name="semester"
                    min="1"
                    max="14"
                    value="<?= e($_POST['semester'] ?? '') ?>"
                    required
                >
                <small class="field-error" <?= empty($fieldErrors['semester']) ? 'hidden' : '' ?>>
                    <?= e($fieldErrors['semester'] ?? '') ?>
                </small>
                <small class="field-error client-field-error" hidden></small>
            </div>

            <div class="form-group">
                <label for="alasan">Alasan Mengikuti Program</label>
                <textarea
                    id="alasan"
                    name="alasan"
                    rows="5"
                    required
                ><?= e($_POST['alasan'] ?? '') ?></textarea>

                <small class="field-error" <?= empty($fieldErrors['alasan']) ? 'hidden' : '' ?>>
                    <?= e($fieldErrors['alasan'] ?? '') ?>
                </small>
                <small class="field-error client-field-error" hidden></small>
            </div>

            <?php if (!empty($documents)): ?>
                <h3>Dokumen Persyaratan</h3>
                <?php foreach ($documents as $document): ?>
                    <div class="form-group">
                        <label for="dokumen_<?= (int) $document['id'] ?>">
                            <?= e($document['isi']) ?>
                        </label>

                        <input
                            type="file"
                            id="dokumen_<?= (int) $document['id'] ?>"
                            name="dokumen[<?= (int) $document['id'] ?>]"
                            accept=".pdf,.jpg,.jpeg,.png"
                            data-document-name="<?= e($document['isi']) ?>"
                        >

                        <?php if (
                            isset($documentErrorId) &&
                            (int) $documentErrorId === (int) $document['id'] &&
                            !empty($documentErrorMessage)
                        ): ?>
                            <small class="field-error">
                                <?= e($documentErrorMessage) ?>
                            </small>
                        <?php endif; ?>

                        <small class="field-error client-error" hidden></small>

                        <small>Format PDF, JPG, atau PNG. Maksimal 100 MB per file.</small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <button type="submit" name="submit_pendaftaran">
                Kirim Pendaftaran
            </button>

        </form>

    <?php endif; ?>

    <!-- RIWAYAT PENDAFTARAN -->
    <section class="history-section">
        <h2>Riwayat Pendaftaran Saya</h2>
        <?php if (!empty($riwayatPendaftaran)): ?>
            <?php foreach ($riwayatPendaftaran as $riwayat): ?>
                <div class="history-item">
                    <h3><?= e($riwayat['nama_program']) ?></h3>
                    <p>Kode: <?= e($riwayat['kode_pendaftaran']) ?></p>
                    <p>Kategori: <?= e($riwayat['kategori']) ?></p>
                    <p>Tanggal: <?= e($riwayat['tanggal_daftar']) ?></p>
                    <p>Status: <?= e($riwayat['status']) ?></p>
                    <div class="history-actions">
                        <a href="<?= e($baseUrl) ?>/public/index.php?page=detail-program&id=<?= (int) $riwayat['program_id'] ?>"
                           class="history-link">
                            Lihat Program
                        </a>

                        <?php if ($riwayat['status'] === 'Menunggu Verifikasi'): ?>
                            <a href="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $riwayat['program_id'] ?>&aksi=edit&pendaftaran_id=<?= (int) $riwayat['id'] ?>"
                               class="edit-registration-button">
                                Edit Pendaftaran
                            </a>

                            <form
                                method="POST"
                                action="<?= e($baseUrl) ?>/public/index.php?page=pendaftaran&id=<?= (int) $riwayat['program_id'] ?>"
                                onsubmit="return confirm('Apakah kamu yakin ingin membatalkan pendaftaran ini? Data dan dokumen yang sudah diunggah akan dihapus.');"
                            >
                                <input type="hidden" name="aksi" value="cancel">

                                <input type="hidden"
                                       name="id"
                                       value="<?= (int) $riwayat['program_id'] ?>">
                                <input type="hidden"
                                       name="pendaftaran_id"
                                       value="<?= (int) $riwayat['id'] ?>">
                                <input type="hidden"
                                       name="csrf_token"
                                       value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                <button type="submit"
                                        class="cancel-registration-button">
                                    Batalkan Pendaftaran
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada riwayat pendaftaran.</p>
        <?php endif; ?>
    </section>

</div>
<script src="<?= e($baseUrl) ?>/public/js/form_pendaftaran.js"></script>
</body>
</html>