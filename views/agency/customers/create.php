<div class="h-[calc(100vh-120px)] flex items-center justify-center px-6">

    <div class="w-full max-w-6xl bg-white rounded-2xl shadow-xl p-8">

        <!-- 🔵 Header Section -->
        <div class="mb-8 flex items-center gap-4">

            <div class="bg-blue-100 text-blue-600 p-4 rounded-2xl shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M18 9v6m3-3H15m-6 6H6a2 2 0 01-2-2V6a2 2 0 012-2h3"/>
                </svg>
            </div>

            <div>
                <h2 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-slate-600 to-indigo-600 bg-clip-text text-transparent">
                    Add New Customer
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Enter customer details and assign route & type
                </p>
            </div>

        </div>

        <!-- 🔵 Form -->
        <form method="POST" action="index.php?route=customers_store">
            <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Customer Name -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Customer Name
                    </label>
                    <input type="text"
                           name="name"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <!-- Mobile -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Mobile
                    </label>
                    <input type="text"
                           name="mobile"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <!-- WhatsApp -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        WhatsApp
                    </label>
                    <input type="text"
                           name="whatsapp"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Customer Type -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Customer Type
                    </label>
                    <select name="customer_type_id" required
    class="w-full border rounded-lg px-2 py-2 focus:ring-2 focus:ring-blue-500">
    
    <option value="">Select Type</option>

    <?php foreach ($types as $type): ?>
        <option value="<?= $type['id'] ?>">
            <?= htmlspecialchars($type['name']) ?>
        </option>
    <?php endforeach; ?>

</select>

                </div>

                <!-- Route -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Route
                    </label>
                    <select name="route_id" required
    class="w-full border rounded-lg px-2 py-2 text-s  focus:ring-2 focus:ring-blue-500">

    <option value="">Select Route</option>

    <?php foreach ($routes as $route): ?>
        <option value="<?= $route['id'] ?>">
            <?= htmlspecialchars($route['name']) ?>
            (<?= htmlspecialchars($route['type']) ?>)
        </option>
    <?php endforeach; ?>

</select>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Latitude
                    </label>
                    <input type="text"
                           name="latitude"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Longitude
                    </label>
                    <input type="text"
                           name="longitude"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-s font-semibold text-gray-500 mb-1">
                        Address
                    </label>
                    <textarea name="address"
                              rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-8">
                <button type="submit"
                        class="bg-gradient-to-br from-blue-500 to-slate-500 hover:opacity-90 text-white px-8 py-2 rounded-lg text-sm font-medium transition">
                    Add Customer
                </button>
            </div>

        </form>

    </div>

</div>