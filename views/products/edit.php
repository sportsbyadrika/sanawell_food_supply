<div class="max-w-2xl mx-auto mt-12">
    
    <div class="bg-white shadow-xl rounded-2xl p-8">
        
        <h2 class="text-xl font-medium text-gray-700 mb-6">
            ✏️ Edit Product
        </h2>

        <form method="POST" enctype="multipart/form-data" action="index.php?route=products_update&id=<?= $product['id'] ?>">
             <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
             <input type="hidden" name="id" value="<?= $product['id'] ?>">
             
             <div class="mb-4">
    <label class="block text-sm font-medium mb-2">Product Image</label>

     <?php if (!empty($product['image'])): ?>
        <div class="mb-3">
            <img 
                src="/sanawell_food_supply/public/uploads/<?= htmlspecialchars($product['image']) ?>" 
                class="w-24 h-24 object-cover rounded shadow"
            >
        </div>
    <?php endif; ?>

    <input type="file"
           name="image"
           accept="image/*"
           class="border p-2 rounded w-full">

    
    <input type="hidden" name="old_image" value="<?= htmlspecialchars($product['image']) ?>">
</div>
            <!-- Product Name -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Product Name
                </label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($product['name']) ?>"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       required>
            </div>

            <!-- Description -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Description
                </label>
                <textarea name="description"
                          class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                          rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <!-- Product Type -->
            <?php $type = $product['variant'] ?? ''; ?>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Product Type
                </label>

                <select name="variant"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    
                    <option value="packed_500ml" <?= $type=='packed_500ml'?'selected':'' ?>>
                        🥛 Packed (500 ml)
                    </option>

                    <option value="loose_500ml" <?= $type=='loose_500ml'?'selected':'' ?>>
                        🧴 Loose (500 ml)
                    </option>

                    <option value="loose_1l" <?= $type=='loose_1l'?'selected':'' ?>>
                        🧴 Loose (1 L)
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center">

                <a href="index.php?route=products"
                   class="text-gray-500 hover:text-blue-600 transition">
                    ← Back to Products
                </a>
              
                <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-slate-700 text-white px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition">
                    Update Product
                </button>

            </div>

        </form>

    </div>
</div>