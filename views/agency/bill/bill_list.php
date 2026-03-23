<?php
$filter = $_GET['filter'] ?? 'all';
$routes = $routes ?? [];
$selectedRouteId = (int) ($selectedRouteId ?? 0);
$selectedRoute = $selectedRoute ?? null;
$routeHeading = $selectedRoute['name'] ?? 'All Routes';
?>
<div class="max-w-6xl mx-auto p-4 space-y-6">
<div class="p-4 md:p-6">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl p-5 mb-6 shadow-md flex justify-between items-center">
        <div>
            <h2 class="text-white text-xl md:text-2xl font-semibold">Bills (<?= $selectedRoute ? 'Route: ' . htmlspecialchars($routeHeading, ENT_QUOTES, "UTF-8") : 'All Routes' ?>)</h2>
            <p class="text-blue-100 text-sm">Manage and track customer bills</p>
        </div>

        <div class="flex flex-wrap gap-2 items-end">
            <form method="GET" action="index.php" class="flex flex-wrap gap-2 items-end">
                <input type="hidden" name="route" value="bill_list">
                <select name="route_id" class="px-3 py-1.5 rounded-lg text-xs md:text-sm text-gray-700">
                    <option value="0">All Routes</option>
                    <?php foreach ($routes as $route): ?>
                        <option value="<?= (int) $route['id'] ?>" <?= $selectedRouteId === (int) $route['id'] ? 'selected' : '' ?>><?= htmlspecialchars($route['name'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="bg-white text-blue-600 px-3 py-1.5 rounded-lg text-xs md:text-sm">Apply</button>
            </form>
            <a href="index.php?route=bill_list&filter=all"
               class="bg-white/20 text-white px-3 py-1.5 rounded-lg text-xs md:text-sm">
               All
            </a>

            <a href="index.php?route=receipt_entry<?= $selectedRouteId > 0 ? "&route_id=" . $selectedRouteId : "" ?>"
               class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs md:text-sm">
               Pending
            </a>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-400">
            <p class="text-xs text-gray-500">Total</p>
            <h3 class="text-lg font-bold text-orange-500">
                ₹ <?= array_sum(array_column($bills, 'total')) ?>
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-400">
            <p class="text-xs text-gray-500">Collected</p>
            <h3 class="text-lg font-bold text-green-500">
                ₹ <?= array_sum(array_map(fn($b) => $b['total'] - $b['balance'], $bills)) ?>
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-red-400">
            <p class="text-xs text-gray-500">Pending</p>
            <h3 class="text-lg font-bold text-red-500">
                ₹ <?= array_sum(array_column($bills, 'balance')) ?>
            </h3>
        </div>

    </div>

    <!-- ================= MOBILE VIEW ================= -->
    <div class="md:hidden space-y-3">

        <?php foreach ($bills as $bill): ?>
            <div class="bg-white rounded-xl shadow p-4">

                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">
                        BILL-<?= str_pad($bill['id'], 4, '0', STR_PAD_LEFT) ?>
                    </h3>

                    <?php if ($bill['balance'] > 0): ?>
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Pending</span>
                    <?php else: ?>
                        <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">Paid</span>
                    <?php endif; ?>
                </div>

                <p class="text-sm font-medium"><?= $bill['customer_name'] ?></p>
                <p class="text-xs text-gray-500"><?= $bill['mobile'] ?></p>

                <p class="text-xs text-gray-400 mt-1">
                    <?= $bill['bill_from'] ?> → <?= $bill['bill_to'] ?>
                </p>

                <div class="flex justify-between mt-3 text-sm">
                    <span>Total: <b>₹ <?= $bill['total'] ?></b></span>
                    <span class="text-red-500">Bal: ₹ <?= $bill['balance'] ?></span>
                </div>

                <div class="mt-3">
                    <?php if ($bill['balance'] > 0): ?>
                        <a href="index.php?route=receipt_entry&bill_id=<?= $bill['id'] ?><?= $selectedRouteId > 0 ? "&route_id=" . $selectedRouteId : "" ?>"
                           class="block text-center bg-blue-500 text-white py-2 rounded-lg text-sm">
                           Make Payment
                        </a>
                    <?php else: ?>
                        <div class="text-center text-gray-400 text-sm">Completed</div>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>

    </div>

    <!-- ================= DESKTOP TABLE ================= -->
    <div class="hidden md:block bg-white rounded-2xl shadow overflow-hidden">

        <div class="px-4 py-3 border-b font-semibold text-gray-700">
            Bills (<?= $selectedRoute ? 'Route: ' . htmlspecialchars($routeHeading, ENT_QUOTES, 'UTF-8') : 'All Routes' ?>)
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3">Bill No</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Mobile</th>
                        <th class="px-4 py-3">Period</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Balance</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    <?php foreach ($bills as $bill): ?>
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                BILL-<?= str_pad($bill['id'], 4, '0', STR_PAD_LEFT) ?>
                            </td>

                            <td class="px-4 py-3 font-medium">
                                <?= $bill['customer_name'] ?>
                            </td>

                            <td class="px-4 py-3"><?= $bill['mobile'] ?></td>

                            <td class="px-4 py-3 text-xs text-gray-500">
                                <?= $bill['bill_from'] ?> → <?= $bill['bill_to'] ?>
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                ₹ <?= $bill['total'] ?>
                            </td>

                            <td class="px-4 py-3 font-semibold text-red-500">
                                ₹ <?= $bill['balance'] ?>
                            </td>

                            <td class="px-4 py-3">
                                <?php if ($bill['balance'] > 0): ?>
                                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">Pending</span>
                                <?php else: ?>
                                    <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Paid</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <?php if ($bill['balance'] > 0): ?>
                                    <a href="index.php?route=receipt_entry&bill_id=<?= $bill['id'] ?><?= $selectedRouteId > 0 ? "&route_id=" . $selectedRouteId : "" ?>"
                                       class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                       Make Payment
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">Completed</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>

</div>