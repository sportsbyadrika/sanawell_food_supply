<div class="flex flex-col lg:flex-row gap-6">
    <div class="bg-white shadow rounded-lg p-6 flex-1">
        <h2 class="text-xl font-semibold mb-4">Supply Agencies</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">Name</th>
                    <th class="py-2">Contact</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agencies as $agency): ?>
                    <tr class="border-b">
                        <td class="py-2 font-medium"><?= htmlspecialchars($agency['name']) ?></td>
                        <td class="py-2 text-gray-500"><?= htmlspecialchars($agency['contact_email']) ?></td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded-full text-xs <?= $agency['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' ?>">
                                <?= htmlspecialchars($agency['status']) ?>
                            </span>
                        </td>
                        <td class="py-2">
                            <form method="POST" action="index.php?route=agencies_status" class="inline">
                                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                                <input type="hidden" name="agency_id" value="<?= (int) $agency['id'] ?>">
                                <input type="hidden" name="status" value="<?= $agency['status'] === 'active' ? 'inactive' : 'active' ?>">
                                <button class="text-blue-600 hover:underline" type="submit">
                                    <?= $agency['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-white shadow rounded-lg p-6 w-full lg:w-96">
        <h2 class="text-xl font-semibold mb-4">Add New Agency</h2>
        <form method="POST" action="index.php?route=agencies_store">
            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Agency Name</label>
                <input type="text" name="name" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Contact Email</label>
                <input type="email" name="contact_email" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Agency</button>
        </form>
    </div>
</div>
