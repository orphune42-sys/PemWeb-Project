document.addEventListener("DOMContentLoaded", function() {
    
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if(this.getAttribute('href') === '#') {
                e.preventDefault();
            }
        
            navItems.forEach(nav => nav.classList.remove('nav-item--active'));
            
            this.classList.add('nav-item--active');
        });
    });

    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', function() {
            alert('Belum ada notifikasi baru untuk Anda.');
        });
    }

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = this.value.trim();
                if(keyword !== "") {
                    alert('Melakukan pencarian untuk: ' + keyword);
                }
            }
        });
    }

    const logoutLink = document.querySelector('.logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault(); 
            
            const confirmLogout = confirm('Apakah Anda yakin ingin keluar dari halaman admin?');
            if (confirmLogout) {
                console.log('Proses logout berjalan...');
            }
        });
    }
    
    const viewAllLink = document.querySelector('.view-all-link');
    if (viewAllLink) {
        viewAllLink.addEventListener('click', function(e) {
            if(this.getAttribute('href') === '#') {
                e.preventDefault();
                console.log('Mengarahkan ke halaman semua pendaftaran...');
            }
        });
    }
});