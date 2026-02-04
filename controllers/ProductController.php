<?php
class ProductController extends BaseController
{
    public function index(): void
    {
        if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $user = Auth::user();
        $productModel = new Product();
        $products = $productModel->allByAgency((int) $user['agency_id']);

        $this->render('agency/products', [
            'title' => 'Products',
            'products' => $products,
            'csrf_token' => Csrf::token(),
        ]);
    }

    public function store(): void
    {
        if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        $user = Auth::user();
        $data = [
            'agency_id' => $user['agency_id'],
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => 'active',
        ];

        $productModel = new Product();
        $productModel->create($data);

        $this->redirect('index.php?route=products');
    }
}
