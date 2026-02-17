<div class="max-w-5xl mx-auto mt-8">

<h2 class="text-2xl font-semibold mb-6">Assign Customers</h2>

<div class="bg-white shadow rounded p-6 mb-6">

<form method="POST" action="index.php?route=route_assign_store"
      class="grid grid-cols-3 gap-4">

    <input type="hidden" name="route_id" value="<?= $_GET['id'] ?>">

    <select name="customer_id" class="border rounded px-3 py-2" required>
        <option value="">Select Customer</option>
        <?php foreach($customers as $customer): ?>
            <option value="<?= $customer['id'] ?>">
                <?= $customer['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input name="delivery_order" type="number"
           placeholder="Order"
           class="border rounded px-3 py-2" required>

    <button class="bg-blue-600 text-white rounded px-4 py-2">
        Assign
    </button>

</form>

</div>

<!-- Assigned List -->
<div class="bg-white shadow rounded">
    <table class="w-full">
        <thead class="bg-blue-500 text-white">
            <tr>
                <th class="p-3">Order</th>
                <th class="p-3">Customer</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($assigned as $row): ?>
            <tr class="border-t">
                <td class="p-3"><?= $row['delivery_order'] ?></td>
                <td class="p-3"><?= $row['name'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>