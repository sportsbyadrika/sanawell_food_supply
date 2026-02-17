<div class="max-w-2xl mx-auto mt-12">
    
    <div class="bg-white shadow-xl rounded-2xl p-8">
        
        <h2 class="text-xl font-medium text-gray-700 mb-6">
            ✏️ Edit Customer Type
        </h2>

        <form method="POST" action="index.php?route=customer_categories_update">
             <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
             <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <!-- Product Name -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Customer Type
                </label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($category['name']) ?>"
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
                          rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>
            <div class="flex justify-between items-center">
                             <button type="submit"
                        class="bg-gradient-to-r from-blue-600 to-slate-700 text-white px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition">
                    Update 
                </button>
<a href="index.php?route=customer_categories"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                   Cancel
                </a>
            </div>

                       </div>
                        <!-- Buttons -->
            
   </form>
</div>
           

     

    
</div>