<?php
class BillController extends BaseController
{
public function generateBillPage()
{
    $model = new BillModel();

    $data['routes'] = $model->getRoutes();
    $data['bills'] = $model->getBills();
    $data['summary'] = $model->getDashboardSummary();

    return $this->render('agency/bill/generate_bill', $data);
}
public function generateBill()
{
    $route_id = $_POST['route_id'];
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $bill_type = $_POST['bill_type'];

    $model = new BillModel();

    // 1. Get customer-wise data from daily bill
    $customers = $model->getCustomerWiseData($route_id, $from, $to);

    foreach ($customers as $cust) {

        // 2. Insert into bills
        $bill_id = $model->createBill([
            'route_id' => $route_id,
            'customer_id' => $cust['customer_id'],
            'bill_from' => $from,
            'bill_to' => $to,
            'bill_type' => $bill_type,
            'total_amount' => $cust['total'],
            'tax_amount' => 0,
            'final_amount' => $cust['total']
        ]);

        // 3. Insert bill items
        $items = $model->getCustomerItems($cust['customer_id'], $from, $to);

        foreach ($items as $item) {
            $model->insertBillItem([
                'bill_id' => $bill_id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'amount' => $item['amount']
            ]);
        }
    }

    header("Location: index.php?route=bill_list");
}

public function billList()
{
    $model = new BillModel();

    $data['bills'] = $model->getBills();

    return $this->render('agency/bills/bill_list', $data);
}
}