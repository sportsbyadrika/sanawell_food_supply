<?php
$config = require __DIR__ . '/../../config/config.php';
?>
<header class="bg-white shadow">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold">SW</div>
            <div>
                <p class="text-lg font-semibold">SanaWell Product Delivery</p>
                <p class="text-xs text-gray-500">SaaS Delivery Management</p>
            </div>
        </div>
        <nav class="space-x-4 text-sm">
            <?php if ($user): ?>
                <a class="text-gray-600 hover:text-blue-600" href="index.php?route=dashboard">Dashboard</a>
                <?php if (Auth::hasRole($config['roles']['SUPER_ADMIN'])): ?>
                    <a class="text-gray-600 hover:text-blue-600" href="index.php?route=agencies">Agencies</a>
                <?php endif; ?>
                <?php if (Auth::hasRole($config['roles']['AGENCY_ADMIN'])): ?>
                    <a class="text-gray-600 hover:text-blue-600" href="index.php?route=users">Users</a>
                    <a class="text-gray-600 hover:text-blue-600" href="index.php?route=products">Products</a>
                <?php endif; ?>
                <a class="text-gray-600 hover:text-blue-600" href="index.php?route=logout">Logout</a>
            <?php else: ?>
                <a class="text-gray-600 hover:text-blue-600" href="index.php?route=login">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
