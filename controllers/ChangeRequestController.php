<?php

class ChangeRequestController extends BaseController
{
    private $changeModel;

    public function __construct()
    {
        $this->changeModel = new ChangeRequest();
    }

    public function index(): void
{
    Auth::requireAgencyAdmin();

    $customerId = $_GET['customer_id'] ?? null;

    if (!$customerId) {
        header("Location: index.php?route=customers");
    exit;
    }
$customer = $this->changeModel->getCustomer($customerId);

$products = $this->changeModel->getCustomerProducts($customerId);

$allProducts = $this->changeModel->getAllProducts();

$requests = $this->changeModel->getRequests($customerId);

$this->render('agency/change_requests/index', [
    'customer' => $customer,
    'products' => $products,
    'allProducts' => $allProducts,
    'requests' => $requests
]);
}

  public function store()
{
    $customerId = $_POST['customer_id'];
    $productId  = $_POST['product_id'];
    $date       = $_POST['date'];
    $newQty     = (int)$_POST['qty'];
    $type       = $_POST['type'];

    $model = new ChangeRequest();

    $normal = $model->getNormalQty($customerId,$productId);

    if($type == 'add')
    {
        $requested = $newQty - $normal;
    }
    elseif($type == 'reduce')
    {
        $requested = $newQty - $normal;
    }
    else
    {
        $requested = $newQty;
        $normal = 0;
    }

    $model->addRequest($customerId,$productId,$date,$requested,$normal);

    header("Location: index.php?route=change_request&customer_id=".$customerId);
    exit;
}
    public function cancel(): void
    {
        $id = $_GET['id'];

        $this->changeModel->cancelRequest($id);

        redirectBack();
    }
}