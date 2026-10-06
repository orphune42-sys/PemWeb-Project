document.addEventListener("DOMContentLoaded", function() {

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