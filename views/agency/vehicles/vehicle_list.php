<div class="max-w-7xl mx-auto px-6 py-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            🚚 Vehicle Management
        </h2>

        <a href="index.php?route=vehicle_create"
           class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            + Add Vehicle
        </a>
    </div>


    <!-- Card -->
    <div class="bg-white rounded-xl shadow border overflow-hidden">

        <table class="w-full text-sm text-left">

            <!-- Table Header -->
            <thead class="bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 text-white">
                <tr>
                    <th class="px-5 py-3">Vehicle No</th>
                    <th class="px-5 py-3">Company</th>
                    <th class="px-5 py-3">Model</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Fuel</th>
                    <th class="px-5 py-3">Registration</th>
                    <th class="px-5 py-3">Insurance</th>
                    <th class="px-5 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y">

            <?php foreach ($vehicles as $vehicle): ?>

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-3 font-medium text-gray-800">
                        <?= htmlspecialchars($vehicle['vehicle_no']) ?>
                    </td>

                    <td class="px-5 py-3 text-gray-700">
                        <?= htmlspecialchars($vehicle['vehicle_company']) ?>
                    </td>

                    <td class="px-5 py-3 text-gray-700">
                        <?= htmlspecialchars($vehicle['vehicle_model'] ?? '-') ?>
                    </td>

                    <td class="px-5 py-3">
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                            <?= htmlspecialchars($vehicle['vehicle_type']) ?>
                        </span>
                    </td>

                    <td class="px-5 py-3">
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">
                            <?= htmlspecialchars($vehicle['fuel_type']) ?>
                        </span>
                    </td>
<td class="px-5 py-3"><?= $vehicle['registration_date'] ?></td>
<td class="px-5 py-3"><?= $vehicle['insurance_valid_upto'] ?></td>
                   <td class="px-5 py-3 text-center space-x-2">

<a href="index.php?route=vehicles/edit&id=<?php echo $vehicle['vehicle_no']; ?>"
class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-md hover:bg-blue-200 transition">
Edit
</a>

<a href="index.php?route=vehicles/delete&id=<?php echo $vehicle['vehicle_no']; ?>"
onclick="return confirm('Delete this vehicle?')"
class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-md hover:bg-red-200 transition">
Delete
</a>
            </td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>