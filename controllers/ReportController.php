<?php

require_once '../models/DeliveryModel.php';

class ReportController extends BaseController
{
   public function deliveryReport()
{
    $model = new DeliveryModel();

    $from = $_GET['from_date'] ?? null;
    $to   = $_GET['to_date'] ?? null;

    $summary    = [];
    $bills      = [];
    $detailsMap = [];

    if ($from && $to) {

        // First fetch summary & bills
      $route_id = $_GET['route_id'] ?? null;

$deliveryModel = new DeliveryModel();

$summary = $deliveryModel->getDeliverySummary(
    $route_id,
    $from_date,
    $to_date
);
        $bills   = $model->getBillsBetweenDates($from, $to);

        // Then build details map
        foreach ($summary as $row) {
            $key = $row['delivery_date'] . '_' . $row['route_id'];

            $detailsMap[$key] =
                $model->getDeliveryDetailsByDateAndRoute(
                    $row['delivery_date'],
                    $row['route_id']
                );
        }
    }

    // Render only once
    $this->render(
        'agency/bill/report_delivery_view',
        compact('summary', 'bills', 'from', 'to', 'detailsMap')
    );
}

public function generateMonthlyBill()
{
    $route_id = $_POST['route_id'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $deliveryModel = new DeliveryModel();
    $billModel = new BillModel();

    // Get delivery summary
    $customers = $deliveryModel->getDeliverySummary($route_id, $from_date, $to_date);

    foreach ($customers as $customer) {

        // Create bill
        $bill_id = $billModel->createBill([
            'customer_id' => $customer['customer_id'],
            'route_id' => $route_id,
            'bill_from_date' => $from_date,
            'bill_to_date' => $to_date,
            'total_amount' => $customer['total_amount'],
            'status' => 'BILL GENERATED'
        ]);

        // Get product summary
        $items = $deliveryModel->getCustomerProducts(
            $customer['customer_id'],
            $from_date,
            $to_date
        );

        foreach ($items as $item) {

            $billModel->addBillItem([
                'bill_id' => $bill_id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'rate' => $item['rate'],
                'amount' => $item['amount']
            ]);
        }
    }

    header("Location:index.php?route=delivery_report");
}
}