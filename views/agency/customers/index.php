

<div class="max-w-6xl mx-auto p-6">
<div class="flex justify-between items-center mb-6">
<form method="GET" action="index.php">
    <input type="hidden" name="route" value="customers">

    <input type="text" name="name"
        value="<?= htmlspecialchars($name ?? '') ?>"
        placeholder="Search by name"
        class="border rounded-lg px-3 py-2">

    <select name="type" class="border rounded-lg px-3 py-2">
        <option value="">All Types</option>
        <?php foreach ($types as $type): ?>
            <option value="<?= $type['id'] ?>"
                <?= ($typeId ?? '') == $type['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($type['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- FIXED HERE -->
    <select name="route_id" class="border rounded-lg px-3 py-2">
        <option value="">All Routes</option>
        <?php foreach ($routes as $route): ?>
            <option value="<?= $route['id'] ?>"
                <?= ($routeId ?? '') == $route['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($route['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit"
        class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-4 py-2 rounded-lg">
        Search
    </button>

    <a href="index.php?route=customers"
       class="bg-gray-300 px-4 py-2 rounded-lg">
       Reset
    </a>
</form>

<a href="index.php?route=agency_customers_import"
class="bg-green-600 text-white px-4 py-2 rounded">
Import Customers
</a>  
            

 <a href="index.php?route=customers_create"
   class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
   + Add Customer
</a>

            </div>

 <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

        <div class="bg-gradient-to-br from-blue-500 to-slate-500 
                    text-white px-6 py-3 font-semibold">
            Customer List
        </div>

        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-700">
<tr>
<th class="px-4 py-3 text-left">Customer</th>
<th class="px-4 py-3 text-left">Contact</th>
<th class="px-4 py-3 text-left">Route</th>
<th class="px-4 py-3 text-left">Location</th>
<th class="px-4 py-3 text-left">Status</th>
<th class="px-4 py-3 text-left">Action</th>
</tr>
</thead>
            <tbody>

<?php $i = ($currentPage - 1) * $perPage + 1; ?>

<?php foreach ($customers as $customer): ?>

<tr class="border-b hover:bg-gray-50">

<!-- CUSTOMER -->
<td class="px-4 py-3">

<div class="bg-gray-50 p-3 rounded-lg">

<div class="font-semibold text-gray-800">
<?= htmlspecialchars($customer['name']) ?>
</div>

<div class="text-sm text-gray-500">
Type : <?= htmlspecialchars($customer['category_name'] ?? '') ?>
</div>

</div>

</td>


<!-- CONTACT -->
<td class="px-4 py-3">

<div class="bg-gray-50 p-3 rounded-lg text-sm">

<div>
📞 <span class="text-gray-500">Mobile :</span>
<?= htmlspecialchars($customer['mobile']) ?>
</div>

<div>
💬 <span class="text-gray-500">WhatsApp :</span>
<?= htmlspecialchars($customer['whatsapp']) ?>
</div>

</div>

</td>


<!-- ROUTE -->
<td class="px-4 py-3">

<div class="bg-gray-50 p-3 rounded-lg text-sm">

<div>
🚚 <?= htmlspecialchars($customer['route_name'] ?? '') ?>
</div>

<div class="text-gray-500">
Shift : <?= htmlspecialchars($customer['route_type'] ?? '') ?>
</div>

</div>

</td>


<!-- LOCATION -->
<td class="px-4 py-3">

<div class="bg-gray-50 p-3 rounded-lg text-center">

<?php if($customer['latitude'] && $customer['longitude']): ?>

<a
href="https://maps.google.com/?q=<?= $customer['latitude'] ?>,<?= $customer['longitude'] ?>"
target="_blank"
class="text-blue-600 hover:underline"
>
📍 View Map
</a>

<?php else: ?>

<span class="text-gray-400 text-sm">No Location</span>

<?php endif; ?>

</div>

</td>


<!-- STATUS -->
<td class="px-4 py-3">

<?php if ($customer['status'] == 1): ?>

<button class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
Active
</button>

<?php else: ?>

<button class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
Inactive
</button>

<?php endif; ?>

</td>


<!-- ACTION -->
<td class="px-4 py-3">
<div class="flex items-center gap-4 text-sm">

<a href="index.php?route=customers_edit&id=<?= $customer['id'] ?>"
class="flex items-center gap-1 text-green-600 hover:text-green-800">
<span>✏</span>
<span>Edit</span>
</a>

<a href="index.php?route=customer_manage&id=<?= $customer['id'] ?>"
class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
<span>📦</span>
<span>Product</span>
</a>

<a href="index.php?route=change_request&customer_id=<?= $customer['id'] ?>"
class="flex items-center gap-1 text-orange-600 hover:text-orange-800">
<span>🔁</span>
<span>Change</span>
</a>

</div>
</td>

</tr>

<?php $i++; ?>
<?php endforeach; ?>

</tbody>
        </table>
<?php if ($totalPages > 1): ?>
    <div class="mt-6 flex justify-center items-center gap-2">

        <!-- Previous Button -->
        <?php if ($currentPage > 1): ?>
            <a href="index.php?route=customers&page=<?= $currentPage - 1 ?>"
               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                << Previous
            </a>
            <?php else: ?>
<span class="opacity-50 cursor-not-allowed"><< Previous</span>
        <?php endif; ?>

        <!-- Page Numbers -->
       <?php
$range = 3;
$start = max(1, $currentPage - $range);
$end = min($totalPages, $currentPage + $range);
?>

<?php if ($start > 1): ?>
<a href="index.php?route=customers&page=1"
class="px-3 py-1 bg-gray-200 rounded">1</a>

<?php if ($start > 2): ?>
<span class="px-2">...</span>
<?php endif; ?>
<?php endif; ?>

<?php for ($i = $start; $i <= $end; $i++): ?>
<a href="index.php?route=customers&page=<?= $i ?>"
class="px-3 py-1 rounded <?= $i == $currentPage
? 'bg-gradient-to-br from-blue-500 to-slate-500 text-white'
: 'bg-gray-200 hover:bg-gray-300' ?>">
<?= $i ?>
</a>
<?php endfor; ?>

<?php if ($end < $totalPages): ?>

<?php if ($end < $totalPages - 1): ?>
<span class="px-2">...</span>
<?php endif; ?>

<a href="index.php?route=customers&page=<?= $totalPages ?>"
class="px-3 py-1 bg-gray-200 rounded">
<?= $totalPages ?>
</a>

<?php endif; ?>

        <!-- Next Button -->
        <?php if ($currentPage < $totalPages): ?>
            <a href="index.php?route=customers&page=<?= $currentPage + 1 ?>"
               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                Next >>
            </a>
            <?php else: ?>
<span class="opacity-50 cursor-not-allowed">Next >></span>
        <?php endif; ?>

    </div>
<?php endif; ?>
    </div>
                            </div>
                            </div>
                            </div>

