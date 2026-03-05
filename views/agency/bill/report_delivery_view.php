<div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Page Title -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Delivery Report</h2>
        <p class="text-gray-500 mt-1">
            View delivery and billing summary by date range
        </p>
    </div>

    <!-- ================= FILTER SECTION ================= -->
    <div class="bg-white shadow-md rounded-xl p-6 mb-10">
        <form method="GET" class="flex flex-col md:flex-row md:items-end gap-6">

            <input type="hidden" name="route" value="delivery_report">

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-600 mb-1">From Date</label>
                <input type="date"
                       name="from_date"
                       value="<?= htmlspecialchars($from ?? '') ?>"
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-medium text-gray-600 mb-1">To Date</label>
                <input type="date"
                       name="to_date"
                       value="<?= htmlspecialchars($to ?? '') ?>"
                       class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-slate-600 text-white px-6 py-2 rounded-lg shadow-md hover:opacity-90 transition">
                    Filter Report
                </button>
                 <?php if (!empty($from) && !empty($to)) : ?>
    <form method="POST" action="index.php?route=generate_monthly_bill" class="ml-4">
        <input type="hidden" name="from_date" value="<?= htmlspecialchars($from) ?>">
        <input type="hidden" name="to_date" value="<?= htmlspecialchars($to) ?>">
        <button type="submit"
            class="bg-gradient-to-r from-blue-500 to-slate-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-700 transition">
            Generate Monthly Bill
        </button>
    
<?php endif; ?>
                </form>
                </form>

               
            

        
    </div>
                </div>
                


    <!-- ================= DELIVERY SUMMARY ================= -->
    <h3 class="text-xl font-semibold text-gray-700 mb-4">Delivery Summary</h3>

    <?php if (!empty($summary)): ?>

        <?php foreach ($summary as $row): ?>
            <?php
                $key = $row['delivery_date'] . '_' . $row['route_id'];
                $details = $detailsMap[$key] ?? [];
            ?>

            <div class="bg-white shadow-md rounded-xl mb-8 overflow-hidden">

                <!-- Route Header -->
                <div class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-4 flex justify-between items-center">
                    <div>
                        <div class="text-lg font-semibold">
                            <?= htmlspecialchars($row['delivery_date']) ?>
                        </div>
                        <div class="text-sm opacity-90">
                            <?= htmlspecialchars($row['route_name']) ?>
                        </div>
                    </div>

                    <div class="text-right text-sm">
                        <div>Total Customers: <span class="font-semibold"><?= $row['total_customers'] ?></span></div>
                        <div>Total Quantity: <span class="font-semibold"><?= $row['total_quantity'] ?></span></div>
                    </div>
                </div>

                <!-- Child Table -->
                <?php if (!empty($details)): ?>
                    <div class="p-4">
                        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Customer</th>
                                    <th class="px-4 py-2 text-left">Product</th>
                                    <th class="px-4 py-2 text-left">Variant</th>
                                    <th class="px-4 py-2 text-right">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($details as $item): ?>
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            <?= htmlspecialchars($item['customer_name']) ?>
                                        </td>
                                        <td class="px-4 py-2">
                                            <?= htmlspecialchars($item['product_name']) ?>
                                        </td>
                                        <td class="px-4 py-2">
                                            <?= htmlspecialchars($item['variant']) ?>
                                        </td>
                                        <td class="px-4 py-2 text-right font-medium">
                                            <?= $item['quantity'] ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-gray-500 text-sm">
                        No customer details available.
                    </div>
                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="bg-white p-6 rounded-xl shadow text-gray-500">
            No delivery data found for selected date range.
        </div>

    <?php endif; ?>


    <!-- ================= BILLS SUMMARY ================= -->
    <h3 class="text-xl font-semibold text-gray-700 mt-12 mb-4">Bills Summary</h3>

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gradient-to-r from-blue-500 to-slate-600 text-white">
                    <tr>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Total Amount</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">

                    <?php if (!empty($bills)): ?>
                        <?php foreach ($bills as $bill): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($bill['bill_date']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($bill['customer_name']) ?>
                                </td>
                                <td class="px-6 py-4 font-semibold text-blue-600">
                                    ₹<?= number_format($bill['total_amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($bill['status'] === 'paid'): ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            Paid
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                No bills found for selected date range.
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>