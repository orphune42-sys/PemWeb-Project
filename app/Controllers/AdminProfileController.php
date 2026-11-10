<?php
require_once __DIR__ . '/../Models/AdminProfile.php';

class AdminProfileController
{
    private AdminProfile $profileModel;
    private string $baseUrl;

    public function __construct(PDO $pdo, string $baseUrl)
    {
        $this->profileModel = new AdminProfile($pdo);
        $this->baseUrl = $baseUrl;
    }

    public function index(): void
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
            header('Location: ' . $this->baseUrl . '/public/index.php?page=login');
            exit;
        }

        $userId = (int) $_SESSION['user']['id'];
        $message = '';
        $messageType = 'success';

        $user = $this->profileModel->getProfile($userId);
        if (!$user) {
            session_destroy();
            header('Location: ' . $this->baseUrl . '/public/index.php?page=login');
            exit;
        }

        $oldAvatar = $user['avatar'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_avatar'])) {
                if (!empty($oldAvatar)) {
                    $this->profileModel->deleteAvatar($userId, $oldAvatar);
                    $message = "Foto profil berhasil dihapus!";
                } else {
                    $message = 'Tidak ada foto profil untuk dihapus.';
                    $messageType = 'error';
                }
            } elseif (isset($_POST['delete_account'])) {
                $this->profileModel->deleteAccount($userId, $oldAvatar);
                session_unset();
                session_destroy();
                header('Location: ' . $this->baseUrl . '/public/index.php?page=login&status=deleted');
                exit;
            } else {
                // Update profile
                $newAvatarPath = $oldAvatar;
                $uploadError = '';

                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $file = $_FILES['avatar'];
                    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($file['tmp_name']);

                    if (!isset($allowed[$mime])) {
                        $uploadError = 'Format foto harus JPG, PNG, atau WEBP.';
                    } elseif ($file['size'] > 2 * 1024 * 1024) {
                        $uploadError = 'Ukuran foto maksimal 2 MB.';
                    } else {
                        $uploadDir = dirname(__DIR__, 2) . '/uploads/avatars/';
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }
                        $newName = 'avatar_admin_' . $userId . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
                        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                            $newAvatarPath = 'uploads/avatars/' . $newName;
                            if (!empty($oldAvatar) && is_file(dirname(__DIR__, 2) . '/' . $oldAvatar)) {
                                @unlink(dirname(__DIR__, 2) . '/' . $oldAvatar);
                            }
                        } else {
                            $uploadError = 'Foto gagal disimpan di server.';
                        }
                    }
                }

                if ($uploadError !== '') {
                    $message = $uploadError;
                    $messageType = 'error';
                } else {
                    if ($this->profileModel->updateProfile($userId, $_POST, $newAvatarPath)) {
                        $message = 'Profil berhasil disimpan!';
                    } else {
                        $message = 'Gagal menyimpan profil ke database.';
                        $messageType = 'error';
                    }
                }
            }

            // Re-fetch data terbaru
            $user = $this->profileModel->getProfile($userId);
        }

        $name       = $user['name'] ?? '';
        $email      = $user['email'] ?? '';
        $phone      = $user['phone'] ?? '-';
        $address    = $user['address'] ?? '-';
        $birthDate  = $user['birth_date'] ?? '-';
        $gender     = $user['gender'] ?? '-';
        $adminId    = $user['admin_id'] ?? 'ADM-1024';
        $position   = $user['position'] ?? 'Administrator';
        $username   = $user['username'] ?? '';
        $avatar     = $user['avatar'] ?? '';
        $baseUrl    = $this->baseUrl;

        require __DIR__ . '/../Views/ProfilAdmin/ProfilAdmin.php';
    }
}