<div class="max-w-6xl mx-auto mt-8">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                🚚 Route Configuration
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Manage delivery order and route customer sequence
            </p>
        </div>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <!-- Table Header -->
        <div class="px-6 py-4 bg-gradient-to-br from-blue-500 to-slate-500 text-white">
            <div class="grid grid-cols-12 font-semibold text-sm uppercase tracking-wide">
                <div class="col-span-4">Route</div>
                <div class="col-span-4">Details</div>
                <div class="col-span-2 text-center">Customers</div>
                <div class="col-span-2 text-center">Action</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y">

            <?php if (!empty($routes)) : ?>
                <?php foreach ($routes as $row) : ?>

                    <div class="grid grid-cols-12 items-center px-6 py-4 hover:bg-blue-50 transition">

                        <!-- Route Name + Type -->
                        <div class="col-span-4">
                            <p class="font-semibold text-gray-800">
                                <?= $row['name'] ?>
                                <span class="text-sm text-gray-500">
                                    (<?= ucfirst($row['type']) ?>)
                                </span>
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="col-span-4">
                            <p class="text-gray-600">
                                <?= $row['description'] ?>
                            </p>
                        </div>

                        <!-- Customer Count -->
                        <div class="col-span-2 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                <?= $row['total_customers'] ?> Customers
                            </span>
                        </div>

                        <!-- Manage Button -->
                        <div class="col-span-2 text-center">
                             <a href="index.php?route=route_configuration_manage&id=<?= $row['id'] ?>"
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-br from-blue-500 to-slate-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                Manage
                            </a>
                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else : ?>

                <!-- Empty State -->
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500 text-lg">
                        No routes available
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>