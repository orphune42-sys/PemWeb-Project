document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const programFilter = document.getElementById('programFilter');
    const tableRows = document.querySelectorAll('#regTable tbody tr');

    function applyFilters() {
        const keyword = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const statusVal = statusFilter ? statusFilter.value : '';
        const dateVal = dateFilter ? dateFilter.value : '';
        const programVal = programFilter ? programFilter.value : '';

        const now = new Date();

        tableRows.forEach(row => {
            const nameCell = row.querySelector('.user-name');
            const emailCell = row.querySelector('.user-email');
            const programCell = row.querySelector('.reg-role');
            const statusCell = row.querySelector('.badge');
            const dateCell = row.querySelector('.reg-date');

            if (!nameCell) return;

            const nameText = nameCell.textContent.toLowerCase();
            const emailText = emailCell ? emailCell.textContent.toLowerCase() : '';
            const programText = programCell ? programCell.textContent.trim() : '';
            const statusText = statusCell ? statusCell.textContent.trim() : '';
            const dateText = dateCell ? dateCell.textContent.trim() : '';

            // 1. Pencarian Nama / Email
            const matchSearch = keyword === '' || nameText.includes(keyword) || emailText.includes(keyword);

            // 2. Filter Status
            const matchStatus = statusVal === '' || statusText === statusVal;

            // 3. Filter Program
            const matchProgram = programVal === '' || programText === programVal;

            // 4. Filter Hari
            let matchDate = true;
            if (dateVal !== '' && dateText !== '') {
                const days = parseInt(dateVal);
                if (!isNaN(days)) {
                    const rowDate = new Date(dateText);
                    const diffTime = Math.abs(now - rowDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    matchDate = diffDays <= days;
                }
            }

            if (matchSearch && matchStatus && matchProgram && matchDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (dateFilter) dateFilter.addEventListener('change', applyFilters);
    if (programFilter) programFilter.addEventListener('change', applyFilters);

    // Fitur Ekspor CSV
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            let csv = [];
            let rows = document.querySelectorAll("#regTable tr");

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display === 'none') continue;
                let row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length - 1; j++) {
                    let text = cols[j].innerText.replace(/\n/g, " ").trim();
                    row.push('"' + text + '"');
                }
                csv.push(row.join(","));
            }

            let csvFile = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
            let downloadLink = document.createElement("a");
            downloadLink.download = "Data_Pendaftaran_FindYourPath.csv";
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
    }
});