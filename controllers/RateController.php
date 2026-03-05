<?php


class RateController extends BaseController {

    private $model;

    public function __construct() {
         parent::__construct();   
        $this->model = new RateModel();
    }

    // Manage rates page
   public function manage()
{
    $product_id = $_GET['id'];

    $rateModel = new RateModel();
    $productModel = new Product();

    $product = $productModel->find($product_id); // get product details
    $rates = $rateModel->getByProduct($product_id);
    $customerTypes = $rateModel->getCustomerTypes();

    $this->render('agency/rates/manage', [
        'product'       => $product,
        'product_id'    => $product_id,
        'rates'         => $rates,
        'customerTypes' => $customerTypes
    ]);
}

    // Store new rate
    public function store() {

        $this->model->create($_POST);

        header("Location: index.php?route=product_rates&id=" . $_POST['product_id']);
        exit;
    }
}