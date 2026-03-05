<div class="min-h-screen bg-gradient-to-br from-blue-50 to-gray-100 py-10">

    <div class="max-w-7xl mx-auto px-6">
        
        <!-- Page Title -->
        <div class="mb-8">
            <div id="toast-container"
     class="fixed top-6 right-6 space-y-4 z-50">
</div>
            <h1 class="text-2xl font-bold text-gray-800">
                Staff Management
            </h1>
            <p class="text-gray-500 mt-1">
                Manage your agency staff and delivery drivers
            </p>
        </div>

     <?php if (!empty($_SESSION['flash_success'])): ?>
<script>
window.addEventListener("DOMContentLoaded", function () {
    showToast("<?= htmlspecialchars($_SESSION['flash_success']) ?>", "success");
});
</script>
<?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_temp_password'])): ?>
<script>
window.addEventListener("DOMContentLoaded", function () {
    showToast(
        "Temporary Password: <?= htmlspecialchars($_SESSION['flash_temp_password']) ?>",
        "warning",
        10000 
    );
});
</script>
<?php unset($_SESSION['flash_temp_password']); ?>
<?php endif; ?>

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
   <script>
function showToast(message, type = "success", duration = 3000) {

    const container = document.getElementById("toast-container");
    const toast = document.createElement("div");

    let bgColor = "";
    if (type === "success") bgColor = "bg-green-500";
    if (type === "error") bgColor = "bg-red-500";
    if (type === "warning") bgColor = "bg-yellow-500";

    toast.className = `
        ${bgColor}
        text-white px-6 py-4 rounded-2xl shadow-lg
        transform transition-all duration-500
        translate-x-10 opacity-0
    `;

    toast.innerHTML = `
        <div class="flex justify-between items-center gap-4">
            <span class="font-semibold">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="font-bold text-lg">×</button>
        </div>
    `;

    container.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.classList.remove("translate-x-10", "opacity-0");
    }, 100);

    // Auto remove only if duration > 0
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.add("opacity-0", "translate-x-10");
            setTimeout(() => toast.remove(), 500);
        }, duration);
    }
}
</script>
</div>