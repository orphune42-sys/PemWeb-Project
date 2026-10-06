document.addEventListener("DOMContentLoaded", function () {

    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function (e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
            }
            navItems.forEach(nav => nav.classList.remove('nav-item--active'));
            this.classList.add('nav-item--active');
        });
    });

    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            alert('Belum ada notifikasi pendaftaran baru.');
        });
    }

    const logoutLink = document.querySelector('.logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                console.log('Mengarahkan ke halaman logout...');
            }
        });
    }

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = this.value.trim();
                if (keyword !== "") {
                    console.log('Mencari data pendaftar: ' + keyword);
                }
            }
        });
    }

    const filterSelects = document.querySelectorAll('.filter-select, .per-page-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            console.log('Filter diterapkan: ', this.value);
        });
    });

    const exportBtn = document.querySelector('.btn--outline');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => console.log('Mengekspor data ke CSV...'));
    }

    const addBtn = document.querySelector('.btn--primary');
    if (addBtn) {
        addBtn.addEventListener('click', () => console.log('Membuka form pendaftaran baru...'));
    }

    const refreshBtn = document.querySelector('.refresh-btn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            console.log('Memuat ulang data tabel...');
        });
    }

    const tableActions = document.querySelectorAll('.action-btn');
    tableActions.forEach(btn => {
        btn.addEventListener('click', function () {
            const actionType = this.getAttribute('title');
            const userName = this.closest('tr').querySel ector('.user-name').innerText;
            console.log(`Menjalankan aksi "${actionType}" untuk pendaftar: ${userName}`);
        });
    });

    const pageButtons = document.querySelectorAll('.page-btn:not(.page-btn--nav)');
    pageButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            pageButtons.forEach(p => p.classList.remove('page-btn--active'));
            this.classList.add('page-btn--active');
            console.log('Berpindah ke halaman:', this.innerText);
        });
    });

    const navButtons = document.querySelectorAll('.page-btn--nav');
    navButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            console.log('Navigasi halaman (Prev/Next) diklik.');
        });
    });
});