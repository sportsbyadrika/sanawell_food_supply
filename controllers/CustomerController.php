<?php

class CustomerController extends BaseController
{
   public function index(): void
{
    Auth::requireAgencyAdmin();

    $customerModel = new Customer();

    $perPage = 2;
    $agencyId = Auth::user()['agency_id'];

    $name    = $_GET['name'] ?? null;
    $typeId  = $_GET['type'] ?? null;
    $routeId = $_GET['route_id'] ?? null;

    $currentPage = isset($_GET['page']) 
        ? max(1, (int)$_GET['page']) 
        : 1;

    $offset = ($currentPage - 1) * $perPage;

    // ✅ Get paginated data
    $customers = $customerModel->filterByAgencyPaginated(
        $agencyId,
        $name,
        $typeId,
        $routeId,
        $perPage,
        $offset
    );

    // ✅ Get total count for pagination
    $totalCustomers = $customerModel->countFiltered(
        $agencyId,
        $name,
        $typeId,
        $routeId
    );

    $totalPages = ceil($totalCustomers / $perPage);

    $types  = (new CustomerCategory())->allByAgency($agencyId);
    $routes = (new Route())->allByAgency($agencyId);

    $this->render('agency/customers/index', [
        'customers'   => $customers,
        'types'       => $types,
        'routes'      => $routes,
        'name'        => $name,
        'typeId'      => $typeId,
        'routeId'     => $routeId,
        'currentPage' => $currentPage,
        'totalPages'  => $totalPages
    ]);
}

public function store(): void
{
   Auth::requireAgencyAdmin();
    $customer = new Customer(); 

    if (!Csrf::verify($_POST['_csrf_token'] ?? '')) {
        http_response_code(403);
        echo 'Invalid CSRF token';
        return;
    }

    $customer->create([
        'agency_id'   => Auth::user()['agency_id'],
        'name'        => trim($_POST['name']),
        'address'     => trim($_POST['address']),
        'latitude'    => $_POST['latitude'],
        'longitude'   => $_POST['longitude'],
        'mobile'      => trim($_POST['mobile']),
         'whatsapp' => trim($_POST['whatsapp']),
       'category_id' => $_POST['customer_type_id'],
       'route_id' => $_POST['route_id'],
        'status'      => 1
    ]);

    $this->redirect('index.php?route=customers');
}
public function create(): void
{
    Auth::requireAgencyAdmin();

    $agencyId = Auth::user()['agency_id'];

    // Load customer types
    $categoryModel = new CustomerCategory();
    $types = $categoryModel->allByAgency($agencyId);

    // Load routes
    $routeModel = new Route();
    $routes = $routeModel->allByAgency($agencyId);

    $this->render('agency/customers/create', [
        'types' => $types,
        'routes' => $routes,
        'csrf_token' => Csrf::token()
    ]);
}
public function edit(): void
{
    Auth::requireAgencyAdmin();

    $customerModel = new Customer();

    $id = (int)$_GET['id'];
    $customer = $customerModel->find($id);

    $types = (new CustomerCategory())->allByAgency(Auth::user()['agency_id']);
    $routes = (new Route())->allByAgency(Auth::user()['agency_id']);

    $this->render('agency/customers/edit', [
        'customer' => $customer,
        'types' => $types,
        'routes' => $routes,
        'csrf_token' => Csrf::token()
    ]);
}

public function update(): void
{
    Auth::requireAgencyAdmin();

    if (!Csrf::verify($_POST['_csrf_token'] ?? '')) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    $customerModel = new Customer();
    $id = (int)$_POST['id'];

    $customerModel->update($id, [
        'name' => trim($_POST['name']),
        'address' => trim($_POST['address']),
        'latitude' => $_POST['latitude'],
        'longitude' => $_POST['longitude'],
        'mobile' => trim($_POST['mobile']),
        'whatsapp' => trim($_POST['whatsapp']),
        'category_id' => $_POST['customer_type_id'],
        'route_id' => $_POST['route_id'],
    ]);

    $this->redirect('index.php?route=customers');
}
public function toggle()
{
    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        header("Location: index.php?route=customers");
        exit;
    }

    $customerModel = new Customer();

    $customer = $customerModel->getById($id);

    if (!$customer) {
        header("Location: index.php?route=customers");
        exit;
    }

    $newStatus = $customer['status'] ? 0 : 1;

    $customerModel->updateStatus($id, $newStatus);

    header("Location: index.php?route=customers");
    exit;
}
}