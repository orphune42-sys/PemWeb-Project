document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = btnSubmit ? btnSubmit.querySelector('.btn-text') : null;
    const alertToast = document.getElementById('alertToast');
    const toastIcon = document.getElementById('toastIcon');
    const toastText = document.getElementById('toastText');
    const authCard = document.getElementById('authCard');
    const heroIllustration = document.getElementById('heroIllustration');

    let toastTimeout = null;

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            if (isPassword) {
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                `;
                togglePasswordBtn.setAttribute('title', 'Sembunyikan Password');
            } else {
                eyeIcon.innerHTML = `
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                `;
                togglePasswordBtn.setAttribute('title', 'Tampilkan Password');
            }
        });
    }

    function showToast(message, type = 'error', duration = 4000) {
        if (!alertToast) return;

        clearTimeout(toastTimeout);
        alertToast.className = `alert-toast show ${type}`;
        if (toastIcon) {
            toastIcon.textContent = type === 'success' ? '✓' : '!';
        }
        if (toastText) {
            toastText.textContent = message;
        }

        toastTimeout = setTimeout(() => {
            alertToast.classList.remove('show');
        }, duration);
    }

    if (alertToast && alertToast.classList.contains('show')) {
        toastTimeout = setTimeout(() => {
            alertToast.classList.remove('show');
        }, 4000);
    }

    if (btnSubmit) {
        btnSubmit.addEventListener('click', function (e) {
            const circle = document.createElement('span');
            circle.classList.add('ripple');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            circle.style.width = circle.style.height = `${size}px`;
            circle.style.left = `${e.clientX - rect.left - size / 2}px`;
            circle.style.top = `${e.clientY - rect.top - size / 2}px`;
            this.appendChild(circle);
            setTimeout(() => circle.remove(), 600);
        });
    }

    if (authCard && window.innerWidth > 768) {
        authCard.addEventListener('mousemove', (e) => {
            const rect = authCard.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            const rotateX = (-y / rect.height) * 4;
            const rotateY = (x / rect.width) * 4;

            authCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;

            if (heroIllustration) {
                const moveX = (x / rect.width) * 8;
                const moveY = (y / rect.height) * 8;
                heroIllustration.style.transform = `translate3d(${moveX}px, ${moveY}px, 0)`;
            }
        });

        authCard.addEventListener('mouseleave', () => {
            authCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            if (heroIllustration) {
                heroIllustration.style.transform = 'translate3d(0, 0, 0)';
            }
        });
    }

    [nameInput, emailInput, usernameInput, passwordInput].forEach((input) => {
        if (!input) return;
        input.addEventListener('input', () => {
            if (input.classList.contains('input-error')) {
                input.classList.remove('input-error');
            }
        });
    });

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = nameInput ? nameInput.value.trim() : '';
            const email = emailInput ? emailInput.value.trim() : '';
            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value : '';

            [nameInput, emailInput, usernameInput, passwordInput].forEach(inp => {
                if (inp) inp.classList.remove('input-error');
            });

            if (!name) {
                if (nameInput) { nameInput.classList.add('input-error'); nameInput.focus(); }
                showToast('Silakan masukkan nama lengkap!', 'error');
                return;
            }

            if (!email) {
                if (emailInput) { emailInput.classList.add('input-error'); emailInput.focus(); }
                showToast('Silakan masukkan email!', 'error');
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                if (emailInput) { emailInput.classList.add('input-error'); emailInput.focus(); }
                showToast('Format email tidak valid!', 'error');
                return;
            }

            if (!username) {
                if (usernameInput) { usernameInput.classList.add('input-error'); usernameInput.focus(); }
                showToast('Silakan masukkan username!', 'error');
                return;
            }

            if (username.length < 3 || !/^[a-zA-Z0-9_]+$/.test(username)) {
                if (usernameInput) { usernameInput.classList.add('input-error'); usernameInput.focus(); }
                showToast('Username minimal 3 karakter (huruf, angka, _)!', 'error');
                return;
            }

            if (!password) {
                if (passwordInput) { passwordInput.classList.add('input-error'); passwordInput.focus(); }
                showToast('Silakan masukkan password!', 'error');
                return;
            }

            if (password.length < 5) {
                if (passwordInput) { passwordInput.classList.add('input-error'); passwordInput.focus(); }
                showToast('Password minimal harus 5 karakter!', 'error');
                return;
            }
            btnSubmit.classList.add('loading');
            btnSubmit.disabled = true;
            if (btnText) btnText.textContent = 'Mendaftarkan...';

            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        username: username,
                        password: password
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message || 'Pendaftaran berhasil!', 'success', 3000);
                    if (btnText) btnText.textContent = 'Berhasil!';
                    registerForm.reset();

                    setTimeout(() => {
                        window.location.href = '../Login/login.php';
                    }, 1400);
                } else {
                    showToast(result.message || 'Pendaftaran gagal!', 'error');
                    btnSubmit.classList.remove('loading');
                    btnSubmit.disabled = false;
                    if (btnText) btnText.textContent = 'Register';
                }
            } catch (err) {
                console.error('Register Fetch Error, mencoba fallback POST biasa:', err);
                registerForm.submit();
            }
        });
    }
});