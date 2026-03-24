
<div class="max-w-4xl mx-auto px-4 py-4">
<div class="flex gap-3 mb-4 text-sm">

<?php
$totalAssigned = count($deliveries);
$deliveredCount = count(array_filter($deliveries, fn($d)=>$d['status']=='delivered'));
$failedCount = count(array_filter($deliveries, fn($d)=>$d['status']=='not_delivered'));
$pendingCount = max(0, $totalAssigned - ($deliveredCount + $failedCount));
?>
<div class="bg-yellow-100 px-3 py-1 rounded">
Pending: <?= $pendingCount ?>
</div>

<div class="bg-green-100 px-3 py-1 rounded">
Delivered: <?= $deliveredCount ?>
</div>

<div class="bg-red-100 px-3 py-1 rounded">
Failed: <?= $failedCount ?>
</div>

</div>
<h1 class="text-xl font-bold mb-4">Today's Deliveries</h1>

<?php $deliveries = $deliveries ?? []; ?>


<!-- ================= PENDING ================= -->



<div class="space-y-4">

<?php foreach ($deliveries as $delivery): ?>
<?php if ($delivery['status'] == 'pending'): ?>

<div class="bg-white shadow rounded-xl p-4 border">

<div class="flex justify-between items-start">

<div>
<div class="font-semibold text-gray-800">
#<?= $delivery['order_no'] ?> - <?= $delivery['name'] ?>
</div>

<div class="text-xs text-gray-500 mt-1">
<?= $delivery['address'] ?>
</div>
</div>

<span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">
Pending
</span>

</div>


<!-- PRODUCTS -->

<div class="mt-2">

<?php if(!empty($delivery['products'])): ?>

    <?php foreach ($delivery['products'] as $product): ?>

        <div class="bg-blue-50 text-green-700 px-2 py-1 rounded inline-block text-sm font-medium mr-2 mb-1">
            <?= htmlspecialchars($product['name']) ?>
            (<?= htmlspecialchars($product['variant']) ?>)
            x<?= $product['qty'] ?>
        </div>

    <?php endforeach; ?>

<?php else: ?>

<span class="text-muted">No products</span>

<?php endif; ?>

</div>
<!-- PHONE + MAP -->

<div class="flex justify-between items-center mt-3">

<a href="tel:<?= $delivery['mobile'] ?>"
class="text-blue-600 text-sm font-medium">

📞 <?= $delivery['mobile'] ?>

</a>

<a target="_blank"
href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($delivery['address']) ?>"
class="text-blue-600 text-sm">

📍 Map

</a>

</div>


<!-- ACTION BUTTONS -->

<div class="flex gap-2 mt-3">

<form method="POST" action="index.php?route=update_delivery_status" class="flex-1">
    <input type="hidden" name="order_id" value="<?= $delivery['id'] ?>">
    <input type="hidden" name="route_id" value="<?= $route_id ?>">
    <input type="hidden" name="status" value="delivered">

    <button type="submit"
        class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg">
        Delivered
    </button>
</form>
<button 
    onclick="openReasonModal(<?= $delivery['id'] ?>)"
    class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg">
    Not Delivered
</button>
</div>

</div>

<?php endif; ?>
<?php endforeach; ?>

</div>



<!-- ================= DELIVERED ================= -->

<h2 class="text-lg font-semibold mt-6 mb-2">Delivered Customers</h2>

<div class="space-y-3">

<?php foreach ($deliveries as $delivery): ?>
<?php if ($delivery['status'] == 'delivered'): ?>

<div class="bg-green-50 border border-green-200 p-3 rounded-lg">

<div class="flex justify-between">

<div>
<div class="font-semibold">
#<?= $delivery['order_no'] ?> - <?= $delivery['name'] ?>
</div>

<div class="text-xs text-gray-500">
<?= $delivery['address'] ?>
</div>
</div>

<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
Delivered
</span>

</div>

</div>

<?php endif; ?>
<?php endforeach; ?>

</div>



<!-- ================= FAILED ================= -->

<h2 class="text-lg font-semibold mt-6 mb-2">Not Delivered</h2>

<div class="space-y-3">

<?php foreach ($deliveries as $delivery): ?>
<?php if ($delivery['status'] == 'not_delivered'): ?>

<div class="bg-red-50 border border-red-200 p-3 rounded-lg">

<div class="flex justify-between">

<div>
<div class="font-semibold">
#<?= $delivery['order_no'] ?> - <?= $delivery['name'] ?>
</div>

<div class="text-xs text-gray-500">
<?= $delivery['address'] ?>
</div>
</div>

<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
Not Delivered
</span>

</div>

</div>

<?php endif; ?>
<?php endforeach; ?>

</div>


</div>



<!-- ================= MODAL ================= -->

<div id="reasonModal"
class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

<div class="bg-white p-6 rounded-lg w-80">

<h3 class="font-semibold mb-3">Reason for Not Delivery</h3>

<form method="POST"
action="index.php?route=driver_save_not_delivered">

<input type="hidden" name="order_id" id="order_id">
<input type="hidden" name="route_id" value="<?= $route_id ?>">
<input type="hidden" name="status" value="not_delivered">

<select name="reason" class="w-full border p-2 mb-3 rounded">

<option value="customer_not_home">Customer Not Home</option>
<option value="customer_cancelled">Customer Cancelled</option>
<option value="address_issue">Address Issue</option>
<option value="payment_issue">Payment Issue</option>

</select>

<textarea
name="remarks"
placeholder="Remarks"
class="w-full border p-2 mb-3 rounded"></textarea>

<div class="flex justify-end gap-2">

<button
type="button"
onclick="closeReasonModal()"
class="px-3 py-1 bg-gray-300 rounded">

Cancel

</button>

<button
type="submit"
class="px-3 py-1 bg-blue-600 text-white rounded">
Save
</button>

</div>

</form>

</div>
<script>

function openReasonModal(orderId){
    document.getElementById('order_id').value = orderId;
    document.getElementById('reasonModal').classList.remove('hidden');
}

function closeReasonModal()
{
document.getElementById('reasonModal').classList.add('hidden');
}

</script>

</div>

