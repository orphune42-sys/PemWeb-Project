<?php

$goals = $goals ?? [];
$projects = $projects ?? [];
$achievements = $achievements ?? [];
$careerPlans = $careerPlans ?? [];

$sections = [
    'goals' => [
        'title' => 'My Goals & Skills',
        'subtitle' => 'Catat target dan kemampuan yang ingin kamu kembangkan.',
        'records' => $goals
    ],
    'projects' => [
        'title' => 'My Projects',
        'subtitle' => 'Dokumentasikan proyek dan karya yang pernah kamu buat.',
        'records' => $projects
    ],
    'achievements' => [
        'title' => 'My Achievements',
        'subtitle' => 'Simpan pencapaian dan pengalaman berhargamu.',
        'records' => $achievements
    ],
    'career' => [
        'title' => 'Career Roadmap',
        'subtitle' => 'Rencanakan langkah menuju karier impianmu.',
        'records' => $careerPlans
    ]
];

$totalRecords = count($goals) + count($projects)
    + count($achievements) + count($careerPlans);

$completedGoals = count(array_filter(
    $goals,
    fn($item) => $item['status'] === 'completed'
));

$completedProjects = count(array_filter(
    $projects,
    fn($item) => $item['status'] === 'completed'
));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Find Opportunities - FindYourPath</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet"
        href="<?= htmlspecialchars($baseUrl ?? '') ?>/public/css/sidebarMahasiswa.css">

    <link rel="stylesheet"
        href="<?= htmlspecialchars($baseUrl ?? '') ?>/public/css/find-opportunity.css">
</head>

