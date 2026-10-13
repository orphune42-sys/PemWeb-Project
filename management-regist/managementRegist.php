<?php
$basePath = '../';
$activeMenu = 'registration';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management - FindYourPath</title>
    <link rel="stylesheet" href="../management-regist/style.css">
    <link rel="stylesheet" href="../sidebarAdmin/style.css">
</head>

<body>

    <?php include '../sidebarAdmin/sidebar.php'; ?>

    <div class="main-wrapper">
        <header class="header">
            <h1 class="header-title">Registration Management</h1>
            <div class="header-right">
                <button class="header-notif" aria-label="Notifikasi">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.70006 15.7506C7.83172 15.9786 8.02108 16.168 8.24911 16.2996C8.47713 16.4313 8.73579 16.5006 8.99909 16.5006C9.26239 16.5006 9.52105 16.4313 9.74907 16.2996C9.9771 16.168 10.1665 15.9786 10.2981 15.7506M2.44596 11.4947C2.34798 11.6021 2.28332 11.7357 2.25984 11.8792C2.23637 12.0226 2.25509 12.1698 2.31373 12.3029C2.37237 12.4359 2.46841 12.549 2.59015 12.6285C2.71189 12.7079 2.8541 12.7502 2.99947 12.7504H14.9997C15.1451 12.7504 15.2873 12.7082 15.4091 12.6289C15.5309 12.5496 15.6271 12.4366 15.6859 12.3037C15.7447 12.1708 15.7636 12.0236 15.7403 11.8801C15.717 11.7366 15.6525 11.603 15.5547 11.4955C14.5572 10.4672 13.4997 9.37432 13.4997 5.99978C13.4997 4.80621 13.0256 3.66152 12.1816 2.81754C11.3377 1.97355 10.1931 1.4994 8.99959 1.4994C7.80609 1.4994 6.66148 1.97355 5.81755 2.81754C4.97361 3.66152 4.4995 4.80621 4.4995 5.99978C4.4995 9.37432 3.44123 10.4672 2.44596 11.4947Z" stroke="#27566A" stroke-width="1.8" stroke-linecap="round" />
                    </svg>

                </button>
                <div class="header-user">
                    <div class="header-avatar">A</div>
                    <div class="header-userinfo">
                        <span class="header-username">Admin</span>
                        <span class="header-userrole">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <h2 class="greeting-title">Hi, Admin!</h2>
            <p class="greeting-sub">Pantau pendaftaran mahasiswa pada seluruh program dalam satu tempat.</p>

            <div class="panel">
                <h3 class="panel-title">Daftar Pendaftaran</h3>

                <div class="filter-bar">
                    <div class="search-wrap">
                        <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" class="search-input" placeholder="Search registra...">
                    </div>

                    <select class="filter-select">
                        <option>All Statuses</option>
                        <option>Approved</option>
                        <option>Pending</option>
                        <option>Rejected</option>
                    </select>

                    <select class="filter-select">
                        <option>Last 30 Days</option>
                        <option>Last 7 Days</option>
                        <option>Last 90 Days</option>
                    </select>

                    <select class="filter-select">
                        <option>All Roles</option>
                        <option>Student</option>
                        <option>Mentor</option>
                        <option>Organizer</option>
                    </select>

                    <div class="filter-actions">
                        <button class="btn btn--outline">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export CSV
                        </button>
                        <button class="btn btn--primary">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add New
                        </button>
                    </div>
                </div>

                <div class="table-meta">
                    <div class="table-meta-left">
                        <span class="table-list-title">Registration List</span>
                        <button class="refresh-btn" aria-label="Refresh">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    <span class="table-count">Showing 7 of 216 registrants</span>
                </div>

                <div class="table-wrap">
                    <table class="reg-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Registration Date</th>
                                <th>Category</th>
                                <th>Contact / Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal">SC</div>
                                    <div class="user-info">
                                        <span class="user-name">Sarah Cantika</span>
                                        <span class="user-email">sarahcantika@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 28, 2026</td>
                                <td class="reg-role">Beasiswa</td>
                                <td class="reg-contact">
                                    <span>+62 812-3456-7890</span>
                                    <span class="reg-location">Jakarta</span>
                                </td>
                                <td><span class="badge badge--approved">Approved</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Lihat">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Lainnya">
                                        <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--blue">MR</div>
                                    <div class="user-info">
                                        <span class="user-name">Mike Rossa</span>
                                        <span class="user-email">mikeross@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 27, 2026</td>
                                <td class="reg-role">Beasiswa</td>
                                <td class="reg-contact">
                                    <span>+62 813-9876-5432</span>
                                    <span class="reg-location">Bandung</span>
                                </td>
                                <td><span class="badge badge--pending">Pending</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal-dark">AK</div>
                                    <div class="user-info">
                                        <span class="user-name">Alex Kenandra</span>
                                        <span class="user-email">alex.ken09@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 26, 2026</td>
                                <td class="reg-role">Beasiswa</td>
                                <td class="reg-contact">
                                    <span>+62 821-5678-1234</span>
                                    <span class="reg-location">Surabaya</span>
                                </td>
                                <td><span class="badge badge--rejected">Rejected</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal">JL</div>
                                    <div class="user-info">
                                        <span class="user-name">Jenna Alifia</span>
                                        <span class="user-email">jenna.altr@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 25, 2026</td>
                                <td class="reg-role">Lomba</td>
                                <td class="reg-contact">
                                    <span>+62 856-7890-1234</span>
                                    <span class="reg-location">Yogyakarta</span>
                                </td>
                                <td><span class="badge badge--approved">Approved</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal">DP</div>
                                    <div class="user-info">
                                        <span class="user-name">David Pratama</span>
                                        <span class="user-email">david.234@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 24, 2026</td>
                                <td class="reg-role">Lomba</td>
                                <td class="reg-contact">
                                    <span>+62 878-2345-6789</span>
                                    <span class="reg-location">Semarang</span>
                                </td>
                                <td><span class="badge badge--pending">Pending</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal">RT</div>
                                    <div class="user-info">
                                        <span class="user-name">Raina Teresa</span>
                                        <span class="user-email">rainneterrs@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 23, 2026</td>
                                <td class="reg-role">Lomba</td>
                                <td class="reg-contact">
                                    <span>+62 819-8765-4321</span>
                                    <span class="reg-location">Medan</span>
                                </td>
                                <td><span class="badge badge--approved">Approved</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="user-cell">
                                    <div class="avatar avatar--teal">RT</div>
                                    <div class="user-info">
                                        <span class="user-name">Kania Andriana</span>
                                        <span class="user-email">kan.09@email.com</span>
                                    </div>
                                </td>
                                <td class="reg-date">Sep 23, 2026</td>
                                <td class="reg-role">Beasiswa</td>
                                <td class="reg-contact">
                                    <span>+62 819-8365-4322</span>
                                    <span class="reg-location">Surabaya</span>
                                </td>
                                <td><span class="badge badge--approved">Approved</span></td>
                                <td class="actions-cell">
                                    <button class="action-btn" title="Edit"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lihat"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg></button>
                                    <button class="action-btn" title="Lainnya"><svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="5" cy="12" r="2"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                            <circle cx="19" cy="12" r="2"></circle>
                                        </svg></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-bar">
                    <span class="pagination-info">Showing 1-10 of 248 results</span>
                    <div class="pagination-pages">
                        <button class="page-btn page-btn--nav">&lsaquo;</button>
                        <button class="page-btn page-btn--active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">4</button>
                        <button class="page-btn">5</button>
                        <span class="page-dots">&hellip;</span>
                        <button class="page-btn">25</button>
                        <button class="page-btn page-btn--nav">&rsaquo;</button>
                    </div>
                    <select class="per-page-select">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
            </div>
        </main>
    </div>

</body>

<script src="../management-regist/script.js"></script>

<script src="../sidebarAdmin/script.js"></script>

</body>

</html>