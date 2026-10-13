document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const typeFilter = document.getElementById('typeFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const btnSearch = document.getElementById('btnSearch');
    const programCards = document.querySelectorAll('.program-card');
    const bookmarkBtns = document.querySelectorAll('.bookmark-btn');

    function filterPrograms() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedType = typeFilter ? typeFilter.value.toLowerCase() : 'semua';
        const selectedCategory = categoryFilter ? categoryFilter.value.toLowerCase() : 'semua';

        programCards.forEach(card => {
            const title = card.getAttribute('data-title') || '';
            const type = card.getAttribute('data-type') || '';
            const category = card.getAttribute('data-category') || '';

            const matchesQuery = title.includes(query);
            const matchesType = selectedType === 'semua' || type === selectedType;
            const matchesCategory = selectedCategory === 'semua' || category === selectedCategory;

            if (matchesQuery && matchesType && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterPrograms);
    if (typeFilter) typeFilter.addEventListener('change', filterPrograms);
    if (categoryFilter) categoryFilter.addEventListener('change', filterPrograms);
    if (btnSearch) btnSearch.addEventListener('click', filterPrograms);

    bookmarkBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            btn.classList.toggle('active');
        });
    });
    
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
    const sidebar = document.getElementById('fypSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('active');
        if (backdrop) backdrop.classList.add('active');
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('active');
        if (backdrop) backdrop.classList.remove('active');
    }

    if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', openSidebar);
    if (sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
});