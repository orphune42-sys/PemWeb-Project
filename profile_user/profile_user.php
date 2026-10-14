<?php
session_start();
$user = $_SESSION["user"] ?? [
    "nama" => "",
    "email" => ""
];

$defaultProfile = [
    "nama"        => $user["nama"] ?? "",
    "nim"         => "",
    "email"       => $user["email"] ?? "",
    "telepon"     => "",
    "gender"      => "",
    "lahir"       => "",
    "domisili"    => "",
    "universitas" => "",
    "fakultas"    => "",
    "prodi"       => "",
    "angkatan"    => "",
    "semester"    => "",
    "ipk"         => "",
    "skills"      => []
];

if (!isset($_SESSION["profile"])) {
    $_SESSION["profile"] = $defaultProfile;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "update_profile") {
        $_SESSION["profile"]["nama"] =
            $_POST["nama"] ?? "";
        $_SESSION["profile"]["email"] =
            $_SESSION["user"]["email"] ?? "";
        $_SESSION["profile"]["telepon"] =
            $_POST["telepon"] ?? "";
        $_SESSION["profile"]["domisili"] =
            $_POST["domisili"] ?? "";
        $_SESSION["profile"]["prodi"] =
            $_POST["prodi"] ?? "";
        $_SESSION["profile"]["semester"] =
            $_POST["semester"] ?? "";
        $_SESSION["profile"]["ipk"] =
            $_POST["ipk"] ?? "";
        $_SESSION["message"] =
            "Profil berhasil diperbarui.";
        header("Location: profile_user.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_academic") {
        $_SESSION["profile"]["universitas"] = $_POST["universitas"] ?? "";
        $_SESSION["profile"]["fakultas"] = $_POST["fakultas"] ?? "";
        $_SESSION["profile"]["prodi"] = $_POST["prodi"] ?? "";
        $_SESSION["profile"]["angkatan"] = $_POST["angkatan"] ?? "";
        $_SESSION["profile"]["semester"] = $_POST["semester"] ?? "";
        $_SESSION["profile"]["ipk"] = $_POST["ipk"] ?? "";
        header("Location: profile_user.php?success=academic");
        exit;
    }

    if ($action === "add_skill") {
        $skill = trim($_POST["skill"] ?? "");
        if ($skill !== "") {
            $_SESSION["profile"]["skills"][] = $skill;
            $_SESSION["message"] =
                "Keahlian berhasil ditambahkan.";
        }
        header("Location: profile_user.php");
        exit;
    }

    if ($action === "edit_skill") {
        $index = isset($_POST["skill_index"])
            ? (int) $_POST["skill_index"]
            : -1;
        $skill = trim($_POST["skill"] ?? "");
        if ($index >= 0 && isset($_SESSION["profile"]["skills"][$index]) && $skill !== "") {
            $_SESSION["profile"]["skills"][$index] =
                $skill;
            $_SESSION["message"] =
                "Keahlian berhasil diperbarui.";
        }
        header("Location: profile_user.php");
        exit;
    }

    if ($action === "delete_skill") {
        $index = isset($_POST["skill_index"]) ? (int) $_POST["skill_index"] : -1;
        if ($index >= 0 && isset($_SESSION["profile"]["skills"][$index])) {
            unset($_SESSION["profile"]["skills"][$index]);
            $_SESSION["profile"]["skills"] = array_values($_SESSION["profile"]["skills"]);
            $_SESSION["message"] = "Keahlian berhasil dihapus.";
        }
        header("Location: profile_user.php");
        exit;
    }
}

if (isset($_GET["action"]) && $_GET["action"] === "reset") {
    unset($_SESSION["profile"]);
    header("Location: profile_user.php");
    exit;
}

$profile = $_SESSION["profile"];
$message = $_SESSION["message"] ?? "";
unset($_SESSION["message"]);

$editSkillIndex = isset($_GET["edit_skill"]) ? (int) $_GET["edit_skill"] : -1;
$editSkillValue = "";

