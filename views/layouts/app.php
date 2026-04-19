<?php
$config = require __DIR__ . '/../../config/config.php';
$user = Auth::user();
$title = $title ?? $config['app_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | <?= htmlspecialchars($this->config['app_name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"/>
</head>
<body class="bg-gray-100 text-gray-800">
  

    <?php include __DIR__ . '/../partials/nav.php'; ?>

    <!-- MAIN -->
  <main class="container mx-auto px-4 py-6 min-h-[calc(100vh-140px)]">
    <?php include $viewFile; ?>
</main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 py-4">
        <div class="container mx-auto px-4 text-sm text-gray-500">
            &copy; <?= date('Y') ?> Dew Route Product Delivery
        </div>
    </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
</body>
</html>