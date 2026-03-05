<main class="bg-slate-50">
  <div class="max-w-6xl mx-auto px-6 mt-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 justify-center">
   <a href="index.php?route=agencies&type=all">
    <div class="bg-white p-6 rounded-lg shadow cursor-pointer hover:shadow-xl transition">
        <p class="text-sm text-slate-500 mb-1">Total Agencies</p>
        <p class="text-3xl font-semibold text-blue-600"><?= (int) $totalAgencies ?></p>
    </div>
</a>
   <a href="index.php?route=agencies&type=active">
    <div class="bg-white p-6 rounded-lg shadow cursor-pointer hover:shadow-xl transition">
        <p class="text-sm text-slate-500 mb-1">Active Agencies</p>
        <p class="text-3xl font-semibold text-blue-600"><?= (int) $activeAgencies ?></p>
    </div>
</a>
   <a href="index.php?route=agencies&type=pending">
    <div class="bg-white p-6 rounded-lg shadow cursor-pointer hover:shadow-xl transition">
        <p class="text-sm text-slate-500 mb-1">Pending Requests</p>
        <p class="text-3xl font-semibold text-blue-600"><?= (int) $pendingRequests ?></p>
    </div>
</a>
