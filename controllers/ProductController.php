
<?php

require_once '../models/Product.php';
require_once '../models/ProductRate.php';
require_once '../models/CustomerCategory.php';

class ProductController extends BaseController
{
     private $productModel;

public function __construct()
{
    parent::__construct();   
    $this->productModel = new Product();
}
   /* public function index()
    {
         $agencyId = $_SESSION['agency']['id'] ?? null;

         if (!$agencyId) {
          die("Agency session not found.");
          }
        $productModel = new Product();
        $categoryModel = new CustomerCategory();

      

$products = $productModel->getByAgency((int)$agencyId);
        
$categories = $categoryModel->allByAgency((int)$agencyId);
       $this->render('products/index', [
    'products' => $products,
    'categories' => $categories,
    'csrf_token' => Csrf::token()
]);
    }*/

public function index()
{
     Auth::requireAgencyAdmin();

    $agencyId = Auth::user()['agency_id'];

    $productModel = new Product();
  
    $products = $productModel->getByAgency((int)$agencyId);
 
    $this->render('products/index', [
        'products' => $products
    ]);
    
}

public function create()
{
    Auth::requireAgencyAdmin();

    $this->render('products/create', [
        'title' => 'Add Product',
        'csrf_token' => Csrf::token(),
    ]);
}

   public function store()
{
    Auth::requireAgencyAdmin();

    if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    $agencyId = Auth::user()['agency_id'];
   $imageName = null;

if (!empty($_FILES['image']['name'])) {

    $uploadDir = __DIR__ . '/../public/uploads/';
    $imageName = time() . '_' . basename($_FILES['image']['name']);
    $targetPath = $uploadDir . $imageName;

    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
}

    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'agency_id'   => $agencyId,
        'image' => $imageName,   
    'variant' => $_POST['variant'] ?? '',
        'status'      => 1
    ];

    if (!$data['name']) {
        die('Product name is required');
    }

    $this->productModel->create($data);

    header('Location: index.php?route=products');
    exit;
}
public function edit()
{
    Auth::requireAgencyAdmin();

    $id = (int)($_GET['id'] ?? 0);

    $productModel = new Product();
    $product = $productModel->find($id);

    if (!$product) {
        die('Product not found');
    }

    $this->render('products/edit', [
        'product' => $product,
        'csrf_token' => Csrf::token()
    ]);
}
public function update()
{
    Auth::requireAgencyAdmin();

    if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }

    $id = (int)$_POST['id'];

    $productModel = new Product();
    $existingProduct = $productModel->find($id);

    $imageName = $existingProduct['image']; 


    if (!empty($_FILES['image']['name'])) {

        $uploadDir = __DIR__ . '/../public/uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $imageName;

        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $data = [
        'name' => trim($_POST['name']),
        'description' => trim($_POST['description']),
        'variant' => trim($_POST['variant']),
        'image' => $imageName
    ];

    $productModel->update($id, $data);

    $this->redirect('index.php?route=products');
}


    public function addRate()
    {
        $rateModel = new ProductRate();

        $rateModel->setRate(
            $_POST['product_id'],
            $_POST['category_id'],
            $_POST['rate']
        );

        header("Location: index.php?route=products");
        exit;
    }
}