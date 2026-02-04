<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Users</h2>
    <form method="POST" action="index.php?route=users_store" class="grid gap-4 md:grid-cols-2">
        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Role ID</label>
            <input type="number" name="role_id" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2" placeholder="Role ID from roles table">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Temporary Password</label>
            <input type="password" name="password" required class="mt-1 w-full border border-gray-300 rounded px-3 py-2">
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create User</button>
        </div>
    </form>
</div>
