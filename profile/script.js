document.addEventListener('DOMContentLoaded', () => {
const tabButtons = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-tab');
            tabButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            tabContents.forEach(content => {
                if (content.id === `tab-${target}`) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });

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
