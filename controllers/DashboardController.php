<?php
class DashboardController extends BaseController
{
    public function index(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect('index.php?route=login');
        }

        $role = $user['role_slug'];
        switch ($role) {
            case $this->config['roles']['SUPER_ADMIN']:
                $this->render('superadmin/dashboard', ['title' => 'Super Admin Dashboard']);
                break;
            case $this->config['roles']['AGENCY_ADMIN']:
                $this->render('agency/dashboard', ['title' => 'Agency Admin Dashboard']);
                break;
            case $this->config['roles']['OFFICE_STAFF']:
                $this->render('agency/office_staff_dashboard', ['title' => 'Office Staff Dashboard']);
                break;
            case $this->config['roles']['DRIVER']:
                $this->render('agency/driver_dashboard', ['title' => 'Driver Dashboard']);
                break;
            default:
                $this->render('agency/dashboard', ['title' => 'Dashboard']);
        }
    }
}
