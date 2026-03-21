<div class="max-w-7xl mx-auto p-6">

    <!-- Page Title -->
    <h1 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
        🚚 Routes
    </h1>

    <!-- Add Route Card -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold text-slate-700 mb-4">
            Add New Route
        </h2>

        <form method="POST"
              action="index.php?route=routes_store"
              class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Route Name -->
            <input type="text"
                   name="name"
                   placeholder="Route name"
                   required
                   class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">

            <!-- Type -->
            <select name="type"
                    required
                    class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                <option value="">Select Type</option>
                <option value="morning">Morning</option>
                <option value="evening">Evening</option>
            </select>

            <!-- Description -->
            <input type="text"
                   name="description"
                   placeholder="Description"
                   class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">

            <!-- Button -->
            <button type="submit"
                    class="bg-gradient-to-br from-blue-500 to-slate-500  text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition">
                Add Route
            </button>

        </form>
    </div>

    <!-- Route List -->
    <div class="bg-white to-slate-500 rounded-2xl shadow-md p-6 ">
        <h2 class="text-lg font-semibold text-slate-700 mb-4">
            Route List
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-br from-blue-500 to-slate-500 text-left text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Description</th>
                         <th class="px-4 py-3">Driver</th>
                        <th class="px-4 py-3">Vehicle</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">

                    <?php if (!empty($routes)) : ?>
                        <?php foreach ($routes as $route) : ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3"><?= htmlspecialchars($route['name']) ?></td>
                                <td class="px-4 py-3 capitalize"><?= htmlspecialchars($route['type']) ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($route['description']) ?></td>
                                <td class="px-4 py-3">
    <?php if ($route['driver_name']) : ?>
        <?= htmlspecialchars($route['driver_name']) ?>
    <?php else : ?>
        <span style="color:#dc3545; font-weight:500;">Not Assigned</span>
    <?php endif; ?>
</td>
<td class="px-4 py-3">
    <?php if ($route['vehicle_no']): ?>
        <?= htmlspecialchars($route['vehicle_no']) ?>
    <?php else: ?>
        <span style="color:#dc3545; font-weight:500;">Not Assigned</span>
    <?php endif; ?>
</td>
                                <td class="px-4 py-3">
    <span class="px-3 py-1 rounded-full text-xs font-medium
        <?= $route['status'] == 1
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700' ?>">
        <?= $route['status'] == 1 ? 'Active' : 'Inactive' ?>
    </span>
</td>
                               <td class="px-4 py-3 space-x-3">

    <a href="index.php?route=routes_edit&id=<?= $route['id'] ?>"
       class="text-blue-600 hover:underline text-sm font-medium">
       Edit
    </a>

    <a href="index.php?route=routes_toggle&id=<?= $route['id'] ?>"
       class="text-green-600 hover:underline text-sm font-medium">
       Toggle
    </a>

</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-6 text-slate-500">
                                No routes found
                            </td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>