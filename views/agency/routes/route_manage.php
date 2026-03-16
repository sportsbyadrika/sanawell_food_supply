<div class="max-w-6xl mx-auto mt-8">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">

<div>
<h2 class="text-2xl font-bold text-gray-800">
🚚 Route: <?= htmlspecialchars($route['name']) ?>
(<?= ucfirst($route['type']) ?>)
</h2>

<p class="text-gray-500 text-sm mt-1">
<?= htmlspecialchars($route['description']) ?>
</p>
</div>

<div class="flex gap-3">

<button class="px-4 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded-lg shadow">
List View
</button>

<button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">
Map View
</button>

<a href="index.php?route=today_delivery_view&id=<?= $route['id'] ?>"
class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-4 py-2 rounded-lg">
Generate Today Delivery
</a>

</div>
</div>


<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">

<!-- HEADER ROW -->
<div class="grid grid-cols-12 bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-4 text-sm font-semibold uppercase tracking-wide">

<div class="col-span-1">Order</div>
<div class="col-span-2">Customer</div>
<div class="col-span-3">Address</div>
<div class="col-span-2">Mobile</div>
<div class="col-span-3">Product Details</div>
<div class="col-span-1 text-center">Map</div>

</div>


<!-- BODY -->
<div id="customerList" class="divide-y">

<?php foreach ($customers as $customer): ?>

<div class="grid grid-cols-12 items-center px-6 py-4 hover:bg-blue-50 transition customer-row"
data-id="<?= $customer['id'] ?>">

<!-- ORDER -->
<div class="col-span-1 font-semibold text-gray-700 order-number">
<?= $customer['delivery_order'] ?>
</div>

<!-- CUSTOMER -->
<div class="col-span-2 font-medium text-gray-800">
<?= htmlspecialchars($customer['name']) ?>
</div>

<!-- ADDRESS -->
<div class="col-span-3 text-gray-600 text-sm">
<?= htmlspecialchars($customer['address']) ?>
</div>

<!-- MOBILE -->
<div class="col-span-2 text-gray-700">
<?= htmlspecialchars($customer['mobile']) ?>
</div>

<!-- PRODUCTS -->
<div class="col-span-3">

<?php if (!empty($customer['product_name'])): ?>
<?= $customer['product_name'] ?>
<?php else: ?>
<span class="text-gray-400 italic text-sm">No Products</span>
<?php endif; ?>

</div>

<!-- MAP -->
<div class="col-span-1 text-center">
<a href="#" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700">
📍
</a>
</div>

</div>

<?php endforeach; ?>

</div>
</div>
</div>


<!-- SORTABLE JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>

const list = document.getElementById('customerList');

Sortable.create(list, {

animation: 150,

onEnd: function () {

let rows = document.querySelectorAll('.customer-row');

let orderData = [];

rows.forEach((row, index) => {

let newOrder = index + 1;

row.querySelector('.order-number').innerText = newOrder;

orderData.push({
customer_id: row.dataset.id,
order: newOrder
});

});


fetch("index.php?route=update_route_order_drag", {

method: "POST",
headers: {
"Content-Type": "application/json"
},

body: JSON.stringify({
route_id: <?= $route['id'] ?>,
orders: orderData
})

});

}

});

</script>