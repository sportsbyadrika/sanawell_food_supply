<div class="max-w-7xl mx-auto py-8 px-4">

<!-- Page Title -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">🚚 Driver Dashboard</h2>
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
$failed    = $route['failed_count'] ?? 0;

$totalCustomers = $pending + $delivered + $failed;

$progress = $totalCustomers > 0
    ? round(($delivered / $totalCustomers) * 100)
    : 0;

/* Progress UI */
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

/* Products */
$products = $route['products'] ?? [];

/* Packet totals */
$total_packets = 0;
$total_added = 0;
$total_cancelled = 0;
?>

<div class="bg-white rounded-2xl shadow-md hover:shadow-xl transform hover:-translate-y-1 transition duration-300 border border-gray-200 p-6">

<!-- Header -->
<div class="flex justify-between items-start mb-3">

<div>
<h3 class="text-lg font-bold text-gray-800">
<?= htmlspecialchars($routeName) ?>
</h3>

<?php if ($timing): ?>
<span class="inline-block text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full mt-1">
<?= htmlspecialchars($timing) ?>
</span>
<?php endif; ?>

</div>

<span class="text-xs bg-gray-100 px-3 py-1 rounded-full text-gray-600">
<?= $totalCustomers ?> Customers
</span>

</div>


<!-- PRODUCT SUMMARY -->
<div class="grid grid-cols-2 gap-3 mb-4">

<?php foreach ($products as $product => $data): ?>

<?php
$normal = $data['normal'] ?? 0;
$added = $data['added'] ?? 0;
$cancelled = $data['cancelled'] ?? 0;

$total = $normal + $added - $cancelled;

$total_packets += $total;
$total_added += $added;
$total_cancelled += $cancelled;
?>

<div class="bg-gray-50 rounded-lg p-3 text-center">

<div class="text-xs text-gray-500">
<?= htmlspecialchars($product) ?>
</div>

<div class="text-xl font-bold text-blue-600">
<?= $total ?>
</div>

<?php if ($added > 0): ?>
<div class="text-xs text-green-600">
+<?= $added ?> added
</div>
<?php endif; ?>

<?php if ($cancelled > 0): ?>
<div class="text-xs text-red-600">
-<?= $cancelled ?> cancelled
</div>
<?php endif; ?>

</div>

<?php endforeach; ?>

</div>


<!-- Packet Summary -->
<div class="border-t pt-3 text-sm space-y-1">

<div class="flex justify-between">
<span class="text-gray-500">Total Packets</span>
<span class="font-semibold text-purple-600">
<?= $total_packets ?>
</span>
</div>

<div class="flex justify-between">
<span class="text-gray-500">Added Packets</span>
<span class="font-semibold text-green-600">
<?= $total_added ?>
</span>
</div>

<div class="flex justify-between">
<span class="text-gray-500">Cancelled Packets</span>
<span class="font-semibold text-red-600">
<?= $total_cancelled ?>
</span>
</div>

</div>


<!-- Divider -->
<div class="my-4 border-t border-gray-200"></div>


<!-- Delivery Summary -->
<div class="space-y-2">

<div class="flex justify-between text-sm">
<span class="text-gray-500">Delivered</span>
<span class="font-semibold text-green-600">
<?= $delivered ?> of <?= $totalCustomers ?>
</span>
</div>

<div class="flex justify-between text-sm">
<span class="text-gray-500">Pending</span>
<span class="font-semibold text-yellow-600">
<?= $pending ?>
</span>
</div>

<!-- Progress bar -->
<div class="w-full bg-gray-200 rounded-full h-2 mt-2">
<div class="<?= $barColor ?> h-2 rounded-full"
style="width: <?= $progress ?>%"></div>
</div>

<div class="text-xs text-gray-500 text-center">
<?= $progress ?>% Completed
</div>

</div>


<!-- Button -->
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
