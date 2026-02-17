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

    $model = new Route();
    $route = $model->find($id);

    if (!$route) {
        die('Route not found');
    }

    $this->render('agency/routes/routes_edit', [
        'route' => $route,
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
        'type' => $_POST['type'],   // important
        'description' => $_POST['description']
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

}