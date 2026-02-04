<?php
class UserController extends BaseController
{
    public function index(): void
    {
        if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $this->render('agency/users', [
            'title' => 'Manage Users',
            'csrf_token' => Csrf::token(),
        ]);
    }

    public function store(): void
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

        $user = Auth::user();
        $data = [
            'agency_id' => $user['agency_id'],
            'role_id' => (int) ($_POST['role_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password_hash' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            'status' => 'active',
        ];

        $userModel = new User();
        $userModel->create($data);

        $this->redirect('index.php?route=users');
    }
}
