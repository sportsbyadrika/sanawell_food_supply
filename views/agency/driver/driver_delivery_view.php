<div class="max-w-3xl mx-auto px-3">

<h2 class="text-xl font-semibold mb-4">
Today's Deliveries
</h2>

<?php foreach ($deliveries as $delivery): ?>

<?php
$id = $delivery['id'];
$status = $delivery['status'] ?? 'pending';
?>

<div class="bg-white rounded-xl shadow-md p-4 mb-4 border border-gray-200">

<!-- HEADER -->
<div class="flex justify-between items-start mb-2">

<div class="font-semibold text-base md:text-lg">
#<?= $delivery['order_no'] ?> <?= $delivery['name'] ?>
</div>

<div>
<?php if($status=='pending'): ?>
<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
Pending
</span>
<?php elseif($status=='delivered'): ?>
<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
Delivered
</span>
<?php else: ?>
<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
Not Delivered
</span>
<?php endif; ?>
</div>

</div>


<!-- PHONE -->
<div class="text-sm text-gray-600 mb-1">

<a href="tel:<?= $delivery['mobile'] ?>" class="flex items-center gap-2 text-blue-600">

<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
fill="none" viewBox="0 0 24 24" stroke="currentColor">

<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M3 5a2 2 0 012-2h2l2 5-2 2a11 11 0 005 5l2-2 5 2v2a2 2 0 01-2 2h-1C9.163 20 4 14.837 4 8V7a2 2 0 01-1-2z" />

</svg>

<?= $delivery['mobile'] ?>

</a>

</div>


<!-- ADDRESS + MAP -->
<div class="text-sm text-gray-700 mb-2 flex items-start gap-2">

<div class="flex-1">

<span class="flex items-start gap-2">

<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-1 text-red-500"
fill="none" viewBox="0 0 24 24" stroke="currentColor">

<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />

<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />

</svg>

<?= $delivery['address'] ?>

</span>

</div>

<!-- MAP ICON -->

<a target="_blank"
href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($delivery['address']) ?>"
class="text-blue-600">

<svg xmlns="http://www.w3.org/2000/svg"
class="w-5 h-5"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor">

<path stroke-linecap="round"
stroke-linejoin="round"
stroke-width="2"
d="M9 20l-5.447-2.724A1 1 0 013 16.382V4.618a1 1 0 011.553-.832L9 6m0 14l6-3m-6 3V6m6 11l5.447 2.724A1 1 0 0021 18.382V6.618a1 1 0 00-1.553-.832L15 9m0 8V9m0 0L9 6" />

</svg>

</a>

</div>


<!-- PRODUCT -->
<div class="text-sm text-gray-800 mb-1">
<?= $delivery['product_name'] ?> (<?= $delivery['variant'] ?>)
</div>


<!-- QTY -->
<div class="font-semibold text-sm mb-3">
Qty: <?= $delivery['quantity'] ?>
</div>


<!-- ACTION BUTTONS -->

<?php if($status=='pending'): ?>

<div class="flex gap-2">

<a class="flex-1 text-center bg-emerald-500 hover:bg-emerald-300 text-white py-3 rounded-md text-sm font-medium transition"
href="index.php?route=driver_mark_delivered&id=<?=$id?>&route_id=<?=$route_id?>">
Delivered
</a>

<a class="flex-1 text-center bg-rose-500 hover:bg-rose-300 text-white py-2 rounded-md text-sm font-medium transition"
href="index.php?route=driver_not_delivered&id=<?=$id?>&route_id=<?=$route_id?>">
Not Delivered
</a>
</div>

<?php else: ?>

<div class="text-green-600 font-semibold text-sm">
Completed
</div>

<?php endif; ?>


</div>

<?php endforeach; ?>

</div>