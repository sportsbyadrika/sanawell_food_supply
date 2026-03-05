<?php

class RouteController extends BaseController
{
    private $routeModel;

public function __construct()
{
    parent::__construct();   
    $this->routeModel = new Route();
}

   public function index(): void
{
    Auth::requireAgencyAdmin();

    $routes = $this->routeModel
        ->allByAgency(Auth::user()['agency_id']);

    $this->render('agency/routes/index', [
        'routes' => $routes
    ]);
}
   public function store()
{
    $agencyId = $_SESSION['user']['agency_id'] ?? null;

    if (!$agencyId) {
        die("Agency not found.");
    }

    $data = [
        'agency_id' => $agencyId,
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'description' => $_POST['description']
    ];

    $this->routeModel->create($data);

    header("Location: index.php?route=routes");
    exit;
}
public function edit(): void
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    $route = $this->routeModel->find($id);

    if (!$route) {
        die('Route not found');
    }

    $agencyId = Auth::user()['agency_id'];

    $userModel = new User();
    $drivers = $userModel->getDriversByAgency($agencyId);

    $this->render('agency/routes/routes_edit', [
        'route' => $route,
        'drivers' => $drivers,   
        'csrf_token' => Csrf::token()
    ]);
}
public function update(): void
{
    Auth::requireAgencyAdmin();

    $id = (int) $_POST['id'];

    $model = new Route();

   $model->update($id, [
    'name' => $_POST['name'],
    'type' => $_POST['type'],
    'description' => $_POST['description'],
    'driver_id' => $_POST['driver_id'] ?: null
]);

    header("Location: index.php?route=routes");
    exit;
}

public function toggle(): void
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    $model = new Route();
    $model->toggle($id);

    header("Location: index.php?route=routes");
    exit;
}

public function routeCustomers(): void
{
    if (!isset($_SESSION['user'])) {
        $this->redirect('index.php?route=login');
    }

    $routeId = (int)($_GET['id'] ?? 0);
    $driverId = $_SESSION['user']['id'];

    require_once APP_PATH . '/models/Route.php';
    require_once APP_PATH . '/models/Customer.php';

    $routeModel = new Route();
    $customerModel = new Customer();

    $route = $routeModel->findByIdAndDriver($routeId, $driverId);

    if (!$route) {
        echo "Route not found";
        return;
    }

    $customers = $customerModel->getByRoute($routeId);

    $this->render('driver/route_customers', [
        'route' => $route,
        'customers' => $customers
    ]);
}

}