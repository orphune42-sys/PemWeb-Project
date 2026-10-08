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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <div class="main-content">
        <header class="top-header">
            <div class="header-left">
                <button type="button" class="mobile-toggle-btn" id="sidebarToggleBtn" aria-label="Menu">
                    <svg viewBox="0 0 24 24" width="22" height="22" stroke="currentColor" stroke-width="2" fill="none">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <h1 class="page-title">Profil Admin</h1>
            </div>
                <div class="header-right">
                    <button type="button" class="btn-icon" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>
                    <div class="user-profile-badge">
                        <div class="user-avatar-circle">
                            <span>A</span>
                        </div>
                        <div class="user-meta">
                            <span class="user-name">Admin Account</span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="tabs-container">
                <button type="button" class="tab-button active" data-tab="data-diri">Data Diri</button>
                <button type="button" class="tab-button" data-tab="informasi-akun">Informasi Akun</button>
                <button type="button" class="tab-button" data-tab="keamanan">Keamanan</button>
            </div>

            <?php if (!empty($message)): ?>
                <div class="toast-alert show <?= $messageType === 'error' ? 'error' : 'success' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="tab-content active" id="tab-data-diri">
                <div class="profile-card">
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

                    <h3 class="card-section-title">Data Diri</h3>

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
            </div>

            <div class="tab-content" id="tab-informasi-akun">
                <div class="profile-card">
                    <h3 class="card-section-title" style="margin-top: 0;">Informasi Akun</h3>
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
            </div>

            <div class="tab-content" id="tab-keamanan">
                <div class="profile-card">
                    <h3 class="card-section-title" style="margin-top: 0;">Keamanan & Sandi</h3>
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
            </div>
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

    <script src="../sidebar/script.js"></script>
    <script src="script.js"></script>
    </body>
</html>