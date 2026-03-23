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
                'route_id' => $route_id,
                'customer_id' => $cust['customer_id'],
                'bill_from' => $from,
                'bill_to' => $to,
                'bill_type' => $bill_type,
                'bill_date' => date('Y-m-d'),
                'total_amount' => $cust['total'],
                'tax_amount' => 0,
                'final_amount' => $cust['total'],
            ]);

            $items = $model->getCustomerItems($cust['customer_id'], $from, $to);

            foreach ($items as $item) {
                $model->insertBillItem([
                    'bill_id' => $bill_id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'amount' => $item['amount'],
                ]);
            }
        }

        header("Location: index.php?route=generate_bill_page");
    }

    public function billList()
    {
        $model = new BillModel();

        $bills = $model->getAllBills();

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

    /**
     * Receipt entry screen.
     * - search pending bills by customer/mobile
     * - load selected bill by bill_id
     * - auto-fill receipt form using pending balance
     */
    public function receiptEntry()
    {
        $model = new BillModel();

        $billId = isset($_GET['bill_id']) ? (int) $_GET['bill_id'] : 0;
        $search = trim($_GET['search'] ?? '');
        $selectedBill = null;
        $formError = $_GET['error'] ?? '';
        $successMessage = $_GET['success'] ?? '';
        $canSubmitReceipt = false;

        if ($billId > 0) {
            $selectedBill = $model->getBillById($billId);

            if (!$selectedBill) {
                $formError = 'Selected bill was not found.';
            } else {
                $canSubmitReceipt = (float) $selectedBill['balance'] > 0 && strcasecmp((string) $selectedBill['status'], 'Paid') !== 0;

                if (!$canSubmitReceipt && $successMessage === '') {
                    $formError = 'Receipts cannot be created for fully paid bills.';
                }
            }
        }

        $summary = $model->getBillsSummary();
        $pendingBills = $model->getPendingBills($search);
        $receipts = $billId > 0 ? $model->getReceiptsByBill($billId) : [];

        $formDefaults = [
            'bill_id' => $selectedBill['id'] ?? '',
            'receipt_date' => date('Y-m-d'),
            'amount' => $selectedBill['balance'] ?? '',
            'payment_mode' => 'Cash',
            'status' => 'entry',
            'transaction_ref' => '',
            'transaction_date' => '',
            'verified_date' => '',
            'verified_user_id' => '',
        ];

        return $this->render('agency/bill/receipt_entry', [
            'summary' => $summary,
            'search' => $search,
            'pendingBills' => $pendingBills,
            'bill' => $selectedBill,
            'receipts' => $receipts,
            'formDefaults' => $formDefaults,
            'error' => $formError,
            'success' => $successMessage,
            'canSubmitReceipt' => $canSubmitReceipt,
        ]);
    }

    public function searchReceipts()
    {
        $model = new BillModel();

        $search = $_POST['search'] ?? '';
        $route_id = $_POST['route_id'] ?? '';

        $data = [
            'routes' => $model->getRoutes(),
            'bills' => $model->getPendingBills($search, $route_id),
            'receipts' => $model->getReceipts($search, $route_id),
            'summary' => $model->getReceiptSummary($search, $route_id),
        ];

        return $this->render('agency/bill/receipt_page', $data);
    }

    /**
     * Save receipt and update bill status.
     * - blocks paid/zero-balance bills
     * - blocks overpayment
     * - supports partial payment by keeping bill Pending
     */
    public function saveReceipt()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=receipt_entry');
            exit;
        }

        $model = new BillModel();
        $billId = (int) ($_POST['bill_id'] ?? 0);
        $bill = $billId > 0 ? $model->getBillById($billId) : null;

        if (!$bill) {
            header('Location: index.php?route=receipt_entry&error=' . urlencode('Invalid bill selected.'));
            exit;
        }

        $balance = (float) ($bill['balance'] ?? 0);
        if ($balance <= 0 || strcasecmp((string) $bill['status'], 'Paid') === 0) {
            header('Location: index.php?route=receipt_entry&error=' . urlencode('Receipt entry is not allowed for paid bills.'));
            exit;
        }

        $amount = (float) ($_POST['amount'] ?? 0);
        if ($amount <= 0) {
            header('Location: index.php?route=receipt_entry&bill_id=' . $billId . '&error=' . urlencode('Receipt amount must be greater than zero.'));
            exit;
        }

        if ($amount > $balance) {
            header('Location: index.php?route=receipt_entry&bill_id=' . $billId . '&error=' . urlencode('Receipt amount cannot exceed the pending balance.'));
            exit;
        }

        $paymentMode = trim($_POST['payment_mode'] ?? 'Cash');
        $data = [
            'bill_id' => $billId,
            'route_id' => $bill['route_id'] ?? null,
            'receipt_date' => $_POST['receipt_date'] ?? date('Y-m-d'),
            'amount' => $amount,
            'payment_mode' => $paymentMode !== '' ? $paymentMode : 'Cash',
            'transaction_ref' => trim($_POST['transaction_ref'] ?? '') ?: null,
            'transaction_date' => trim($_POST['transaction_date'] ?? '') ?: null,
            'status' => $_POST['status'] ?? 'entry',
            'verified_date' => trim($_POST['verified_date'] ?? '') ?: null,
            'verified_user_id' => trim($_POST['verified_user_id'] ?? '') ?: null,
        ];

        $model->saveReceipt($data);
        $model->updateBillStatus($billId);

        header('Location: index.php?route=receipt_entry&bill_id=' . $billId . '&success=' . urlencode('Receipt saved successfully.'));
        exit;
    }

    public function notifications()
    {
        $model = new BillModel();
        $data['counts'] = $model->getNotificationCounts();

        return $this->render('agency/bill/notifications', $data);
    }
}
