<?php
$config = require __DIR__ . '/../../config/config.php';
$user = $_SESSION['user'] ?? null;
$agencyName = $user['agency_name'] ?? null;
?>

<header class="sticky top-0 z-50 bg-gradient-to-br from-blue-500 to-slate-500">
<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

<!-- Logo -->
<div class="flex items-center gap-3">
<div class="h-9 w-9 rounded-xl 
bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600
flex items-center justify-center
shadow-lg ring-1 ring-white/30">

<span class="text-white font-bold text-sm tracking-wide">SW</span>

</div>

<div class="leading-tight">
<p class="text-sm font-semibold text-white">SanaWell Product Delivery</p>
<p class="text-xs text-white">SaaS Delivery Management</p>
</div>

<?php if ($user): ?>

<nav class="ml-auto flex items-center gap-6 text-sm font-medium text-white/90">

<!-- Agency name -->
<?php if (!empty($_SESSION['agency'])): ?>
<span class="inline-flex items-center px-3 py-1 rounded-full
bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600
shadow-lg ring-1 ring-white/30 text-sm font-semibold text-white">
<?= htmlspecialchars($_SESSION['agency']['name']) ?>
</span>
<?php endif; ?>


<!-- DRIVER -->
<?php if (Auth::hasRole($config['roles']['DRIVER']['slug'])): ?>

<a href="index.php?route=driver_dashboard"
class="text-white hover:text-blue-200 transition">
Dashboard
</a>


<!-- OFFICE STAFF -->
<?php elseif (Auth::hasRole($config['roles']['OFFICE_STAFF']['slug'])): ?>

<a href="index.php?route=dashboard"
class="text-white hover:text-blue-200 transition">
Dashboard
</a>

<a href="index.php?route=customers"
class="text-white hover:text-blue-200 transition">
Customers
</a>

<a href="index.php?route=route_configuration"
class="text-white hover:text-blue-200 transition">
Routes
</a>


<a href="index.php?route=delivery_report"
class="text-white hover:text-blue-200 transition">
Reports
</a>


<!-- AGENCY ADMIN -->
<?php elseif (Auth::hasRole($config['roles']['AGENCY_ADMIN']['slug'])): ?>

<a href="index.php?route=dashboard"
class="text-white hover:text-blue-200 transition">
Dashboard
</a>

<!-- Customers Dropdown -->
<div class="relative group">
<button class="flex items-center gap-2 text-white hover:text-white transition font-medium">

<span>Customers</span>

<svg xmlns="http://www.w3.org/2000/svg"
class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="2">
<path stroke-linecap="round"
stroke-linejoin="round"
d="M19 9l-7 7-7-7"/>
</svg>

</button>

<div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl
opacity-0 invisible group-hover:opacity-100
group-hover:visible transition-all duration-200 z-50">

<a href="index.php?route=customers"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl">
Customer List
</a>

<a href="index.php?route=route_configuration"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Route Configuration
</a>

</div>
</div>


<!-- Settings Dropdown -->
<div class="relative group">

<button class="flex items-center gap-2 text-white hover:text-white transition font-medium">

<svg xmlns="http://www.w3.org/2000/svg"
class="w-5 h-5 transition-transform duration-300 group-hover:rotate-90"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="1.5">

<path stroke-linecap="round"
stroke-linejoin="round"
d="M12 9a3 3 0 100 6 3 3 0 000-6z"/>

</svg>

<span>Settings</span>

</button>

<div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl
opacity-0 invisible group-hover:opacity-100
group-hover:visible transition-all duration-200 z-50">

<a href="index.php?route=products"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-xl">
Products
</a>

<a href="index.php?route=customer_categories"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Categories
</a>

<a href="index.php?route=routes"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Routes
</a>

<a href="index.php?route=users"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-xl">
Users
</a>
<a href="index.php?route=vehicles"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Vehicles
</a>

</div>

</div>

<a href="index.php?route=delivery_report"
class="px-3 py-2 rounded-md hover:bg-white/20 transition">
Reports
</a>


<!-- SUPER ADMIN -->
<?php elseif (Auth::hasRole($config['roles']['SUPER_ADMIN']['slug'])): ?>

<a href="index.php?route=agencies"
class="text-white hover:text-blue-200 transition">
Agencies
</a>

<?php endif; ?>


<!-- Divider -->
<span class="h-5 w-px bg-gray-300"></span>

<!-- Logout -->
<a href="index.php?route=logout"
class="px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white transition">
Logout
</a>

</nav>

<?php endif; ?>

</div>
</div>
</header>