

<div class="max-w-6xl mx-auto p-6">
   
    <!-- Product Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">

       <div class="bg-gradient-to-br from-blue-500 to-slate-500
            text-white px-6 py-3 font-semibold
            flex items-center justify-between rounded-t-xl">

    <span>Product List</span>

    <a href="index.php?route=products_create"
       class="bg-white/20 hover:bg-white/30
              text-white text-sm px-4 py-1.5 rounded-lg
              transition shadow-sm">
        + Add Product
    </a>

</div>

        <table class="w-full text-sm">
            <thead class="bg-slate-100 text-slate-700">
    <tr>
        <th class="px-4 py-3 text-left">Image</th>
        <th class="px-4 py-3 text-left">Name</th>
        <th class="px-4 py-3 text-left">Description</th>
         <th class="px-4 py-3 text-left">Product Type</th>
        <th class="px-4 py-3 text-left">Rates</th>
        <th class="px-4 py-3 text-left">Actions</th>
    </tr>
</thead>

            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr class="border-t">
                       <td class="px-4 py-3">
<?php if (!empty($product['image']) && file_exists(__DIR__ . '/../../public/uploads/' . $product['image'])): ?>
    <img 
        src="uploads/<?= htmlspecialchars($product['image']) ?>" 
        class="w-16 h-16 object-cover rounded-lg shadow"
        alt="Product Image">
<?php else: ?>
    <span class="text-gray-400 text-sm">No Image</span>
<?php endif; ?>
</td>
                        <td class="px-4 py-3 font-medium">
                            <?= htmlspecialchars($product['name']) ?>
                        </td>

                        <td class="px-4 py-3 text-slate-600">
                            <?= htmlspecialchars($product['description']) ?>
                        </td>
                         <td class="px-4 py-3 text-slate-600">
                            <?= htmlspecialchars($product['variant']) ?>
                        </td>
                        
                        <td class="px-4 py-3">
                           <a href="index.php?route=product_rates&id=<?= $product['id'] ?>"
                               class="text-blue-600 hover:underline font-medium">
                                Manage Rates
                            </a>
                        </td>
                        <td class="px-4 py-3">
    <a href="index.php?route=products_edit&id=<?= $product['id'] ?>"
       class="text-blue-600 hover:underline font-medium">
       Edit
    </a> 
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </table>

    </div>
 
</div>
                