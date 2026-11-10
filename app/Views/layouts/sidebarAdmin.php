<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="fyp-sidebar" id="fypSidebar">
    <div class="sidebar-header">
        <a href="<?= htmlspecialchars($baseUrl) ?>/public/index.php?page=profile-admin" class="sidebar-brand">
            <img src="<?= htmlspecialchars($logoUrl) ?>" alt="FindYourPath Logo" class="brand-logo">
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
        <a href="<?= htmlspecialchars($logoutUrl) ?>" class="nav-link logout-link">
            <span class="nav-title">Keluar</span>
        </a>
    </div>
</aside>