<?php

class DriverDashboardController extends BaseController
{
   public function index(): void
{
    if (
        !isset($_SESSION['user']) ||
        (int)$_SESSION['user']['role_id'] !== $this->config['roles']['DRIVER']['id']
    ) {
        header("Location: index.php?route=login");
        exit;
    }

    $driverId = $_SESSION['user']['id'];

    $routeModel = new Route();
    $deliveryModel = new DeliveryModel();

    $routes = $routeModel->getByDriver($driverId);

    $totalPending = 0;
    $totalDelivered = 0;

   foreach ($routes as &$route) {

    $counts = $deliveryModel->getDeliveryCounts($route['id']);

    $route['pending_count'] = $counts['pending'];
    $route['delivered_count'] = $counts['delivered'];

    $totalPending += $counts['pending'];
    $totalDelivered += $counts['delivered'];

    // NEW PRODUCT TOTALS
    $milk = 0;
$curd = 0;

$products = $deliveryModel->getRouteProductTotals($route['id']);

foreach ($products as $p) {

 $productName = $p['product_name'] . ' (' . $p['variant'] . ')';
$route['products'][$productName] = $p['qty'];
    $name = strtolower($p['product_name']);

    if (strpos($name, 'milk') !== false) {
        $milk += $p['qty'];
    }

    if (strpos($name, 'curd') !== false) {
        $curd += $p['qty'];
    }
}

$route['milk_total'] = $milk;
$route['curd_total'] = $curd;

    $this->render('agency/driver/driver_dashboard', [
        'routes' => $routes,
        'totalPending' => $totalPending,
        'totalDelivered' => $totalDelivered
    ]);
}
}
}