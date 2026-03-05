<div class="max-w-2xl mx-auto mt-12">
    <div class="bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-xl font-medium text-gray-700 mb-6">
            ✏ Edit Route
        </h2>

        <form method="POST" action="index.php?route=routes_update">

            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="id" value="<?= $route['id'] ?>">

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Route Name
                </label>
                <input type="text"
                       name="name"
                       value="<?= htmlspecialchars($route['name']) ?>"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                       required>
            </div>
 <!-- Type -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Type</label>
        <select name="type"
                class="mt-1 w-full border rounded-lg px-3 py-2"
                required>

            <option value="morning"
                <?= $route['type'] == 'morning' ? 'selected' : '' ?>>
                Morning
            </option>

            <option value="evening"
                <?= $route['type'] == 'evening' ? 'selected' : '' ?>>
                Evening
            </option>

        </select>
    </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Description
                </label>
                <textarea name="description"
                          rows="3"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($route['description']) ?></textarea>
            </div>
            <div class="mb-5">
<label  class="block text-sm font-medium text-gray-700">Driver</label>
<select name="driver_id" class="mt-1 w-full border rounded-lg px-3 py-2">
    <option value="">Not Assigned</option>

    <?php foreach ($drivers as $driver): ?>
        <option value="<?= $driver['id'] ?>"
            <?= $route['driver_id'] == $driver['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($driver['name']) ?>
        </option>
    <?php endforeach; ?>
</select>
    </div>
            <div class="flex justify-between items-center">
                <a href="index.php?route=routes"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                   Cancel
                </a>

                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-slate-700 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Route
                </button>
            </div>

        </form>

    </div>
</div>