<body>

    <div class="dashboard-container">

        <?php
        $activeMenu = 'find-opportunities';
        require_once __DIR__ . '/../../Controllers/SidebarMahasiswaController.php';

        $sidebarController = new SidebarMahasiswaController();
        $sidebarController->render('find-opportunities', $baseUrl ?? '');
        ?>

        <main class="main-content">

            <header class="header">
                <div class="header-left">
                    <button class="hamburger-btn" id="sidebarToggleBtn"
                        aria-label="Buka menu">☰</button>
                    <h1 class="header-title">Find Opportunities</h1>
                </div>

                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="header-user">
                        <div class="header-avatar">
                            <?= strtoupper(substr($_SESSION['user']['name'] ?? 'M', 0, 1)) ?>
                        </div>

                        <div class="header-userinfo">
                            <span class="header-username">
                                <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Mahasiswa') ?>
                            </span>
                            <span class="header-userrole">Mahasiswa</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-content">

                <section class="opportunity-hero">
                    <div class="hero-text">
                        <span class="hero-badge">Personal Growth Planner</span>

                        <h2>Kenali Potensimu, Bangun Masa Depanmu!</h2>

                        <p>
                            Catat perkembangan dirimu, dokumentasikan pencapaian,
                            dan susun langkah untuk meraih karier impianmu.
                        </p>
                    </div>

                    <div class="hero-decoration">✦</div>
                </section>

                <section class="summary-grid">

                    <div class="summary-card">
                        <div class="summary-icon blue">🎯</div>
                        <div>
                            <span>Total Goals & Skills</span>
                            <h3><?= count($goals) ?></h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon teal">💻</div>
                        <div>
                            <span>Total Projects</span>
                            <h3><?= count($projects) ?></h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon purple">🏆</div>
                        <div>
                            <span>Achievements</span>
                            <h3><?= count($achievements) ?></h3>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="summary-icon orange">🚀</div>
                        <div>
                            <span>Completed Items</span>
                            <h3><?= $completedGoals + $completedProjects ?></h3>
                        </div>
                    </div>

                </section>

                <section class="section-heading">
                    <div>
                        <h2>My Personal Development</h2>
                        <p>Semua perjalanan dan perkembanganmu dalam satu tempat.</p>
                    </div>

                    <span class="total-badge">
                        <?= $totalRecords ?> Data Tersimpan
                    </span>
                </section>

                <nav class="opportunity-tabs">

                    <?php $first = true; ?>

                    <?php foreach ($sections as $key => $section): ?>

                        <button
                            class="tab-btn <?= $first ? 'active' : '' ?>"
                            data-tab="<?= $key ?>">

                            <?= htmlspecialchars($section['title']) ?>

                        </button>

                        <?php $first = false; ?>

                    <?php endforeach; ?>

                </nav>

                <?php $first = true; ?>

                <?php foreach ($sections as $key => $section): ?>

                    <section
                        class="tab-content <?= $first ? 'active' : '' ?>"
                        id="tab-<?= $key ?>">

                        <div class="content-heading">
                            <div>
                                <h3><?= htmlspecialchars($section['title']) ?></h3>
                                <p><?= htmlspecialchars($section['subtitle']) ?></p>
                            </div>

                            <button
                                class="btn-add"
                                data-create="<?= $key ?>">
                                + Tambah Data
                            </button>
                        </div>

                        <div class="record-grid">

                            <?php if (!empty($section['records'])): ?>

                                <?php foreach ($section['records'] as $record): ?>

                                    <?php
                                    $recordTitle = $record['title']
                                        ?? $record['target_career']
                                        ?? '';

                                    $status = $record['status'] ?? 'completed';

                                    $recordJson = htmlspecialchars(
                                        json_encode($record),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>

                                    <article class="record-card">

                                        <div class="record-top">
                                            <span class="record-category">
                                                <?= $section['icon'] ?>
                                                <?= htmlspecialchars(
                                                    $record['item_type'] ?? $section['title']
                                                ) ?>
                                            </span>

                                            <?php if (isset($record['status'])): ?>
                                                <span class="status-badge <?= htmlspecialchars($status) ?>">
                                                    <?= htmlspecialchars(
                                                        ucfirst(str_replace('_', ' ', $status))
                                                    ) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h4>
                                            <?= htmlspecialchars($recordTitle) ?>
                                        </h4>

                                        <?php if (!empty($record['issuer'])): ?>
                                            <p class="record-subtitle">
                                                <?= htmlspecialchars($record['issuer']) ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($record['category'])): ?>
                                            <span class="category-tag">
                                                <?= htmlspecialchars($record['category']) ?>
                                            </span>
                                        <?php endif; ?>

                                        <p class="record-description">
                                            <?= htmlspecialchars(
                                                $record['description']
                                                    ?? $record['action_plan']
                                                    ?? 'Belum ada deskripsi.'
                                            ) ?>
                                        </p>

                                        <div class="record-date">
                                            <?php
                                            $date = $record['target_date']
                                                ?? $record['end_date']
                                                ?? $record['achievement_date']
                                                ?? null;
                                            ?>

                                            <?php if ($date): ?>
                                                <span>◷ <?= htmlspecialchars($date) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="record-actions">

                                            <button
                                                type="button"
                                                class="btn-edit"
                                                data-edit="<?= $key ?>"
                                                data-record="<?= $recordJson ?>">
                                                Edit
                                            </button>

                                            <form method="POST"
                                                action="<?= htmlspecialchars($baseUrl ?? '') ?>/public/?page=find-opportunities"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">

                                                <input type="hidden" name="csrf_token"
                                                    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="type" value="<?= $key ?>">
                                                <input type="hidden" name="id" value="<?= (int)$record['id'] ?>">

                                                <button type="submit" class="btn-delete">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </article>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="empty-state">
                                    <div>✦</div>
                                    <h3>Belum ada data</h3>
                                    <p>
                                        Mulai catat perkembanganmu dan bangun
                                        perjalanan masa depanmu!
                                    </p>
                                </div>

                            <?php endif; ?>

                        </div>

                    </section>

                    <?php $first = false; ?>

                <?php endforeach; ?>

            </div>

        </main>

    </div>

    <div class="modal-overlay" id="recordModal">

        <div class="modal-card">

            <div class="modal-header">
                <div>
                    <span class="modal-label">PERSONAL DEVELOPMENT</span>
                    <h2 id="modalTitle">Tambah Data</h2>
                </div>

                <button type="button" id="closeModal" class="modal-close">
                    &times;
                </button>
            </div>

            <form method="POST"
                action="<?= htmlspecialchars($baseUrl ?? '') ?>/public/?page=find-opportunities"
                id="recordForm">

                <input type="hidden" name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <input type="hidden" name="action" id="formAction" value="create">
                <input type="hidden" name="type" id="formType">
                <input type="hidden" name="id" id="formId">

                <div id="formFields"></div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="cancelModal">
                        Batal
                    </button>

                    <button type="submit" class="btn-save">
                        Simpan Data
                    </button>
                </div>

            </form>

        </div>

    </div>

    <script src="<?= htmlspecialchars($baseUrl ?? '') ?>/public/js/find-opportunity.js"></script>

</body>

</html>