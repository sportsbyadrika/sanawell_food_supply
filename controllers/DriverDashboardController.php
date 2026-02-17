<?php

class DriverDashboardController
{
    public function index()
    {
        
        if (
            !isset($_SESSION['user']) ||
            (int)$_SESSION['user']['role_id'] !== $this->config['roles']['DRIVER']['id']
        ) {
            header('Location: index.php?route=login');
            exit;
        }

       
        $driverId = $_SESSION['user']['id'];

        require_once APP_PATH . '/models/DeliveryModel.php';
        $deliveryModel = new DeliveryModel();

        $deliveries = $deliveryModel->getDeliveriesByDriver($driverId);

        require APP_PATH . '/views/driver/driver_dashboard.php';
    }
}