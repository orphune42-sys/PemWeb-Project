<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="fyp-sidebar" id="fypSidebar">
    <div class="sidebar-header">
        <a href="<?= htmlspecialchars(($baseUrl ?? '') . '/dashboard-user/user.php', ENT_QUOTES, 'UTF-8') ?>" class="sidebar-brand">
            <img src="<?= htmlspecialchars($logoUrl ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="FindYourPath Logo" class="brand-logo">
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup menu">&times;</button>
    </div>

    <nav class="sidebar-nav" aria-label="Navigasi mahasiswa">
        <ul class="nav-list">
            <?php foreach ($menuItems ?? [] as $item): ?>
                <li class="nav-item">
                    <a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"
                       class="nav-link <?= ($activeMenu === $item['key']) ? 'active' : '' ?>">
                        <span class="nav-title"><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= htmlspecialchars($logoutUrl ?? '', ENT_QUOTES, 'UTF-8') ?>" class="nav-link logout-link">
            <span class="nav-title">Keluar</span>
        </a>
    </div>
</aside>
