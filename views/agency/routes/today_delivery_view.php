
<?php if (!empty($_SESSION['success'])): ?>
<div id="flash-message" class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['info'])): ?>
<div id="flash-message" class="bg-blue-100 text-blue-700 px-4 py-2 rounded mb-4">
    <?= $_SESSION['info']; unset($_SESSION['info']); ?>
</div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div id="flash-message" class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>
<?php
$totalPackets = 0;
$totalAddedPackets = 0;
$totalCancelledPackets = 0;
?>

<div class="max-w-7xl mx-auto px-6 py-6">

<!-- HEADER -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold">
            🚚 <?= htmlspecialchars($route['name'] ?? 'Route') ?> - Today's Delivery
        </h2>
        <p class="text-sm text-gray-500">
            <?= htmlspecialchars($route['description'] ?? '') ?>
        </p>
    </div>

    <a href="index.php?route=route_configuration_manage&id=<?= $route['id'] ?>"
       class="bg-gray-200 px-4 py-2 rounded-lg text-sm">
        Back to Routes
    </a>
</div>

<!-- TRIP CONTROLS -->
<div class="bg-white shadow rounded-xl p-4 mb-6">
<form method="POST" action="index.php?route=generate_delivery">

<div class="flex items-center gap-4 flex-wrap">

    <input type="hidden" name="route_id" value="<?= $route['id'] ?>">

    <!-- TRIP DATE -->
    <div>
        <label class="text-s-bold text-gray-500">Trip Date</label>
        <input type="date"
               name="trip_date"
               value="<?= date('Y-m-d') ?>"
               class="border rounded px-3 py-2 text-sm">
    </div>

    <!-- TRIP START TIME -->
   <div class="flex items-center gap-2 border rounded-lg px-3 py-2 bg-white shadow-sm">
<label class="text-s-bold text-gray-500">Trip Start Time</label>
    <span class="text-gray-400">⏰</span>

    <select id="tripHour" class="outline-none bg-transparent">
        <?php for($i=1;$i<=12;$i++): ?>
            <option value="<?= $i ?>"><?= $i ?></option>
        <?php endfor; ?>
    </select>

    :

    <select id="tripMinute" class="outline-none bg-transparent">
        <?php for($i=0;$i<60;$i++): ?>
            <option value="<?= str_pad($i,2,'0',STR_PAD_LEFT) ?>">
                <?= str_pad($i,2,'0',STR_PAD_LEFT) ?>
            </option>
        <?php endfor; ?>
    </select>

    <select id="tripAmPm" class="outline-none bg-transparent">
        <option value="AM">AM</option>
        <option value="PM">PM</option>
    </select>
<input type="hidden" name="trip_start_time" id="tripStartTime">
</div>
    <!-- DRIVER -->
    <div>
        <label class="text-s-bold text-gray-500">Driver</label>
       <select name="driver_id" class="border rounded px-3 py-2 text-sm">

    <option value="">Select Driver</option>

    <?php foreach ($drivers ?? [] as $driver): ?>
        <option value="<?= $driver['id']; ?>"
            <?= ($driver['id'] == $selected_driver) ? 'selected' : ''; ?>>
            
            <?= htmlspecialchars($driver['name']); ?>

        </option>
    <?php endforeach; ?>

</select>
    </div>

    <!-- VEHICLE -->
    <div>
        <label class="text-s-bold text-gray-500">Vehicle</label>
       <select name="vehicle_no" class="border rounded px-3 py-2 text-sm">

    <option value="">Select Vehicle</option>

    <?php foreach ($vehicles ?? [] as $vehicle): ?>
        <option value="<?= $vehicle['id']; ?>"
            <?= ($vehicle['id'] == $selected_vehicle) ? 'selected' : ''; ?>>
            
            <?= $vehicle['vehicle_no']; ?>

        </option>
    <?php endforeach; ?>

</select>
    </div>

    <div class="pt-5">
            <button type="submit"
            class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-5 py-2 rounded-lg text-sm">
            Generate Delivery
        </button>
            
    </div>

</div>

</form>
</div>

<!-- MAIN GRID -->
<div class="grid grid-cols-12 gap-6">

<!-- LEFT SIDE : PRODUCT SUMMARY -->
<div class="col-span-3">

<div class="grid grid-cols-2 gap-3">

<?php
$cardColors = [
"bg-blue-100 border-blue-300 text-blue-800",
"bg-green-100 border-green-300 text-green-800",
"bg-purple-100 border-purple-300 text-purple-800",
"bg-orange-100 border-orange-300 text-orange-800",
"bg-pink-100 border-pink-300 text-pink-800"
];

$colorIndex = 0;
?>

<?php foreach ($loadSummary ?? [] as $item): ?>

<?php

$colorClass = $cardColors[$colorIndex % count($cardColors)];
$colorIndex++;

