<div class="max-w-6xl mx-auto p-6">

<!-- CUSTOMER INFO -->
<div class="bg-gradient-to-br from-blue-500 to-slate-500 shadow-md rounded-xl p-6 mb-6">
<div class="flex justify-between items-center">

<div>
<h2 class="text-white text-xl font-semibold">
<?= htmlspecialchars($customer['name']) ?>
</h2>

<div class="text-sm text-white/90 mt-1">

<span class="mr-6">
Customer Type :
<?= htmlspecialchars($customer['category_name'] ?? '') ?>
</span>

<span class="mr-6">
📞 <?= htmlspecialchars($customer['mobile']) ?>
</span>

<span>
Route :
<?= htmlspecialchars($customer['route_name']) ?>
(<?= htmlspecialchars($customer['route_type']) ?>)
</span>

</div>
</div>

</div>
</div>


<!-- NORMAL PRODUCTS -->
<div class="bg-white shadow-md rounded-xl p-6 mb-6">

<h3 class="text-lg font-semibold text-gray-700 mb-4">
Normal Products
</h3>

<table class="w-full text-sm">

<thead class="bg-gray-100 text-gray-600">
<tr>
<th class="p-3 text-left">#</th>
<th class="p-3 text-left">Product</th>
<th class="p-3 text-center">Quantity</th>
<th class="p-3 text-center">Actions</th>
</tr>
</thead>

<tbody>

<?php if(!empty($products)): ?>

<?php $i=1; foreach($products as $p): ?>

<tr class="border-b hover:bg-gray-50">

<td class="p-3">
<?= $i++ ?>
</td>

<td class="p-3 font-medium text-gray-800">
<?= htmlspecialchars($p['product_name']) ?>

<div class="text-xs text-gray-500">
(<?= htmlspecialchars($p['variant']) ?>)
</div>
</td>

<td class="p-3 text-center font-semibold">
<?= (int)$p['quantity'] ?>
</td>

<td class="p-3 text-center">

<div class="flex justify-center items-center gap-2">

<button
onclick="openModal('reduce',<?= $p['product_id'] ?>,<?= (int)$p['quantity'] ?>)"
class="bg-red-500 hover:bg-red-600 text-white w-8 h-8 rounded-full"
>
-
</button>

<span class="font-semibold text-gray-700">
<?= (int)$p['quantity'] ?>
</span>

<button
onclick="openModal('add',<?= $p['product_id'] ?>,<?= (int)$p['quantity'] ?>)"
class="bg-green-500 hover:bg-green-600 text-white w-8 h-8 rounded-full"
>
+
</button>

</div>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="4" class="p-4 text-center text-gray-500">
No products assigned to this customer
</td>
</tr>

<?php endif; ?>

</tbody>

</table>


<!-- ADD PRODUCT BUTTON -->
<div class="mt-4 text-right">

<button
onclick="openModal('other',0,1)"
class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow"
>
+ Add Extra Product
</button>

</div>

</div>



<!-- CHANGE REQUEST LIST -->
<div class="bg-white shadow rounded-lg p-5">

<h3 class="text-lg font-semibold mb-4 text-gray-700">
Change Request List
</h3>

<table class="w-full text-sm">

<thead class="bg-gray-100 text-gray-600">
<tr>
<th class="p-3 text-left">#</th>
<th class="p-3 text-left">Date</th>
<th class="p-3 text-left">Product</th>
<th class="p-3 text-left">Requested Qty</th>
<th class="p-3 text-left">Normal Qty</th>
<th class="p-3 text-left">Final Qty</th>
<th class="p-3 text-left">Action</th>
</tr>
</thead>

<tbody>

<?php $i=1; foreach($requests as $r): ?>

<tr class="border-t">

<td class="p-3">
<?= $i++ ?>
</td>

<td>
<?= date('d-m-Y',strtotime($r['request_date'])) ?>
</td>

<td>

<?= htmlspecialchars($r['name']) ?>

<span class="text-xs text-gray-500">
(<?= htmlspecialchars($r['variant']) ?>)
</span>

</td>

<td class="font-semibold <?= $r['requested_qty'] < 0 ? 'text-red-600' : 'text-blue-600' ?>">
<?= $r['requested_qty'] ?>
</td>

<td>
<?= $r['normal_qty'] ?>
</td>

<td class="font-semibold text-green-600">

<?php
$final_qty = $r['normal_qty'] + $r['requested_qty'];

if($final_qty < 0){
$final_qty = 0;
}

echo $final_qty;
?>

</td>

<td>

<a
href="index.php?route=change_request_cancel&id=<?= $r['id'] ?>"
class="text-red-600 hover:underline"
>
Cancel
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>


</div>



<!-- CHANGE MODAL -->

<div id="changeModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center">

<div class="bg-white rounded-lg shadow-lg w-96 p-6">

<h3 class="text-lg font-semibold mb-4">
Change Request
</h3>

<form method="POST" action="index.php?route=change_request_store">

<input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
<input type="hidden" id="modalProduct" name="product_id">
<input type="hidden" id="modalType" name="type">

<div class="mb-4">

<label class="block text-sm text-gray-600 mb-1">
Date
</label>

<input
type="date"
name="date"
id="dateInput"
class="w-full border rounded px-3 py-2"
required
>

</div>


<div class="mb-4">

<label class="block text-sm text-gray-600 mb-1">
Quantity
</label>

<input
type="number"
name="qty"
value="1"
min="1"
class="w-full border rounded px-3 py-2"
required
>

</div>

<div id="productSelectWrapper" class="mb-4 hidden">
    <label class="block text-sm text-gray-600 mb-1">Product</label>

    <select name="product_id" class="w-full border rounded-lg px-3 py-2">
        <option value="">Select Product</option>

        <?php foreach($allProducts as $prod): ?>
            <option value="<?= $prod['id'] ?>">
                <?= htmlspecialchars($prod['name']) ?> (<?= htmlspecialchars($prod['variant']) ?>)
            </option>
        <?php endforeach; ?>

    </select>
</div>
<div class="flex justify-end gap-3">

<button
type="button"
onclick="closeModal()"
class="px-4 py-2 bg-gray-300 rounded"
>
Cancel
</button>

<button
type="submit"
class="px-4 py-2 bg-blue-600 text-white rounded"
>
Save
</button>

</div>

</form>

</div>

</div>



<script>

function openModal(type, productId, qty){

    const modal = document.getElementById('changeModal');
    const productWrapper = document.getElementById('productSelectWrapper');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    document.getElementById('modalType').value = type;

    if(type === 'other'){
        productWrapper.classList.remove('hidden');   
        document.getElementById('modalProduct').value = '';
    } else {
        productWrapper.classList.add('hidden');      
        document.getElementById('modalProduct').value = productId;
    }

    document.querySelector('input[name="qty"]').value = 1;
}
function closeModal(){

document.getElementById('changeModal').classList.add('hidden')
document.getElementById('changeModal').classList.remove('flex')

}

</script>