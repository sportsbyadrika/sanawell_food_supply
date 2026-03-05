<?php

class DriverDeliveryController extends BaseController
{
    public function start()
    {
        Auth::requireDriver();

        $routeId = $_GET['route_id'] ?? null;

        if (!$routeId) {
            header("Location: index.php?route=driver_dashboard");
            exit;
        }

        $deliveryModel = new DeliveryModel();

        // Check if already generated
        if (!$deliveryModel->deliveryExists($routeId)) {
            $deliveryModel->generateDeliveryForRoute($routeId);
        }

        header("Location: index.php?route=driver_delivery&route_id=" . $routeId);
        exit;
    }

    public function view()
    {
        Auth::requireDriver();

        $routeId = $_GET['route_id'] ?? null;

        $deliveryModel = new DeliveryModel();
        $deliveries = $deliveryModel->getDeliveriesByRoute($routeId);

        $this->render('agency/driver/driver_delivery_view', [
            'deliveries' => $deliveries
        ]);
    }

    public function markDelivered()
{
    if (!isset($_GET['order_id'])) {
        die("Order ID missing");
    }

    $orderId = $_GET['order_id'];

    $model = new DeliveryModel();
    $model->markAsDelivered($orderId);

    $routeId = $model->getRouteIdByOrder($orderId);

    header("Location: index.php?route=driver_delivery&route_id=" . $routeId);
    exit;
}
}