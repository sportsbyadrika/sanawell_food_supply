<div class="max-w-5xl mx-auto mt-6 space-y-6">

    <!-- 🔷 Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-5 rounded-xl shadow flex justify-between items-center">
        <div>
            <h1 class="text-xl font-semibold">Receipt Entry</h1>
            <p class="text-sm opacity-90">Capture and manage customer payments</p>
        </div>
        <a href="index.php?route=receipt_page"
           class="bg-white/20 px-4 py-2 rounded-lg hover:bg-white/30">
           Back
        </a>
    </div>

    <!-- 👤 Customer Info Card -->
    <div class="bg-white rounded-xl shadow p-5 grid md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">Customer</p>
            <p class="font-semibold text-lg"><?= $bill['customer_name'] ?></p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Mobile</p>
            <p class="font-medium"><?= $bill['mobile'] ?></p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Address</p>
            <p class="text-sm"><?= $bill['address'] ?></p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Bill Amount</p>
            <p class="text-lg font-bold text-blue-600">₹ <?= $bill['final_amount'] ?></p>
        </div>
    </div>

    <!-- 💰 Summary Cards -->
    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-orange-500">
            <p class="text-sm text-gray-500">Total Demand</p>
            <p class="text-xl font-bold text-orange-600">₹ <?= $summary['total_demand'] ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Collection</p>
            <p class="text-xl font-bold text-green-600">₹ <?= $summary['total_collection'] ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Balance</p>
            <p class="text-xl font-bold text-red-600">₹ <?= $summary['balance'] ?></p>
        </div>
    </div>

    <!-- 🧾 Receipt Form -->
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-semibold mb-4 text-gray-700">Enter Receipt Details</h2>

        <form method="POST" action="index.php?route=save_receipt"
              class="grid md:grid-cols-3 gap-4">

            <input type="hidden" name="bill_id" value="<?= $bill['id'] ?>">

            <!-- Receipt Date -->
            <div>
                <label class="text-sm text-gray-500">Receipt Date</label>
                <input type="date" name="receipt_date"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Amount -->
            <div>
                <label class="text-sm text-gray-500">Amount</label>
                <input type="number" name="amount"
                       value="<?= $bill['final_amount'] ?>"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Payment Mode -->
            <div>
                <label class="text-sm text-gray-500">Mode</label>
                <select name="payment_mode"
                        class="w-full border p-2 rounded-lg">
                    <option>Cash</option>
                    <option>UPI</option>
                    <option>Bank</option>
                </select>
            </div>

            <!-- Transaction Ref -->
            <div>
                <label class="text-sm text-gray-500">Transaction Ref</label>
                <input type="text" name="transaction_ref"
                       placeholder="Txn Ref"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Transaction Date -->
            <div>
                <label class="text-sm text-gray-500">Transaction Date</label>
                <input type="date" name="transaction_date"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Status -->
            <div>
                <label class="text-sm text-gray-500">Status</label>
                <select name="status"
                        class="w-full border p-2 rounded-lg">
                    <option value="ENTRY">Entry</option>
                    <option value="VERIFIED">Verified</option>
                </select>
            </div>

            <!-- Verified Date -->
            <div>
                <label class="text-sm text-gray-500">Verified Date</label>
                <input type="date" name="verified_date"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Verified User -->
            <div>
                <label class="text-sm text-gray-500">Verified User</label>
                <input type="text" name="verified_user"
                       placeholder="User ID"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Submit -->
            <div class="md:col-span-3">
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Save Receipt
                </button>
            </div>
        </form>
    </div>

    <!-- 📋 Receipt History -->
    <div class="bg-white rounded-xl shadow">
        <div class="p-4 border-b font-semibold text-gray-700">
            Receipt History
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Amount</th>
                    <th class="p-3 text-left">Mode</th>
                    <th class="p-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($receipts)): ?>
                    <?php foreach ($receipts as $r): ?>
                        <tr class="border-t">
                            <td class="p-3"><?= $r['receipt_date'] ?></td>
                            <td class="p-3 text-green-600 font-medium">₹ <?= $r['amount'] ?></td>
                            <td class="p-3"><?= $r['payment_mode'] ?></td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs 
                                    <?= $r['status'] == 'VERIFIED' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                    <?= $r['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-400">
                            No receipts found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>