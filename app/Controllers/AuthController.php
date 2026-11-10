<?php
require_once __DIR__ . '/../Models/User.php';

class AuthController
{
    private User $userModel;
    private string $baseUrl;

    public function __construct(PDO $pdo, string $baseUrl)
    {
        $this->userModel = new User($pdo);
        $this->baseUrl = $baseUrl;
    }

    private function input(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw !== '') {
            $json = json_decode($raw, true);
            if (is_array($json)) return [$json, true];
        }
        return [$_POST, false];
    }

    private function respond(bool $isJson, array $payload, string $redirect = ''): void
    {
        if ($isJson) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode($payload);
        } elseif ($redirect !== '') {
            header('Location: ' . $redirect);
        }
        exit;
    }

    public function login(): void
    {
        if (isset($_SESSION['user'])) $this->redirectByRole($_SESSION['user']['role'] ?? 'mahasiswa');
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$data, $isJson] = $this->input();
            $email = strtolower(trim($data['email'] ?? ''));
            $password = $data['password'] ?? '';
            $user = null;
            $dbError = false;
            if ($email !== '' && $password !== '') {
                try {
                    $candidate = $this->userModel->findByEmail($email);
                    if ($candidate && password_verify($password, $candidate['password'])) $user = $candidate;
                } catch (PDOException $e) {
                    error_log('Login gagal: ' . $e->getMessage());
                    $dbError = true;
                }
            }
            if ($user) {
                $role = strtolower($user['role'] ?? 'mahasiswa');
                session_regenerate_id(true);
                $_SESSION['user'] = ['id'=>$user['id'], 'name'=>$user['name'], 'email'=>$user['email'], 'role'=>$role];
                $target = $role === 'admin' ? $this->baseUrl . '/dashboard-admin/dashboardAdmin.php' : $this->baseUrl . '/dashboard-user/user.php';
                if ($isJson) $this->respond(true, ['success'=>true, 'message'=>'Login berhasil! Selamat datang, ' . htmlspecialchars($user['name']), 'redirect'=>$target]);
                $this->respond(false, [], $target);
            }
            $message = $dbError ? 'Terjadi kesalahan pada server. Silakan coba lagi nanti.' : (($email === '' || $password === '') ? 'Email dan password tidak boleh kosong!' : 'Email atau password yang Anda masukkan salah!');
            if ($isJson) $this->respond(true, ['success'=>false, 'message'=>$message]);
        }
        $serverMessage = $message;
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function register(): void
    {
        if (isset($_SESSION['user'])) $this->redirectByRole($_SESSION['user']['role'] ?? 'mahasiswa');
        $message = '';
        $success = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            [$data, $isJson] = $this->input();
            $name = trim($data['name'] ?? '');
            $email = strtolower(trim($data['email'] ?? ''));
            $username = strtolower(trim($data['username'] ?? ''));
            $password = $data['password'] ?? '';
            if ($name === '' || $email === '' || $username === '' || $password === '') $message = 'Semua bidang wajib diisi!';
            elseif (!preg_match('/^.{1,100}$/su', $name) || strlen($email) > 100 || strlen($username) > 100) $message = 'Nama, email, dan username maksimal 100 karakter!';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $message = 'Format email tidak valid!';
            elseif (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) $message = 'Username minimal 3 karakter (hanya huruf, angka, underscore)!';
            elseif (strlen($password) < 5) $message = 'Password minimal 5 karakter!';
            else {
                try {
                    $rows = $this->userModel->findEmailOrUsername($email, $username);
                    $emailExists = $usernameExists = false;
                    foreach ($rows as $row) {
                        if (strtolower($row['email'] ?? '') === $email) $emailExists = true;
                        if (strtolower($row['username'] ?? '') === $username) $usernameExists = true;
                    }
                    if ($emailExists) $message = 'Email sudah terdaftar. Silakan login atau gunakan email lain!';
                    elseif ($usernameExists) $message = 'Username sudah digunakan. Silakan pilih username lain!';
                    else {
                        $this->userModel->createMahasiswa($name, $email, $username, $password);
                        $success = true;
                        $message = 'Pendaftaran berhasil! Mengalihkan ke halaman login...';
                    }
                } catch (PDOException $e) {
                    error_log('Register gagal: ' . $e->getMessage());
                    $message = $e->getCode() === '23000' ? 'Email atau username sudah digunakan!' : 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                } catch (Throwable $e) {
                    error_log('Register gagal: ' . $e->getMessage());
                    $message = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                }
            }
            if ($isJson) $this->respond(true, ['success'=>$success, 'message'=>$message]);
        }
        $serverMessage = $message;
        $serverSuccess = $success;
        $baseUrl = $this->baseUrl;
        require __DIR__ . '/../Views/auth/register.php';
    }

    private function redirectByRole(string $role): void
    {
        $target = strtolower($role) === 'admin' ? $this->baseUrl . '/dashboard-admin/dashboardAdmin.php' : $this->baseUrl . '/dashboard-user/user.php';
        header('Location: ' . $target);
        exit;
    }
}
