document.addEventListener('DOMContentLoaded', function () {

    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');

    const profileMenuBtn = document.getElementById('profileMenuBtn');
    const profilePanel = document.getElementById('profilePanel');

    const toast = document.getElementById('toast');

    function showToast(message) {
        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.classList.add('show');

        clearTimeout(window.toastTimer);

        window.toastTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 2200);
    }

    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', function (event) {
            event.stopPropagation();

            notificationPanel.classList.toggle('show');

            if (profilePanel) {
                profilePanel.classList.remove('show');
            }
        });
    }

    if (profileMenuBtn && profilePanel) {
        profileMenuBtn.addEventListener('click', function (event) {
            event.stopPropagation();

            profilePanel.classList.toggle('show');

            if (notificationPanel) {
                notificationPanel.classList.remove('show');
            }
        });
    }

    document.addEventListener('click', function (event) {

        if (
            notificationPanel &&
            notificationBtn &&
            !notificationPanel.contains(event.target) &&
            !notificationBtn.contains(event.target)
        ) {
            notificationPanel.classList.remove('show');
        }

        if (
            profilePanel &&
            profileMenuBtn &&
            !profilePanel.contains(event.target) &&
            !profileMenuBtn.contains(event.target)
        ) {
            profilePanel.classList.remove('show');
        }
    });

    const clickableRows = document.querySelectorAll('.clickable-row');

    clickableRows.forEach(function (item) {

        item.addEventListener('click', function () {

            const title = item.getAttribute('data-title');

            if (title) {
                showToast(title + ' dipilih.');
            }
        });
    });

    const logoutBtn = document.getElementById('logoutBtn');
    const profileLogout = document.getElementById('profileLogout');

    function confirmLogout(event) {

        const confirmLogout = window.confirm(
            'Apakah kamu yakin ingin logout?'
        );

        if (!confirmLogout) {
            event.preventDefault();
        }
    }

    if (logoutBtn) {
        logoutBtn.addEventListener('click', confirmLogout);
    }

    if (profileLogout) {
        profileLogout.addEventListener('click', confirmLogout);
    }

    const searchInput = document.querySelector(
        '.search-box input[name="q"]'
    );

    if (searchInput) {

        searchInput.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {

                searchInput.value = '';

                if (searchInput.form) {
                    searchInput.form.submit();
                }
            }
        });
    }
    
    window.loadSimpleData = function (url, callback) {

        fetch(url)
            .then(function (response) {

                if (!response.ok) {
                    throw new Error('Request gagal.');
                }

                return response.text();
            })
            .then(function (data) {

                if (typeof callback === 'function') {
                    callback(data);
                }
            })
            .catch(function (error) {

                console.error('AJAX Error:', error);

                showToast('Data gagal dimuat.');
            });
    };

});