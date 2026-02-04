<?php
class RateController extends BaseController
{
    public function edit(): void
    {
        if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $productId = (int) ($_GET['product_id'] ?? 0);
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

        $rateModel = new ProductRate();
        $productId = (int) ($_POST['product_id'] ?? 0);
        $rates = $_POST['rates'] ?? [];

        foreach ($rates as $userTypeId => $rate) {
            $rateModel->upsert([
                'product_id' => $productId,
                'user_type_id' => (int) $userTypeId,
                'rate' => (float) $rate,
            ]);
        }

        $this->redirect('index.php?route=product_rates&product_id=' . $productId);
    }
}
