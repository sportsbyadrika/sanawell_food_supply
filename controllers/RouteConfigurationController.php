<?php

class RouteConfigurationController extends BaseController
{
    private $routeModel;

    public function __construct()
    {
        parent::__construct();
        $this->routeModel = new Route();
         $this->DeliveryModel = new DeliveryModel();
    }

    public function index()
    {
        Auth::requireAgencyAdmin();

        $agencyId = Auth::user()['agency_id'];

        $routes = $this->routeModel
            ->getRoutesWithCustomerCount($agencyId);

        $this->render('agency/routes/route_configuration', [
            'routes' => $routes
        ]);
    }
   public function manage()
{
    Auth::requireAgencyAdmin();

    $routeId = $_GET['id'] ?? null;

    if (!$routeId) {
        die("Route not found");
    }

    $agencyId = Auth::user()['agency_id'];

    $route = $this->routeModel->findById($routeId);

   $customers = $this->routeModel->getCustomersByRoute($routeId);

if (!empty($customers) && is_array($customers)) {

   foreach ($customers as &$customer) {
    $customer['product_name'] =
        $this->routeModel->get_products_by_customer($customer['id']);
}

} else {
    $customers = [];
}

    $this->render('agency/routes/route_manage', [
        'route' => $route,
        'customers' => $customers
    ]);
}

public function updateRouteOrder()
{
    Auth::requireAgencyAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $customerId = $_POST['customer_id'] ?? null;
        $newOrder = (int)($_POST['new_order'] ?? 0);
        $routeId = $_POST['route_id'] ?? $_GET['id'] ?? null;

        if (!$customerId || !$newOrder || !$routeId) {
            die("Invalid request");
        }

        $db = Database::connection();

        
        $stmt = $db->prepare("
            SELECT delivery_order 
            FROM route_customers 
            WHERE route_id = ? AND customer_id = ?
        ");

        $stmt->execute([$routeId, $customerId]);
        $current = $stmt->fetchColumn();

        if (!$current) {
            die("Customer not found in route");
        }

        
        if ($newOrder > $current) {

            $shift = $db->prepare("
                UPDATE route_customers
                SET delivery_order = delivery_order - 1
                WHERE route_id = ?
                AND delivery_order > ?
                AND delivery_order <= ?
            ");

            $shift->execute([$routeId, $current, $newOrder]);
        }

        
        if ($newOrder < $current) {

            $shift = $db->prepare("
                UPDATE route_customers
                SET delivery_order = delivery_order + 1
                WHERE route_id = ?
                AND delivery_order >= ?
                AND delivery_order < ?
            ");

            $shift->execute([$routeId, $newOrder, $current]);
        }

        
        $update = $db->prepare("
            UPDATE route_customers
            SET delivery_order = ?
            WHERE route_id = ? AND customer_id = ?
        ");

        $update->execute([$newOrder, $routeId, $customerId]);

        header("Location: index.php?route=route_configuration_manage&id=".$routeId);
        exit;
    }
}
public function generateDelivery()
{
    Auth::requireAgencyAdmin();

    $route_id = $_GET['id'] ?? null;

    if (!$route_id) {
        die("Route not found");
    }

    $deliveryModel = new DeliveryModel();

    if ($deliveryModel->deliveryExistsToday($route_id)) {
        $_SESSION['info'] = "Today's delivery already generated.";
        header("Location: index.php?route=today_delivery_view&id=".$route_id);
        exit;
    }

    $deliveryModel->generateDeliveryForRoute($route_id);

    $_SESSION['success'] = "Today's delivery generated successfully.";
    header("Location: index.php?route=today_delivery_view&id=".$route_id);
    exit;
}

public function todayDeliveryView()
{
    Auth::requireAgencyAdmin();

    $routeId = $_GET['id'] ?? null;

    if (!$routeId) {
        die("Route not found");
    }

    $routeModel = new Route();
    $route = $routeModel->find($routeId);

    if (!$route) {
        die("Invalid route");
    }

    // ✅ Check if route has customers
    $customers = $routeModel->getCustomersByRoute($routeId);

    if (empty($customers)) {
        $_SESSION['error'] = "No customers assigned to this route.";
        header("Location: index.php?route=route_configuration_manage&id=" . $routeId);
        exit;
    }

    $deliveryModel = new DeliveryModel();
    $loadSummary = $deliveryModel->getDeliveryLoadSummary($routeId);

    if (!$deliveryModel->deliveryExistsToday($routeId)) {
        $deliveryModel->generateDeliveryForRoute($routeId);
    }

    $deliveries = $deliveryModel->getTodayDeliveries($routeId);

    $pendingCount = 0;
    $deliveredCount = 0;

    foreach ($deliveries as $delivery) {
        if ($delivery['status'] === 'pending') {
            $pendingCount++;
        } elseif ($delivery['status'] === 'delivered') {
            $deliveredCount++;
        }
    }

  $this->render('agency/routes/today_delivery_view', [
    'route' => $route,
    'deliveries' => $deliveries,
    'pendingCount' => $pendingCount,
    'deliveredCount' => $deliveredCount,
    'loadSummary' => $loadSummary
]);
}


public function updateQty()
{
    header('Content-Type: application/json');

    $id = $_POST['id'] ?? 0;
    $change = $_POST['change'] ?? 0;
    $routeId = $_POST['route_id'] ?? 0;

    $model = new DeliveryModel();

    if ($change == 1) {
        $model->increaseQty($id);
    } else {
        $model->decreaseQty($id);
    }

    $qty = $model->getQty($id);

    echo json_encode([
        "success" => true,
        "qty" => $qty,
        "summary" => $model->getDeliveryLoadSummary($routeId)
    ]);

    exit;
}
}