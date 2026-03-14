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
            <button class="px-4 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded-lg shadow hover:bg-blue-700 transition">
                List View
            </button>

            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Map View
            </button>

            <a href="index.php?route=today_delivery_view&id=<?= $route['id'] ?>"
class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-4 py-2 rounded-lg">
Generate Today Delivery
</a>
        </div>
    </div>

    <!-- TABLE CARD -->
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
        <div class="divide-y">

            <?php foreach ($customers as $customer): ?>

                <div class="grid grid-cols-12 items-center px-6 py-4 hover:bg-blue-50 transition">

                    <!-- ORDER -->
                  <div class="col-span-1 flex items-center gap-3">

    <span class="font-semibold text-gray-800">
        <?= $customer['delivery_order'] ?? 1 ?>
    </span>

    <button
        onclick="openModal(<?= $customer['id'] ?>, <?= $customer['delivery_order'] ?? 1 ?>)"
        class="group relative inline-flex items-center justify-center
               w-8 h-8
               bg-blue-50 text-blue-600
               rounded-full
               hover:bg-blue-100
               transition shadow-sm">

        ✏️

        <!-- Tooltip -->
        <span class="absolute bottom-full mb-2 hidden group-hover:block
                     text-xs bg-gray-800 text-white px-2 py-1 rounded shadow">
            Edit Order
        </span>
    </button>

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
                            <span class="text-gray-400 italic text-sm">
                                No Products
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- MAP -->
                    <div class="col-span-1 text-center">
                        <a href="#"
                           class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700 hover:bg-green-200 transition shadow">
                            📍
                        </a>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>
    </div>
</div>


<!-- MODAL -->
<div id="orderModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

    <div class="bg-white rounded-xl shadow-xl p-6 w-96">

        <h3 class="text-lg font-semibold mb-4">
            Update Route Order
        </h3>

        <form method="POST"
              action="index.php?route=route_order_update&id=<?= $route['id'] ?>">

            <input type="hidden" name="customer_id" id="customerId">

            <div class="mb-4">
                <label class="text-sm text-gray-600">Current Order</label>
                <input type="text"
                       id="currentOrder"
                       class="w-full border rounded-lg px-3 py-2 mt-1 bg-gray-100"
                       readonly>
            </div>

            <div class="mb-4">
                <label class="text-sm text-gray-600">New Order</label>
                <input type="number"
                       name="new_order"
                       class="w-full border rounded-lg px-3 py-2 mt-1"
                       required>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 rounded-lg">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function openModal(id, order) {
    document.getElementById('orderModal').classList.remove('hidden');
    document.getElementById('customerId').value = id;
    document.getElementById('currentOrder').value = order;
}

function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
}
</script>