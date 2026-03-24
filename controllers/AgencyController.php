<?php
class AgencyController extends BaseController
{
    public function index(): void
{
    if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
        http_response_code(403);
        echo "Unauthorized";
        return;
    }

    $agencyModel = new Agency();

    $agencyAdminRoleId = $this->config['roles']['AGENCY_ADMIN']['id'];

    $type   = $_GET['type'] ?? null;
    $status = $_GET['status'] ?? null;
    $from   = $_GET['from_date'] ?? null;
    $to     = $_GET['to_date'] ?? null;

    if ($type === 'active') {
        $status = 'active';
    } elseif ($type === 'pending') {
        $status = 'pending';
    }

    if ($status || $from || $to) {
        $agencies = $agencyModel->filter($status, $from, $to, $agencyAdminRoleId);
    } else {
        $agencies = $agencyModel->getAll($agencyAdminRoleId);
    }

    $this->render('superadmin/agencies', [
        'title'      => 'Manage Agencies',
        'agencies'   => $agencies,
        'csrf_token' => Csrf::token(),
    ]);
}

public function create()
{
    $this->render('superadmin/agency_create', [
        'title' => 'Add New Agency',
        'csrf_token' => Csrf::token(),
    ]);
}
    public function users()
{
    if (empty($_SESSION['agency_id'])) {
        http_response_code(403);
        echo 'Unauthorized';
        return;
    }

    $agencyId = $_SESSION['agency_id'];

    $users = $this->userModel->getUsersByAgency($agencyId);

    
    $this->render('agency/users', [
        'users' => $this->userModel->getUsersByAgency($_SESSION['agency_id']),
        'csrf_token' => Csrf::token(),
        'title' => 'Staff Users'
    ]);
}
    public function store(): void
    {
      
        if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
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
            'contact_number' => trim($_POST['contact_number'] ?? ''),
            'contact_email' => trim($_POST['contact_email'] ?? ''),
            'whatsapp_number' => trim($_POST['whatsapp_number'] ?? ''),
            'status' => 'pending',
        ];

        $agencyModel = new Agency();
        
        $agencyModel->create($data);

        $this->redirect('index.php?route=agencies');
    }

  public function updateStatus(): void
{
    if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
        http_response_code(403);
        echo "Unauthorized";
        return;
    }

    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        echo "Invalid CSRF token";
        return;
    }

    $agencyId = (int) ($_POST['agency_id'] ?? 0);
    $status   = $_POST['status'] ?? 'inactive';

    $agencyModel = new Agency();
    $agencyModel->updateStatus($agencyId, $status);
     
    if ($status === 'active') {

        $agency = $agencyModel->findById($agencyId);

        if (!$agency) {
            $this->redirect('index.php?route=agencies');
            return;
        }

        $agencyName   = $agency['name'];
        $contactEmail = $agency['contact_email'];
        $mobile       = $agency['contact_number'];

        $userModel = new User();

        // Generate temp password
        $tempPassword = substr(
            str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789'),
            0,
            8
        );

        $passwordHash = password_hash($tempPassword, PASSWORD_DEFAULT);

        $userModel->create([
            'agency_id'     => $agencyId,
            'role_id'       => $this->config['roles']['AGENCY_ADMIN']['id'],
            'name'          => $agencyName . ' Admin',
            'email'         => $contactEmail,
            'mobile'        => $mobile,
            'password_hash' => $passwordHash,
            'first_login'   => 1,
            'status'        => 'active'
        ]);

        // ✅ Show temp password only in local
        $config = require __DIR__ . '/../config/config.php';
        if ($config['environment'] === 'local') {
            $_SESSION['dev_temp_password'] = $tempPassword;
        }

        $this->sendAgencyApprovalEmail($contactEmail, $tempPassword);
    }

    $this->redirect('index.php?route=agencies');
}
public function resetAdminPassword(): void
{
    
    if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
        http_response_code(403);
        echo "Unauthorized";
        return;
    }

    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        echo "Invalid CSRF token";
        return;
    }

    $agencyId = (int) ($_POST['agency_id'] ?? 0);

    if ($agencyId <= 0) {
        $this->redirect('index.php?route=agencies');
        return;
    }

    $agencyModel = new Agency();
    $agency = $agencyModel->findById($agencyId);

    if (!$agency) {
        $this->redirect('index.php?route=agencies');
        return;
    }

    $tempPassword = substr(
        str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'),
        0,
        8
    );

    $hash = password_hash($tempPassword, PASSWORD_DEFAULT);

    $userModel = new User();

    $userModel->updateAdminPassword($agencyId, $hash);

   
    $this->sendAgencyApprovalEmail($agency['contact_email'], $tempPassword);

    $config = require __DIR__ . '/../config/config.php';
    if ($config['environment'] === 'local') {
        $_SESSION['dev_temp_password'] = $tempPassword;
    }

    $_SESSION['success'] = "Tenant admin password reset successfully.";
    $this->redirect('index.php?route=agencies');
}

private function sendAgencyApprovalEmail(string $email, string $tempPassword): void
{
    $subject = "Your Dew Route Agency is Approved";

    $message = "
Hello PackRun Admin,

Your agency has been approved.

Login URL:
http://localhost/sanawell_food_supply/public/index.php?route=login

Email: {$email}
Temporary Password: {$tempPassword}

Please change your password after login.

Regards,
Dew Route Team
";

    $headers = "From: no-reply@sanawell.com";

    mail($email, $subject, $message, $headers);
    error_log("Agency Admin Credentials |Email: $email | Temp Password: $tempPassword");
}
}
