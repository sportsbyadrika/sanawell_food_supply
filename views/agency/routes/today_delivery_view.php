<?php
$route_id = $_GET['id'] ?? 0;
 if (isset($_SESSION['info'])): ?>
    <div id="flash-message" 
         class="mb-4 rounded-lg bg-blue-100 text-blue-800 px-4 py-3 transition-opacity duration-500">
        <?= $_SESSION['info']; ?>
    </div>
    <?php unset($_SESSION['info']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div id="flash-message" 
         class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3 transition-opacity duration-500">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
<div class="max-w-6xl mx-auto px-6 py-8">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
    🚚 
    <?= htmlspecialchars($route['name']) ?>
    (<?= ucfirst($route['type']) ?>)
    <span class="text-gray-600 text-lg font-normal">
        - Today's Delivery
    </span>
</h2>

<p class="text-gray-500 text-sm mt-1">
    <?= htmlspecialchars($route['description'] ?? '') ?>
</p>
        </div>

        <a href="index.php?route=route_configuration"
           class="px-4 py-2 bg-gray-300 hover:bg-gray-300 rounded-lg text-sm font-medium">
            Back to Routes
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-gray-500 text-sm">Total Orders</p>
            <h2 class="text-2xl font-bold text-blue-600">
                <?= count($deliveries) ?>
            </h2>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-gray-500 text-sm">Pending</p>
            <h2 class="text-2xl font-bold text-yellow-500">
                <?= $pendingCount ?>
            </h2>
        </div>

        <div class="bg-white shadow rounded-xl p-6">
            <p class="text-gray-500 text-sm">Delivered</p>
            <h2 class="text-2xl font-bold text-green-600">
                <?= $deliveredCount ?>
            </h2>
        </div>
    </div>
 <?php
$totalPackets = 0;
$totalAddedPackets = 0;
$totalCancelledPackets = 0;
?>

<div class="flex flex-wrap gap-4">

<?php foreach(($loadSummary ?? []) as $item): ?>

<?php
$productName = strtolower($item['name']);

$colorClass = "bg-gray-100 border-gray-300 text-gray-700";

if(str_contains($productName,"milk")){
    $colorClass = "bg-blue-100 border-blue-300 text-blue-700";
}
elseif(str_contains($productName,"curd")){
    $colorClass = "bg-green-100 border-green-300 text-green-700";
}

$addedQty = $item['added_qty'] ?? 0;
$cancelledQty = $item['cancelled_qty'] ?? 0;
$totalQty = $item['total_qty'] ?? 0;

$finalQty = $totalQty - $cancelledQty;

$totalPackets += $finalQty;
$totalAddedPackets += $addedQty;
$totalCancelledPackets += $cancelledQty;
?>

<div class="border rounded-lg px-5 py-3 min-w-[180px] <?= $colorClass ?>">

<div class="text-lg font-semibold text-green-600">
<?= htmlspecialchars($item['name']) ?> (<?= htmlspecialchars($item['variant']) ?>)
: <?= $finalQty ?>
</div>

<?php if($addedQty > 0): ?>
<div class="text-xs text-green-600">
Added <?= $addedQty ?>
</div>
<?php endif; ?>

<?php if($cancelledQty > 0): ?>
<div class="text-xs text-red-600">
Cancelled <?= $cancelledQty ?>
</div>
<?php endif; ?>

</div>

<?php endforeach; ?>


<!-- Added Packets Card -->

<div class="border rounded-lg px-5 py-3 min-w-[180px] bg-green-100 border-green-300 text-green-700">

<div class="text-xs uppercase font-semibold">
Added Packets
</div>

<div id="addedPackets" class="text-2xl font-bold">
<?= $totalAddedPackets ?>
</div>

</div>


<!-- Cancelled Packets Card -->

<div class="border rounded-lg px-5 py-3 min-w-[180px] bg-red-100 border-red-300 text-red-700">

<div class="text-xs uppercase font-semibold">
Cancelled Packets
</div>

<div id="cancelledPackets" class="text-2xl font-bold">
<?= $totalCancelledPackets ?>
</div>
</div>


<!-- TOTAL CARD -->

<div class="border rounded-lg px-5 py-3 min-w-[180px] bg-indigo-100 border-indigo-300 text-indigo-700">

<div class="text-xs uppercase font-semibold">
Total Packets
</div>

<div id="totalPackets" class="text-3xl font-bold">
<?= $totalPackets ?>
</div>
</div>

</div>
    <!-- Table -->
  <div class="bg-white shadow rounded-xl overflow-hidden">
<table class="min-w-full text-sm text-left">

<thead class="bg-gradient-to-br from-blue-500 to-slate-500 text-white">
<tr>
<th class="px-6 py-3">Order No</th>
<th class="px-6 py-3">Customer</th>
<th class="px-6 py-3">Products(Qty)</th>
<th class="px-6 py-3">Status</th>
</tr>
</thead>

<tbody class="divide-y">

<?php if (empty($deliveries)) : ?>
<tr>
<td colspan="4" class="text-center py-6 text-gray-500">
No deliveries generated for today
</td>
</tr>
<?php endif; ?>

<?php foreach ($deliveries as $delivery) : ?>

<tr class="hover:bg-gray-50">

<td class="px-6 py-4 font-semibold text-blue-600">
<?= $delivery['order_no'] ?>
</td>

<td class="px-6 py-4">
<?= htmlspecialchars($delivery['name']) ?>
</td>

<td class="px-6 py-4">

<?php
$products = explode("||", $delivery['products']);

foreach ($products as $prod) {

$parts = explode("|", $prod);

$name = $parts[0] ?? '';
$qty = isset($parts[1]) ? (int)$parts[1] : 0;
$itemId = isset($parts[2]) ? (int)$parts[2] : 0;
?>

<div style="
border:1px solid #c7d2fe;
background:#eef2ff;
border-radius:12px;
padding:12px;
max-width:320px;
margin-bottom:8px;
">

<div style="
font-weight:600;
color:#2563eb;
margin-bottom:8px;
">

<?= htmlspecialchars($name) ?> 

</div>

<div style="display:flex;align-items:center;gap:10px">

<button
onclick="changeQty(<?= $itemId ?>,-1)"
style="width:32px;height:32px;border-radius:6px;border:1px solid #22c55e;background:#dcfce7;font-weight:bold;">
-
</button>

<div id="qty-<?= $itemId ?>"
style="font-size:20px;font-weight:700;width:35px;text-align:center">
<?= $qty ?>
</div>

<button
onclick="changeQty(<?= $itemId ?>,1)"
style="width:32px;height:32px;border-radius:6px;border:1px solid #22c55e;background:#dcfce7;font-weight:bold;">
+
</button>

<button
onclick="cancelOrder(<?= $delivery['order_no'] ?>)"
style="padding:6px 12px;border-radius:6px;border:1px solid #f59e0b;background:#fef3c7;">
Cancel
</button>

</div>
<?php } ?>

</td>

<td class="px-6 py-4">

<span class="px-3 py-1 rounded-full text-xs font-medium
<?= $delivery['status']=='pending'
? 'bg-yellow-100 text-yellow-700'
: 'bg-green-100 text-green-700' ?>">

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
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500); 
        }
    }, 2500); 
</script>
<script>
    header('Content-Type: application/json');
function changeQty(id, change){

let routeId = <?= $route['id'] ?>;

fetch("index.php?route=updateQty",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"id="+id+"&change="+change+"&route_id="+routeId
})
.then(res=>res.json())
.then(data=>{

document.getElementById("qty-"+id).innerText = data.qty;

updateSummaryCards(data.summary);

});

}
</script>
<script>
    function updateSummaryCards(summary){

let totalPackets = 0;
let addedPackets = 0;
let cancelledPackets = 0;

summary.forEach(item => {

let qty = parseInt(item.total_qty);
let added = parseInt(item.added_qty || 0);
let cancelled = parseInt(item.cancelled_qty || 0);

totalPackets += qty;
addedPackets += added;
cancelledPackets += cancelled;

let id = "product-" + item.name + item.variant;

let el = document.getElementById(id);

if(el){
el.innerText = qty;
}

});

document.getElementById("totalPackets").innerText = totalPackets;
document.getElementById("addedPackets").innerText = addedPackets;
document.getElementById("cancelledPackets").innerText = cancelledPackets;

}
</script>

</div>