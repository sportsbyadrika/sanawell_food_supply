<div class="max-w-6xl p-6">
<div class="space-y-8">

    <!-- STAT CARDS -->
    <div class="grid gap-6 md:grid-cols-3">

    <!-- Total Agencies -->
    <a href="index.php?route=agencies&type=all">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Total Agencies</p>
            <p class="text-3xl font-bold text-blue-600">
                <?= (int) $totalAgencies ?>
            </p>
        </div>
    </a>

    <!-- Active Agencies -->
    <a href="index.php?route=agencies&type=active">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Active Agencies</p>
            <p class="text-3xl font-bold text-green-600">
                <?= (int) $activeAgencies ?>
            </p>
        </div>
    </a>

    <!-- Pending Requests -->
    <a href="index.php?route=agencies&type=pending">
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Pending Requests</p>
            <p class="text-3xl font-bold text-red-600">
                <?= (int) $pendingRequests ?>
            </p>
        </div>
    </a>

</div>