<?php

class ProductRateController extends BaseController
{
    public function manage(): void
    {
        Auth::requireAgencyAdmin();

        $productId = (int)$_GET['product_id'];
        $agencyId = Auth::user()['agency_id'];

        $categoryModel = new Category();
        $rateModel = new ProductRate();

        $this->render('products/rates', [
            'title' => 'Manage Rates',
            'categories' => $categoryModel->allByAgency($agencyId),
            'rates' => $rateModel->getRates($productId),
            'product_id' => $productId,
            'csrf_token' => Csrf::token()
        ]);
    }

    public function store(): void
    {
        Auth::requireAgencyAdmin();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            die('Invalid CSRF token');
        }

        $rateModel = new ProductRate();

        foreach ($_POST['rates'] as $categoryId => $rate) {
            if ($rate !== '') {
                $rateModel->save(
                    (int)$_POST['product_id'],
                    (int)$categoryId,
                    (float)$rate
                );
            }
        }

        header('Location: index.php?route=products');
    }
}