if ($editSkillIndex >= 0 && isset($profile["skills"][$editSkillIndex])) {
    $editSkillValue = $profile["skills"][$editSkillIndex];
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Profile - FindYourPath </title>

    <link rel="stylesheet" href="../sidebarMahasiswa/style.css">
    <link rel="stylesheet" href="profile_user.css">
</head>

<body>
    <!-- SIDEBAR -->
    <?php
    $activeMenu = 'profile';
    $basePath = '../';
    include '../sidebarMahasiswa/sidebar.php';
    ?>
    <main class="main-content">
        <header class="header">
            <div class="header-left">
                <button type="button" class="hamburger-btn" id="sidebarToggleBtn" aria-label="Buka Menu">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="header-title">My Profile</h1>
            </div>

            <div class="header-right">
                <button type="button" class="header-notif" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
                <div class="header-user">
                    <div class="header-avatar"><?= strtoupper(substr($profile["nama"], 0, 1)); ?></div>
                    <div class="header-userinfo">
                        <span class="header-username"><?= htmlspecialchars($profile["nama"]); ?></span>
                        <span class="header-userrole">Mahasiswa</span>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-content">
            <?php if ($message): ?>
                <div class="alert-success"><?= htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <section class="profile-header content-card">
                <div class="profile-avatar-large"><?= strtoupper(substr($profile["nama"], 0, 1)); ?></div>
                <div class="profile-main-info">
                    <h2><?= htmlspecialchars($profile["nama"]); ?></h2>
                    <p><?= htmlspecialchars($profile["prodi"]); ?></p>
                    <p class="profile-location">📍<?= htmlspecialchars($profile["domisili"]); ?></p>
                </div>

                <div class="profile-header-action"><button class="btn-primary" onclick="toggleEdit()">✎ Edit Profile</button></div>
            </section>

            <section id="editProfile" class="content-card edit-box hidden">
                <div class="section-header">
                    <div>
                        <h2>Edit Profile</h2>
                        <p>Ubah informasi profil kamu.</p>
                    </div>
                </div>

                <form method="POST" class="profile-form">
                    <input
                        type="hidden"
                        name="action"
                        value="update_profile">

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="<?= htmlspecialchars($profile["nama"]); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($profile["email"]); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="telepon">Nomor Telepon</label>
                        <input
                            type="text"
                            id="telepon"
                            name="telepon"
                            value="<?= htmlspecialchars($profile["telepon"]); ?>">
                    </div>

                    <div class="form-group">
                        <label for="domisili">Domisili</label>
                        <input
                            type="text"
                            id="domisili"
                            name="domisili"
                            value="<?= htmlspecialchars($profile["domisili"]); ?>">
                    </div>

                    <div class="form-group">
                        <label for="prodi">Program Studi</label>
                        <input
                            type="text"
                            id="prodi"
                            name="prodi"
                            value="<?= htmlspecialchars($profile["prodi"]); ?>">
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <input
                            type="number"
                            id="semester"
                            name="semester"
                            value="<?= htmlspecialchars($profile["semester"]); ?>"
                            min="1"
                            max="14">
                    </div>

                    <div class="form-group">
                        <label for="ipk">IPK</label>
                        <input
                            type="text"
                            id="ipk"
                            name="ipk"
                            value="<?= htmlspecialchars($profile["ipk"]); ?>">
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="toggleEdit()">Batal</button>
                        <button type="submit" class="btn-primary"> Simpan Perubahan </button>
                    </div>
                </form>
            </section>



            <!-- DATA DIRI -->
            <section class="content-card">
                <div class="section-header">
                    <div>
                        <h2>Data Diri</h2>
                        <p>Informasi pribadi pengguna</p>
                    </div>
                </div>

                <div class="profile-grid">
                    <div class="info-item">
                        <span>Nama Lengkap</span>
                        <strong><?= htmlspecialchars($profile["nama"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>NIM</span>
                        <strong><?= htmlspecialchars($profile["nim"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Email</span>
                        <strong><?= htmlspecialchars($profile["email"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Nomor Telepon</span>
                        <strong><?= htmlspecialchars($profile["telepon"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Jenis Kelamin</span>
                        <strong><?= htmlspecialchars($profile["gender"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Tanggal Lahir</span>
                        <strong><?= htmlspecialchars($profile["lahir"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Domisili</span>
                        <strong><?= htmlspecialchars($profile["domisili"]); ?></strong>
                    </div>

                </div>
            </section>

            <section class="content-card">
                <div class="section-header">
                    <div>
                        <h2>Data Akademik</h2>
                        <p>Informasi pendidikan pengguna</p>
                    </div>

                    <button type="button"
                        class="btn-small"
                        onclick="openAcademicModal()">
                        <?= empty($profile["universitas"])
                            ? "Lengkapi Data"
                            : "Edit Data"; ?>
                    </button>

                </div>

                <div class="profile-grid">
                    <div class="info-item">
                        <span>Universitas</span>
                        <strong><?= htmlspecialchars($profile["universitas"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Fakultas</span>
                        <strong><?= htmlspecialchars($profile["fakultas"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Program Studi</span>
                        <strong><?= htmlspecialchars($profile["prodi"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Angkatan</span>
                        <strong><?= htmlspecialchars($profile["angkatan"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>Semester</span>
                        <strong><?= htmlspecialchars($profile["semester"]); ?></strong>
                    </div>

                    <div class="info-item">
                        <span>IPK</span>
                        <strong><?= htmlspecialchars($profile["ipk"]); ?></strong>
                    </div>
                </div>
            </section>

            <section class="content-card">
                <div class="section-header">

                    <div>
                        <h2>Keahlian</h2>
                        <p>Skill yang dimiliki</p>
                    </div>

                    <button type="button" class="btn-outline" onclick="toggleSkillForm()">+ Tambah Keahlian</button>
                </div>

                <div id="addSkillForm" class="skill-form hidden">
                    <form method="POST">
                        <input
                            type="hidden"
                            name="action"
                            value="add_skill">

                        <div class="form-group">
                            <label for="skill">Nama Keahlian</label>
                            <input
                                type="text"
                                id="skill"
                                name="skill"
                                placeholder="Contoh: Python"
                                required>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-secondary" onclick="toggleSkillForm()">Batal</button>
                            <button type="submit" class="btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>

                <div class="skill-list">
                    <?php if (empty($profile["skills"])): ?>
                        <p class="empty-skill">Belum ada keahlian.</p>
                    <?php else: ?>
                        <?php foreach ($profile["skills"] as $index => $skill): ?>
                            <div class="skill-item">
                                <span class="skill-badge">
                                    <?= htmlspecialchars($skill); ?>
                                </span>
                                <div class="skill-actions">

                                    <a href="profile_user.php?edit_skill=<?= $index ?>" class="btn-small">✎ Edit</a>

                                    <form method="POST" class="delete-skill-form">
                                        <input
                                            type="hidden"
                                            name="action"
                                            value="delete_skill">

                                        <input
                                            type="hidden"
                                            name="skill_index"
                                            value="<?= $index ?>">

                                        <button type="submit" class="btn-small btn-small-danger">🗑 Hapus</button>
                                    </form>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if ($editSkillIndex >= 0 && $editSkillValue !== ""): ?>
                    <div class="skill-form">
                        <h3>Edit Keahlian</h3>
                        <form method="POST">
                            <input
                                type="hidden"
                                name="action"
                                value="edit_skill">

                            <input
                                type="hidden"
                                name="skill_index"
                                value="<?= $editSkillIndex ?>">

                            <div class="form-group">
                                <label for="editSkill">Nama Keahlian</label>

                                <input
                                    type="text"
                                    id="editSkill"
                                    name="skill"
                                    value="<?= htmlspecialchars($editSkillValue); ?>"
                                    required>
                            </div>

                            <div class="form-actions">
                                <a href="profile_user.php" class="btn-secondary">Batal</a>
                                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </section>

            <section class="content-card">
                <div class="section-header">
                    <div>
                        <h2>Portofolio</h2>
                        <p>Pengalaman dan karya</p>
                    </div>
                    <button type="button" class="btn-outline">+ Tambah Portofolio</button>
                </div>

                <div class="portfolio-empty">
                    <div class="empty-icon">◫</div>
                    <h3>Belum ada portofolio</h3>
                    <p>Tambahkan pengalaman atau karya terbaikmu untuk meningkatkan profil.</p>
                </div>
            </section>

            <section class="content-card">
                <div class="section-header">
                    <div>
                        <h2>Profile Completion</h2>
                        <p>Lengkapi profilmu agar lebih optimal.</p>
                    </div>
                    <strong class="completion-number">80%</strong>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" style="width: 80%;"></div>
                </div>

                <p class="progress-text">Profil kamu sudah 80% lengkap.</p>
            </section>

            <section class="content-card tips-card">
                <div class="tips-icon">💡</div>
                <div>
                    <h3>Tips untuk profilmu</h3>
                    <p>Lengkapi keahlian dan portofolio agar kamu lebih mudah menemukan peluang yang sesuai dengan minat dan kemampuanmu.</p>
                </div>
            </section>

            <section class="content-card danger-card">
                <div>
                    <h2>Reset Profile</h2>
                    <p>Mengembalikan data profil ke data awal.</p>
                </div>

                <a href="profile_user.php?action=reset" class="btn-danger">Reset Profile</a>
            </section>

        </div>
    </main>

    <script src="../sidebarMahasiswa/script.js"></script>
    <script src="profile_user.js"></script>
    <div id="academicModal" class="modal-overlay hidden">
        <div class="academic-modal">
            <div class="modal-header">
                <div>
                    <h2>Lengkapi Data Akademik</h2>
                    <p>Isi informasi akademik kamu.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    onclick="closeAcademicModal()">
                    ×
                </button>

            </div>

            <form
                method="POST"
                action="profile_user.php"
                class="academic-form">
                <input
                    type="hidden"
                    name="action"
                    value="update_academic">

                <div class="form-group">
                    <label for="universitas">Universitas</label>
                    <input
                        type="text"
                        id="universitas"
                        name="universitas"
                        placeholder="Masukkan universitas"
                        value="<?= htmlspecialchars($profile["universitas"]); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="fakultas">Fakultas</label>
                    <input
                        type="text"
                        id="fakultas"
                        name="fakultas"
                        placeholder="Masukkan fakultas"
                        value="<?= htmlspecialchars($profile["fakultas"]); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="prodi">Program Studi</label>
                    <input
                        type="text"
                        id="prodi"
                        name="prodi"
                        placeholder="Masukkan program studi"
                        value="<?= htmlspecialchars($profile["prodi"]); ?>"
                        required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="angkatan">Angkatan</label>
                        <input
                            type="number"
                            id="angkatan"
                            name="angkatan"
                            placeholder="Contoh: 2025"
                            value="<?= htmlspecialchars($profile["angkatan"]); ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="semester">Semester</label>

                        <input
                            type="number"
                            id="semester"
                            name="semester"
                            min="1"
                            max="14"
                            placeholder="Contoh: 3"
                            value="<?= htmlspecialchars($profile["semester"]); ?>"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ipk">IPK</label>

                    <input
                        type="number"
                        id="ipk"
                        name="ipk"
                        min="0"
                        max="4"
                        step="0.01"
                        placeholder="Contoh: 3.75"
                        value="<?= htmlspecialchars($profile["ipk"]); ?>"
                        required>

                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeAcademicModal()">Batal</button>

                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>