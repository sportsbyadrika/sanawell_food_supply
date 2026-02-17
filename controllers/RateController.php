<?php

class RateController extends BaseController
{
    public function edit(): void
    {
        Auth::requireAgencyAdmin();

        $productId = (int)($_GET['product_id'] ?? 0);

        $rateModel = new ProductRate();
        $rates = $rateModel->allByProduct($productId);

        $userTypeModel = new UserType();
        $userTypes = $userTypeModel->all();

        $this->render('agency/product_rates', [
            'title' => 'Product Rates',
            'rates' => $rates,
            'userTypes' => $userTypes,
            'productId' => $productId,
            'csrf_token' => Csrf::token(),
        ]);
    }

    public function update(): void
    {
        Auth::requireAgencyAdmin();

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token';
            return;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rates = $_POST['rates'] ?? [];

        $rateModel = new ProductRate();

        foreach ($rates as $userTypeId => $rate) {
            $rateModel->upsert([
                'product_id' => $productId,
                'user_type_id' => (int)$userTypeId,
                'rate' => (float)$rate,
            ]);
        }

        $this->redirect(
            'index.php?route=product_rates&product_id=' . $productId
        );
    }
}