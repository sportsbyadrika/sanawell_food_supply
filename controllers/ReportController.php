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
        $summary = $model->getDeliverySummary($from, $to);
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
    $from = $_POST['from_date'] ?? null;
    $to   = $_POST['to_date'] ?? null;

    if (!$from || !$to) {
        header("Location: index.php?route=delivery_report");
        exit;
    }

    $model = new DeliveryModel($this->db);

    $customerTotals = $model->getCustomerTotalsForPeriod($from, $to);

    foreach ($customerTotals as $row) {

        $customerId = $row['customer_id'];
        $totalAmount = $row['total_amount'];

        // Insert into bills
        $billId = $model->insertBill($customerId, $totalAmount);

        // Insert bill items
        $items = $model->getCustomerItemsForPeriod($customerId, $from, $to);

        foreach ($items as $item) {
            $model->insertBillItem(
                $billId,
                $item['product_id'],
                $item['quantity'],
                $item['price'],
                $item['total']
            );
        }
    }

    header("Location: index.php?route=delivery_report&from_date=$from&to_date=$to");
}
}