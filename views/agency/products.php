<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Add Product</h2>
    <form method="POST" action="index.php?route=products_store" class="grid gap-4 md:grid-cols-2">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" name="name" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <input type="text" name="description" class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Product</button>
        </div>
    </form>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Products</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b">
                <th class="py-2">Name</th>
                <th class="py-2">Description</th>
                <th class="py-2">Rates</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="border-b">
                    <td class="py-2 font-medium"><?= htmlspecialchars($product['name']) ?></td>
                    <td class="py-2 text-gray-500"><?= htmlspecialchars($product['description']) ?></td>
                    <td class="py-2">
                        <a class="text-blue-600 hover:underline" href="index.php?route=product_rates&product_id=<?= (int) $product['id'] ?>">Manage Rates</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
