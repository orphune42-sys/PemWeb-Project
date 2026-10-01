document.addEventListener("DOMContentLoaded", () => {
    const sidebarLinks = document.querySelectorAll(".sidebar-menu a");

    sidebarLinks.forEach(link => {
        link.classList.remove("active");

        if (link.getAttribute("href") && link.getAttribute("href").includes("program.php")) {
            link.classList.add("active");
        }
    });

    const searchInput = document.getElementById("searchProgram");
    const typeFilter = document.getElementById("typeFilter");
    const programFilter = document.getElementById("programFilter");
    const tableBody = document.getElementById("programTableBody");
    const resultText = document.getElementById("programResult");

    const modal = document.getElementById("programModal");
    const modalTitle = document.getElementById("modalTitle");
    const addButton = document.getElementById("addProgramButton");
    const closeModal = document.getElementById("closeModal");
    const cancelModal = document.getElementById("cancelModal");
    const form = document.getElementById("programForm");

    const programName = document.getElementById("programName");
    const programOrganizer = document.getElementById("programOrganizer");
    const programType = document.getElementById("programType");
    const programField = document.getElementById("programField");
    const programDeadline = document.getElementById("programDeadline");
    const programRegistrants = document.getElementById("programRegistrants");

    let editingRow = null;

    function getRows() {
        return [...tableBody.querySelectorAll("tr")];
    }

    function filterPrograms() {
        const searchValue = searchInput.value.toLowerCase();
        const typeValue = typeFilter.value;

        const rows = getRows();
        let visible = 0;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const type = row.children[1].innerText.trim();

            const matchSearch = text.includes(searchValue);
            const matchType = typeValue === "all" || type === typeValue;

            const show = matchSearch && matchType;

            row.style.display = show ? "" : "none";

            if (show) {
                visible++;
            }
        });

        resultText.textContent = `Menampilkan ${visible} dari 48 program`;
    }

    searchInput.addEventListener("input", filterPrograms);
    typeFilter.addEventListener("change", filterPrograms);
    programFilter.addEventListener("change", filterPrograms);

    function openModal(title) {
        modalTitle.textContent = title;
        modal.classList.add("show");
    }

    function closeProgramModal() {
        modal.classList.remove("show");
        form.reset();
        editingRow = null;
    }

    addButton.addEventListener("click", () => {
        editingRow = null;
        form.reset();
        openModal("Tambah Program");
    });

    closeModal.addEventListener("click", closeProgramModal);
    cancelModal.addEventListener("click", closeProgramModal);

    modal.addEventListener("click", event => {
        if (event.target === modal) {
            closeProgramModal();
        }
    });

    tableBody.addEventListener("click", event => {
        const editButton = event.target.closest(".edit-program");
        const deleteButton = event.target.closest(".delete-program");

        if (editButton) {
            editingRow = editButton.closest("tr");

            const cells = editingRow.children;

            programName.value = cells[0].querySelector("strong").textContent;
            programOrganizer.value = cells[0].querySelector("small").textContent;
            programType.value = cells[1].textContent.trim();
            programField.value = cells[2].textContent.trim();
            programDeadline.value = cells[3].textContent.trim();
            programRegistrants.value = cells[4].textContent.trim().replace(/\./g, "");

            openModal("Edit Program");
        }

        if (deleteButton) {
            const row = deleteButton.closest("tr");
            const name = row.querySelector(".program-name strong").textContent;

            const confirmed = confirm(`Hapus program "${name}"?`);

            if (confirmed) {
                row.remove();
                filterPrograms();
            }
        }
    });

    form.addEventListener("submit", event => {
        event.preventDefault();

        if (editingRow) {
            editingRow.children[0].querySelector("strong").textContent = programName.value;
            editingRow.children[0].querySelector("small").textContent = programOrganizer.value;
            editingRow.children[1].textContent = programType.value;
            editingRow.children[2].querySelector(".field-tag").textContent = programField.value;
            editingRow.children[3].textContent = programDeadline.value;
            editingRow.children[4].textContent = Number(programRegistrants.value).toLocaleString("id-ID");
        } else {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>
                    <div class="program-name">
                        <strong>${programName.value}</strong>
                        <small>${programOrganizer.value}</small>
                    </div>
                </td>
                <td>${programType.value}</td>
                <td>
                    <span class="field-tag">${programField.value}</span>
                </td>
                <td>${programDeadline.value}</td>
                <td>${Number(programRegistrants.value).toLocaleString("id-ID")}</td>
                <td>
                    <div class="program-actions">
                        <button class="edit-program" type="button">✎</button>
                        <button class="delete-program" type="button">⌫</button>
                    </div>
                </td>
            `;

            tableBody.appendChild(row);
        }

        closeProgramModal();
        filterPrograms();
    });

    const pageButtons = document.querySelectorAll(".page-number");

    pageButtons.forEach(button => {
        button.addEventListener("click", () => {
            pageButtons.forEach(item => item.classList.remove("active"));
            button.classList.add("active");
        });
    });
});