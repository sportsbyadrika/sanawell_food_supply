<?php
// $bills (pending bills), $routes available
?>

<div class="max-w-6xl mx-auto mt-6 space-y-6">

    <!-- SUMMARY -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

    <!-- SEARCH -->
    <form method="POST" action="index.php?route=receipt_page"
          class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row gap-3">

        <input type="text" name="search"
               placeholder="Search Name / Mobile"
               class="flex-1 border p-2 rounded-lg">

        <select name="route_id" class="border p-2 rounded-lg">
            <option value="">All Routes</option>
            <?php foreach ($routes as $route): ?>
                <option value="<?= $route['id'] ?>">
                    <?= $route['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Show
        </button>
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <div class="p-4 border-b font-semibold">Pending Bills</div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-100 text-gray-600 text-xs uppercase">
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

                <?php if (!empty($bills)): ?>
                    <?php foreach ($bills as $bill): ?>

                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-3">
                                BILL-<?= str_pad($bill['id'], 4, '0', STR_PAD_LEFT) ?>
                            </td>

                            <td class="px-4 py-3 font-medium">
                                <?= $bill['customer_name'] ?>
                            </td>

                            <td class="px-4 py-3">
                                <?= $bill['mobile'] ?>
                            </td>

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
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs">
                                    Pending
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <button
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs pay-btn"
                                    data-id="<?= $bill['id'] ?>"
                                    data-name="<?= $bill['customer_name'] ?>"
                                    data-amount="<?= $bill['balance'] ?>">
                                    Pay
                                </button>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center p-6 text-gray-400">
                            No pending bills
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL ================= -->

<div id="receiptModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-5 relative">

        <button onclick="closeModal()"
                class="absolute top-2 right-3 text-gray-500 text-xl">
            ×
        </button>

        <h2 class="text-lg font-semibold mb-4">Quick Receipt</h2>

        <form method="POST" action="index.php?route=save_receipt">

            <input type="hidden" name="bill_id" id="modal_bill_id">

            <p class="text-sm text-gray-500">Customer</p>
            <p id="modal_customer" class="font-semibold mb-3"></p>

            <input type="number" name="amount" id="modal_amount"
                   class="w-full border p-2 rounded-lg mb-3" required>

            <input type="date" name="receipt_date"
                   class="w-full border p-2 rounded-lg mb-3" required>

            <select name="payment_mode"
                    class="w-full border p-2 rounded-lg mb-3">
                <option>Cash</option>
                <option>UPI</option>
                <option>Bank</option>
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 bg-green-600 text-white py-2 rounded-lg">
                    Save
                </button>

                <button type="button"
                        onclick="closeModal()"
                        class="flex-1 bg-gray-300 py-2 rounded-lg">
                    Cancel
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ================= SCRIPT ================= -->

<script>
document.querySelectorAll('.pay-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        document.getElementById('receiptModal').classList.remove('hidden');
        document.getElementById('receiptModal').classList.add('flex');

        document.getElementById('modal_bill_id').value = this.dataset.id;
        document.getElementById('modal_customer').innerText = this.dataset.name;
        document.getElementById('modal_amount').value = this.dataset.amount;
    });
});

function closeModal() {
    document.getElementById('receiptModal').classList.add('hidden');
}
</script>