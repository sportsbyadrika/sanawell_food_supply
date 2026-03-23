<?php
class BillController extends BaseController
{
    public function generateBillPage()
    {
        $model = new BillModel();
        $routeId = isset($_GET['route_id']) ? (int) $_GET['route_id'] : 0;
        $selectedRoute = $routeId > 0 ? $model->getRouteById($routeId) : null;

        $data['routes'] = $model->getRoutes();
        $data['selectedRoute'] = $selectedRoute;
        $data['selectedRouteId'] = $routeId;
        $data['bills'] = $model->getBills($routeId ?: null);
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

        header('Location: index.php?route=generate_bill_page&route_id=' . (int) $route_id);
        exit;
    }

    public function billList()
    {
        $model = new BillModel();
        $routeId = isset($_GET['route_id']) ? (int) $_GET['route_id'] : 0;
        $selectedRoute = $routeId > 0 ? $model->getRouteById($routeId) : null;

        $bills = $model->getAllBills($routeId ?: null);

        $this->render('agency/bill/bill_list', [
            'bills' => $bills,
            'routes' => $model->getRoutes(),
            'selectedRouteId' => $routeId,
            'selectedRoute' => $selectedRoute,
        ]);
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
     * - search filters only the pending bill list
     * - selected bill changes only through explicit Select action
     * - route and search filters are preserved in the URL
     */
    public function receiptEntry()
    {
        $model = new BillModel();

        $billId = isset($_GET['bill_id']) ? (int) $_GET['bill_id'] : 0;
        $routeId = isset($_GET['route_id']) ? (int) $_GET['route_id'] : 0;
        $search = trim($_GET['search'] ?? '');
        $selectedBill = null;
        $selectedBillId = 0;
        $formError = $_GET['error'] ?? '';
        $successMessage = $_GET['success'] ?? '';
        $canSubmitReceipt = false;
        $selectedRoute = $routeId > 0 ? $model->getRouteById($routeId) : null;

        if ($billId > 0) {
            $selectedBill = $model->getBillById($billId);

            if (!$selectedBill) {
                $formError = 'Selected bill was not found.';
            } elseif ($routeId > 0 && (int) ($selectedBill['route_id'] ?? 0) !== $routeId) {
                $formError = 'Selected bill does not belong to the chosen route.';
                $selectedBill = null;
            } else {
                $selectedBillId = (int) $selectedBill['id'];
                $canSubmitReceipt = (float) $selectedBill['balance'] > 0 && strcasecmp((string) $selectedBill['status'], 'Paid') !== 0;

                if (!$canSubmitReceipt && $successMessage === '') {
                    $formError = 'Receipts cannot be created for fully paid bills.';
                }
            }
        }

        $summary = $model->getBillsSummary($routeId ?: null);
        $pendingBills = $model->getPendingBills($search, $routeId ?: null);
        $receipts = $selectedBillId > 0 ? $model->getReceiptsByBill($selectedBillId) : [];

        $formDefaults = [
            'bill_id' => $selectedBill['id'] ?? '',
            'route_id' => $routeId ?: ($selectedBill['route_id'] ?? ''),
            'search' => $search,
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
            'routes' => $model->getRoutes(),
            'summary' => $summary,
            'search' => $search,
            'route_id' => $routeId,
            'selectedRoute' => $selectedRoute,
            'selectedBillId' => $selectedBillId,
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
     * - inserts into receipts table
     * - keeps route/search context on redirect
     * - refreshes the same selected bill after save
     */
    public function saveReceipt()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=receipt_entry');
            exit;
        }

        $model = new BillModel();
        $billId = (int) ($_POST['bill_id'] ?? 0);
        $routeId = (int) ($_POST['route_id'] ?? 0);
        $search = trim($_POST['search'] ?? '');
        $bill = $billId > 0 ? $model->getBillById($billId) : null;
        $redirectBase = 'index.php?route=receipt_entry&bill_id=' . $billId;

        if ($routeId > 0) {
            $redirectBase .= '&route_id=' . $routeId;
        }

        if ($search !== '') {
            $redirectBase .= '&search=' . urlencode($search);
        }

        if (!$bill) {
            header('Location: index.php?route=receipt_entry&error=' . urlencode('Invalid bill selected.'));
            exit;
        }

        if ($routeId > 0 && (int) ($bill['route_id'] ?? 0) !== $routeId) {
            header('Location: index.php?route=receipt_entry&route_id=' . $routeId . '&error=' . urlencode('Selected bill does not belong to the chosen route.'));
            exit;
        }

        $balance = (float) ($bill['balance'] ?? 0);
        if ($balance <= 0 || strcasecmp((string) $bill['status'], 'Paid') === 0) {
            header('Location: ' . $redirectBase . '&error=' . urlencode('Receipt entry is not allowed for paid bills.'));
            exit;
        }

        $amount = (float) ($_POST['amount'] ?? 0);
        if ($amount <= 0) {
            header('Location: ' . $redirectBase . '&error=' . urlencode('Receipt amount must be greater than zero.'));
            exit;
        }

        if ($amount > $balance) {
            header('Location: ' . $redirectBase . '&error=' . urlencode('Receipt amount cannot exceed the pending balance.'));
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

        header('Location: ' . $redirectBase . '&success=' . urlencode('Receipt saved successfully.'));
        exit;
    }

    public function notifications()
    {
        $model = new BillModel();
        $data['counts'] = $model->getNotificationCounts();

        return $this->render('agency/bill/notifications', $data);
    }
}
