<div class="max-w-7xl mx-auto py-8 px-4">

    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                🚚 Today's Deliveries
            </h2>
            <p class="text-gray-500 text-sm">
                Route-wise delivery list
            </p>
        </div>

        <a href="index.php?route=driver_dashboard"
           class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition">
            Back
        </a>
    </div>

<?php if (empty($deliveries)): ?>

    <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">
        No deliveries assigned for today.
    </div>

<?php else: ?>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">

        <table class="min-w-full text-sm text-left">

            <!-- Header -->
            <thead class="bg-gradient-to-br from-blue-500 to-slate-500 
                    text-white px-6 py-3 font-semibold text-white hidden md:table-header-group">
                <tr>
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Visit</th>
                    <th class="px-5 py-3">Customer</th>
                    <th class="px-5 py-3">Mobile</th>
                    <th class="px-5 py-3">Address</th>
                    <th class="px-5 py-3">Product</th>
                    <th class="px-5 py-3 text-center">Qty</th>
                    <th class="px-5 py-3 text-center">Status</th>
                    <th class="px-5 py-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

            <?php $count = 1; ?>
            <?php foreach ($deliveries as $delivery): ?>

                <?php
                    $orderNo  = $delivery['order_no'] ?? '-';
                    $name     = $delivery['name'] ?? 'Customer';
                    $mobile   = $delivery['mobile'] ?? '';
                    $address  = $delivery['address'] ?? '';
                    $product  = $delivery['product_name'] ?? '';
                    $variant  = $delivery['variant'] ?? '';
                    $quantity = $delivery['quantity'] ?? 0;
                    $status   = $delivery['status'] ?? 'pending';
                    $id       = $delivery['id'] ?? 0;
                ?>

                <tr class="block md:table-row hover:bg-gray-50 transition p-4 md:p-0">

                    <!-- Customer Number -->
                    <td class="block md:table-cell px-5 py-2 font-semibold">
                        <span class="md:hidden text-gray-400 text-xs">Customer No:</span>
                        <?= $count++ ?>
                    </td>

                    <!-- Visit -->
                    <td class="block md:table-cell px-5 py-2">
                        <span class="md:hidden text-gray-400 text-xs">Visit:</span>
                        #<?= htmlspecialchars($orderNo) ?>
                    </td>

                    <!-- Customer -->
                    <td class="block md:table-cell px-5 py-2 font-semibold text-gray-800">
                        <span class="md:hidden text-gray-400 text-xs">Customer:</span>
                        <?= htmlspecialchars($name) ?>
                    </td>

                    <!-- Mobile -->
                    <td class="block md:table-cell px-5 py-2 text-gray-600">
                        <span class="md:hidden text-gray-400 text-xs">Mobile:</span>
                        <?= htmlspecialchars($mobile) ?>
                    </td>

                    <!-- Address -->
                    <td class="block md:table-cell px-5 py-2 text-gray-600">
                        <span class="md:hidden text-gray-400 text-xs">Address:</span>
                        <?= htmlspecialchars($address) ?>
                    </td>

                    <!-- Product -->
                    <td class="block md:table-cell px-5 py-2 text-gray-700">
                        <span class="md:hidden text-gray-400 text-xs">Product:</span>
                        <?= htmlspecialchars($product) ?>
                        <?php if ($variant): ?>
                            <span class="text-gray-500">
                                (<?= htmlspecialchars($variant) ?>)
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Quantity -->
                    <td class="block md:table-cell px-5 py-2 font-bold text-blue-600 md:text-center">
                        <span class="md:hidden text-gray-400 text-xs">Quantity:</span>
                        <?= $quantity ?>
                    </td>

                    <!-- Status -->
                    <td class="block md:table-cell px-5 py-2 md:text-center">
                        <span class="md:hidden text-gray-400 text-xs">Status:</span>

                        <?php if ($status === 'delivered'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Delivered
                            </span>
                        <?php elseif ($status === 'partial'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Partial
                            </span>
                        <?php elseif ($status === 'not_delivered'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Not Delivered
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- Action -->
                    <td class="block md:table-cell px-5 py-2 md:text-center">
                        <a href="index.php?route=order_details&id=<?= $id ?>"
                           class="bg-gradient-to-br from-blue-500 to-slate-500  hover:bg-blue-700 text-white px-4 py-1.5 rounded-lg text-xs font-medium inline-block mt-2 md:mt-0 transition">
                            Update
                        </a>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>

    </div>

<?php endif; ?>

</div>