<div class="p-6">

    <!-- Page Title -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">
            🧾 Generate Monthly Bill
        </h2>
        <a href="index.php?route=bill_list"
           class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
           View Bills
        </a>
    </div>

    <!-- FILTER CARD -->
    <div class="bg-white p-4 rounded shadow mb-6">

        <form method="POST" action="index.php?route=generate_bill">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Route -->
                <div>
                    <label class="text-sm text-gray-600">Route</label>
                    <select name="route_id" required
                        class="w-full border rounded px-3 py-2">
                        <option value="">Select Route</option>
                        <?php foreach($routes as $route): ?>
                            <option value="<?= $route['id'] ?>">
                                <?= $route['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- From Date -->
                <div>
                    <label class="text-sm text-gray-600">From Date</label>
                    <input type="date" name="from_date" required
                        class="w-full border rounded px-3 py-2">
                </div>

                <!-- To Date -->
                <div>
                    <label class="text-sm text-gray-600">To Date</label>
                    <input type="date" name="to_date" required
                        class="w-full border rounded px-3 py-2">
                </div>

                <!-- Bill Type -->
                <div>
                    <label class="text-sm text-gray-600">Bill Type</label>
                    <select name="bill_type"
                        class="w-full border rounded px-3 py-2">
                        <option value="MAIN">Main Bill</option>
                        <option value="ADDITIONAL">Additional Bill</option>
                    </select>
                </div>

            </div>

            <!-- Button -->
            <div class="mt-4 text-right">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Generate Bill
                </button>
            </div>

        </form>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-blue-100 p-4 rounded shadow text-center">
            <h4 class="text-gray-600">Total Demand</h4>
            <p class="text-xl font-bold text-blue-700">
                ₹ <?= $summary['total_demand'] ?? 0 ?>
            </p>
        </div>

        <div class="bg-green-100 p-4 rounded shadow text-center">
            <h4 class="text-gray-600">Total Collection</h4>
            <p class="text-xl font-bold text-green-700">
                ₹ <?= $summary['total_collection'] ?? 0 ?>
            </p>
        </div>

        <div class="bg-red-100 p-4 rounded shadow text-center">
            <h4 class="text-gray-600">Balance</h4>
            <p class="text-xl font-bold text-red-700">
                ₹ <?= ($summary['total_demand'] ?? 0) - ($summary['total_collection'] ?? 0) ?>
            </p>
        </div>

    </div>

    <!-- GENERATED BILL LIST -->
    <div class="bg-white p-4 rounded shadow">

        <h3 class="text-lg font-semibold mb-3">Generated Bills</h3>

        <table class="w-full border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Bill No</th>
                    <th class="p-2 border">Customer</th>
                    <th class="p-2 border">Mobile</th>
                    <th class="p-2 border">Period</th>
                    <th class="p-2 border">Amount</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if(!empty($bills)): ?>
                    <?php foreach($bills as $b): ?>
                        <tr class="text-center">
                            <td class="p-2 border"><?= $b['id'] ?></td>
                            <td class="p-2 border"><?= $b['name'] ?></td>
                            <td class="p-2 border"><?= $b['mobile'] ?></td>
                            <td class="p-2 border">
                                <?= $b['bill_from'] ?> to <?= $b['bill_to'] ?>
                            </td>
                            <td class="p-2 border">₹ <?= $b['final_amount'] ?></td>
                            <td class="p-2 border">
                                <?php if($b['status'] == 'GENERATED'): ?>
                                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">
                                        Generated
                                    </span>
                                <?php else: ?>
                                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded">
                                        Closed
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-3 text-center text-gray-500">
                            No Bills Found
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>

    </div>

</div>