
<?php if (isset($_SESSION['info'])): ?>
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

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-hidden">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gradient-to-br from-blue-500 to-slate-500 text-white">
                <tr>
                    <th class="px-6 py-3">Order No</th>
                    <th class="px-6 py-3">Customer</th>
                    <th class="px-6 py-3">Product</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">

                <?php if (empty($deliveries)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            No deliveries generated for today.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($deliveries as $delivery): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-blue-600">
                            <?= $delivery['order_no'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= $delivery['name'] ?>
                        </td>
                        <td class="px-6 py-4">
                           <?= htmlspecialchars($delivery['product_name']) ?>
<?php if (!empty($delivery['product_variant'])): ?>
    (<?= htmlspecialchars($delivery['product_variant']) ?>)
<?php endif; ?>
                        </td>
                         <td class="px-6 py-4">
                            <?= $delivery['quantity'] ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                <?= $delivery['status'] == 'pending'
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
            setTimeout(() => flash.remove(), 500); // remove after fade
        }
    }, 2500); // 2.5 seconds
</script>
</div>