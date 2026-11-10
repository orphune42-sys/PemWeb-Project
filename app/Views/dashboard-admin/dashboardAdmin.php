<?php

if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES,
            'UTF-8'
        );
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - FindYourPath</title>

    <link rel="stylesheet" href="../../dashboard-admin/style.css">
    <link rel="stylesheet" href="../../sidebarAdmin/sidebarAdmin.css">
</head>

<body>

    <?php
    require_once __DIR__ . '/../../../sidebarAdmin/sidebarAdmin.php';
    ?>

    <div class="dashboard-container">

        <h1>Dashboard Admin</h1>

        <div class="dashboard-cards">

            <div class="dashboard-card">
                <h3>Total Program</h3>
                <p><?= e($totalProgram) ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Total Lomba</h3>
                <p><?= e($totalLomba) ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Total Beasiswa</h3>
                <p><?= e($totalBeasiswa) ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Total Pendaftar</h3>
                <p><?= e($totalPendaftar) ?></p>
            </div>

        </div>

    </div>

    <div class="dashboard-container">

        <h2>Data User</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($dataUser)): ?>

                    <?php foreach ($dataUser as $user): ?>

                        <tr>
                            <td><?= e($user['id']) ?></td>
                            <td><?= e($user['name']) ?></td>
                            <td><?= e($user['username']) ?></td>
                            <td><?= e($user['email']) ?></td>
                            <td><?= e($user['role']) ?></td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5">Belum ada data user.</td>
                    </tr>

                <?php endif; ?>

            </tbody>
        </table>

    </div>

    <script src="../../dashboard-admin/script.js?v=1"></script>
    <script src="../../sidebarAdmin/sidebarAdmin.js"></script>

</body>

</html>