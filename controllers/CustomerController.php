<?php

class CustomerController extends BaseController
{

  private $customerModel;

    public function __construct()
    {
        $this->customerModel = new Customer();
    }
   public function index(): void
{
    Auth::requireAgencyAdmin();

    $customerModel = $this->customerModel;
    $perPage = 10;
    $agencyId = Auth::user()['agency_id'];

    $name    = $_GET['name'] ?? null;
    $typeId  = $_GET['type'] ?? null;
    $routeId = $_GET['route_id'] ?? null;

    $currentPage = isset($_GET['page']) 
        ? max(1, (int)$_GET['page']) 
        : 1;

    $offset = ($currentPage - 1) * $perPage;

    $customers = $customerModel->filterByAgencyPaginated(
        $agencyId,
        $name,
        $typeId,
        $routeId,
        $perPage,
        $offset
    );

    
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
        'totalPages'  => $totalPages,
         'perPage' => $perPage
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

    $categoryModel = new CustomerCategory();
    $types = $categoryModel->allByAgency($agencyId);

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

public function manage(): void
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        $this->redirect('index.php?route=customers');
    }

    $customerModel = new Customer();
    $routeModel    = new Route();
    $productModel  = new Product();

    $customer = $customerModel->find($id);

   $rateModel = new RateModel();

$products = $rateModel->getProductsWithRate($customer['category_id']); 
   $routes = $routeModel->getAllByAgency($customer['agency_id']);   

    $customer_products = $customerModel->getCustomerProducts($id);

    $this->render('agency/customers/manage', [
        'customer' => $customer,
        'products' => $products,
        'routes'   => $routes,
        'customer_products' => $customer_products
    ]);
}

public function storeProduct(): void
{
    Auth::requireAgencyAdmin();

    $customerId = (int)($_POST['customer_id'] ?? 0);
    $productId  = (int)($_POST['product_id'] ?? 0);
    $quantity   = (float)($_POST['quantity'] ?? 0);
    $routeId    = (int)($_POST['route_id'] ?? 0);

    if (!$customerId || !$productId || !$quantity || !$routeId) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: index.php?route=customer_manage&id=" . $customerId);
        exit;
    }

    $customerModel = new Customer();
    $rateModel     = new RateModel();

    $customer = $customerModel->find($customerId);
    $customerTypeId = $customer['category_id'];

    $rate = $rateModel->getCurrentRate($productId, $customerTypeId);

    $total = $rate * $quantity;

    $customerModel->addCustomerProduct(
        $customerId,
        $productId,
        $quantity,
        $routeId,
        $rate,
        $total
    );

    header("Location: index.php?route=customer_manage&id=" . $customerId);
    exit;
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

public function toggleProduct(): void
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        header("Location: index.php?route=customers");
        exit;
    }

    $customerModel = new Customer();

    $customerModel->toggleCustomerProduct($id);

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

public function updateProduct(): void
{
    Auth::requireAgencyAdmin();

    $id       = (int)($_POST['id'] ?? 0);
    $quantity = (float)($_POST['quantity'] ?? 0);
    $routeId  = (int)($_POST['route_id'] ?? 0);

    if (!$id || !$quantity || !$routeId) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $customerModel = new Customer();

    $productRow = $customerModel->getCustomerProductById($id);

    if (!$productRow) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $rate = (float)$productRow['rate'];  
    $total = $rate * $quantity;         

     $customerModel->updateCustomerProduct(
        $id,
        $quantity,
        $routeId,
        $total
    );

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

public function getProductRate()
{
    $customer_id = $_GET['customer_id'];
    $product_id = $_GET['product_id'];

    $customerModel = new CustomerModel();
    $rateModel = new RateModel();

    $customer = $customerModel->find($customer_id);

    $rate = $rateModel->getCurrentRate(
        $product_id,
        $customer['customer_type_id']
    );

    echo json_encode(['rate' => $rate]);
}



public function import(): void
{
    Auth::requireAgencyAdmin();

    $this->render('agency/customers/import');
}

public function importProcess()
{
    if(isset($_FILES['csv_file']))
    {

        $file = $_FILES['csv_file']['tmp_name'];

        $handle = fopen($file,"r");

        fgetcsv($handle); // skip header

        while(($row = fgetcsv($handle,1000,",")) !== FALSE)
        {

            $name = trim($row[0]);
            $mobile = trim($row[1]);
               $address = trim($row[2]);
            $whatsapp = trim($row[3]);
            $category = trim($row[4]);
            $route = trim($row[5]);

            $category_id = $this->customerModel->getCategoryId($category);
            $route_id = $this->customerModel->getRouteId($route);

            if($category_id && $route_id)
            {
                $data = [
                    'agency_id' => Auth::user()['agency_id'],
                    'name'=>$name,
                    'mobile'=>$mobile,
                    'address'=>$address,
                    'whatsapp'=>$whatsapp,
                    'category_id'=>$category_id,
                    'route_id'=>$route_id
                ];

                $this->customerModel->insertCustomer($data);
            }

        }

        fclose($handle);

        header("Location:index.php?route=customers");
        exit;

    }
}
}