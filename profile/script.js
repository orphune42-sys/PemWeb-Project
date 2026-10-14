document.addEventListener('DOMContentLoaded', () => {
const modal = document.getElementById('editProfileModal');
const btnOpen = document.getElementById('btnOpenEditModal');
const btnClose = document.getElementById('btnCloseEditModal');
const btnCancel = document.getElementById('btnCancelEdit');

    function openModal() {
        if (modal) modal.classList.add('open');
    } function closeModal() {
        if (modal) modal.classList.remove('open');
    }

    if (btnOpen) btnOpen.addEventListener('click', openModal);
    if (btnClose) btnClose.addEventListener('click', closeModal);
    if (btnCancel) btnCancel.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
            }
        });
    }
});