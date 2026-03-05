<div class="px-6 py-10">
   <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">
        ✏️ Edit Customer
    </h2>

    <a href="index.php?route=customer_manage&id=<?= $customer['id']; ?>"
       class="bg-gradient-to-br from-blue-500 to-slate-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
        + Add Product
    </a>

</div>   
  

        <form method="POST" action="index.php?route=customers_update">
            
            <!-- Hidden Fields -->
            <input type="hidden" name="id" value="<?= $customer['id'] ?>">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Name
                    </label>
                    <input type="text" name="name" required
                        value="<?= htmlspecialchars($customer['name']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Mobile
                    </label>
                    <input type="text" name="mobile" required
                        value="<?= htmlspecialchars($customer['mobile']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Whatsapp -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Whatsapp
                    </label>
                    <input type="text" name="whatsapp"
                        value="<?= htmlspecialchars($customer['whatsapp']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Address
                    </label>
                    <input type="text" name="address" required
                        value="<?= htmlspecialchars($customer['address']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Customer Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Customer Type
                    </label>
                    <select name="customer_type_id" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        
                        <option value="">Select Type</option>

                        <?php foreach ($types as $type): ?>
                            <option value="<?= $type['id'] ?>"
                                <?= $customer['category_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Route -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Route
                    </label>
                    <select name="route_id" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        
                        <option value="">Select Route</option>

                        <?php foreach ($routes as $route): ?>
                            <option value="<?= $route['id'] ?>"
                                <?= $customer['route_id'] == $route['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($route['name']) ?>
                                (<?= htmlspecialchars($route['type']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Latitude
                    </label>
                    <input type="text" name="latitude"
                        value="<?= htmlspecialchars($customer['latitude']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">
                        Longitude
                    </label>
                    <input type="text" name="longitude"
                        value="<?= htmlspecialchars($customer['longitude']) ?>"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end space-x-4">

                <a href="index.php?route=customers"
                   class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                   Cancel
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white rounded-lg hover:bg-blue-700 shadow-md">
                    Update customer
                </button>
                

            </div>

        </form>
    </div>
</div>