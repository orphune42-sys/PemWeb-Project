<?php
$dataFile = '../data/users.json';
$users = [];
if (file_exists($dataFile)) {
    $decoded = json_decode(file_get_contents($dataFile), true);
    $users = is_array($decoded) ? $decoded : [];
}

$user = null;
if (!empty($users)) {
    foreach ($users as $u) {
    if (str_contains(strtolower($u['email'] ?? ''), 'admin') || str_contains(strtolower($u['username'] ?? ''), 'admin')) {
        $user = $u;
        break;
        }
    } if (!$user) {
        $user = end($users);
    }
}

$name       = $user['name'] ?? 'Nadhira Rindra';
$email      = $user['email'] ?? 'nadhirarindra@ub.ac.id';
$phone      = $user['phone'] ?? '081234567890';
$address    = $user['address'] ?? 'Jl. Melati No. 12, Malang';
$birthDate  = $user['birth_date'] ?? '12 Mei 2000';
$gender     = $user['gender'] ?? 'Perempuan';
$adminId    = $user['admin_id'] ?? 'ADM-1024';
$position   = $user['position'] ?? 'Administrator';
$username   = $user['username'] ?? 'nadhirarindra';
$avatar     = $user['avatar'] ?? '';

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_avatar'])) {
    if ($avatar === '') {
        $message = 'Tidak ada foto profil untuk dihapus.';
        $messageType = 'error';
    } elseif (empty($users)) {
        $message = 'Data pengguna tidak ditemukan, foto tidak dapat dihapus.';
        $messageType = 'error';
    } else {
        $oldAvatar = $avatar;
        foreach ($users as &$u) {
            if (($user && isset($user['id']) && ($u['id'] ?? '') === $user['id']) ||
                (strtolower($u['email'] ?? '') === strtolower($email))) {
                $u['avatar'] = '';
                break;
            }
        }
        unset($u);

        $json = json_encode($users, JSON_PRETTY_PRINT);
        if ($json === false || file_put_contents($dataFile, $json, LOCK_EX) === false) {
            $message = 'Gagal menghapus foto profil.';
            $messageType = 'error';
        } else {
            $avatar = '';
            $message = 'Foto profil berhasil dihapus!';
            if (str_starts_with($oldAvatar, 'uploads/avatars/') && strpos($oldAvatar, '..') === false) {
                $oldPath = __DIR__ . '/../' . $oldAvatar;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? $name);
    $email = trim($_POST['email'] ?? $email);
    $phone = trim($_POST['phone'] ?? $phone);
    $address = trim($_POST['address'] ?? $address);
    $birthDate = trim($_POST['birth_date'] ?? $birthDate);
    $gender = trim($_POST['gender'] ?? $gender);
    $adminId = trim($_POST['admin_id'] ?? $adminId);
    $position = trim($_POST['position'] ?? $position);
    $oldAvatar = $avatar;
    $uploadError = '';

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadError = ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE)
                ? 'Ukuran foto terlalu besar.'
                : 'Upload foto gagal, silakan coba lagi.';
        } else {
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
            ];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($file['tmp_name']);

            if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
                $uploadError = 'Format foto harus JPG, PNG, atau WEBP.';
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $uploadError = 'Ukuran foto maksimal 2 MB.';
            } else {
                $uploadDir = __DIR__ . '/../uploads/avatars/';
                if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                    $uploadError = 'Folder upload tidak dapat dibuat.';
                } else {
                    $newName = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                        $avatar = 'uploads/avatars/' . $newName;
                    } else {
                        $uploadError = 'Foto gagal disimpan di server.';
                    }
                }
            }
        }
    }

    if ($uploadError !== '') {
        $message = $uploadError;
        $messageType = 'error';
        $avatar = $oldAvatar;
    } elseif (empty($users)) {
        $message = 'Data pengguna tidak ditemukan, profil tidak dapat disimpan.';
        $messageType = 'error';
    } else {
        foreach ($users as &$u) {
            if (($user && isset($user['id']) && ($u['id'] ?? '') === $user['id']) || 
                (strtolower($u['email'] ?? '') === strtolower($email))) {
                $u['name'] = $name;
                $u['email'] = $email;
                $u['phone'] = $phone;
                $u['address'] = $address;
                $u['birth_date'] = $birthDate;
                $u['gender'] = $gender;
                $u['admin_id'] = $adminId;
                $u['position'] = $position;
                $u['avatar'] = $avatar;
                break;
            }
        }
        unset($u);

        $json = json_encode($users, JSON_PRETTY_PRINT);
        if ($json === false || file_put_contents($dataFile, $json, LOCK_EX) === false) {
            $message = 'Gagal menyimpan profil.';
            $messageType = 'error';
            if ($avatar !== $oldAvatar && $avatar !== '') {
                @unlink(__DIR__ . '/../' . $avatar);
            }
            $avatar = $oldAvatar;
        } else {
            $message = 'Profil berhasil disimpan!';
            if ($oldAvatar !== '' && $oldAvatar !== $avatar && str_starts_with($oldAvatar, 'uploads/avatars/')) {
                $oldPath = __DIR__ . '/../' . $oldAvatar;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil Admin - FindYourPath</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../sidebarAdmin/style.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="layout-wrapper">
       <?php 
            $activeMenu = 'profile'; 
            $basePath = '../';
            include '../sidebarAdmin/sidebar.php'; 
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
                    <div class="header-avatar">A</div>
                    <div class="header-userinfo">
                        <span class="header-username">Admin</span>
                        <span class="header-userrole">Administrator</span>
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
                        <?php if (!empty($avatar)): ?>
                            <img src="../<?= htmlspecialchars($avatar) ?>" class="profile-avatar-placeholder" style="object-fit: cover;" alt="Foto profil">
                        <?php else: ?>
                            <div class="profile-avatar-placeholder"></div>
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
            <form method="POST" action="profile.php" enctype="multipart/form-data">
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

    <form id="deleteAvatarForm" method="POST" action="profile.php" style="display: none;">
        <input type="hidden" name="delete_avatar" value="1">
    </form>

    <script src="../sidebarAdmin/script.js"></script>
    <script src="script.js"></script>
    </body>
</html>