
<div class="h-[calc(100vh-120px)] flex items-center justify-center px-6">
    <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl p-8">

        <h2 class="text-2xl font-bold text-gray-700 mb-6">
            ✏ Edit User
        </h2>

        <form method="POST" action="index.php?route=users_update">
            
            <!-- Hidden Fields -->
            <input type="hidden" name="id" value="<?= $edituser['id'] ?>">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Name
                    </label>
                 <input type="text"
       name="name"
       required
       value="<?= htmlspecialchars($edituser['name'] ?? '') ?>"
       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" required
                        value="<?= htmlspecialchars($edituser['email']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>


                <!-- Mobile -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Mobile
                    </label>
                    <input type="text" name="mobile" required
                        value="<?= htmlspecialchars($edituser['mobile'] ?? '') ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                 <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Select Role
                    </label>
                    <select name="role_id"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2 
                        focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">

                        <?php foreach ($roles as $role): ?>
                            <?php if ($role['name'] == 'Driver' || $role['name'] == 'Office Staff'): ?>
                                <option value="<?= $role['id'] ?>"
                                    <?= $role['id'] == $edituser['role_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </select>
                </div>
            
                                        
                               
                    
            <!-- Buttons -->
            <div class="mt-8 flex justify-end space-x-4">

                <a href="index.php?route=users"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                   Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded-lg hover:bg-blue-700 shadow-md">
                    Update user
                </button>

            </div>

        </form>
    </div>
</div>