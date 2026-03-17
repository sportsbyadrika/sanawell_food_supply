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
    $route_id = $_GET['route_id'] ?? null;

    $model = new DeliveryModel();

    $deliveries = $model->getDeliveriesByRoute($route_id);

    $this->render('agency/driver/driver_delivery_view', [
        'deliveries' => $deliveries,
        'route_id' => $route_id
    ]);
}

public function markDelivered()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        return;
    }

    $model = new DeliveryModel();

    $model->markAsDelivered($id);

    $routeId = $model->getRouteIdByOrder($id);

    header("Location:index.php?route=driver_delivery&route_id=".$routeId);
    exit;
}
public function saveNotDelivered()
{
    $order_id = $_POST['order_id'];
    $route_id = $_POST['route_id'];
    $reason = $_POST['reason'];
    $remarks = $_POST['remarks'];

    $deliveryModel = new DeliveryModel();

    // 1. Update status
    $deliveryModel->updateStatus($order_id, 'cancelled', $reason, $remarks);

    // 2. Insert into daily bill (IMPORTANT)
    if (!$deliveryModel->checkBillExists($order_id)) {

        $items = $deliveryModel->getOrderItems($order_id);

        foreach ($items as $item) {
            $deliveryModel->insertDailyBill([
                'delivery_order_id' => $order_id,
                'product_id' => $item['product_id'],
                'qty' => 0, // ❗ cancelled
                'amount' => 0,
                'status' => 'cancelled'
            ]);
        }
    }

    // 3. Redirect back
    header("Location: index.php?route=driver_delivery&route_id=" . $route_id);
    exit;
}

public function updateStatus()
{
    $order_id = $_POST['order_id'];
    $route_id = $_POST['route_id'];

    $deliveryModel = new DeliveryModel();

    // 1. Update status
    $deliveryModel->updateStatus($order_id, 'delivered');

    // 2. Prevent duplicate bill
    if (!$deliveryModel->checkBillExists($order_id)) {

        $items = $deliveryModel->getOrderItems($order_id);

        foreach ($items as $item) {
            $deliveryModel->insertDailyBill([
                'delivery_order_id' => $order_id,
                'product_id' => $item['product_id'],
                'qty' => $item['quantity'],
                'amount' => $item['total_amount'],
                'status' => 'delivered'
            ]);
        }
    }

    // 3. Redirect back
    header("Location: index.php?route=driver_delivery&route_id=" . $route_id);
    exit;
}
}