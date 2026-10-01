<?php
session_start();

/* DATA DEFAULT PROFILE */

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

/* CREATE PROFILE */

if (!isset($_SESSION["profile"])) {
    $_SESSION["profile"] = $defaultProfile;
}

/* PROSES POST */

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    /* UPDATE PROFILE */

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

    // penambahan alert data akademik
    if ($_SERVER["REQUEST_METHOD"] === "POST" &&($_POST["action"] ?? "") === "update_academic") {
        $_SESSION["profile"]["universitas"] = $_POST["universitas"] ?? "";
        $_SESSION["profile"]["fakultas"] = $_POST["fakultas"] ?? "";
        $_SESSION["profile"]["prodi"] = $_POST["prodi"] ?? "";
        $_SESSION["profile"]["angkatan"] = $_POST["angkatan"] ?? "";
        $_SESSION["profile"]["semester"] = $_POST["semester"] ?? "";
        $_SESSION["profile"]["ipk"] = $_POST["ipk"] ?? "";
        header("Location: profile_user.php?success=academic");
        exit;
    }

    /* CREATE / TAMBAH KEAHLIAN */

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

    /* UPDATE / EDIT KEAHLIAN */

    if ($action === "edit_skill") {
        $index = isset($_POST["skill_index"])
            ? (int) $_POST["skill_index"]
            : -1;
        $skill = trim($_POST["skill"] ?? "");
        if ( $index >= 0 && isset($_SESSION["profile"]["skills"][$index]) && $skill !== "") {
            $_SESSION["profile"]["skills"][$index] =
                $skill;
            $_SESSION["message"] =
                "Keahlian berhasil diperbarui.";
        }
        header("Location: profile_user.php");
        exit;
    }


    /*  DELETE / HAPUS KEAHLIAN */
    if ($action === "delete_skill") {
        $index = isset($_POST["skill_index"]) ? (int) $_POST["skill_index"] : -1;
        if ( $index >= 0 && isset($_SESSION["profile"]["skills"][$index])) {
            unset($_SESSION["profile"]["skills"][$index]);
            $_SESSION["profile"]["skills"] = array_values($_SESSION["profile"]["skills"]);
            $_SESSION["message"] = "Keahlian berhasil dihapus.";
        }
        header("Location: profile_user.php");
        exit;
    }
}


/* RESET PROFILE */

if (isset($_GET["action"]) && $_GET["action"] === "reset") {
    unset($_SESSION["profile"]);
    header("Location: profile_user.php");
    exit;
}


/* AMBIL DATA PROFILE */
$profile = $_SESSION["profile"];
$message = $_SESSION["message"] ?? "";
unset($_SESSION["message"]);


/* MODE EDIT KEAHLIAN */

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

    <link rel="stylesheet" href="profile_user.css">
</head>

<body>
<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"> F </div>
        <div>
            <h2>FindYourPath</h2>
            <span>Student Opportunities</span>
        </div>
    </div>

    <nav class="sidebar-menu">
        <a href="index.php"><span>▦</span>Dashboard</a>
        <a href="explore-program.php"><span>⌕</span>Explore Program</a>
        <a href="form-pendaftaran.php"><span>▤</span>Applications</a>
        <a href="find-opportunities.php"><span>✦</span>Find Opportunities</a>
        <a href="profile_user.php" class="active"><span>♙</span>Profile</a>
    </nav>

    <div class="sidebar-bottom">
        <a href="#">⚙ Settings</a>
        <a href="#">↪ Logout</a>
    </div>

</aside>



