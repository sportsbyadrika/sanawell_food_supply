<?php

require_once __DIR__ . '/../models/RouteCustomer.php';
require_once __DIR__ . '/../models/Customer.php';
class RouteCustomerController
{
    private $routeCustomerModel;
    private $customerModel;

    public function __construct()
    {
        $this->routeCustomerModel = new RouteCustomer();
        $this->customerModel = new Customer();
    }

    public function assignPage($route_id)
    {
        $agency_id = $_SESSION['agency_id'];

        $customers = $this->customerModel->allByAgency($agency_id);
        $assigned = $this->routeCustomerModel->customersByRoute($route_id);

        require APPROOT . '/views/routes/assign.php';
    }

    public function store()
    {
        $data = [
            'route_id' => $_POST['route_id'],
            'customer_id' => $_POST['customer_id'],
            'delivery_order' => $_POST['delivery_order']
        ];

        $this->routeCustomerModel->assign($data);

        header("Location: index.php?route=route_assign&id=".$_POST['route_id']);
    }
}