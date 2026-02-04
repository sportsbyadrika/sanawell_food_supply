<?php
$user = Auth::user();
$title = $title ?? $this->config['app_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($this->config['app_name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <?php include __DIR__ . '/../partials/nav.php'; ?>
        <main class="flex-1 container mx-auto px-4 py-6">
            <?php include $viewPath; ?>
        </main>
        <footer class="bg-white border-t border-gray-200 py-4">
            <div class="container mx-auto px-4 text-sm text-gray-500">&copy; <?= date('Y') ?> SanaWell Product Delivery</div>
        </footer>
    </div>
</body>
</html>
