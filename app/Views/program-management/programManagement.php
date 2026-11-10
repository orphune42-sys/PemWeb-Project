<?php

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Program Management - FindYourPath</title>

    <link rel="stylesheet" href="css/programManagement.css">
</head>

<body>

    <main class="main-content">

        <header class="page-header">
            <div>
                <h1>Program Management</h1>
                <p>Kelola informasi program, lomba, beasiswa, dan magang.</p>
            </div>

            <button type="button" id="addProgramButton" class="btn-primary">
                + Tambah Program
            </button>
        </header>

        <section class="stats-container">

            <div class="stat-card">
                <p>Total Program</p>
                <h2><?= e($stats['total']) ?></h2>
            </div>

            <div class="stat-card">
                <p>Program Aktif</p>
                <h2><?= e($stats['active']) ?></h2>
            </div>

            <div class="stat-card">
                <p>Akan Datang</p>
                <h2><?= e($stats['upcoming']) ?></h2>
            </div>

            <div class="stat-card">
                <p>Selesai</p>
                <h2><?= e($stats['finished']) ?></h2>
            </div>

        </section>

        <section class="filter-container">

            <input
                type="text"
                id="searchProgram"
                placeholder="Cari nama program atau penyelenggara...">

            <select id="typeFilter">
                <option value="">Semua Jenis</option>
                <option value="Beasiswa">Beasiswa</option>
                <option value="Lomba">Lomba</option>
                <option value="Program">Program</option>
                <option value="Magang">Magang</option>
            </select>

        </section>

        <section class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Program</th>
                        <th>Penyelenggara</th>
                        <th>Jenis</th>
                        <th>Bidang</th>
                        <th>Deadline</th>
                        <th>Pendaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="programTableBody">

                    <?php foreach ($programs as $index => $program): ?>

                        <tr
                            data-id="<?= e($program['id']) ?>"
                            data-name="<?= e($program['name']) ?>"
                            data-organizer="<?= e($program['organizer']) ?>"
                            data-type="<?= e($program['type']) ?>"
                            data-field="<?= e($program['field']) ?>"
                            data-deadline="<?= e($program['deadline']) ?>"
                            data-registrants="<?= e($program['registrants']) ?>">

                            <td><?= $index + 1 ?></td>

                            <td class="program-name">
                                <strong><?= e($program['name']) ?></strong>
                            </td>

                            <td><?= e($program['organizer']) ?></td>

                            <td>
                                <span class="field-tag">
                                    <?= e($program['type']) ?>
                                </span>
                            </td>

                            <td><?= e($program['field']) ?></td>

                            <td>
                                <?= e(date('d-m-Y', strtotime($program['deadline']))) ?>
                            </td>

                            <td><?= e($program['registrants']) ?></td>

                            <td class="action-buttons">
                                <button type="button" class="edit-program">
                                    Edit
                                </button>

                                <button type="button" class="delete-program">
                                    Hapus
                                </button>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    <?php if (empty($programs)): ?>
                        <tr>
                            <td colspan="8">Belum ada data program.</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>

            <p id="programResult">
                Menampilkan <?= count($programs) ?> program
            </p>

        </section>

    </main>

    <div class="modal" id="programModal">

        <div class="modal-content">

            <div class="modal-header">
                <h2 id="modalTitle">Tambah Program</h2>

                <button type="button" id="closeModal">&times;</button>
            </div>

            <form
                id="programForm"
                method="POST"
                action="index.php?page=program-management">

                <input type="hidden" name="action" id="formAction" value="store">
                <input type="hidden" name="id" id="programId">

                <label for="programName">Nama Program</label>
                <input type="text" name="name" id="programName" required>

                <label for="programOrganizer">Penyelenggara</label>
                <input type="text" name="organizer" id="programOrganizer" required>

                <label for="programType">Jenis Program</label>
                <select name="type" id="programType" required>
                    <option value="">Pilih Jenis</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Lomba">Lomba</option>
                    <option value="Program">Program</option>
                    <option value="Magang">Magang</option>
                </select>

                <label for="programField">Bidang</label>
                <input type="text" name="field" id="programField" required>

                <label for="programDeadline">Deadline</label>
                <input type="date" name="deadline" id="programDeadline" required>

                <label for="programRegistrants">Jumlah Pendaftar</label>
                <input
                    type="number"
                    name="registrants"
                    id="programRegistrants"
                    min="0"
                    required>

                <div class="modal-actions">
                    <button type="button" id="cancelModal">Batal</button>
                    <button type="submit" class="btn-primary">Simpan</button>
                </div>

            </form>

        </div>

    </div>

    <form
        id="deleteForm"
        method="POST"
        action="index.php?page=program-management"
        hidden>
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="id" id="deleteId">
    </form>

    <script src="js/programManagement.js"></script>

</body>

</html>