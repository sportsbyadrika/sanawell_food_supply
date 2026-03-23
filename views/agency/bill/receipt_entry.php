<div class="max-w-5xl mx-auto mt-6 space-y-6">

    <!-- 🔷 Header -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-5 rounded-xl shadow flex justify-between">
        <div>
            <h1 class="text-xl font-semibold">Receipt Entry</h1>
            <p class="text-sm opacity-90">Capture and manage customer payments</p>
        </div>
        <a href="index.php?route=bill_list"
           class="bg-white/20 px-4 py-2 rounded-lg hover:bg-white/30">Back</a>
    </div>

    <!-- 🔶 Summary Cards -->
    <div class="grid md:grid-cols-3 gap-4">

        <!-- Total -->
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-orange-500">
            <p class="text-sm text-gray-500">Total</p>
            <p class="text-xl font-bold text-orange-600">
                ₹ <?= $summary['total_demand'] ?? 0 ?>
            </p>
        </div>

        <!-- Collected -->
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Collected</p>
            <p class="text-xl font-bold text-green-600">
                ₹ <?= $summary['total_collection'] ?? 0 ?>
            </p>
        </div>

        <!-- Pending / Balance -->
        <?php
        $balance = $summary['balance'] ?? 0;

        if ($balance == 0) {
            $color = 'green';   
        } elseif ($balance < 0) {
            $color = 'orannge';
        } else {
            $color = 'red';     
        }
        ?>
        <div class="bg-white p-4 rounded-xl shadow border-l-4 border-<?= $color ?>-500">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-xl font-bold text-<?= $color ?>-600">
                ₹ <?= $balance ?>
            </p>
        </div>

    </div>

    <!-- 🧾 Receipt Form -->
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-semibold mb-4 text-gray-700">Enter Receipt Details</h2>

        <form method="POST" action="index.php?route=save_receipt"
              class="grid md:grid-cols-3 gap-4">

            <input type="hidden" name="bill_id" value="<?= $bill['id'] ?? '' ?>">

            <!-- Receipt Date -->
            <div>
                <label class="text-sm text-gray-600">Receipt Date</label>
                <input type="date" name="receipt_date"
                       class="w-full border p-2 rounded-lg" required>
            </div>

            <!-- Mode -->
            <div>
                <label class="text-sm text-gray-600">Mode</label>
                <select name="mode" class="w-full border p-2 rounded-lg">
                    <option value="cash">Cash</option>
                    <option value="upi">UPI</option>
                    <option value="bank">Bank</option>
                </select>
            </div>

            <!-- Amount -->
            <div>
                <label class="text-sm text-gray-600">Amount</label>
                <input type="number" name="amount"
                       class="w-full border p-2 rounded-lg" required>
            </div>

            <!-- Transaction Ref -->
            <div>
                <label class="text-sm text-gray-600">Transaction Ref</label>
                <input type="text" name="transaction_ref"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Transaction Date -->
            <div>
                <label class="text-sm text-gray-600">Transaction Date</label>
                <input type="date" name="transaction_date"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Status -->
            <div>
                <label class="text-sm text-gray-600">Status</label>
                <select name="status" class="w-full border p-2 rounded-lg">
                    <option value="entry">Entry</option>
                    <option value="verified">Verified</option>
                </select>
            </div>

            <!-- Verified Date -->
            <div>
                <label class="text-sm text-gray-600">Verified Date</label>
                <input type="date" name="verified_date"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Verified User -->
            <div>
                <label class="text-sm text-gray-600">Verified User ID</label>
                <input type="text" name="verified_user_id"
                       class="w-full border p-2 rounded-lg">
            </div>

            <!-- Submit -->
            <div class="md:col-span-3">
                <button
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Save Receipt
                </button>
            </div>

        </form>
    </div>

    <!-- 📋 Receipt List -->
    <div class="bg-white rounded-xl shadow p-5">
        <h2 class="font-semibold mb-4 text-gray-700">Receipt List</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Mode</th>
                    <th class="p-2 border">Amount</th>
                    <th class="p-2 border">Ref</th>
                    <th class="p-2 border">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($receipts)) { ?>
                    <?php foreach ($receipts as $r) { ?>
                        <tr>
                            <td class="p-2 border"><?= $r['receipt_date'] ?></td>
                            <td class="p-2 border"><?= ucfirst($r['mode']) ?></td>
                            <td class="p-2 border">₹ <?= $r['amount'] ?></td>
                            <td class="p-2 border"><?= $r['transaction_ref'] ?></td>
                            <td class="p-2 border">
                                <span class="<?= $r['status'] == 'verified' ? 'text-green-600' : 'text-orange-600' ?>">
                                    <?= ucfirst($r['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center p-3 text-gray-500">
                            No receipts found
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</div>