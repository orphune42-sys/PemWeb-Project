document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.querySelector('.search-input');
    const tableRows = document.querySelectorAll('#dashboardTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                const nameCell = row.querySelector('.student-name');
                const progCell = row.querySelector('.program-badge');

                if (nameCell || progCell) {
                    const nameText = nameCell ? nameCell.textContent.toLowerCase() : '';
                    const progText = progCell ? progCell.textContent.toLowerCase() : '';

                    if (nameText.includes(keyword) || progText.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }

    const container = document.getElementById('chartSvgContainer');
    if (container && typeof monthlyTrendData !== 'undefined') {
        const values = Object.values(monthlyTrendData);
        const maxVal = 500;

        const width = container.clientWidth || 400;
        const height = container.clientHeight || 160;

        const stepX = width / (values.length);
        let points = [];

        values.forEach((val, idx) => {
            const x = (idx * stepX) + (stepX / 2);
            const y = height - ((val / maxVal) * height);
            points.push({ x, y, val });
        });

        let pathD = `M ${points[0].x} ${points[0].y}`;
        for (let i = 1; i < points.length; i++) {
            pathD += ` L ${points[i].x} ${points[i].y}`;
        }

        let svgHtml = `
            <svg width="100%" height="100%" viewBox="0 0 ${width} ${height}" style="overflow: visible;">
                <path d="${pathD}" fill="none" stroke="#4EACD4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        `;

        points.forEach((p) => {
            svgHtml += `
                <circle cx="${p.x}" cy="${p.y}" r="5" fill="#ffffff" stroke="#4EACD4" stroke-width="3" />
                <text x="${p.x}" y="${p.y - 10}" text-anchor="middle" font-size="10" font-weight="600" fill="#27566A">${p.val}</text>
            `;
        });

        svgHtml += `</svg>`;
        container.innerHTML = svgHtml;
    }

    const notifBtn = document.querySelector('.header-notif');
    if (notifBtn) {
        notifBtn.addEventListener('click', function () {
            alert('Belum ada notifikasi pendaftaran baru.');
        });
    }
});