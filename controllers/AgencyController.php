<?php
class AgencyController extends BaseController
{
    public function index(): void
    {
        if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $agencyModel = new Agency();
        $agencies = $agencyModel->getAll();

        $this->render('superadmin/agencies', [
            'title' => 'Manage Agencies',
            'agencies' => $agencies,
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
            'status' => 'active',
        ];

        $agencyModel = new Agency();
        
        $agencyModel->create($data);

        $this->redirect('index.php?route=agencies');
    }

   public function updateStatus(): void
   {
   if (!Auth::hasRole($this->config['roles']['SUPER_ADMIN']['slug'])) {
        http_response_code(403);
        echo 'Unauthorized';
        return;
    }

    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        echo 'Invalid CSRF token';
        return;
    }

    $agencyId = (int) ($_POST['agency_id'] ?? 0);
    $status   = $_POST['status'] ?? 'inactive';

    $agencyModel = new Agency();
    $agencyModel->updateStatus($agencyId, $status);

    /* =====================================================
       ONLY when agency is APPROVED / ACTIVATED
    ===================================================== */
    if ($status === 'active') {
          error_log("updateStatus triggered for Agency ID: $agencyId with status: $status");

        $agency = $agencyModel->findById($agencyId);
        if (!$agency) {
            $this->redirect('index.php?route=agencies');
            return;
        }

        $userModel = new User();

        
        if (!$userModel->agencyAdminExists($agencyId)) {

            $agencyName   = $agency['name'];
            $contactEmail = $agency['contact_email'];
          if ($userModel->emailExists($contactEmail)) {
         $this->redirect('index.php?route=agencies');
        return;
         }
        
            $tempPassword = substr(str_shuffle(
                'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'
            ), 0, 8);

            $passwordHash = password_hash($tempPassword, PASSWORD_DEFAULT);
            $_SESSION['generated_temp_password'] = $tempPassword;
            //error_log("Agency Admin Created | Email: {$contactEmail} | Temp Password: {$tempPassword}");
            die("Temp Password: " . $tempPassword);
            $userModel->create([
                'agency_id'     => $agencyId,
                'role_id'       => $this->config['roles']['AGENCY_ADMIN']['id'],
                'name'          => $agencyName . ' Admin',
                'email'         => $contactEmail,
                'password_hash'=> $passwordHash,
                'first_login'   => 1,          
                'status'        => 'active'
            ]);

            
            $this->sendAgencyApprovalEmail(
                $contactEmail,
                $tempPassword
            );
        }
    }

    $this->redirect('index.php?route=agencies');
}
private function sendAgencyApprovalEmail(string $email, string $tempPassword): void
{
    $subject = "Your SanaWell Agency is Approved";

    $message = "
Hello PackRun Admin,

Your agency has been approved.

Login URL:
http://localhost/sanawell_food_supply/public/index.php?route=login

Email: {$email}
Temporary Password: {$tempPassword}

Please change your password after login.

Regards,
SanaWell Team
";

    $headers = "From: no-reply@sanawell.com";

    mail($email, $subject, $message, $headers);
    error_log("Agency Admin Credentials |Email: $email | Temp Password: $tempPassword");
}
}
