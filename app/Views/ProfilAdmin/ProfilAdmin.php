<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - FindYourPath</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/public/css/sidebarAdmin.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/public/css/profilAdmin.css">
</head>

<body>
    <div class="layout-wrapper">
        <?php 
        require_once __DIR__ . '/../../Controllers/SidebarAdminController.php'; 
        (new SidebarAdminController())->render('profile', $baseUrl);
        ?>
        <div class="main-wrapper">
            <header class="header">
                <h1 class="header-title">Profil Admin</h1>
                <div class="header-right">
                    <button class="header-notif" aria-label="Notifikasi">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                        </svg>

                    </button>
                    <div class="header-user">
                        <?php if (!empty($avatar) && is_file(dirname(__DIR__, 3) . '/' . $avatar)): ?>
                            <img src="<?= $baseUrl ?>/<?= htmlspecialchars($avatar) ?>" class="header-avatar" style="object-fit:cover; border-radius: 50%; width: 36px; height: 36px;" alt="Avatar">
                        <?php else: ?>
                            <div class="header-avatar"><?= htmlspecialchars(strtoupper(substr($name ?: $username, 0, 1))) ?></div>
                        <?php endif; ?>

                        <div class="header-userinfo">
                            <span class="header-username"><?= htmlspecialchars($name ?: $username) ?></span>
                            <span class="header-userrole"><?= htmlspecialchars($position) ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="main-content">
                <?php if (!empty($message)): ?>
                    <div class="toast-alert show <?= $messageType === 'error' ? 'error' : 'success' ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="profile-card profile-card-top">
                    <div class="profile-card-header">
                        <div class="profile-user-info">
                            <?php if (!empty($avatar) && is_file(dirname(__DIR__, 3) . '/' . $avatar)): ?>
                                <img src="<?= $baseUrl ?>/<?= htmlspecialchars($avatar) ?>" class="profile-avatar-placeholder" style="object-fit: cover;" alt="Foto profil">
                            <?php else: ?>
                                <div class="profile-avatar-placeholder">
                                    <?= htmlspecialchars(strtoupper(substr($name ?: $username, 0, 1))) ?>
                                </div>
                            <?php endif; ?>
                            <div class="profile-name-group">
                                <h2 class="profile-name"><?= htmlspecialchars($name) ?></h2>
                                <p class="profile-role"><?= htmlspecialchars($position) ?></p>
                            </div>
                        </div>

                        <button type="button" class="btn-edit-profile" id="btnOpenEditModal">
                            Edit Profil
                        </button>
                    </div>
                </div>

                <div class="profile-card">
                    <h3 class="card-section-title">Data Diri</h3>
                    <p class="card-section-subtitle">Informasi pribadi admin</p>

                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <tbody>
                                <tr>
                                    <td class="col-label">Email</td>
                                    <td class="col-value"><?= htmlspecialchars($email) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">No. HP</td>
                                    <td class="col-value"><?= htmlspecialchars($phone) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Alamat</td>
                                    <td class="col-value"><?= htmlspecialchars($address) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Tanggal Lahir</td>
                                    <td class="col-value"><?= htmlspecialchars($birthDate) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Jenis Kelamin</td>
                                    <td class="col-value"><?= htmlspecialchars($gender) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">ID Admin</td>
                                    <td class="col-value"><?= htmlspecialchars($adminId) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Jabatan</td>
                                    <td class="col-value"><?= htmlspecialchars($position) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-card">
                    <h3 class="card-section-title">Informasi Akun</h3>
                    <p class="card-section-subtitle">Detail akun dan hak akses</p>

                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <tbody>
                                <tr>
                                    <td class="col-label">Username</td>
                                    <td class="col-value"><?= htmlspecialchars($username) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Role Akses</td>
                                    <td class="col-value"><?= htmlspecialchars($position) ?></td>
                                </tr>
                                <tr>
                                    <td class="col-label">Status Akun</td>
                                    <td class="col-value">
                                        <span class="badge-status-active">Aktif</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="profile-card">
                    <h3 class="card-section-title">Keamanan & Sandi</h3>
                    <p class="card-section-subtitle">Pengaturan keamanan akun</p>

                    <div class="data-table-wrapper">
                        <table class="data-table">
                            <tbody>
                                <tr>
                                    <td class="col-label">Password</td>
                                    <td class="col-value">••••••••••••</td>
                                </tr>
                                <tr>
                                    <td class="col-label">Autentikasi 2 Faktor</td>
                                    <td class="col-value">Aktif</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal-overlay" id="editProfileModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Edit Data Diri</h3>
                <button type="button" class="modal-close-btn" id="btnCloseEditModal">&times;</button>
            </div>
            <form method="POST" action="<?= $baseUrl ?>/public/index.php?page=profile-admin" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputAvatar">Foto Profil</label>
                        <input type="file" id="inputAvatar" name="avatar" accept="image/png,image/jpeg,image/webp">
                        <?php if (!empty($avatar)): ?>
                            <button type="submit" form="deleteAvatarForm" class="btn-delete-avatar" onclick="return confirm('Hapus foto profil?');">Hapus foto profil</button>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="inputName">Nama Lengkap</label>
                        <input type="text" id="inputName" name="name" value="<?= htmlspecialchars($name) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputEmail">Email</label>
                            <input type="email" id="inputEmail" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPhone">No. HP</label>
                            <input type="text" id="inputPhone" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputAddress">Alamat</label>
                        <textarea id="inputAddress" name="address" rows="2" required><?= htmlspecialchars($address) ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputBirthDate">Tanggal Lahir</label>
                            <input type="text" id="inputBirthDate" name="birth_date" value="<?= htmlspecialchars($birthDate) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputGender">Jenis Kelamin</label>
                            <select id="inputGender" name="gender" required>
                                <option value="Perempuan" <?= ($gender === 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                <option value="Laki-laki" <?= ($gender === 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="inputAdminId">ID Admin</label>
                            <input type="text" id="inputAdminId" name="admin_id" value="<?= htmlspecialchars($adminId) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="inputPosition">Jabatan</label>
                            <input type="text" id="inputPosition" name="position" value="<?= htmlspecialchars($position) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="btnCancelEdit">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <form id="deleteAvatarForm" method="POST" action="<?= $baseUrl ?>/public/index.php?page=profile-admin" style="display: none;">
        <input type="hidden" name="delete_avatar" value="1">
    </form>

    <script src="<?= $baseUrl ?>/public/js/sidebarAdmin.js"></script>
    <script src="<?= $baseUrl ?>/public/js/profilAdmin.js"></script>
</body>

</html>