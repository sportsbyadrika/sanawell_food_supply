 
 <div class="max-w-6xl mx-auto py-10">
 <div class="bg-white rounded-2xl shadow-lg p-8 mb-10">


    <!-- Product Header Card -->
    <div class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-4 rounded-xl shadow-lg mb-10">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold"><?= $product['name'] ?></h2>
                <p class="opacity-90 mt-1">
                    Product Type: <?= $product['variant'] ?? '-' ?>
                </p>
            </div>
            <a href="index.php?route=products"
               class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                ← Back to Products
            </a>
        </div>
</div>

    <!-- Add Rate Card -->
   

        <h3 class="text-xl font-semibold mb-6 text-gray-700">
            Add New Rate
        </h3>

        <form method="POST" action="index.php?route=rate_store" class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <input type="hidden" name="product_id" value="<?= $product_id ?>">

            <!-- Customer Type -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Customer Type
                </label>
                <select name="customer_type_id"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                         <option value="">select</option>
                    <?php foreach ($customerTypes as $type): ?>
                       
                        <option value="<?= $type['id'] ?>">
                            <?= $type['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
                    
                       <!-- Rate -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Rate
                </label>
                <input type="number" step="0.01" name="rate"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       placeholder="Enter rate" required>
            </div>

            <!-- Valid From -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Valid From
                </label>
                <input type="date" name="valid_from"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Valid To -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Valid To
                </label>
                <input type="date" name="valid_to"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <!-- Submit -->
            <div class="md:col-span-2">
                <button type="submit"
                        class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow">
                    + Add Rate
                </button>
            </div>

        </form>
    </div>

    <!-- Rates Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-6 py-4">
            <h3 class="text-lg font-semibold">Existing Rates</h3>
        </div>

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Customer Type</th>
                    <th class="px-6 py-3">Rate</th>
                    <th class="px-6 py-3">From</th>
                    <th class="px-6 py-3">To</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">

                <?php if (!empty($rates)): ?>
                    <?php foreach ($rates as $rate): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-700">
                                <?= $rate['customer_type_name'] ?>
                            </td>
                            
                            <td class="px-6 py-4 font-semibold text-blue-600">
                                ₹ <?= number_format($rate['rate'], 2) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= $rate['valid_from'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <?= $rate['valid_to'] ?? '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                            No rates added yet.
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

    </div>


                </div>