
document.addEventListener('DOMContentLoaded', () => {

    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.dataset.tab;

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            button.classList.add('active');

            document.getElementById(`tab-${target}`)
                ?.classList.add('active');
        });
    });

    const fields = {
        goals: [
            {
                name: 'item_type',
                label: 'Jenis Data',
                type: 'select',
                options: {
                    goal: 'Goal / Target',
                    skill: 'Skill / Kemampuan'
                }
            },
            { name: 'title', label: 'Nama Target / Skill', type: 'text', required: true },
            { name: 'description', label: 'Deskripsi', type: 'textarea' },
            { name: 'target_date', label: 'Target Tanggal', type: 'date' },
            {
                name: 'status',
                label: 'Status',
                type: 'select',
                options: {
                    planned: 'Direncanakan',
                    in_progress: 'Sedang Berjalan',
                    completed: 'Selesai'
                }
            }
        ],

        projects: [
            { name: 'title', label: 'Nama Proyek', type: 'text', required: true },
            { name: 'category', label: 'Kategori', type: 'text' },
            { name: 'description', label: 'Deskripsi Proyek', type: 'textarea' },
            { name: 'project_url', label: 'Link Proyek', type: 'url' },
            { name: 'start_date', label: 'Tanggal Mulai', type: 'date' },
            { name: 'end_date', label: 'Tanggal Selesai', type: 'date' },
            {
                name: 'status',
                label: 'Status',
                type: 'select',
                options: {
                    planned: 'Direncanakan',
                    in_progress: 'Sedang Berjalan',
                    completed: 'Selesai'
                }
            }
        ],

        achievements: [
            { name: 'title', label: 'Nama Pencapaian', type: 'text', required: true },
            { name: 'issuer', label: 'Penyelenggara / Pemberi Penghargaan', type: 'text' },
            { name: 'description', label: 'Deskripsi', type: 'textarea' },
            { name: 'achievement_date', label: 'Tanggal Pencapaian', type: 'date' },
            { name: 'proof_url', label: 'Link Sertifikat / Bukti', type: 'url' }
        ],

        career: [
            { name: 'target_career', label: 'Karier Impian', type: 'text', required: true },
            { name: 'target_date', label: 'Target Tanggal', type: 'date' },
            { name: 'current_skills', label: 'Kemampuan yang Dimiliki', type: 'textarea' },
            { name: 'required_skills', label: 'Kemampuan yang Dibutuhkan', type: 'textarea' },
            { name: 'action_plan', label: 'Langkah untuk Mencapai Karier', type: 'textarea' },
            {
                name: 'status',
                label: 'Status',
                type: 'select',
                options: {
                    planned: 'Direncanakan',
                    in_progress: 'Sedang Berjalan',
                    completed: 'Selesai'
                }
            }
        ]
    };

    const modal = document.getElementById('recordModal');
    const form = document.getElementById('recordForm');
    const formFields = document.getElementById('formFields');

    const formAction = document.getElementById('formAction');
    const formType = document.getElementById('formType');
    const formId = document.getElementById('formId');
    const modalTitle = document.getElementById('modalTitle');

    function generateForm(type, record = {}) {
        formFields.innerHTML = '';

        fields[type].forEach(field => {
            const group = document.createElement('div');
            group.className = 'form-group';

            const label = document.createElement('label');
            label.textContent = field.label;
            label.htmlFor = field.name;

            let input;

            if (field.type === 'textarea') {
                input = document.createElement('textarea');
            } else if (field.type === 'select') {
                input = document.createElement('select');

                Object.entries(field.options).forEach(([value, text]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = text;
                    input.appendChild(option);
                });
            } else {
                input = document.createElement('input');
                input.type = field.type;
            }

            input.name = field.name;
            input.id = field.name;

            if (field.required) {
                input.required = true;
            }

            if (record[field.name] != null) {
                input.value = record[field.name];
            } else if (field.type === 'select') {
                input.value = Object.keys(field.options)[0];
            }

            group.appendChild(label);
            group.appendChild(input);
            formFields.appendChild(group);
        });
    }

    function openModal(type, action, record = {}) {
        if (!fields[type]) return;

        form.reset();

        formType.value = type;
        formAction.value = action;
        formId.value = record.id || '';

        modalTitle.textContent =
            action === 'create' ? 'Tambah Data Baru' : 'Edit Data';

        generateForm(type, record);

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        form.reset();
    }

    document.querySelectorAll('[data-create]').forEach(button => {
        button.addEventListener('click', () => {
            openModal(button.dataset.create, 'create');
        });
    });

    document.querySelectorAll('[data-edit]').forEach(button => {
        button.addEventListener('click', () => {
            try {
                const record = JSON.parse(button.dataset.record);

                openModal(button.dataset.edit, 'update', record);
            } catch (error) {
                alert('Data gagal dibaca.');
            }
        });
    });

    document.getElementById('closeModal')
        .addEventListener('click', closeModal);

    document.getElementById('cancelModal')
        .addEventListener('click', closeModal);

    modal.addEventListener('click', event => {
        if (event.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    const sidebar = document.getElementById('fypSidebar');
    const backdrop = document.getElementById('sidebarBackdrop');

    const toggle = document.getElementById('sidebarToggleBtn');
    const close = document.getElementById('sidebarCloseBtn');

    function toggleSidebar(show) {
        sidebar?.classList.toggle('active', show);
        backdrop?.classList.toggle('active', show);
    }

    toggle?.addEventListener('click', () => toggleSidebar(true));
    close?.addEventListener('click', () => toggleSidebar(false));
    backdrop?.addEventListener('click', () => toggleSidebar(false));

});
