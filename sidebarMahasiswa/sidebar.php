<?php
$basePath = isset($basePath) ? $basePath : '../';
$activeMenu = isset($activeMenu) ? $activeMenu : '';
$menuItems = [
    [
    'key'   => 'dashboard',
    'title' => 'Dashboard',
    'url'   => $basePath . 'dashboard/dashboard.php'
    ],
    [
    'key'   => 'explore-program',
    'title' => 'Explore Program',
    'url'   => $basePath . 'explore-program/exploreProgram.php'
    ],
    [
    'key'   => 'fine-opportunities',
    'title' => 'Find Opportunities',
    'url'   => $basePath . 'registration/findOpportunities.php'
    ],
    [
    'key'   => 'profile',
    'title' => 'Profile',
    'url'   => $basePath . 'profile-user/profile-user.php'
    ],
];
?>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="fyp-sidebar" id="fypSidebar">
    <div class="sidebar-header">
        <a href="<?= htmlspecialchars($basePath) ?>profile/profile.php" class="sidebar-brand">
            <img src="<?= htmlspecialchars($basePath) ?>assets/LOGO.png" alt="FindYourPath Logo" class="brand-logo">
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup Menu">
            &times;
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <?php foreach ($menuItems as $item): ?>
                <li class="nav-item">
                    <a href="<?= htmlspecialchars($item['url']) ?>" 
                       class="nav-link <?= ($activeMenu === $item['key']) ? 'active' : '' ?>">
                       <span class="nav-title"><?= htmlspecialchars($item['title']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= htmlspecialchars($basePath) ?>login/login.php" class="nav-link logout-link">
        <span class="nav-title">Logout</span>
        </a>
    </div>
</aside>
