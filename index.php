<?php
require __DIR__ . '/helpers/Session.php';
require __DIR__ . '/helpers/Database.php';
require __DIR__ . '/helpers/Auth.php';
require __DIR__ . '/helpers/Csrf.php';

Session::start();

// Basic autoload for controllers and models.
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
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
    'dashboard' => [DashboardController::class, 'index'],
    'agencies' => [AgencyController::class, 'index'],
    'agencies_store' => [AgencyController::class, 'store'],
    'agencies_status' => [AgencyController::class, 'updateStatus'],
    'users' => [UserController::class, 'index'],
    'users_store' => [UserController::class, 'store'],
    'products' => [ProductController::class, 'index'],
    'products_store' => [ProductController::class, 'store'],
    'product_rates' => [RateController::class, 'edit'],
    'product_rates_update' => [RateController::class, 'update'],
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
