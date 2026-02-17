<?php
class UserController extends BaseController
{
     protected User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
    }
  public function index(): void
{
    if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN']['slug'])) {
        http_response_code(403);
        echo 'Unauthorized';
        return;
    }

    $agencyId = $_SESSION['user']['agency_id'];
$officeStaffRoleId = $this->config['roles']['OFFICE_STAFF']['id'];
$driverRoleId      = $this->config['roles']['DRIVER']['id'];
    
$users = $this->userModel->getAgencyStaffUsers(
    $agencyId,
    $officeStaffRoleId,
    $driverRoleId
);

    $roles = $this->userModel->getRoles();

$this->render('agency/users', [
    'users' => $users,
    'roles' => $roles,
    'csrf_token' => Csrf::token()
]);

    
}
    public function store(): void
    {
       
    if (!Auth::hasRole($this->config['roles']['AGENCY_ADMIN']['slug'])) {
        http_response_code(403);
        echo 'Unauthorized';
        return;
    }

    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        echo 'Invalid CSRF token';
        return;
    }

   
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
     $mobile = trim($_POST['mobile'] ?? '');
   $roleId = (int)($_POST['role_id'] ?? 0);

    if (!$name || !$email || !$roleId) {
        echo 'Missing required fields';
        return;
    }

      $allowedRoles = [
    $this->config['roles']['OFFICE_STAFF']['id'],
    $this->config['roles']['DRIVER']['id'],
];

if (!in_array($roleId, $allowedRoles, true)) {
    echo 'Invalid role selected';
    return;
}



if (!$roleId) {
    echo 'Role mapping failed';
    return;
}
$agencyId = $_SESSION['user']['agency_id'] ?? null;

if (!$agencyId) {
    http_response_code(403);
    echo 'Agency not found in session';
    return;
}
$tempPassword  = bin2hex(random_bytes(4));
$passwordHash  = password_hash($tempPassword, PASSWORD_DEFAULT);

$userModel = new User();
$userModel->create([
    'name'          => $name,
    'email'         => $email,
    'mobile'         => $mobile,
    'password_hash'=> $passwordHash,   
    'role_id'       => $roleId,
    'agency_id'     => $agencyId,
    'status'        => 'active',
    'first_login'   => 1,
]);
 
    $this->sendStaffInviteEmail($email, $tempPassword);

    $_SESSION['flash_success'] = 'User created successfully. Login credentials have been sent.';
    $this->redirect('index.php?route=users');
    return;
}
private function sendStaffInviteEmail(string $email, string $tempPassword): void
{
    $subject = 'You have been added to SanaWell';

    $message = "
Hello,

You have been added as a staff member.

Login URL:
http://localhost/sanawell_food_supply/public/index.php?route=login

Email: {$email}
Temporary Password: {$tempPassword}

You will be required to change your password on first login.

Regards,
SanaWell Team
";

    $headers = "From: no-reply@sanawell.com";

    mail($email, $subject, $message, $headers);

    // Backup for local testing
    error_log("STAFF LOGIN → {$email} | {$tempPassword}");
}

public function edit()
{
    Auth::requireAgencyAdmin();

    $id = (int) ($_GET['id'] ?? 0);

    if (!$id) {
        header("Location: index.php?route=users");
        exit;
    }

    $agencyId = Auth::user()['agency_id'];

    $userModel = new User();
    $edituser = $userModel->getById($id, $agencyId);

    if (!$edituser) {
        header("Location: index.php?route=users");
        exit;
    }

    $roles = $userModel->getStaffRoles();

    $this->render('agency/users_edit', [
        'edituser' => $edituser,
        'roles' => $roles,
        'csrf_token' => Csrf::token()
    ]);
}
function update()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?route=users");
        exit;
    }

    require_once '../models/User.php';

    $userModel = new User();

    $id     = (int)($_POST['id'] ?? 0);
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $roleId = (int)($_POST['role_id'] ?? 0);
    $status = $_POST['status'] ?? 'Active';

    if (!$id || !$name || !$email || !$mobile) {
        header("Location: index.php?route=users");
        exit;
    }

    // 🔐 Security: Validate allowed roles
    if (!$userModel->isValidStaffRole($roleId)) {
        die("Invalid role selected.");
    }

    $userModel->updateUser($id, [
        'name'    => $name,
        'email'   => $email,
        'mobile'  => $mobile,
        'role_id' => $roleId,
        'status'  => $status
    ]);

    header("Location: index.php?route=users");
    exit;
}

public function toggle()
{
    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        header("Location: index.php?route=users");
        exit;
    }

    $userModel = new User($this->db);

    $user = $userModel->getById($id, $_SESSION['agency']['id']);

    if (!$user) {
        header("Location: index.php?route=users");
        exit;
    }

    $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';

    $userModel->updateUser($id, [
        'name'    => $user['name'],
        'email'   => $user['email'],
        'mobile'  => $user['mobile'],
        'role_id' => $user['role_id'],
        'status'  => $newStatus
    ]);

    header("Location: index.php?route=users");
    exit;
}

}
