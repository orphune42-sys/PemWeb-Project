document.addEventListener('DOMContentLoaded', function () {

    const addButton = document.getElementById('addProgramButton');
    const modal = document.getElementById('programModal');
    const closeButton = document.getElementById('closeModal');
    const cancelButton = document.getElementById('cancelModal');

    const form = document.getElementById('programForm');
    const formAction = document.getElementById('formAction');

    const searchInput = document.getElementById('searchProgram');
    const typeFilter = document.getElementById('typeFilter');

    const tableBody = document.getElementById('programTableBody');
    const resultText = document.getElementById('programResult');

    addButton.addEventListener('click', function () {
        form.reset();

        document.getElementById('modalTitle').textContent = 'Tambah Program';
        formAction.value = 'store';
        document.getElementById('programId').value = '';

        modal.classList.add('show');
    });

    function closeModal() {
        modal.classList.remove('show');
    }

    closeButton.addEventListener('click', closeModal);
    cancelButton.addEventListener('click', closeModal);

    function filterPrograms() {
        const searchValue = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;

        const rows = tableBody.querySelectorAll('tr[data-id]');
        let visible = 0;

        rows.forEach(function (row) {
            const name = row.dataset.name.toLowerCase();
            const organizer = row.dataset.organizer.toLowerCase();
            const type = row.dataset.type;

            const matchSearch =
                name.includes(searchValue) ||
                organizer.includes(searchValue);

            const matchType =
                typeValue === '' || type === typeValue;

            if (matchSearch && matchType) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        resultText.textContent =
            'Menampilkan ' + visible + ' dari ' + rows.length + ' program';
    }

    searchInput.addEventListener('input', filterPrograms);
    typeFilter.addEventListener('change', filterPrograms);

    tableBody.addEventListener('click', function (event) {
        const row = event.target.closest('tr[data-id]');

        if (!row) {
            return;
        }

        if (event.target.classList.contains('edit-program')) {
            form.reset();

            document.getElementById('modalTitle').textContent = 'Edit Program';

            formAction.value = 'update';

            document.getElementById('programId').value = row.dataset.id;
            document.getElementById('programName').value = row.dataset.name;
            document.getElementById('programOrganizer').value = row.dataset.organizer;
            document.getElementById('programType').value = row.dataset.type;
            document.getElementById('programField').value = row.dataset.field;
            document.getElementById('programDeadline').value = row.dataset.deadline;
            document.getElementById('programRegistrants').value = row.dataset.registrants;

            modal.classList.add('show');
        }

        if (event.target.classList.contains('delete-program')) {
            const confirmDelete = confirm(
                'Apakah kamu yakin ingin menghapus program ini?'
            );

            if (confirmDelete) {
                document.getElementById('deleteId').value = row.dataset.id;
                document.getElementById('deleteForm').submit();
            }
        }
    });
});
