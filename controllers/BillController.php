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

    
    $customers = $model->getCustomerWiseData($route_id, $from, $to);

    foreach ($customers as $cust) {

        
        $bill_id = $model->createBill([
    'route_id'     => $route_id,
    'customer_id'  => $cust['customer_id'],
    'bill_from'    => $from,
    'bill_to'      => $to,
    'bill_type'    => $bill_type,
    'bill_date'    => date('Y-m-d'), 
    'total_amount' => $cust['total'],
    'tax_amount'   => 0,
    'final_amount' => $cust['total']
]);

        
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

    header("Location: index.php?route=generate_bill_page");
}

public function billList()
{
    $model = new BillModel();

    $bills = $model->getAllBills(); // always all bills

    $this->render('agency/bill/bill_list', ['bills' => $bills]);
}

public function receiptPage()
{
    $model = new BillModel();

    $search = $_POST['search'] ?? '';
    $route_id = $_POST['route_id'] ?? '';

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $bills = $model->getPendingBills($search, $route_id);
    } else {
        $bills = $model->getPendingBills(); 
    }

    $data = [
        'routes' => $model->getRoutes(),
        'bills' => $bills,
        'receipts' => [],
        'summary' => $model->getDashboardSummary(),
    ];

    return $this->render('agency/bill/receipt_page', $data);
}

public function receiptEntry()
{
    $model = new BillModel();

    $bill_id = $_GET['id'] ?? null;

        if (!$bill_id) {
        $pending = $model->getPendingBills();

        if (!empty($pending)) {
            $bill_id = $pending[0]['id']; 
        } else {
            
            header("Location: index.php?route=receipt_page");
            exit;
        }
    }

    $bill = $model->getBillById($bill_id);

    $data = [
        'bill' => $bill,
        'summary' => $model->getDashboardSummary()
    ];

    return $this->render('agency/bill/receipt_entry', $data);
}

public function searchReceipts()
{
    $model = new BillModel();

    $search   = $_POST['search'] ?? '';
    $route_id = $_POST['route_id'] ?? '';

    $data = [
        'routes' => $model->getRoutes(),
        'bills' => $model->getPendingBills($search, $route_id),
        'receipts' => $model->getReceipts($search, $route_id),
        'summary' => $model->getReceiptSummary($search, $route_id),
    ];

    return $this->render('agency/bill/receipt_page', $data);
}

public function saveReceipt()
{
    $model = new BillModel();

        $data = [
        'bill_id' => $_POST['bill_id'] ?? null,
        'route_id' => $_POST['route_id'] ?? null,
        'receipt_date' => $_POST['receipt_date'] ?? date('Y-m-d'),
        'amount' => $_POST['amount'] ?? 0,
        'payment_mode' => $_POST['payment_mode'] ?? 'Cash',
        'transaction_ref' => $_POST['transaction_ref'] ?? null,
        'transaction_date' => $_POST['transaction_date'] ?? null,
        'status' => $_POST['status'] ?? 'entry',
        'verified_date' => $_POST['verified_date'] ?? null,
        'verified_user_id' => $_SESSION['user_id'] ?? null
    ];

    $model->saveReceipt($data);

    $search = $_POST['search'] ?? '';
    $route_id = $_POST['route_id'] ?? '';

    header("Location: index.php?route=receipt_page&success=1&search=$search&route_id=$route_id");
    exit;
}
public function notifications()
{
    $model = new BillModel();
    $data['counts'] = $model->getNotificationCounts();

    return $this->render('agency/bill/notifications', $data);
}
}