<!-- MAIN CONTENT -->
<main class="main-content">

    <!-- TOPBAR -->
    <header class="topbar">
        <div>
            <p class="breadcrumb">Profile</p>
            <h1>My Profile</h1>
        </div>

        <div class="topbar-user">
            <div class="notification">♢</div>
            <div class="mini-avatar">
                <?= strtoupper(substr($profile["nama"], 0, 1)); ?>
            </div>

            <div class="topbar-user-info">
                <strong><?= htmlspecialchars($profile["nama"]); ?></strong>
                <span>Mahasiswa</span>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="page-content">
        <!-- MESSAGE -->
        <?php if ($message): ?>
            <div class="alert-success"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- PROFILE HEADER -->
        <section class="profile-header content-card">
            <div class="profile-avatar-large"><?= strtoupper(substr($profile["nama"], 0, 1)); ?></div>
            <div class="profile-main-info"><h2><?= htmlspecialchars($profile["nama"]); ?></h2>
                <p><?= htmlspecialchars($profile["prodi"]); ?></p>
                <p class="profile-location">📍<?= htmlspecialchars($profile["domisili"]); ?></p>
            </div>

            <div class="profile-header-action"><button class="btn-primary" onclick="toggleEdit()">✎ Edit Profile</button></div>
        </section>

        <!-- EDIT PROFILE -->
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
                    value="update_profile"
                >

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="<?= htmlspecialchars($profile["nama"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($profile["email"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="telepon">Nomor Telepon</label>
                    <input
                        type="text"
                        id="telepon"
                        name="telepon"
                        value="<?= htmlspecialchars($profile["telepon"]); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="domisili">Domisili</label>
                    <input
                        type="text"
                        id="domisili"
                        name="domisili"
                        value="<?= htmlspecialchars($profile["domisili"]);?>"
                    >
                </div>

                <div class="form-group">
                    <label for="prodi">Program Studi</label>
                    <input
                        type="text"
                        id="prodi"
                        name="prodi"
                        value="<?= htmlspecialchars($profile["prodi"]); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <input
                        type="number"
                        id="semester"
                        name="semester"
                        value="<?= htmlspecialchars($profile["semester"]); ?>"
                        min="1"
                        max="14"
                    >
                </div>

                <div class="form-group">
                    <label for="ipk">IPK</label>
                    <input
                        type="text"
                        id="ipk"
                        name="ipk"
                        value="<?= htmlspecialchars($profile["ipk"]); ?>"
                    >
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



        <!-- DATA AKADEMIK -->

        <section class="content-card">
            <div class="section-header">
                <div>
                    <h2>Data Akademik</h2>
                    <p>Informasi pendidikan pengguna</p>
                </div>

                <button type="button"
                    class="btn-small"
                    onclick="openAcademicModal()"
                >
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

        <!-- KEAHLIAN -->
        <section class="content-card">
            <div class="section-header">

                <div>
                    <h2>Keahlian</h2>
                    <p>Skill yang dimiliki</p>
                </div>

                <button type="button" class="btn-outline" onclick="toggleSkillForm()">+ Tambah Keahlian</button>
            </div>


            <!-- FORM TAMBAH -->
            <div id="addSkillForm" class="skill-form hidden">
                <form method="POST">
                    <input
                        type="hidden"
                        name="action"
                        value="add_skill"
                    >

                    <div class="form-group">
                        <label for="skill">Nama Keahlian</label>
                        <input
                            type="text"
                            id="skill"
                            name="skill"
                            placeholder="Contoh: Python"
                            required
                        >
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="toggleSkillForm()" >Batal</button>
                        <button type="submit" class="btn-primary">Tambah</button>
                    </div>
                </form>
            </div>

            <!-- LIST KEAHLIAN -->
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

                                <!-- EDIT -->
                                <a href="profile_user.php?edit_skill=<?= $index ?>" class="btn-small">✎ Edit</a>

                                <!-- HAPUS -->
                                <form method="POST" class="delete-skill-form">
                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete_skill"
                                    >

                                    <input
                                        type="hidden"
                                        name="skill_index"
                                        value="<?= $index ?>"
                                    >

                                    <button type="submit" class="btn-small btn-small-danger">🗑 Hapus</button>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- FORM EDIT KEAHLIAN -->
            <?php if ($editSkillIndex >= 0 && $editSkillValue !== ""): ?>
                <div class="skill-form">
                    <h3>Edit Keahlian</h3>
                    <form method="POST">
                        <input
                            type="hidden"
                            name="action"
                            value="edit_skill"
                        >

                        <input
                            type="hidden"
                            name="skill_index"
                            value="<?= $editSkillIndex ?>"
                        >

                        <div class="form-group">
                            <label for="editSkill">Nama Keahlian</label>

                            <input
                                type="text"
                                id="editSkill"
                                name="skill"
                                value="<?= htmlspecialchars($editSkillValue); ?>"
                                required
                            >
                        </div>

                        <div class="form-actions">
                            <a href="profile_user.php" class="btn-secondary">Batal</a>
                            <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </section>

        <!-- PORTOFOLIO -->
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

        <!-- PROFILE COMPLETION -->
        <section class="content-card">
            <div class="section-header">
                <div>
                    <h2>Profile Completion</h2>
                    <p>Lengkapi profilmu agar lebih optimal.</p>
                </div>
                <strong class="completion-number">80%</strong>
            </div>

            <div class="progress-bar">
                <div class="progress-fill" style="width: 80%;" ></div>
            </div>

            <p class="progress-text">Profil kamu sudah 80% lengkap.</p>
        </section>

        <!-- TIPS -->
        <section class="content-card tips-card">
            <div class="tips-icon">💡</div>
            <div>
                <h3>Tips untuk profilmu</h3>
                <p>Lengkapi keahlian dan portofolio agar kamu lebih mudah menemukan peluang yang sesuai dengan minat dan kemampuanmu.</p>
            </div>
        </section>

        <!-- RESET PROFILE -->
        <section class="content-card danger-card">
            <div>
                <h2>Reset Profile</h2>
                <p>Mengembalikan data profil ke data awal.</p>
            </div>

            <a href="profile_user.php?action=reset" class="btn-danger">Reset Profile</a>
        </section>

    </div>
</main>

<!-- JAVASCRIPT -->
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
                onclick="closeAcademicModal()"
            >
                ×
            </button>

        </div>

        <form
            method="POST"
            action="profile_user.php"
            class="academic-form"
        >
            <input
                type="hidden"
                name="action"
                value="update_academic"
            >

            <div class="form-group">
                <label for="universitas">Universitas</label>
                <input
                    type="text"
                    id="universitas"
                    name="universitas"
                    placeholder="Masukkan universitas"
                    value="<?= htmlspecialchars($profile["universitas"]); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="fakultas">Fakultas</label>
                <input
                    type="text"
                    id="fakultas"
                    name="fakultas"
                    placeholder="Masukkan fakultas"
                    value="<?= htmlspecialchars($profile["fakultas"]); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="prodi">Program Studi</label>
                <input
                    type="text"
                    id="prodi"
                    name="prodi"
                    placeholder="Masukkan program studi"
                    value="<?= htmlspecialchars($profile["prodi"]); ?>"
                    required
                >
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
                        required
                    >
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
                        required
                    >
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
                    required
                >

            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeAcademicModal()">Batal</button>

                <button type="submit" class="btn-primary" >Simpan Data</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>