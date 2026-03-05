<div class="max-w-6xl mx-auto p-6">
<div class="bg-white shadow rounded-xl p-6">
   <form method="POST"  action="index.php?route=customer_categories_store">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        <div class="grid grid-cols-3 gap-6 items-end">

            <!-- Customer Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Customer Categories
                </label>
                <input 
                    type="text" 
                    name="name"
                    placeholder="Enter customer type"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>
                <input 
                    type="text" 
                    name="description"
                    placeholder="Enter description"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Add Button -->
            <div>
                 <button type="submit"
                   class="bg-gradient-to-br from-blue-500 to-slate-500  text-white px-4 py-2 rounded-lg">
                Add Category
            </button>
            </div>

        </div>
    </form>
</div>
</div>

    <!-- Category List -->
     <div class="max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow">
        
        <table class="w-full">
            <thead class="bg-gradient-to-br from-blue-500 to-slate-500  text-white font-bold text-sm tracking-wide text-left">
                <tr>
                    <th class="px-4 py-3 text-left">Name</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Actions</th>
                    <th class="px-4 py-3 text-left">Customers</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                <tr class="border-t">
                    <td class="px-4 py-3"><?= htmlspecialchars($c['name']) ?></td>
                    
                    <td class="px-4 py-3"><?= htmlspecialchars($c['description']) ?></td>
                   
                    <td class="px-4 py-3">
    <span class="px-3 py-1 rounded-full text-xs font-medium 
        <?= $c['status'] == 'active' || $c['status'] == 1
            ? 'bg-emerald-100 text-emerald-700'
            : 'bg-red-100 text-red-700' ?>">
        <?= ($c['status'] == 'active' || $c['status'] == 1) ? 'Active' : 'Inactive' ?>
    </span>
</td>
                   <td class="px-4 py-3">
    <div class="flex items-center gap-4">
       <a href="index.php?route=customer_categories_edit&id=<?= $c['id'] ?>"
   class="text-blue-600 hover:underline font-medium text-sm">
   Edit
</a>
<a href="index.php?route=customer_categories_toggle&id=<?= $c['id'] ?>"
   class="text-green-600 hover:underline font-medium text-sm">
   Toggle 
</a>
    </div>
</td>
<td class="px-4 py-3"><?= $c['customer_count'] ?></td>

                    
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
                </div>