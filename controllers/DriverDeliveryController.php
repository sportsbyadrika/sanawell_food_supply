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
    Auth::requireDriver();

    $id      = $_POST['order_id'] ?? null;
    $reason  = $_POST['reason'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $routeId = $_POST['route_id'] ?? null;

    if(!$id){
        header("Location: index.php?route=driver_delivery&route_id=".$routeId);
        exit;
    }

    $model = new DeliveryModel();
    $model->markNotDelivered($id,$reason,$remarks);

    header("Location: index.php?route=driver_delivery&route_id=".$routeId);
    exit;
}
}