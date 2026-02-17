<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-10">

    <div class="max-w-7xl mx-auto px-6">

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Staff Management
            </h1>
            <p class="text-gray-500 mt-1">
                Manage your agency staff and delivery drivers
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- LEFT CARD - ADD USER -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">

                <h2 class="text-lg font-semibold text-gray-700 mb-6">
                    Add Staff User
                </h2>

                <form method="POST" action="index.php?route=users_store" class="space-y-5">

                    <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Full Name
                        </label>
                        <input type="text" name="name"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Email Address
                        </label>
                        <input type="email" name="email"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Mobile Number
                        </label>
                        <input type="text" name="mobile"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">
                            Select Role
                        </label>
                        <select name="role_id"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                            <option value="">Select Role</option>
                          <?php foreach ($roles as $role): ?>
    <?php if ($role['name'] == 'Driver' || $role['name'] == 'Office Staff'): ?>
        <option value="<?= $role['id'] ?>">
            <?= htmlspecialchars($role['name']) ?>
        </option>
    <?php endif; ?>
<?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-slate-600 text-white py-2.5 rounded-xl shadow-md hover:shadow-lg hover:scale-[1.02] transition duration-200">
                        Create User
                    </button>

                </form>

            </div>


            <!-- RIGHT CARD - STAFF LIST -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-700">
                        Staff Users
                    </h2>

                    <span class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                        <?= count($users) ?> Members
                    </span>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead>
                            <tr class="text-left text-gray-500 border-b">
                                <th class="py-3">Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($users as $user): ?>
                            <tr class="border-b hover:bg-gray-50 transition">

                                <td class="py-3 font-medium text-gray-700">
                                    <?= htmlspecialchars($user['name']) ?>
                                </td>

                                <td><?= htmlspecialchars($user['email']) ?></td>

                                <td><?= htmlspecialchars($user['mobile']) ?></td>

                                <td>
                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-lg text-xs">
                                        <?= htmlspecialchars($user['role_name']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        <?= $user['status'] === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-600' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>

                                <td class="text-right space-x-3">
                                    <a href="index.php?route=users_edit&id=<?= $user['id'] ?>"
                                     class="text-blue-600 hover:underline">
                                     Edit
                                      </a>
                                    <a href="index.php?route=users_toggle&id=<?= $user['id'] ?>"
                                        class="text-green-500 font-medium hover:underline text-sm">
                                        Toggle
                                    </a>
                                </td>

                            </tr>
                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>