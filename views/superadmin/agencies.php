
<div class="bg-white shadow rounded-lg p-6">

    <!-- Header Row -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Supply Agencies
        </h2>

        <a href="index.php?route=agencies_create"
           class="bg-gradient-to-r from-blue-600 to-slate-600 to-indigo-600 text-white px-5 py-2 rounded-md shadow">
            + Add Agency
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="flex flex-wrap gap-4 items-end mb-6">
        <input type="hidden" name="route" value="agencies">

        <div>
            <label class="block text-sm text-gray-600 mb-1">Status</label>
            <select name="status" class="border rounded-md px-3 py-2 w-40">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">From</label>
            <input type="date" name="from_date" class="border rounded-md px-3 py-2">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">To</label>
            <input type="date" name="to_date" class="border rounded-md px-3 py-2">
        </div>

        <button type="submit"
                class="bg-gradient-to-r from-blue-600 to-slate-600 to-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-md">
            Filter
        </button>
        
        </form>
    

       <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b hover:bg-gray-50 transition">
                    <th class="py-2 text-left">Name</th>
                    <th class="py-2 text-left">Contact Number</th>
                    <th class="py-2 text-left">Contact Email</th>
                    <th class="py-2 text-left">Whatsapp Number</th>
                    <th class="py-2 text-left">Registerd Date</th>
                    <th class="py-2 text-left">Status</th>
                    <th class="py-2 text-left">Action</th>
                    <th class="py-2 text-left">Last Login</th>
                   
                </tr>
              
            </thead>
            <tbody>
                <?php foreach ($agencies as $agency): ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 space-x-3"><?= htmlspecialchars($agency['name']) ?></td>
                        <td class="py-3 space-x-3"><?= htmlspecialchars($agency['contact_number']) ?></td>
                        <td class="py-3 space-x-3"><?= htmlspecialchars($agency['contact_email']) ?></td>
                        <td class="py-3 space-x-3"><?= htmlspecialchars($agency['whatsapp_number']) ?></td>
                        <td class="py-3 space-x-3"><?= date('d-m-Y', strtotime($agency['created_at'])) ?></td>
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
                            <form method="POST" action="index.php?route=tenant_admin_reset" style="display:inline;">
    <input type="hidden" name="agency_id" value="<?= $agency['id'] ?>">
    <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
    <button type="submit"
        onclick="return confirm('Are you sure?')"
        class="text-red-600 hover:underline ">
        Reset Admin Password
    </button>
     <td class="py-2">
    <?= !empty($agency['last_login'])
        ? date('d-m-Y h:i A', strtotime($agency['last_login']))
        : 'Never logged in' ?>
</td>
     
</form>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($_SESSION['dev_temp_password'])): ?>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 p-3 rounded mb-4">
        <strong>Temporary Password:</strong>
        <?= $_SESSION['dev_temp_password']; ?>
    </div>
<?php unset($_SESSION['dev_temp_password']); ?>
<?php endif; ?>
                </div>
   
