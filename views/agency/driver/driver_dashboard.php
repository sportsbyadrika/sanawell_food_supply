<div class="max-w-7xl mx-auto py-8 px-4">

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            🚚 Driver Dashboard
        </h2>
        <p class="text-gray-500 text-sm">
            Manage your assigned routes and deliveries.
        </p>
    </div>

    <?php if (empty($routes)): ?>
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-500">
            No routes assigned for today.
        </div>
    <?php else: ?>

    <div class="grid md:grid-cols-3 gap-6">

        <?php foreach ($routes as $route): ?>

        <?php
            $routeId   = $route['id'] ?? 0;
            $routeName = $route['name'] ?? 'Route';
            $timing    = $route['type'] ?? '';

            $pending   = $route['pending_count'] ?? 0;
            $delivered = $route['delivered_count'] ?? 0;

            $total     = $pending + $delivered;
            $progress  = $total > 0 ? round(($delivered / $total) * 100) : 0;

            // Dynamic progress color
            if ($progress == 100) {
                $barColor = "bg-green-500";
                $buttonText = "Completed";
                $buttonColor = "bg-green-600";
            } elseif ($progress > 0) {
                $barColor = "bg-blue-500";
                $buttonText = "Continue Delivery";
                $buttonColor = "bg-blue-600 hover:bg-blue-700";
            } else {
                $barColor = "bg-gray-400";
                $buttonText = "Start Delivery";
                $buttonColor = "bg-blue-600 hover:bg-blue-700";
            }
        ?>

        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transform transition duration-300 border border-gray-200 p-6">

            <!-- Header -->
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        <?= htmlspecialchars($routeName) ?>
                    </h3>

                    <?php if ($timing): ?>
                        <span class="inline-block text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium mt-1">
                            <?= htmlspecialchars($timing) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-600">
                    <?= $total ?> Customers
                </span>
            </div>
<!-- PRODUCT SUMMARY -->
<div class="grid grid-cols-2 gap-2 mb-4">

<?php
$products = $route['products'] ?? [];
$total_packets = array_sum($products);
?>
<?php foreach ($products as $product => $qty): ?>

<div class="bg-gray-50 rounded-lg p-3 text-center">

<div class="text-xs text-gray-500">
<?= htmlspecialchars($product) ?>
</div>

<div class="text-lg font-bold text-blue-600">
<?= $qty ?>
</div>

</div>

<?php endforeach; ?>
<div class="mt-3 border-t pt-3">

<div class="flex justify-between text-sm font-semibold">

<span>Total Packets</span>

<span class="text-purple-600">
<?= $total_packets ?>
</span>

</div>

</div>
</div>

            <!-- Divider -->
            <div class="my-4 border-t border-gray-200"></div>

            <!-- Delivery Summary -->
            <div class="space-y-2">

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Delivered</span>
                    <span class="font-semibold text-green-600">
                        <?= $delivered ?> of <?= $total ?>
                    </span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Pending</span>
                    <span class="font-semibold text-yellow-600">
                        <?= $pending ?>
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="<?= $barColor ?> h-2 rounded-full transition-all duration-300"
                         style="width: <?= $progress ?>%">
                    </div>
                </div>

                <div class="text-right text-xs text-gray-500 mt-1">
                    <?= $progress ?>% Completed
                </div>
            </div>

            <!-- Start / Continue Button -->
            <div class="mt-6">
                <a href="index.php?route=driver_delivery&route_id=<?= $routeId ?>"
                   class="inline-block bg-gradient-to-br from-blue-500 to-slate-500text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    <?= $buttonText ?>
                </a>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>

</div>