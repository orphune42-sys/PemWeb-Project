<!DOCTYPE html>
<?php
$baseUrl = $baseUrl ?? '';
?>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindYourPath</title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/public/css/login.css">
</head>

<body>
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>
    <div class="bg-blob blob-4"></div>

    <div class="auth-card" id="authCard">
        <div class="auth-left">
            <div class="logo-header">
                <img src="<?= $baseUrl ?>/public/images/LOGO.png" alt="logo-fyp" class="logo-fyp">
            </div>

            <div class="welcome-section">
                <h2 class="welcome-title">Selamat Datang<br>Kembali!</h2>
                <p class="welcome-subtitle">Masuk untuk melanjutkan perjalananmu.</p>
            </div>

            <div class="illustration-box">
                <img
                    src="<?= $baseUrl ?>/public/images/assest_login_register.png"
                    alt="FindYourPath Illustration"
                    class="hero-illustration"
                    id="heroIllustration">
            </div>
        </div>

        <div class="auth-right">
            <div id="alertToast" class="alert-toast <?= !empty($serverMessage) ? ($serverSuccess ? 'show success' : 'show error') : '' ?>">
                <span class="toast-text" id="toastText"><?= htmlspecialchars($serverMessage) ?></span>
            </div>
            <form id="loginForm" method="POST" action="<?= $baseUrl ?>/index.php?page=login" novalidate>
                <div class="form-group">
                    <input
                        type="text"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Email"
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
                <small>Belum punya akun? <a href="<?= $baseUrl ?>/index.php?page=register">Register</a></small>
            </div>
        </div>
    </div>

    <script src="<?= $baseUrl ?>/public/js/login.js"></script>
</body>

</html>