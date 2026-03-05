

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
                     <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Mobile</th>
                    <th class="px-4 py-3 text-left">Customer Type</th>
                     <th class="px-4 py-3 text-left">Route</th>
                    <th class="px-4 py-3 text-left">whatsapp</th>
                     <th class="px-4 py-3 text-left">Latitude</th>
                      <th class="px-4 py-3 text-left">Longitude</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = ($currentPage - 1) * $perPage + 1; ?>
                <?php foreach ($customers as $customer): ?>
                    <tr class="border-t">
                        <td class="px-4 py-3 text-gray-500">
<?= $i++ ?>
</td>
                        <td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($customer['name']) ?>
                        </td>

                        <td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($customer['mobile']) ?>
                        </td>

                        <td class="px-4 py-3 font-medium">
                           <?= htmlspecialchars($customer['category_name'] ?? '') ?>
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($customer['route_name'] ?? '') ?>
                           (<?= htmlspecialchars($customer['route_type'] ?? '') ?>)
                       <td class="px-4 py-3 font-medium">
  <?= htmlspecialchars($customer['whatsapp'] ?? '') ?>
</td>
<td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($customer['latitude']) ?>
                        </td>
                        <td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($customer['longitude']) ?>
                        </td>

                        <td class="px-4 py-3 font-medium">
                            <?php if ($customer['status'] == 1): ?>
                                <span class="bg-green-100 text-green-700 
                                             px-2 py-1 rounded-full text-xs">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 
                                             px-2 py-1 rounded-full text-xs">
                                    Inactive
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 flex gap-3 items-center">
    <a href="index.php?route=customers_edit&id=<?= $customer['id'] ?>"
       class="text-red-600 font-medium hover:underline mr-3">
       Edit
    </a>
    <a href="index.php?route=customer_manage&id=<?= $customer['id'] ?>"
     class="text-blue-600 font-medium hover:underline mr-3">
    Product
</a>

    <a href="index.php?route=customers_toggle&id=<?= $customer['id'] ?>"
       class="text-green-600 font-medium hover:underline">
       Toggle
    </a>
</td>

                    </tr>
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

