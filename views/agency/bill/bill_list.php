<?php
$routes = $routes ?? [];
$bills = $bills ?? [];
$selectedRouteId = (int) ($selectedRouteId ?? ($selected_route ?? 0));
$selectedRouteName = $selected_route_name ?? (($selectedRoute['name'] ?? null) ?: 'All Routes');
$totalAmount = array_sum(array_map(static fn ($bill) => (float) ($bill['total'] ?? 0), $bills));
$totalCollection = array_sum(array_map(static fn ($bill) => (float) ($bill['total'] ?? 0) - (float) ($bill['balance'] ?? 0), $bills));
$totalBalance = array_sum(array_map(static fn ($bill) => (float) ($bill['balance'] ?? 0), $bills));
?>
<div class="max-w-6xl mx-auto p-4 space-y-6">
    <div class="p-4 md:p-6">
        <div class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 rounded-2xl p-5 mb-6 shadow-md flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-white text-xl md:text-2xl font-semibold">
                    Bills (<?= $selectedRouteId > 0 ? htmlspecialchars($selectedRouteName, ENT_QUOTES, 'UTF-8') : 'All Routes' ?>)
                </h2>
                <p class="text-blue-100 text-sm">Manage and track customer bills within the current route context.</p>
            </div>

            <div class="flex flex-wrap gap-2 items-end">
                <form method="GET" action="index.php" class="flex flex-wrap gap-2 items-end">
                    <input type="hidden" name="route" value="bill_list">
                    <select name="route_id" class="px-3 py-1.5 rounded-lg text-xs md:text-sm text-gray-700">
                        <option value="0">All Routes</option>
                        <?php foreach ($routes as $route): ?>
                            <option value="<?= (int) $route['id'] ?>" <?= $selectedRouteId === (int) $route['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($route['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="bg-white text-blue-600 px-3 py-1.5 rounded-lg text-xs md:text-sm">Apply</button>
                </form>

                <a href="index.php?route=bill_list" class="bg-white/20 text-white px-3 py-1.5 rounded-lg text-xs md:text-sm">Reset</a>
                <a href="index.php?route=receipt_entry<?= $selectedRouteId > 0 ? '&route_id=' . $selectedRouteId : '' ?>"
                   class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs md:text-sm">
                    Pending Bills
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-orange-400">
                <p class="text-xs text-gray-500">Total</p>
                <h3 class="text-lg font-bold text-orange-500">₹ <?= number_format($totalAmount, 2) ?></h3>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-400">
                <p class="text-xs text-gray-500">Collected</p>
                <h3 class="text-lg font-bold text-green-500">₹ <?= number_format($totalCollection, 2) ?></h3>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border-l-4 border-red-400">
                <p class="text-xs text-gray-500">Pending</p>
                <h3 class="text-lg font-bold text-red-500">₹ <?= number_format($totalBalance, 2) ?></h3>
            </div>
        </div>

        <div class="md:hidden space-y-3">
            <?php if (!empty($bills)): ?>
                <?php foreach ($bills as $bill): ?>
                    <?php $isPending = (float) $bill['balance'] > 0; ?>
                    <div class="bg-white rounded-xl shadow p-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-semibold text-gray-700">BILL-<?= str_pad((string) $bill['id'], 4, '0', STR_PAD_LEFT) ?></h3>
                            <?php if ($isPending): ?>
                                <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Pending</span>
                            <?php else: ?>
                                <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">Paid</span>
                            <?php endif; ?>
                        </div>

                        <p class="text-sm font-medium"><?= htmlspecialchars($bill['customer_name'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($bill['mobile'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($bill['bill_from'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($bill['bill_to'], ENT_QUOTES, 'UTF-8') ?></p>

                        <div class="flex justify-between mt-3 text-sm">
                            <span>Total: <b>₹ <?= number_format((float) $bill['total'], 2) ?></b></span>
                            <span class="text-red-500">Bal: ₹ <?= number_format((float) $bill['balance'], 2) ?></span>
                        </div>

                        <div class="mt-3">
                            <?php if ($isPending): ?>
                                <a href="index.php?route=receipt_entry&bill_id=<?= (int) $bill['id'] ?>&route_id=<?= (int) ($selectedRouteId > 0 ? $selectedRouteId : ($bill['route_id'] ?? 0)) ?>"
                                   class="block text-center bg-blue-500 text-white py-2 rounded-lg text-sm">
                                    Make Payment
                                </a>
                            <?php else: ?>
                                <div class="text-center text-gray-400 text-sm">Completed</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow p-4 text-center text-gray-500">No bills found for the selected route.</div>
            <?php endif; ?>
        </div>

        <div class="hidden md:block bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-4 py-3 border-b font-semibold text-gray-700">
                Bills (<?= $selectedRouteId > 0 ? htmlspecialchars($selectedRouteName, ENT_QUOTES, 'UTF-8') : 'All Routes' ?>)
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3">Bill No</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Mobile</th>
                            <th class="px-4 py-3">Route</th>
                            <th class="px-4 py-3">Period</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Balance</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if (!empty($bills)): ?>
                            <?php foreach ($bills as $bill): ?>
                                <?php $isPending = (float) $bill['balance'] > 0; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">BILL-<?= str_pad((string) $bill['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($bill['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($bill['mobile'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars((string) ($bill['route_name'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-4 py-3 text-xs text-gray-500"><?= htmlspecialchars($bill['bill_from'], ENT_QUOTES, 'UTF-8') ?> → <?= htmlspecialchars($bill['bill_to'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="px-4 py-3 font-semibold">₹ <?= number_format((float) $bill['total'], 2) ?></td>
                                    <td class="px-4 py-3 font-semibold <?= $isPending ? 'text-red-500' : 'text-green-600' ?>">₹ <?= number_format((float) $bill['balance'], 2) ?></td>
                                    <td class="px-4 py-3">
                                        <?php if ($isPending): ?>
                                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">Pending</span>
                                        <?php else: ?>
                                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Paid</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <?php if ($isPending): ?>
                                            <a href="index.php?route=receipt_entry&bill_id=<?= (int) $bill['id'] ?>&route_id=<?= (int) ($selectedRouteId > 0 ? $selectedRouteId : ($bill['route_id'] ?? 0)) ?>"
                                               class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                                Make Payment
                                            </a>
                                        <?php else: ?>
                                            <span class="text-gray-400 text-xs">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-4 py-5 text-center text-gray-500">No bills found for the selected route.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
