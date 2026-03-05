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
    }

    $this->render('agency/driver/driver_dashboard', [
        'routes' => $routes,
        'totalPending' => $totalPending,
        'totalDelivered' => $totalDelivered
    ]);
}
}