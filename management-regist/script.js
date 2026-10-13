document.addEventListener("DOMContentLoaded", function () {

    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', () => {
            alert('Belum ada notifikasi pendaftaran baru.');
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