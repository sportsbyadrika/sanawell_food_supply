<?php
class AgencyController extends BaseController
{
    public function index(): void
    {
        if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $agencyModel = new Agency();
        $agencies = $agencyModel->all();

        $this->render('superadmin/agencies', [
            'title' => 'Manage Agencies',
            'agencies' => $agencies,
            'csrf_token' => Csrf::token(),
        ]);
    }

    public function store(): void
    {
        if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'contact_email' => trim($_POST['contact_email'] ?? ''),
            'status' => 'active',
        ];

        $agencyModel = new Agency();
        $agencyModel->create($data);

        $this->redirect('index.php?route=agencies');
    }

    public function updateStatus(): void
    {
        if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token.';
            return;
        }

        $agencyId = (int) ($_POST['agency_id'] ?? 0);
        $status = $_POST['status'] ?? 'inactive';

        $agencyModel = new Agency();
        $agencyModel->updateStatus($agencyId, $status);

        $this->redirect('index.php?route=agencies');
    }
}
