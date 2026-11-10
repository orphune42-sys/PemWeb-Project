document.addEventListener('DOMContentLoaded', () => {
const sidebar = document.getElementById('fypSidebar');
const backdrop = document.getElementById('sidebarBackdrop');
const closeBtn = document.getElementById('sidebarCloseBtn');
const toggleBtn = document.getElementById('sidebarToggleBtn');

function openSidebar() {
    if (sidebar && backdrop) {
        sidebar.classList.add('open');
        backdrop.classList.add('show');
    }
}

function closeSidebar() {
    if (sidebar && backdrop) {
        sidebar.classList.remove('open');
        backdrop.classList.remove('show');
    }
}

    if (toggleBtn) {
        toggleBtn.addEventListener('click', openSidebar);
    } if (closeBtn) {
        closeBtn.addEventListener('click', closeSidebar);
    } if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }
});
