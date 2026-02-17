<?php

require __DIR__ . '/../helpers/Session.php';
require __DIR__ . '/../helpers/Database.php';
require __DIR__ . '/../helpers/Auth.php';
require __DIR__ . '/../helpers/Csrf.php';

Session::start();

// Basic autoload for controllers and models.
spl_autoload_register(function ($class) {
    $paths = [
    __DIR__ . '/../controllers/' . $class . '.php',
    __DIR__ . '/../models/' . $class . '.php',
];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$route = $_GET['route'] ?? 'login';

$routes = [
    'login' => [AuthController::class, 'showLogin'],
    'logout' => [AuthController::class, 'logout'],
    'login_post' => [AuthController::class, 'login'],
     'change_password' => [AuthController::class, 'changePassword'],
     'forgot_password' => [AuthController::class, 'forgotPasswordForm'],
'forgot_password_send' => [AuthController::class, 'sendResetLink'],
'reset_password' => [AuthController::class, 'resetPasswordForm'],
'reset_password_submit' => [AuthController::class, 'resetPassword'],

    'dashboard' => [DashboardController::class, 'index'],
    'driver_dashboard' => [DriverDashboardController::class, 'index'],
    'agencies' => [AgencyController::class, 'index'],
    'agencies_store' => [AgencyController::class, 'store'],
    'agencies_status' => [AgencyController::class, 'updateStatus'],
    'agency_users' => [AgencyController::class, 'users'],
    'users' => [UserController::class, 'index'],
    'users_store' => [UserController::class, 'store'],
       'users_edit'   => [UserController::class, 'edit'],
      'users_update' => [UserController::class, 'update'],
      'users_toggle' => [UserController::class, 'toggle'],
    'products' => [ProductController::class, 'index'],
    'products_create' => [ProductController::class, 'create'],
    'products_store' => [ProductController::class, 'store'],
    'products_edit' => [ProductController::class, 'edit'],
    'products_update' => [ProductController::class, 'update'],

    'product_rates' => [ProductRateController::class, 'manage'],
    'product_rate_store' => [ProductRateController::class, 'store'],
    'customers' => [CustomerController::class, 'index'],
    'customers_create' => [CustomerController::class, 'create'],
'customers_store' => [CustomerController::class, 'store'],
'customers_edit'   => [CustomerController::class, 'edit'],
'customers_update' => [CustomerController::class, 'update'],
'customers_toggle' => [CustomerController::class, 'toggle'],
 'customers_search' => [CustomerController::class, 'search'],
    
    'customer_categories' => [CustomerCategoryController::class, 'index'],
    'customer_categories_store' => [CustomerCategoryController::class, 'store'],
     'customer_categories_edit' => [CustomerCategoryController::class, 'edit'],
      'customer_categories_update' => [CustomerCategoryController::class, 'update'],
    'customer_categories_toggle' => [CustomerCategoryController::class, 'toggle'],

    'product_rates' => [RateController::class, 'edit'],
    'product_rates_update' => [RateController::class, 'update'],

    'routes' => [RouteController::class, 'index'],
'routes_store' => [RouteController::class, 'store'],
'routes_edit' => [RouteController::class, 'edit'],
'routes_update' => [RouteController::class, 'update'],
'routes_toggle' => [RouteController::class, 'toggle'],

'route_assign' => [RouteCustomerController::class, 'assignPage'],
'route_assign_store' => [RouteCustomerController::class, 'store'],
];

if ($route === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $route = 'login_post';
}

if (!isset($routes[$route])) {
    http_response_code(404);
    echo 'Route not found';
    exit;
}

[$controllerClass, $method] = $routes[$route];
$controller = new $controllerClass();
$controller->$method();
