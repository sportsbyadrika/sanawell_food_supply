<?php
$config = require __DIR__ . '/../../config/config.php';
$user = $_SESSION['user'] ?? null;

/* ✅ FIX: Convert role_id → role slug */
$rolesConfig = $config['roles'];

function getRoleSlug($roleId, $rolesConfig) {
    foreach ($rolesConfig as $role) {
        if ($role['id'] == $roleId) {
            return $role['slug'];
        }
    }
    return null;
}

$role = isset($user['role_id'])
    ? getRoleSlug($user['role_id'], $rolesConfig)
    : ($user['role'] ?? null);
?>

<header class="sticky top-0 z-50 bg-gradient-to-r from-blue-500 to-slate-500">
<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

<!-- LOGO -->
<div class="flex items-center gap-3">
    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-slate-600 via-blue-500 to-slate-600 flex items-center justify-center shadow-lg ring-1 ring-white/30">
        <span class="text-white font-bold text-sm tracking-wide">SW</span>
    </div>
    <div class="leading-tight">
        <p class="text-sm font-semibold text-white">Sanawell Product Delivery</p>
        <p class="text-xs text-white/90">SaaS Delivery Management</p>
    </div>
</div>

<?php if ($user): ?>

<nav class="ml-auto flex items-center gap-6 text-sm font-medium text-white/90">

<!-- DASHBOARD (ALL) -->
<a href="index.php?route=dashboard" class="flex items-center gap-1 hover:text-white transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round"
        d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM3 21h8v-6H3v6zm10-10h8V3h-8v8z"/>
    </svg>
    Dashboard
</a>

<!-- SUPER ADMIN -->
<?php if ($role === 'super_admin'): ?>
<a href="index.php?route=agency" class="flex items-center gap-1 hover:text-white transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
        d="M3 7h18M3 12h18M3 17h18"/>
    </svg>
    Agency
</a>
<?php endif; ?>


<!-- AGENCY ADMIN + OFFICE STAFF -->
<?php if (in_array($role, ['agency_admin','office_staff'])): ?>

<!-- CUSTOMERS DROPDOWN -->
<div class="relative group">

<button class="flex items-center gap-1 hover:text-white transition">

<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
<path stroke-linecap="round" stroke-linejoin="round"
d="M17 20h5V9H2v11h5m10 0v-5a3 3 0 00-6 0v5m6 0H7"/>
</svg>

Customers

<svg xmlns="http://www.w3.org/2000/svg"
class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180"
fill="none" viewBox="0 0 24 24" stroke="currentColor">

<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M19 9l-7 7-7-7"/>

</svg>

</button>


<div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl
opacity-0 invisible group-hover:visible group-hover:opacity-100
transition-all duration-200 z-50">

<a href="index.php?route=customers"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Customer List
</a>

<a href="index.php?route=route_configuration"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Route Configuration
</a>

</div>

</div>



<!-- SETTINGS DROPDOWN -->
<div class="relative group">

<button class="flex items-center gap-1 hover:text-white transition">

<!-- Attractive Gear -->
<svg xmlns="http://www.w3.org/2000/svg"
class="w-5 h-5 transition-transform duration-300 group-hover:rotate-180"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="1.8">

<path stroke-linecap="round" stroke-linejoin="round"
d="M12 15.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5z"/>

<path stroke-linecap="round" stroke-linejoin="round"
d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06
a1.65 1.65 0 00-1.82-.33
1.65 1.65 0 00-1 1.51V21
a2 2 0 11-4 0v-.09
a1.65 1.65 0 00-1-1.51
1.65 1.65 0 00-1.82.33l-.06.06
a2 2 0 11-2.83-2.83l.06-.06
a1.65 1.65 0 00.33-1.82
1.65 1.65 0 00-1.51-1H3
a2 2 0 110-4h.09
a1.65 1.65 0 001.51-1
1.65 1.65 0 00-.33-1.82l-.06-.06
a2 2 0 112.83-2.83l.06.06
a1.65 1.65 0 001.82.33
1.65 1.65 0 001-1.51V3
a2 2 0 114 0v.09
a1.65 1.65 0 001 1.51
1.65 1.65 0 001.82-.33l.06-.06
a2 2 0 112.83 2.83l-.06.06
a1.65 1.65 0 00-.33 1.82
1.65 1.65 0 001.51 1H21
a2 2 0 110 4h-.09
a1.65 1.65 0 00-1.51 1z"/>

</svg>

Settings

</button>


<div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl
opacity-0 invisible group-hover:visible group-hover:opacity-100
transition-all duration-200 z-50">

<a href="index.php?route=products"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Users
</a>

<a href="index.php?route=vehicles"
class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
Vehicles
</a>

</div>

</div>


<!-- BILLS -->
<div class="relative group">

    <a href="#"
    class="flex items-center gap-1 hover:text-white transition">

        <svg xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 14l2-2 4 4m1-10H6a2 2 0 00-2 2v12l4-2 4 2 4-2 4 2V6a2 2 0 00-2-2z"/>
        </svg>

        Bills
    </a>

    <!-- DROPDOWN -->
    <div class="absolute left-0 hidden group-hover:block z-50">
        <div class="pt-2">
            <div class="bg-white text-black rounded shadow-lg w-48">

                <a href="index.php?route=generate_bill_page" class="block px-4 py-2 hover:bg-gray-100">
                    Generate Bill
                </a>

                <a href="index.php?route=bill_list" class="block px-4 py-2 hover:bg-gray-100">
                    Bill List
                </a>

                <a href="index.php?route=receipt_page" class="block px-4 py-2 hover:bg-gray-100">
                    Receipt Entry
                </a>

            </div>
        </div>
    </div>

</div> <!-- ✅ THIS IS IMPORTANT -->



<!-- REPORTS -->
<a href="index.php?route=delivery_report"
class="flex items-center gap-1 hover:text-white transition">

<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">

<path stroke-linecap="round" stroke-linejoin="round"
d="M3 3v18h18M7 15l3-3 4 4 5-5"/>

</svg>

Reports
</a>


<?php endif; ?>


<!-- LOGOUT -->
<a href="index.php?route=logout"
class="flex items-center gap-1 hover:text-white transition">

<svg xmlns="http://www.w3.org/2000/svg"
class="w-5 h-5"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="1.8">

<path stroke-linecap="round" stroke-linejoin="round"
d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1
a2 2 0 01-2 2H5
a2 2 0 01-2-2V7
a2 2 0 012-2h6
a2 2 0 012 2v1"/>

</svg>

Logout
</a>


</nav>

<?php endif; ?>

</div>
</header>