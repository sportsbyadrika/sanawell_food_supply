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

   

    'agencies' => [AgencyController::class, 'index'],
    'agencies_store' => [AgencyController::class, 'store'],
    'agencies_status' => [AgencyController::class, 'updateStatus'],
    'tenant_admin_reset' => [AgencyController::class, 'resetAdminPassword'],
    'agency_users' => [AgencyController::class, 'users'],
    'agencies_create' => [AgencyController::class, 'create'],
    
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

    'dashboard' => [DashboardController::class, 'index'],
    // DRIVER
'driver_dashboard' => [DriverDashboardController::class, 'index'],
'driver_start_delivery' => [DriverDeliveryController::class, 'start'],
'driver_delivery' => [DriverDeliveryController::class, 'view'],
'driver_mark_delivered' => [DriverDeliveryController::class, 'markDelivered'],
'driver_save_not_delivered' => [DriverDeliveryController::class, 'saveNotDelivered'],

    'product_rates' => [ProductRateController::class, 'manage'],
    'product_rate_store' => [ProductRateController::class, 'store'],
    'customers' => [CustomerController::class, 'index'],
    'customers_create' => [CustomerController::class, 'create'],
'customers_store' => [CustomerController::class, 'store'],
'customers_edit'   => [CustomerController::class, 'edit'],
'customers_update' => [CustomerController::class, 'update'],
'customers_toggle' => [CustomerController::class, 'toggle'],
 'customers_search' => [CustomerController::class, 'search'],
 'customer_product_toggle'=>[CustomerController::class,'toggleProduct'],
 'customer_product_update'=>[CustomerController::class,'updateProduct'],
    'get_product_rate'=>[CustomerController::class,'getProductRate'],
    'agency_customers_import' => [CustomerController::class, 'import'],
'agency_customers_import_process' => [CustomerController::class, 'importProcess'],
    'customer_categories' => [CustomerCategoryController::class, 'index'],
    'customer_categories_store' => [CustomerCategoryController::class, 'store'],
     'customer_categories_edit' => [CustomerCategoryController::class, 'edit'],
      'customer_categories_update' => [CustomerCategoryController::class, 'update'],
      'customer_manage' => [CustomerController::class, 'manage'],
'customer_product_store' => [CustomerController::class, 'storeProduct'],
'customer_product_delete' => [CustomerController::class, 'deleteProduct'],
    'customer_categories_toggle' => [CustomerCategoryController::class, 'toggle'],

    'product_rates' => [RateController::class, 'manage'],
    'rate_store' => [RateController::class, 'store'],

    'routes' => [RouteController::class, 'index'],
'routes_store' => [RouteController::class, 'store'],
'routes_edit' => [RouteController::class, 'edit'],
'routes_update' => [RouteController::class, 'update'],
'routes_toggle' => [RouteController::class, 'toggle'],
'route_configuration' => [RouteConfigurationController::class, 'index'],
'route_configuration_manage' => [RouteConfigurationController::class, 'manage'],
'route_order_update' => [RouteConfigurationController::class, 'updateRouteOrder'],
'generate_delivery' => [RouteConfigurationController::class, 'generateDelivery'],
'today_delivery_view' => [RouteConfigurationController::class, 'todayDeliveryView'],

'updateQty' => [RouteConfigurationController::class, 'updateQty'],
'cancel_order' => [RouteConfigurationController::class, 'cancelOrder'],

'delivery_report' => [ReportController::class, 'deliveryreport'],
'generate_monthly_bill'=>[ReportController::class,'generateMonthlyBill'],
'generate_bill_page' => [BillController::class, 'generateBillPage'],
'generate_bill' => [BillController::class, 'generateBill'],
'bill_list' => [BillController::class, 'billList'],

'receipt_entry' => [BillController::class, 'receiptEntry'],
'save_receipt' => [BillController::class, 'saveReceipt'],
'receipt_page' => [BillController::class, 'receiptPage'],
'change_request'=>[ChangeRequestController::class,'index'],
'change_request_store'=>[ChangeRequestController::class,'store'],
'change_request_cancel'=>[ChangeRequestController::class,'cancel'],
'vehicles'=>[VehicleController::class,'index'],
'vehicle_create'=>[VehicleController::class,'create'],
'vehicle_store'=>[VehicleController::class,'store'],
'update_delivery_status' => [DriverDeliveryController::class, 'updateStatus'],




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
