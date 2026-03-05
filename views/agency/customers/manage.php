<div class="max-w-6xl mx-auto mt-8">

    <!-- Customer Header -->
    <div class="bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded-xl p-6 shadow-md mb-6">
        <h2 class="text-2xl font-bold"><?= $customer['name'] ?></h2>
        <p class="text-sm mt-1">
            Customer Type: <?= $customer['customer_type_name'] ?? '' ?>
        </p>
         
        <p class="text-sm">
            Default Route: <?= $customer['route_name'] ?? '' ?>
        </p>
        <p class="text-sm mt-1">
            Mobile: <?= $customer['mobile'] ?? '' ?>
        </p>
    </div>


    <!-- Add Product Card -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            Add Product
        </h3>

        <form method="POST" action="index.php?route=customer_product_store">
            <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
<input type="hidden" id="rate" name="rate">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Product Dropdown -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Product
                    </label>
                   <select name="product_id" id="product_id"  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
    <option value="">select</option>
                   <?php foreach ($products as $product): ?>
        <option value="<?= $product['id'] ?>"
                data-rate="<?= $product['rate'] ?>">
            <?= $product['name'] ?> (<?= $product['variant'] ?>)
        </option>
    <?php endforeach; ?>
</select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Quantity
                    </label>
                    <input type="number" step="1" name="quantity"
                        placeholder="Enter qty"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
<div>
    <label  class="block text-sm font-medium text-gray-600 mb-1">Total Amount</label>
    <input type="text" name="total"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
           readonly>
</div>
                <!-- Route -->
               <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Route
                    </label>
                    <select name="route_id" required
    class="w-full border rounded-lg px-2 py-2 text-s  focus:ring-2 focus:ring-blue-500">

    <option value="">Select Route</option>

   <?php foreach ($routes as $route): ?>
    <option value="<?= $route['id'] ?>">
        <?= htmlspecialchars($route['name']) ?>
        (<?= htmlspecialchars($route['type']) ?>)
    </option>
<?php endforeach; ?>

</select>
                </div>

                <!-- Add Button -->
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-gradient-to-br from-blue-500 to-slate-500 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition duration-200">
                        Add
                    </button>
                </div>

            </div>
        </form>
    </div>


    <!-- Selected Products Table -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            Selected Products
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">

                <thead class="bg-gradient-to-br from-blue-500 to-slate-500 text-white">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Rate</th>
                         <th class="px-4 py-3">Total Amount</th>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    <?php if(!empty($customer_products)): ?>
                        <?php $i=1; foreach($customer_products as $cp): ?>
                            <tr class="hover:bg-gray-50 transition">

                                <td class="px-4 py-3"><?= $i++ ?></td>

                                <td class="px-4 py-3 font-medium text-gray-700">
                                   <?= htmlspecialchars($cp['product_name']) ?>
<?= !empty($cp['product_variant']) 
        ? '(' . htmlspecialchars($cp['product_variant']) . ')' 
        : '' ?>
                                </td>

                                <td class="px-4 py-3">
                                   <?= rtrim(rtrim($cp['quantity'], '0'), '.') ?>
                                </td>
<td class="px-4 py-3">₹<?= number_format($cp['rate'], 2) ?></td>
<td class="font-bold text-green-600">
    ₹<?= number_format($cp['total_amount'], 2) ?>
</td>
                                <td class="px-4 py-3">
                                   <?= htmlspecialchars($cp['route_name']) ?>
        (<?= htmlspecialchars($cp['route_type']) ?>)
                                </td>
<td>
    <?php if ($cp['status'] == 1): ?>
        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs"> Active </span>
    <?php else: ?>
        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Inactive </span>
    <?php endif; ?>
</td>
                                
<td class="px-4 py-3">
    <button 
        class="text-blue-600 font-medium hover:underline mr-3"
        onclick="openEditModal(
            <?= $cp['id']; ?>,
            <?= $cp['quantity']; ?>,
            <?= $cp['route_id'] ?? 0; ?>
        )">
        Edit
    </button>

    <?php if ($cp['status'] == 1): ?>
        |
        <a href="index.php?route=customer_product_toggle&id=<?= $cp['id']; ?>" 
            class="text-red-600 font-medium hover:underline mr-3"
           onclick="return confirm('Are you sure?')">
           Deactivate
        </a>
    <?php else: ?>
        |
        <a href="index.php?route=customer_product_toggle&id=<?= $cp['id']; ?>" 
            class="text-green-600 font-medium hover:underline mr-3">
           Activate
        </a>
    <?php endif; ?>
</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">
                                No products added yet
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
            <!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <h3 class="text-lg font-semibold mb-4">Edit Product</h3>

        <form method="POST" action="index.php?route=customer_product_update">
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-3">
                <label class="block text-sm mb-1">Quantity</label>
                <input type="number" step="1" name="quantity" id="edit_quantity"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Route</label>
                <select name="route_id" id="edit_route" class="w-full border rounded px-3 py-2">
    <?php foreach ($routes as $route): ?>
        <option value="<?= $route['id']; ?>">
            <?= htmlspecialchars($route['name']); ?>
            (<?= htmlspecialchars($route['type']); ?>)
        </option>
    <?php endforeach; ?>
</select>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="closeEditModal()"
                        class="px-3 py-2 bg-gray-300 rounded">
                    Cancel
                </button>

                <button type="submit"
                        class="px-3 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function openEditModal(id, quantity, routeId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_route').value = routeId;

    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const productSelect = document.getElementById("product_id");
    const quantityInput = document.querySelector("input[name='quantity']");
    const totalField = document.querySelector("input[name='total']");
    
    function calculateTotal() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const rate = parseFloat(selectedOption.getAttribute("data-rate")) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;

        totalField.value = (rate * quantity).toFixed(2);
    }

    productSelect.addEventListener("change", calculateTotal);
    quantityInput.addEventListener("input", calculateTotal);

});
</script>
        </div>
    </div>

</div>