$addedQty = $item['added_qty'] ?? 0;
$cancelledQty = $item['cancelled_qty'] ?? 0;
$totalQty = $item['total_qty'] ?? 0;

$finalQty = $totalQty;

$totalPackets += $finalQty;
$totalAddedPackets += $addedQty;
$totalCancelledPackets += $cancelledQty;

?>

<div class="border rounded-lg px-3 py-2 <?= $colorClass ?>">

<div class="text-xs font-medium">
<?= htmlspecialchars($item['name']) ?>
</div>

<div class="text-[11px] opacity-70">
<?= htmlspecialchars($item['variant']) ?>
</div>

<div class="text-lg font-bold mt-1">
<?= $finalQty ?>
</div>

</div>

<?php endforeach; ?>

<!-- ADDED -->
<div class="border rounded-lg px-3 py-2 bg-green-100">
<div class="text-xs">ADDED PACKETS</div>
<div class="text-lg font-bold"><?= $totalAddedPackets ?></div>
</div>

<!-- CANCELLED -->
<div class="border rounded-lg px-3 py-2 bg-red-100">
<div class="text-xs">CANCELLED PACKETS</div>
<div class="text-lg font-bold"><?= $totalCancelledPackets ?></div>
</div>

<!-- TOTAL -->
<div class="border rounded-lg px-3 py-2 bg-purple-100">
<div class="text-xs">TOTAL PACKETS</div>
<div class="text-lg font-bold"><?= $totalPackets ?></div>
</div>

</div>
</div>

<!-- RIGHT SIDE : DELIVERY TABLE -->
<div class="col-span-9">

<div class="bg-white shadow rounded-xl overflow-hidden">

<table class="w-full text-sm">

<thead class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white">
<tr>
<th class="px-4 py-3 text-left">Order No</th>
<th class="px-4 py-3 text-left">Customer</th>
<th class="px-4 py-3 text-left">Mobile</th>
<th class="px-4 py-3 text-left">Products (Qty)</th>
<th class="px-4 py-3 text-left">Status</th>
</tr>
</thead>

<tbody>

<?php foreach ($deliveries ?? [] as $delivery): ?>

<tr class="border-b">

<td class="px-4 py-3">
<?= $delivery['order_no'] ?>
</td>

<td class="px-4 py-3 font-medium">
<?= htmlspecialchars($delivery['name']) ?>
</td>

<td class="px-4 py-3">
<?= htmlspecialchars($delivery['mobile']) ?>
</td>

<td class="px-4 py-3">

<?php
$products = explode('||', $delivery['products']);
?>

<?php foreach ($products as $product): ?>

<?php
$parts = explode('|', $product);

$name = $parts[0] ?? '';
$variant = $parts[1] ?? '';
$qtyParts = explode(',', $parts[2] ?? '');

$normalQty = $qtyParts[0] ?? 0;
$extraQty  = $qtyParts[1] ?? 0;
$totalQty  = $qtyParts[2] ?? 0;
?>

<div class="bg-gray-100 rounded-lg px-3 py-2 mb-1 inline-block">

<div class="text-xs font-medium text-blue-700">
<?= htmlspecialchars($name) ?>
(<?= htmlspecialchars($variant) ?>)
</div>

<div class="text-xs text-gray-500">
<div class="text-xs text-gray-600">
Normal : <?= $normalQty ?>
</div>

<?php if($extraQty > 0): ?>
<div class="text-xs text-green-600">
Extra : +<?= $extraQty ?>
</div>
<?php endif; ?>

<div class="text-xs font-semibold">
Total : <?= $totalQty ?>
</div>
</div>

</div>

<?php endforeach; ?>

</td>

<td class="px-4 py-3">

<span class="px-3 py-1 text-xs rounded-full
<?= $delivery['status'] === 'delivered'
? 'bg-green-100 text-green-700'
: 'bg-yellow-100 text-yellow-700'
?>">

<?= ucfirst($delivery['status']) ?>

</span>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>
<script>
setTimeout(function () {
    const flash = document.getElementById('flash-message');
    if (flash) {
        flash.style.transition = "opacity 0.5s";
        flash.style.opacity = "0";
        setTimeout(() => flash.remove(), 500);
    }
}, 3000); // 3 seconds
</script>
<script>

function updateTripTime() {

    let hour = document.getElementById("tripHour").value;
    let minute = document.getElementById("tripMinute").value;
    let ampm = document.getElementById("tripAmPm").value;

    hour = parseInt(hour);

    if(ampm === "PM" && hour !== 12) hour += 12;
    if(ampm === "AM" && hour === 12) hour = 0;

    hour = hour.toString().padStart(2,'0');

    document.getElementById("tripStartTime").value = hour + ":" + minute;
}

document.querySelectorAll("#tripHour,#tripMinute,#tripAmPm")
.forEach(el => el.addEventListener("change", updateTripTime));

updateTripTime();

</script>

</div>

</div>


</div>