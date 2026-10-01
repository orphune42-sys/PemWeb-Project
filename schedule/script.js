document.addEventListener('DOMContentLoaded', () => {
const modal = document.getElementById('programModal');
const modalTitle = document.getElementById('modalTitle');
const programForm = document.getElementById('programForm');
const formAction = document.getElementById('formAction');
const formId = document.getElementById('formId');
const btnOpenAdd = document.getElementById('btnOpenAddModal');
const btnCloseModal = document.getElementById('btnCloseModal');
const btnCancelModal = document.getElementById('btnCancelModal');
const deleteForm = document.getElementById('deleteForm');
const deleteId = document.getElementById('deleteId');
const inputTitle = document.getElementById('inputTitle');
const inputCategory = document.getElementById('inputCategory');
const inputStatus = document.getElementById('inputStatus');
const inputStartDate = document.getElementById('inputStartDate');
const inputEndDate = document.getElementById('inputEndDate');
const inputEventDate = document.getElementById('inputEventDate');
const inputLocation = document.getElementById('inputLocation');
const inputDescription = document.getElementById('inputDescription');

    function openModal() {
        if (modal) modal.classList.add('open');
    }function closeModal() {
        if (modal) modal.classList.remove('open');
    }

    if (btnOpenAdd) {
        btnOpenAdd.addEventListener('click', () => {
            modalTitle.textContent = 'Tambah Program';
            formAction.value = 'create';
            formId.value = '';
            programForm.reset();
            inputStatus.value = 'Aktif';
            inputCategory.value = 'Lomba';
            
            const today = new Date();
            const yyyy = today.getFullYear() < 2026 ? '2026' : today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            inputEventDate.value = `${yyyy}-${mm}-${dd}`;
            openModal();
        });
    }

    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (btnCancelModal) btnCancelModal.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    function setupRowActions() {
        document.querySelectorAll('.program-row').forEach(row => {
            const editBtn = row.querySelector('.btn-action-edit');
            const deleteBtn = row.querySelector('.btn-action-delete');

            if (editBtn) {
                editBtn.onclick = (e) => {
                    e.stopPropagation();
                    const id = row.getAttribute('data-id');
                    const title = row.getAttribute('data-title') || '';
                    const category = row.getAttribute('data-category') || 'Lomba';
                    const status = row.getAttribute('data-status') || 'Aktif';
                    const start = row.getAttribute('data-start') || '';
                    const end = row.getAttribute('data-end') || '';
                    const event = row.getAttribute('data-event') || '';
                    const location = row.getAttribute('data-location') || '';
                    const description = row.getAttribute('data-description') || '';

                    modalTitle.textContent = 'Edit Program';
                    formAction.value = 'update';
                    formId.value = id;
                    inputTitle.value = title;
                    inputCategory.value = category;
                    inputStatus.value = status;
                    inputStartDate.value = start;
                    inputEndDate.value = end;
                    inputEventDate.value = event || start;
                    inputLocation.value = location;
                    inputDescription.value = description;

                    openModal();
                };
            }

            if (deleteBtn) {
                deleteBtn.onclick = (e) => {
                    e.stopPropagation();
                    const id = row.getAttribute('data-id');
                    const title = row.getAttribute('data-title') || 'Program ini';
                    if (confirm(`Apakah Anda yakin ingin menghapus program "${title}"?`)) {
                        deleteId.value = id;
                        deleteForm.submit();
                    }
                };
            }
        });
    }
    setupRowActions();

    const searchInput = document.getElementById('searchInput');
    const filterCategory = document.getElementById('filterCategory');
    const filterDateRange = document.getElementById('filterDateRange');
    const tableBody = document.getElementById('programTableBody');
    const allRows = Array.from(document.querySelectorAll('.program-row'));
    const paginationControls = document.getElementById('paginationControls');
    const pageRangeText = document.getElementById('pageRangeText');
    const pageTotalText = document.getElementById('pageTotalText');
    const programCountBadge = document.getElementById('programCountBadge');

    const pageSize = 6;
    let currentPage = 1;
    let filteredRows = [...allRows];

    function applyFilters() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedCat = filterCategory ? filterCategory.value.trim() : '';
        const selectedDate = filterDateRange ? filterDateRange.value.trim() : '';

        filteredRows = allRows.filter(row => {
            const title = (row.getAttribute('data-title') || '').toLowerCase();
            const location = (row.getAttribute('data-location') || '').toLowerCase();
            const category = row.getAttribute('data-category') || '';
            const start = row.getAttribute('data-start') || '';
            const end = row.getAttribute('data-end') || '';
            const event = row.getAttribute('data-event') || '';

            const matchQuery = !query || title.includes(query) || location.includes(query) || category.toLowerCase().includes(query);
            const matchCategory = !selectedCat || category.toLowerCase() === selectedCat.toLowerCase();
            
            let matchDate = true;
            if (selectedDate) {
                matchDate = (event === selectedDate) || (start <= selectedDate && end >= selectedDate);
            }

            return matchQuery && matchCategory && matchDate;
        });

        currentPage = 1;
        renderTablePage();
    }

    function renderTablePage() {
        const total = filteredRows.length;
        const totalPages = Math.ceil(total / pageSize) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const startIndex = (currentPage - 1) * pageSize;
        const endIndex = Math.min(startIndex + pageSize, total);

        // Hide all rows first
        allRows.forEach(row => {
            row.style.display = 'none';
        });

        // Show only rows for current page
        for (let i = startIndex; i < endIndex; i++) {
            if (filteredRows[i]) {
                filteredRows[i].style.display = '';
            }
        }

        // Check if no rows matched
        let emptyNotice = document.getElementById('emptyFilterRow');
        if (total === 0) {
            if (!emptyNotice) {
                emptyNotice = document.createElement('tr');
                emptyNotice.id = 'emptyFilterRow';
                emptyNotice.innerHTML = `<td colspan="6" class="empty-cell">Tidak ada jadwal program yang sesuai dengan filter.</td>`;
                tableBody.appendChild(emptyNotice);
            }
            emptyNotice.style.display = '';
        } else if (emptyNotice) {
            emptyNotice.style.display = 'none';
        }

        // Update range and badge text
        if (pageRangeText) {
            pageRangeText.textContent = total > 0 ? `${startIndex + 1}-${endIndex}` : '0';
        }
        if (pageTotalText) {
            pageTotalText.textContent = total;
        }
        if (programCountBadge) {
            programCountBadge.textContent = total;
        }

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        if (!paginationControls) return;
        paginationControls.innerHTML = '';

        if (totalPages <= 1) return;

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'btn-page';
        prevBtn.innerHTML = '&lt;';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderTablePage();
            }
        };
        paginationControls.appendChild(prevBtn);

        // Page Numbers
        for (let p = 1; p <= totalPages; p++) {
            // Simple pagination display: first, last, current, adjacent
            if (p === 1 || p === totalPages || (p >= currentPage - 1 && p <= currentPage + 1)) {
                const pageBtn = document.createElement('button');
                pageBtn.type = 'button';
                pageBtn.className = `btn-page ${p === currentPage ? 'active' : ''}`;
                pageBtn.textContent = p;
                pageBtn.onclick = () => {
                    currentPage = p;
                    renderTablePage();
                };
                paginationControls.appendChild(pageBtn);
            } else if (
                (p === currentPage - 2 && p > 1) || 
                (p === currentPage + 2 && p < totalPages)
            ) {
                const ellipsis = document.createElement('span');
                ellipsis.style.padding = '0 4px';
                ellipsis.style.color = '#94a3b8';
                ellipsis.textContent = '...';
                paginationControls.appendChild(ellipsis);
            }
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'btn-page';
        nextBtn.innerHTML = '&gt;';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderTablePage();
            }
        };
        paginationControls.appendChild(nextBtn);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (filterCategory) filterCategory.addEventListener('change', applyFilters);
    if (filterDateRange) filterDateRange.addEventListener('input', applyFilters);

    // Initial table render
    applyFilters();

    // ----------------------------------------------------
    // Calendar Management (Lomba & Jadwal)
    // ----------------------------------------------------
    const calendarGrid = document.getElementById('calendarGrid');
    const calMonthTitle = document.getElementById('calMonthTitle');
    const btnPrevMonth = document.getElementById('btnPrevMonth');
    const btnNextMonth = document.getElementById('btnNextMonth');

    // Default to October 2026 to match Pic 1 and Pic 2!
    let calYear = 2026;
    let calMonth = 9; // 0-based: 9 = October

    const monthNamesIndo = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    function getCategoryDotClass(category) {
        const cat = (category || '').toLowerCase();
        if (cat.includes('beasiswa')) return 'dot-beasiswa';
        if (cat.includes('workshop')) return 'dot-workshop';
        if (cat.includes('magang')) return 'dot-magang';
        return 'dot-lomba';
    }

    function renderCalendar() {
        if (!calendarGrid || !calMonthTitle) return;

        // Month heading title
        calMonthTitle.textContent = `${monthNamesIndo[calMonth]} ${calYear}`;

        calendarGrid.innerHTML = '';

        // Schedules data from PHP initialSchedules array
        const schedulesList = (typeof initialSchedules !== 'undefined' && Array.isArray(initialSchedules))
            ? initialSchedules
            : [];

        // Month calculations
        // First day of current month:
        const firstDayObj = new Date(calYear, calMonth, 1);
        let firstDayOfWeek = firstDayObj.getDay(); // 0 = Sun, 1 = Mon, ..., 6 = Sat
        // Convert to Monday = 0:
        firstDayOfWeek = (firstDayOfWeek === 0) ? 6 : firstDayOfWeek - 1;

        const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(calYear, calMonth, 0).getDate();

        // 1. Previous month padding days
        for (let i = firstDayOfWeek - 1; i >= 0; i--) {
            const dayNum = daysInPrevMonth - i;
            const prevMonthIndex = calMonth === 0 ? 11 : calMonth - 1;
            const prevYear = calMonth === 0 ? calYear - 1 : calYear;
            const dateStr = `${prevYear}-${String(prevMonthIndex + 1).padStart(2, '0')}-${String(dayNum).padStart(2, '0')}`;
            
            const cell = createCalendarCell(dayNum, dateStr, true, schedulesList);
            calendarGrid.appendChild(cell);
        }

        // 2. Current month days
        const today = new Date();
        const isCurrentMonthReal = (today.getFullYear() === calYear && today.getMonth() === calMonth);

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${calYear}-${String(calMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = isCurrentMonthReal && (today.getDate() === day);

            const cell = createCalendarCell(day, dateStr, false, schedulesList, isToday);
            calendarGrid.appendChild(cell);
        }

        // 3. Next month padding days to complete grid (total multiple of 7)
        const totalRendered = firstDayOfWeek + daysInMonth;
        const remainingCells = (totalRendered % 7 === 0) ? 0 : 7 - (totalRendered % 7);

        for (let day = 1; day <= remainingCells; day++) {
            const nextMonthIndex = calMonth === 11 ? 0 : calMonth + 1;
            const nextYear = calMonth === 11 ? calYear + 1 : calYear;
            const dateStr = `${nextYear}-${String(nextMonthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            const cell = createCalendarCell(day, dateStr, true, schedulesList);
            calendarGrid.appendChild(cell);
        }
    }

    function createCalendarCell(dayNum, dateStr, isOtherMonth, schedulesList, isToday = false) {
        const cell = document.createElement('div');
        cell.className = `cal-day-cell ${isOtherMonth ? 'other-month' : ''} ${isToday ? 'today' : ''}`;

        const dayHeader = document.createElement('div');
        dayHeader.className = 'day-header';

        const dayNumberSpan = document.createElement('span');
        dayNumberSpan.className = 'day-number';
        dayNumberSpan.textContent = dayNum;
        dayHeader.appendChild(dayNumberSpan);

        cell.appendChild(dayHeader);

        const eventsList = document.createElement('div');
        eventsList.className = 'events-list';

        const matchingEvents = schedulesList.filter(item => {
            if (item.event_date && item.event_date === dateStr) 
                return true;
            if (item.start_date && item.start_date === dateStr) 
                return true;

            return false;
        });

        matchingEvents.forEach(evt => {
        const eventItem = document.createElement('div');
            eventItem.className = 'cal-event-item';
            eventItem.title = `${evt.title} (${evt.category}) - Lokasi: ${evt.location}`;

        const dot = document.createElement('span');
            dot.className = `event-dot ${getCategoryDotClass(evt.category)}`;

        const titleSpan = document.createElement('span');
            titleSpan.className = 'event-title-text';
            titleSpan.textContent = evt.title;

            eventItem.appendChild(dot);
            eventItem.appendChild(titleSpan);

            eventItem.addEventListener('click', (e) => {
                e.stopPropagation();
                modalTitle.textContent = 'Edit Program';
                formAction.value = 'update';
                formId.value = evt.id;
                inputTitle.value = evt.title || '';
                inputCategory.value = evt.category || 'Lomba';
                inputStatus.value = evt.status || 'Aktif';
                inputStartDate.value = evt.start_date || '';
                inputEndDate.value = evt.end_date || '';
                inputEventDate.value = evt.event_date || evt.start_date || '';
                inputLocation.value = evt.location || '';
                inputDescription.value = evt.description || '';
                openModal();
            });
            eventsList.appendChild(eventItem);
        });
        cell.appendChild(eventsList);

        if (!isOtherMonth) {
            cell.addEventListener('click', () => {
                modalTitle.textContent = 'Tambah Program';
                formAction.value = 'create';
                formId.value = '';
                programForm.reset();
                inputStatus.value = 'Aktif';
                inputCategory.value = 'Lomba';
                inputEventDate.value = dateStr;
                inputStartDate.value = dateStr;
                openModal();
            });
        }

        return cell;
    }

    if (btnPrevMonth) {
        btnPrevMonth.addEventListener('click', () => {
            calMonth--;
            if (calMonth < 0) {
                calMonth = 11;
                calYear--;
            }
            renderCalendar();
        });
    }

    if (btnNextMonth) {
        btnNextMonth.addEventListener('click', () => {
            calMonth++;
            if (calMonth > 11) {
                calMonth = 0;
                calYear++;
            }
            renderCalendar();
        });
    }
    renderCalendar();
});
