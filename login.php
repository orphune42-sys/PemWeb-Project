<?php
session_start();

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'] ?? 'mahasiswa';
    if ($role === 'admin') {
        header('Location: dashboard-admin/dashboardAdmin.php');
    } else {
        header('Location: mahasiswa/dashboard.php');
    }
    exit;
}

$serverMessage = '';
$serverSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawInput = file_get_contents('php://input');
    $isJson = false;
    $inputData = [];

    if (!empty($rawInput)) {
        $decoded = json_decode($rawInput, true);
        if (is_array($decoded)) {
            $isJson = true;
            $inputData = $decoded;
        }
    }
    if (!$isJson) {
        $inputData = $_POST;
    }

    $email = strtolower(trim($inputData['email'] ?? ''));
    $password = $inputData['password'] ?? '';

    $dataFile = __DIR__ . '/data/users.json';
    $users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    if (!is_array($users)) $users = [];

    $userFound = null;
    if (!empty($email) && !empty($password)) {
        foreach ($users as $user) {
            $userEmail = strtolower($user['email'] ?? '');
            $userUsername = strtolower($user['username'] ?? '');

            if ($userEmail === $email || $userUsername === $email) {
                if (password_verify($password, $user['password'])) {
                    $userFound = $user;
                    break;
                }
            }
        }
    }

    if ($userFound) {
        $userRole = strtolower($userFound['role'] ?? 'mahasiswa');
        $targetRedirect = ($userRole === 'admin') ? 'dashboard-admin/dashboardAdmin.php' : 'mahasiswa/dashboard.php';

        $_SESSION['user'] = [
            'name'  => $userFound['name'],
            'email' => $userFound['email'],
            'role'  => $userRole
        ];

        if ($isJson) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil! Selamat datang, ' . htmlspecialchars($userFound['name']),
                'redirect' => $targetRedirect
            ]);
            exit;
        }

        header('Location: ' . $targetRedirect);
        exit;
    } else {
        $errorMessage = empty($email) || empty($password)
            ? 'Email/Username dan password tidak boleh kosong!'
            : 'Email/Username atau password yang Anda masukkan salah!';

        if ($isJson) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'success' => false,
                'message' => $errorMessage
            ]);
            exit;
        }

        $serverSuccess = false;
        $serverMessage = $errorMessage;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindYourPath</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>
    <div class="bg-blob blob-4"></div>

    <div class="auth-card" id="authCard">
        <div class="auth-left">
            <div class="logo-header">
                <img src="../PemWeb-Project/assets/LOGO.png" alt="logo-fyp" class="logo-fyp">
            </div>

            <div class="welcome-section">
                <h2 class="welcome-title">Selamat Datang<br>Kembali!</h2>
                <p class="welcome-subtitle">Masuk untuk melanjutkan perjalananmu.</p>
            </div>

            <div class="illustration-box">
                <img
                    src="../PemWeb-Project/assets/assest_login_register.png"
                    alt="FindYourPath Illustration"
                    class="hero-illustration"
                    id="heroIllustration">
            </div>
        </div>

        <div class="auth-right">
            <div id="alertToast" class="alert-toast <?= !empty($serverMessage) ? ($serverSuccess ? 'show success' : 'show error') : '' ?>">
                <span class="toast-text" id="toastText"><?= htmlspecialchars($serverMessage) ?></span>
            </div>
            <form id="loginForm" method="POST" action="login.php" novalidate>
                <div class="form-group">
                    <input
                        type="text"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Email atau Username"
                        required
                        autocomplete="username">
                </div>

                <div class="form-group">
                    <div class="password-wrapper">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Password"
                            required
                            autocomplete="current-password">
                        <button
                            type="button"
                            id="togglePassword"
                            class="password-toggle"
                            title="Tampilkan / Sembunyikan Password"
                            aria-label="Toggle password visibility">
                            <svg class="eye-icon" id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn-submit">
                    <span class="btn-text">Login</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <div class="auth-footer">
                <small>Belum punya akun? <a href="register/register.php">Register</a></small>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>