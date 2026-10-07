<?php
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

    $name = trim($inputData['name'] ?? '');
    $email = strtolower(trim($inputData['email'] ?? ''));
    $username = strtolower(trim($inputData['username'] ?? ''));
    $password = $inputData['password'] ?? '';

    $dataFile = __DIR__ . '/../data/users.json';
    $users = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
    if (!is_array($users)) $users = [];

    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        $errorMessage = 'Semua bidang wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'Format email tidak valid!';
    } elseif (strlen($username) < 3 || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errorMessage = 'Username minimal 3 karakter (hanya huruf, angka, underscore)!';
    } elseif (strlen($password) < 5) {
        $errorMessage = 'Password minimal 5 karakter!';
    } else {
        $emailExists = false;
        $usernameExists = false;

        foreach ($users as $user) {
            if (strtolower($user['email'] ?? '') === $email) {
                $emailExists = true;
                break;
            }
            if (isset($user['username']) && strtolower($user['username']) === $username) {
                $usernameExists = true;
                break;
            }
        }

        if ($emailExists) {
            $errorMessage = 'Email sudah terdaftar. Silakan login atau gunakan email lain!';
        } elseif ($usernameExists) {
            $errorMessage = 'Username sudah digunakan. Silakan pilih username lain!';
        } else {
            $newUser = [
                'id' => 'usr_' . bin2hex(random_bytes(6)),
                'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
                'email' => $email,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'mahasiswa',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $users[] = $newUser;
            file_put_contents($dataFile, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

            $serverSuccess = true;
            $serverMessage = 'Pendaftaran berhasil! Mengalihkan ke halaman login...';
        }
    }

    if (!$serverSuccess && empty($serverMessage)) {
        $serverMessage = $errorMessage ?? 'Terjadi kesalahan saat mendaftar.';
    }

    if ($isJson) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode([
            'success' => $serverSuccess,
            'message' => $serverMessage
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FindYourPath</title>
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
                <img src="../assets/LOGO.png" alt="logo-fyp" class="logo-fyp">
            </div>

            <div class="welcome-section">
                <h2 class="welcome-title">Halo, Selamat Datang!</h2>
                <p class="welcome-subtitle">Bergabung dan temukan peluang<br>untuk masa depanmu.</p>
            </div>

            <div class="illustration-box">
                <img 
                    src="../assets/assest_login_register.png" 
                    alt="FindYourPath Illustration" 
                    class="hero-illustration"
                    id="heroIllustration"
                >
            </div>
        </div>

        <div class="auth-right">
            <div id="alertToast" class="alert-toast <?= !empty($serverMessage) ? ($serverSuccess ? 'show success' : 'show error') : '' ?>">
                <span class="toast-text" id="toastText"><?= htmlspecialchars($serverMessage) ?></span>
            </div>

            <form id="registerForm" method="POST" action="register.php" novalidate>
                <div class="form-group">
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="form-control" 
                        placeholder="Nama Lengkap" 
                        required 
                        autocomplete="name"
                    >
                </div>

                <div class="form-group">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        class="form-control" 
                        placeholder="Email" 
                        required 
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="form-control" 
                        placeholder="Username" 
                        required 
                        autocomplete="username"
                    >
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
                            autocomplete="new-password"
                        >
                        <button 
                            type="button" 
                            id="togglePassword" 
                            class="password-toggle" 
                            title="Tampilkan / Sembunyikan Password" 
                            aria-label="Toggle password visibility"
                        >
                            <svg class="eye-icon" id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="btn-submit">
                    <span class="btn-text">Register</span>
                    <span class="btn-spinner" aria-hidden="true"></span>
                </button>
            </form>

            <div class="auth-footer">
                <small>Sudah punya akun? <a href="../login.php">Login</a></small>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>