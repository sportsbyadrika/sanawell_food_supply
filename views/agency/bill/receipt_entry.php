<?php
$summary = $summary ?? ['total_demand' => 0, 'total_collection' => 0, 'balance' => 0];
$routes = $routes ?? [];
$route_id = (int) ($route_id ?? ($selected_route ?? 0));
$selectedRouteName = $selected_route_name ?? (($selectedRoute['name'] ?? null) ?: 'All Routes');
$selectedBillId = (int) ($selectedBillId ?? 0);
$pendingBills = $pendingBills ?? [];
$bill = $bill ?? null;
$receipts = $receipts ?? [];
$formDefaults = $formDefaults ?? [];
$search = $search ?? '';
$error = $error ?? '';
$success = $success ?? '';
$canSubmitReceipt = $canSubmitReceipt ?? false;

$pendingBalance = (float) ($summary['balance'] ?? 0);
$pendingBorderClass = $pendingBalance > 0 ? 'border-red-500' : 'border-green-500';
$pendingTextClass = $pendingBalance > 0 ? 'text-red-600' : 'text-green-600';
$routeHeading = $route_id > 0 ? $selectedRouteName : 'All Routes';
?>

<div class="max-w-7xl mx-auto mt-6 space-y-6 px-4">
    <div class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white p-5 rounded-xl shadow flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-xl font-semibold">Pending Bills - <?= htmlspecialchars($routeHeading, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-sm opacity-90">Search filters list. Click Select to load form.</p>
        </div>
        <a href="index.php?route=bill_list<?= $route_id > 0 ? '&route_id=' . $route_id : '' ?>" class="bg-white/20 px-4 py-2 rounded-lg hover:bg-white/30 text-center">Back to Bills</a>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-orange-500">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-xl font-bold text-orange-600">₹ <?= number_format((float) ($summary['total_demand'] ?? 0), 2) ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Collected</p>
            <p class="text-xl font-bold text-green-600">₹ <?= number_format((float) ($summary['total_collection'] ?? 0), 2) ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-l-4 <?= $pendingBorderClass ?>">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-xl font-bold <?= $pendingTextClass ?>">₹ <?= number_format($pendingBalance, 2) ?></p>
        </div>
    </div>

    <?php if ($error !== ''): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="grid xl:grid-cols-5 gap-6 items-start">
        <div class="xl:col-span-3 space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
                    <div>
                        <h2 class="font-semibold text-gray-700">Pending Bills - <?= htmlspecialchars($routeHeading, ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="text-sm text-gray-500">Search filters list. Click Select to load form.</p>
                    </div>
                </div>

                <form method="GET" action="index.php" class="grid md:grid-cols-4 gap-3 mb-4 items-end">
                    <input type="hidden" name="route" value="receipt_entry">

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Route</label>
                        <select name="route_id" class="w-full border p-3 rounded-lg">
                            <option value="0">All Routes</option>
                            <?php foreach ($routes as $routeItem): ?>
                                <option value="<?= (int) $routeItem['id'] ?>" <?= $route_id === (int) $routeItem['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($routeItem['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Search</label>
                        <input type="text" name="search" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search customer name or mobile" class="w-full border p-3 rounded-lg">
                    </div>

                    <button class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700">Filter</button>
                    <a href="index.php?route=receipt_entry<?= $route_id > 0 ? '&route_id=' . $route_id : '' ?>" class="bg-gray-100 text-gray-700 px-5 py-3 rounded-lg hover:bg-gray-200 text-center">Reset</a>
                </form>

                <div class="overflow-x-auto w-full">
                    <table class="min-w-[900px] text-sm border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="p-3 text-left">Bill No</th>
                                <th class="p-3 text-left">Customer</th>
                                <th class="p-3 text-left">Route</th>
                                <th class="p-3 text-left">Mobile</th>
                                <th class="p-3 text-left">Period</th>
                                <th class="p-3 text-right">Total</th>
                                <th class="p-3 text-right">Balance</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($pendingBills)): ?>
                                <?php foreach ($pendingBills as $pendingBill): ?>
                                    <?php
                                    $isPaid = (float) $pendingBill['balance'] <= 0 || strcasecmp((string) $pendingBill['status'], 'Paid') === 0;
                                    $isSelected = $selectedBillId === (int) $pendingBill['id'];
                                    $rowClass = $isSelected ? 'bg-blue-50 ring-1 ring-inset ring-blue-200' : 'hover:bg-gray-50';
                                    $selectUrl = 'index.php?route=receipt_entry&bill_id=' . (int) $pendingBill['id'] . '&route_id=' . (int) ($route_id > 0 ? $route_id : ($pendingBill['route_id'] ?? 0));
                                    if ($search !== '') {
                                        $selectUrl .= '&search=' . urlencode($search);
                                    }
                                    ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td class="p-3">
                                            <div class="font-medium text-gray-800">BILL-<?= str_pad((string) $pendingBill['id'], 4, '0', STR_PAD_LEFT) ?></div>
                                            <?php if ($isSelected): ?>
                                                <div class="text-xs text-blue-700 font-semibold mt-1">Selected bill</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3 font-medium text-gray-800"><?= htmlspecialchars($pendingBill['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="p-3 text-gray-600"><?= htmlspecialchars((string) ($pendingBill['route_name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="p-3"><?= htmlspecialchars($pendingBill['mobile'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="p-3 text-xs text-gray-500"><?= htmlspecialchars(($pendingBill['bill_from'] ?? $pendingBill['bill_date']) . ' to ' . ($pendingBill['bill_to'] ?? $pendingBill['bill_date']), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="p-3 text-right font-semibold">₹ <?= number_format((float) $pendingBill['total'], 2) ?></td>
                                        <td class="p-3 text-right font-semibold <?= $isPaid ? 'text-green-600' : 'text-red-600' ?>">₹ <?= number_format((float) $pendingBill['balance'], 2) ?></td>
                                        <td class="p-3 text-center">
                                            <?php if ($isPaid): ?>
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Paid</span>
                                            <?php else: ?>
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3 text-center">
                                            <?php if ($isPaid): ?>
                                                <button type="button" class="bg-gray-200 text-gray-500 px-3 py-1.5 rounded text-xs cursor-not-allowed" disabled>Paid</button>
                                            <?php else: ?>
                                               <a href="<?= htmlspecialchars($selectUrl, ENT_QUOTES, 'UTF-8') ?>"
   class="inline-block px-3 py-1 text-xs font-medium rounded 
          <?= $isSelected 
                ? 'bg-green-500 text-white cursor-default pointer-events-none' 
                : 'bg-blue-500 text-white hover:bg-blue-600' ?>">
   <?= $isSelected ? 'Selected' : 'Select' ?>
</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="p-4 text-center text-gray-500">No pending bills found for the selected route and search.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-semibold text-gray-700">Receipt History</h2>
                        <p class="text-sm text-gray-500">Recent receipts for the selected bill.</p>
                    </div>
                </div>

                <?php if (empty($bill['id'])): ?>
                    <p class="text-sm text-gray-500">No bill selected. Select a bill to continue.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="p-3 text-left">Receipt Date</th>
                                    <th class="p-3 text-left">Mode</th>
                                    <th class="p-3 text-right">Amount</th>
                                    <th class="p-3 text-left">Reference</th>
                                    <th class="p-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php if (!empty($receipts)): ?>
                                    <?php foreach ($receipts as $receipt): ?>
                                        <tr>
                                            <td class="p-3"><?= htmlspecialchars($receipt['receipt_date'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="p-3"><?= htmlspecialchars($receipt['payment_mode'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="p-3 text-right font-semibold">₹ <?= number_format((float) $receipt['amount'], 2) ?></td>
                                            <td class="p-3"><?= htmlspecialchars((string) ($receipt['transaction_ref'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="p-3">
                                                <span class="<?= ($receipt['status'] ?? '') === 'verified' ? 'text-green-600' : 'text-orange-600' ?> font-medium">
                                                    <?= htmlspecialchars(ucfirst((string) ($receipt['status'] ?? 'entry')), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-500">No receipts found for this bill yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="xl:col-span-2 bg-white rounded-xl shadow p-5 sticky top-4">
            <div class="mb-4">
                <h2 class="font-semibold text-gray-700">Receipt Form</h2>
                <p class="text-sm text-gray-500">The form stays empty until you explicitly click Select from the pending bills list.</p>
            </div>

            <?php if (!empty($bill['id'])): ?>
                <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-100 text-sm text-gray-700 space-y-1">
                    <p class="text-base font-semibold text-blue-800">Selected Bill: <?= htmlspecialchars($bill['customer_name'], ENT_QUOTES, 'UTF-8') ?> (₹<?= number_format((float) $bill['balance'], 2) ?> pending)</p>
                    <p><span class="font-semibold">Bill No:</span> BILL-<?= str_pad((string) $bill['id'], 4, '0', STR_PAD_LEFT) ?></p>
                    <p><span class="font-semibold">Customer Name:</span> <?= htmlspecialchars($bill['customer_name'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><span class="font-semibold">Mobile:</span> <?= htmlspecialchars($bill['mobile'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><span class="font-semibold">Balance:</span> ₹ <?= number_format((float) $bill['balance'], 2) ?></p>
                </div>
                <?php if (!$canSubmitReceipt): ?>
                    <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">This bill is fully paid, so the receipt form is locked.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mb-4 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-600">No bill selected. Select a bill to continue.</div>
            <?php endif; ?>

            <form method="POST" action="index.php?route=save_receipt" class="space-y-4">
                <input type="hidden" name="bill_id" value="<?= htmlspecialchars((string) ($formDefaults['bill_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="route_id" value="<?= htmlspecialchars((string) ($formDefaults['route_id'] ?? $route_id), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="search" value="<?= htmlspecialchars((string) ($formDefaults['search'] ?? $search), ENT_QUOTES, 'UTF-8') ?>">

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Bill No</label>
                    <input type="text" value="<?= !empty($bill['id']) ? 'BILL-' . str_pad((string) $bill['id'], 4, '0', STR_PAD_LEFT) : '' ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Customer Name</label>
                    <input type="text" value="<?= htmlspecialchars((string) ($bill['customer_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Mobile</label>
                    <input type="text" value="<?= htmlspecialchars((string) ($bill['mobile'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Balance</label>
                    <input type="text" value="<?= !empty($bill['id']) ? '₹ ' . number_format((float) ($bill['balance'] ?? 0), 2) : '' ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Receipt Date</label>
                    <input type="date" name="receipt_date" value="<?= htmlspecialchars((string) ($formDefaults['receipt_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?> required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Amount</label>
                    <input type="number" step="0.01" min="0.01" max="<?= htmlspecialchars((string) ($bill['balance'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" name="amount" value="<?= htmlspecialchars((string) ($formDefaults['amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" <?= !$canSubmitReceipt ? 'disabled' : 'readonly' ?> required>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Payment Mode</label>
                    <select name="payment_mode" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?> required>
                        <?php foreach (['Cash', 'UPI', 'Bank'] as $mode): ?>
                            <option value="<?= $mode ?>" <?= ($formDefaults['payment_mode'] ?? 'Cash') === $mode ? 'selected' : '' ?>><?= $mode ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Transaction Ref</label>
                    <input type="text" name="transaction_ref" value="<?= htmlspecialchars((string) ($formDefaults['transaction_ref'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?>>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Transaction Date</label>
                    <input type="date" name="transaction_date" value="<?= htmlspecialchars((string) ($formDefaults['transaction_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?>>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Status</label>
                    <input type="text" name="status" value="<?= htmlspecialchars((string) ($formDefaults['status'] ?? 'entry'), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg bg-gray-50" readonly>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Verified Date</label>
                    <input type="date" name="verified_date" value="<?= htmlspecialchars((string) ($formDefaults['verified_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?>>
                </div>

                <div>
                    <label class="text-sm text-gray-600 block mb-1">Verified User ID</label>
                    <input type="text" name="verified_user_id" value="<?= htmlspecialchars((string) ($formDefaults['verified_user_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="w-full border p-2.5 rounded-lg" <?= !$canSubmitReceipt ? 'disabled' : '' ?>>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed" <?= !$canSubmitReceipt ? 'disabled' : '' ?>>Save Payment</button>
            </form>
        </div>
    </div>
</div>
