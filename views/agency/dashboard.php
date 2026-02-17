<div class="max-w-6xl mx-auto p-6">
<div class="space-y-8">

    <!-- STAT CARDS -->
    <div class="grid gap-6 md:grid-cols-3">

        <!-- PRODUCTS CARD -->
        <a href="index.php?route=products"
           class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Active Products</p>
                    <p class="text-3xl font-bold text-blue-600">
                        <?= $activeProducts ?? 0 ?>
                    </p>
                </div>

                <!-- ICON -->
                <div class="bg-blue-100 p-4 rounded-full">
                    📦
                </div>
            </div>

          <div class="mt-4 bg-gradient-to-r from-blue-600 to-blue-800 
            bg-clip-text text-transparent font-semibold text-sm">
    View Products →
</div>
        </a>


        <!-- OFFICE STAFF CARD -->
        <a href="index.php?route=users&type=office"
           class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Office Staff</p>
                    <p class="text-3xl font-bold text-blue-600">
                        <?= $officeStaff ?? 0 ?>
                    </p>
                </div>

                <div class="bg-green-100 p-4 rounded-full">
                    👩‍💼
                </div>
            </div>

            <div class="mt-4 text-green-500 text-sm font-medium">
                Manage Staff →
            </div>
        </a>


        <!-- DRIVERS CARD -->
        <a href="index.php?route=users&type=driver"
           class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition block">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Drivers</p>
                    <p class="text-3xl font-bold text-blue-600">
                        <?= $drivers ?? 0 ?>
                    </p>
                </div>

                <div class="bg-yellow-100 p-4 rounded-full">
                    🚚
                </div>
            </div>

            <div class="mt-4 text-yellow-500 text-sm font-medium">
                Manage Drivers →
            </div>
        </a>

    </div>


    <!-- ACTION BUTTON -->
    <a href="index.php?route=products_create"
   class="inline-block bg-gradient-to-r from-blue-600 to-slate-600 
   text-white px-4 py-2 rounded shadow hover:shadow-lg transition">
   + Add Product
</a>
</div>