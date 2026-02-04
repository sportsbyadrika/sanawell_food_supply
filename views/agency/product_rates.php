<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Rate Cards</h2>
    <form method="POST" action="index.php?route=product_rates_update" class="space-y-4">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <input type="hidden" name="product_id" value="<?= (int) $productId ?>">
        <div class="grid gap-4 md:grid-cols-2">
            <?php foreach ($userTypes as $userType): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700"><?= htmlspecialchars($userType['name']) ?> Rate</label>
                    <input type="number" step="0.01" name="rates[<?= (int) $userType['id'] ?>]" class="mt-1 w-full border border-gray-300 rounded px-3 py-2" placeholder="0.00">
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Rates</button>
    </form>
</div>
