const loginForm = document.getElementById('loginForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const showPasswordCheckbox = document.getElementById('showPassword');
const messageDiv = document.getElementById('message');

showPasswordCheckbox.addEventListener('change', function () {
    if (this.checked) {
        passwordInput.type = 'text'; 
    } else {
        passwordInput.type = 'password'; 
    }
});

loginForm.addEventListener('submit', function (event) {

    event.preventDefault();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    const defaultEmail = "admin@gmail.com";
    const defaultPass = "12345";

    if (email === defaultEmail && password === defaultPass) {
        messageDiv.className = 'message success';
        messageDiv.textContent = 'Login berhasil! Selamat datang!';
    } else {
        messageDiv.className = 'message error';
        messageDiv.textContent = 'Email atau password salah!';
    }
});
