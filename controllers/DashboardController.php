<?php
class DashboardController extends BaseController
{
    public function index(): void
    {
        $user = Auth::user();
       
               if (!$user) {
            $this->redirect('index.php?route=login');
        }
       $config = $this->config ?? require __DIR__ . '/../config/config.php';

      $roleId = (int) $user['role_id'];   
     
switch ($roleId) {                 

     case $config['roles']['SUPER_ADMIN']['id']:
        $agencyModel = new Agency();
        $this->render('superadmin/dashboard', [
            'title'           => 'Super Admin Dashboard',
            'totalAgencies'   => $agencyModel->countAll(),
            'activeAgencies'  => $agencyModel->countActive(),
            'pendingRequests' => $agencyModel->countPending(),
        ]);
        break;

  case $this->config['roles']['AGENCY_ADMIN']['id']:

    $agencyId = $_SESSION['user']['agency_id'];

    $productModel = new Product();
    $userModel    = new User();

    $activeProducts = $productModel->countByAgency($agencyId);
    $officeStaff    = $userModel->countByAgencyAndRole(
        $agencyId,
        $this->config['roles']['OFFICE_STAFF']['id']
    );
    $drivers        = $userModel->countByAgencyAndRole(
        $agencyId,
        $this->config['roles']['DRIVER']['id']
    );

    $this->render('agency/dashboard', [
        'title'          => 'Agency Admin Dashboard',
        'activeProducts' => $activeProducts,
        'officeStaff'    => $officeStaff,
        'drivers'        => $drivers,
    ]);
break;

    case $this->config['roles']['OFFICE_STAFF']['id']:
        $this->render('agency/office_staff_dashboard', [
            'title' => 'Office Staff Dashboard',
        ]);
        break;

    case $this->config['roles']['DRIVER']['id']:
    $this->redirect('index.php?route=driver_dashboard');
    break;

    default:
        $this->render('agency/dashboard', [
            'title' => 'Dashboard',
        ]);
}
}
    }

