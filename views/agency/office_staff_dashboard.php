<?php

$db = Database::connection();
$today = date('Y-m-d');

/* ------------------------------
   Stats
------------------------------ */

$customers = $db->query("SELECT COUNT(*) FROM customers")->fetchColumn();

$routes = $db->query("SELECT COUNT(*) FROM routes")->fetchColumn();

$todaysDeliveries = $db->query("
    SELECT COUNT(*)
    FROM delivery_orders
    WHERE delivery_date = '$today'
")->fetchColumn();

$completedToday = $db->query("
    SELECT COUNT(*)
    FROM delivery_orders
    WHERE delivery_date = '$today'
    AND status = 'delivered'
")->fetchColumn();


/* ------------------------------
   Packet Summary
------------------------------ */

$sql = "
SELECT
    CONCAT(p.name,' (',p.variant,')') AS product_name,
    SUM(doi.quantity + doi.added_qty - doi.cancelled_qty) AS total_packets
FROM delivery_orders d
JOIN delivery_order_items doi ON doi.delivery_order_id = d.id
JOIN products p ON p.id = doi.product_id
WHERE d.delivery_date = '$today'
GROUP BY p.id
";

$packetSummary = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$totalPackets = 0;
foreach ($packetSummary as $p) {
    $totalPackets += $p['total_packets'];
}


/* ------------------------------
   Route Packet Summary
------------------------------ */

$sql = "
SELECT
    r.name AS route,
    COUNT(DISTINCT d.customer_id) AS customers,
    CONCAT(p.name,' (',p.variant,')') AS product,
    SUM(doi.quantity + doi.added_qty - doi.cancelled_qty) AS packets
FROM delivery_orders d
JOIN delivery_order_items doi ON doi.delivery_order_id = d.id
JOIN products p ON p.id = doi.product_id
JOIN routes r ON r.id = d.route_id
WHERE d.delivery_date = '$today'
GROUP BY r.id, p.id
ORDER BY r.name, p.name
";

$routeSummary = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


/* ------------------------------
   Today's Delivery List
------------------------------ */

$sql = "
SELECT
    c.name AS customer,
    r.name AS route,
    CONCAT(p.name,' (',p.variant,')') AS product,
    (doi.quantity + doi.added_qty - doi.cancelled_qty) AS qty,
    d.status
FROM delivery_orders d
JOIN customers c ON c.id = d.customer_id
JOIN routes r ON r.id = d.route_id
JOIN delivery_order_items doi ON doi.delivery_order_id = d.id
JOIN products p ON p.id = doi.product_id
WHERE d.delivery_date = '$today'
ORDER BY r.name, d.order_no
";

$deliveries = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="max-w-7xl mx-auto px-6 py-6">

<h1 class="text-2xl font-semibold mb-6">Office Staff Dashboard</h1>


<!-- Stats -->

<div class="grid grid-cols-4 gap-6 mb-6">

<div class="bg-white rounded-xl shadow p-5">
<p class="text-sm text-gray-500">Customers</p>
<p class="text-2xl font-bold text-blue-600"><?= $customers ?></p>
</div>

<div class="bg-white rounded-xl shadow p-5">
<p class="text-sm text-gray-500">Routes</p>
<p class="text-2xl font-bold text-green-600"><?= $routes ?></p>
</div>

<div class="bg-white rounded-xl shadow p-5">
<p class="text-sm text-gray-500">Today's Deliveries</p>
<p class="text-2xl font-bold text-yellow-600"><?= $todaysDeliveries ?></p>
</div>

<div class="bg-white rounded-xl shadow p-5">
<p class="text-sm text-gray-500">Completed Today</p>
<p class="text-2xl font-bold text-purple-600"><?= $completedToday ?></p>
</div>

</div>


<!-- Packet Summary -->

<h2 class="text-lg font-semibold mb-3">Today's Packet Summary</h2>

<div class="grid grid-cols-4 gap-4 mb-6">

<?php foreach ($packetSummary as $p): ?>

<div class="bg-white rounded-xl shadow p-4">
<p class="text-sm text-gray-500"><?= htmlspecialchars($p['product_name']) ?></p>
<p class="text-2xl font-bold text-blue-700"><?= $p['total_packets'] ?></p>
</div>

<?php endforeach; ?>

<div class="bg-blue-50 rounded-xl shadow p-4">
<p class="text-sm text-gray-600">Total Packets</p>
<p class="text-2xl font-bold text-blue-800"><?= $totalPackets ?></p>
</div>

</div>


<!-- Route Summary -->

<h2 class="text-lg font-semibold mb-3">Route Summary</h2>

<div class="grid grid-cols-3 gap-4 mb-6">

<?php

$routesGrouped = [];

foreach ($routeSummary as $row) {
    $routesGrouped[$row['route']]['customers'] = $row['customers'];
    $routesGrouped[$row['route']]['products'][] = $row;
}

?>

<?php foreach ($routesGrouped as $routeName => $data): ?>

<div class="bg-white rounded-xl shadow p-4">

<p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($routeName) ?></p>

<p class="text-sm text-gray-600 mb-2">
Customers : <?= $data['customers'] ?>
</p>

<?php 
$total = 0;
foreach ($data['products'] as $p): 
$total += $p['packets'];
?>

<p class="text-lg font-semibold text-green-600">
<?= htmlspecialchars($p['product']) ?> : <?= $p['packets'] ?>
</p>

<?php endforeach; ?>

<p class="text-sm text-gray-700 mt-2">
Total Packets : <strong><?= $total ?></strong>
</p>

</div>

<?php endforeach; ?>

</div>

<!-- Today's Deliveries -->

<h2 class="text-lg font-semibold mb-3">Today's Deliveries</h2>

<div class="bg-white shadow rounded-xl overflow-hidden">

<table class="min-w-full text-sm">

<thead class="bg-gray-100">
<tr>
<th class="px-4 py-2 text-left">Customer</th>
<th class="px-4 py-2 text-left">Route</th>
<th class="px-4 py-2 text-left">Product</th>
<th class="px-4 py-2 text-left">Qty</th>
<th class="px-4 py-2 text-left">Status</th>
</tr>
</thead>

<tbody>

<?php foreach ($deliveries as $d): ?>

<tr class="border-t">

<td class="px-4 py-2"><?= htmlspecialchars($d['customer']) ?></td>

<td class="px-4 py-2"><?= htmlspecialchars($d['route']) ?></td>

<td class="px-4 py-2"><?= htmlspecialchars($d['product']) ?></td>

<td class="px-4 py-2"><?= $d['qty'] ?></td>

<td class="px-4 py-2">
<span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">
<?= $d['status'] ?>
</span>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>