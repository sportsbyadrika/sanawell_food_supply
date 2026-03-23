<?php
$selectedRoute = $selectedRoute ?? null;
$selectedRouteId = (int) ($selectedRouteId ?? 0);
$routeHeading = $selectedRoute['name'] ?? 'All Routes';
?>
<div class="max-w-6xl mx-auto p-4 space-y-6">

 <div class="relative rounded-2xl p-6 shadow-xl text-white 
            bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 overflow-hidden">

    <!-- Soft glow -->
    <div class="absolute inset-0 opacity-10 
                bg-[radial-gradient(circle_at_top_right,white,transparent)]"></div>

    <div class="relative flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <!-- LEFT -->
        <div>
            <h2 class="text-2xl font-bold tracking-tight">
                Generate Monthly Bill
            </h2>
            <p class="text-sm text-white/80 mt-1">
                Generated Bills - Route <?= htmlspecialchars($routeHeading, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

        <!-- RIGHT BUTTON -->
        <a href="index.php?route=bill_list<?= $selectedRouteId > 0 ? "&route_id=" . $selectedRouteId : "" ?>"
           class="bg-white/15 hover:bg-white/25 px-4 py-2 rounded-xl text-sm backdrop-blur-md transition shadow-sm">
            View Bills
        </a>

    </div>
</div>
    <!-- FILTER CARD -->
    <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">

        <form method="POST" action="index.php?route=generate_bill">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Route -->
                <div>
                    <label class="text-xs text-gray-500">Route</label>
                    <select name="route_id" required
                        class="mt-1 w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Route</option>
                        <?php foreach($routes as $route): ?>
                            <option value="<?= $route['id'] ?>" <?= $selectedRouteId === (int) $route['id'] ? 'selected' : '' ?>>
                                <?= $route['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- From Date -->
                <div>
                    <label class="text-xs text-gray-500">From Date</label>
                    <input type="date" name="from_date" required
                        class="mt-1 w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- To Date -->
                <div>
                    <label class="text-xs text-gray-500">To Date</label>
                    <input type="date" name="to_date" required
                        class="mt-1 w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Bill Type -->
                <div>
                    <label class="text-xs text-gray-500">Bill Type</label>
                    <select name="bill_type"
                        class="mt-1 w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="MAIN">Main Bill</option>
                        <option value="ADDITIONAL">Additional</option>
                    </select>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white px-6 py-2 rounded-xl shadow-md hover:scale-105 transition">
                    Generate Bill
                </button>
            </div>

        </form>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl p-5 shadow border-l-4 border-orange-400">
            <p class="text-sm text-gray-500">Total Demand</p>
            <h3 class="text-2xl font-bold text-orange-500">
                ₹ <?= $summary['total_demand'] ?? 0 ?>
            </h3>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Collection</p>
            <h3 class="text-2xl font-bold text-green-600">
                ₹ <?= $summary['total_collection'] ?? 0 ?>
            </h3>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Balance</p>
            <h3 class="text-2xl font-bold text-red-600">
                ₹ <?= ($summary['total_demand'] ?? 0) - ($summary['total_collection'] ?? 0) ?>
            </h3>
        </div>

    </div>

    <!-- TABLE -->
   <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

    <div class="p-4 border-b">
        <h3 class="font-semibold text-gray-700">Generated Bills - Route <?= htmlspecialchars($routeHeading, ENT_QUOTES, 'UTF-8') ?></h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm table-auto">

            <!-- HEADER -->
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="p-3 text-left w-[80px]">Bill No</th>
                    <th class="p-3 text-left w-[180px]">Customer</th>
                    <th class="p-3 text-left w-[130px]">Mobile</th>
                    <th class="p-3 text-left w-[200px]">Address</th>
                    <th class="p-3 text-left w-[120px]">Bill Date</th>
                    <th class="p-3 text-center w-[100px]">Type</th>
                    <th class="p-3 text-left w-[180px]">Period</th>
                    <th class="p-3 text-right w-[100px]">Total</th>
                    <th class="p-3 text-right w-[100px]">Tax</th>
                    <th class="p-3 text-right w-[110px]">Final</th>
                    <th class="p-3 text-center w-[140px]">Status</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>
                <?php foreach($bills as $b): ?>
                <tr class="border-t hover:bg-gray-50">

                    <!-- Bill No -->
                    <td class="p-3 text-left font-medium">
                        <?= $b['id'] ?>
                    </td>

                    <!-- Customer -->
                    <td class="p-3 text-left font-medium text-gray-800">
                        <?= $b['name'] ?>
                    </td>

                    <!-- Mobile -->
                    <td class="p-3 text-left text-gray-600">
                        <?= $b['mobile'] ?>
                    </td>

                    <!-- Address -->
                    <td class="p-3 text-left text-gray-500 text-xs">
                        <div class="max-w-[200px] truncate" title="<?= $b['address'] ?>">
                            <?= $b['address'] ?>
                        </div>
                    </td>

                    <!-- Bill Date -->
                    <td class="p-3 text-left text-gray-500 text-xs">
                        <?= date('d M Y', strtotime($b['bill_date'])) ?>
                    </td>

                    <!-- Type -->
                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs 
                            <?= $b['bill_type'] == 'MAIN' 
                                ? 'bg-blue-100 text-blue-700' 
                                : 'bg-purple-100 text-purple-700' ?>">
                            <?= $b['bill_type'] ?>
                        </span>
                    </td>

                    <!-- Period -->
                    <td class="p-3 text-left text-xs text-gray-500">
                        <?= $b['bill_from'] ?> → <?= $b['bill_to'] ?>
                    </td>

                    <!-- Total -->
                    <td class="p-3 text-right font-medium text-gray-700">
                        ₹ <?= number_format($b['total_amount'], 2) ?>
                    </td>

                    <!-- Tax -->
                    <td class="p-3 text-right text-gray-500">
                        ₹ <?= number_format($b['tax_amount'], 2) ?>
                    </td>

                    <!-- Final -->
                    <td class="p-3 text-right font-semibold text-green-600">
                        ₹ <?= number_format($b['final_amount'], 2) ?>
                    </td>

                    <!-- Status -->
                    <td class="p-3 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            <?= $b['status'] == 'PAID' 
                                ? 'bg-green-100 text-green-700' 
                                : 'bg-red-100 text-red-600' ?>">
                            <?= $b['status'] ?>
                        </span>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>