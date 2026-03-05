<?php
class AuthController extends BaseController
{
    public function showLogin(): void
    {
       $this->render('auth/login', [
    'title' => 'Login',
    'csrf_token' => Csrf::token(),
],null);
    }

   public function login(): void
{
    
    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        echo 'Invalid CSRF token.';
        return;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $this->render('auth/login', [
            'title' => 'Login',
            'error' => 'Invalid credentials.',
            'csrf_token' => Csrf::token(),
        ]);
        return;
    }

    if ($user['status'] !== 'active') {
        $this->render('auth/login', [
            'title' => 'Login',
            'error' => 'Account is inactive.',
            'csrf_token' => Csrf::token(),
        ]);
        return;
    }

    $agency = null;

    if (!empty($user['agency_id'])) {
        $agencyModel = new Agency();
        $agency = $agencyModel->findById($user['agency_id']);

        if (!$agency || $agency['status'] !== 'active') {
            $this->render('auth/login', [
                'title' => 'Login',
                'error' => 'Your agency is inactive. Contact Super Admin.',
                'csrf_token' => Csrf::token(),
            ]);
            return;
        }
    }

    $userModel->updateLastLogin($user['id']);

    Auth::login($user);

    $_SESSION['user'] = [
        'id'        => $user['id'],
        'role_id'   => $user['role_id'],
        'agency_id' => $user['agency_id'],
        'email'     => $user['email'],
    ];

    $_SESSION['agency'] = $agency ?? null;

    
    $superAdminRoleId = $this->config['roles']['SUPER_ADMIN']['id'];

    if (
        (int)$user['first_login'] === 1 &&
        (int)$user['role_id'] !== (int)$superAdminRoleId
    ) {
        $this->redirect('index.php?route=change_password');
        return;
    }

    if ((int)$user['role_id'] === (int)$this->config['roles']['DRIVER']['id']) {
        $this->redirect('index.php?route=driver_dashboard');
        return;
    }

    $this->redirect('index.php?route=dashboard');
}

public function changePassword(): void
{
    
    if (!Auth::check()) {
        $this->redirect('index.php?route=login');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
            http_response_code(403);
            echo 'Invalid CSRF token';
            return;
        }

        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

     
        if (strlen($password) < 6) {
            $this->render('auth/change_password', [
                'title' => 'Change Password',
                'error' => 'Password must be at least 6 characters.',
                'csrf_token' => Csrf::token(),
            ]);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->render('auth/change_password', [
                'title' => 'Change Password',
                'error' => 'Passwords do not match.',
                'csrf_token' => Csrf::token(),
            ]);
            return;
        }

       
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $userModel = new User();
        $userModel->updatePasswordAndUnlock(
            $_SESSION['user']['id'],
            $passwordHash
        );

     
        $this->redirect('index.php?route=dashboard');
        return;
    }

        $this->render('auth/change_password', [
        'title' => 'Change Password',
        'csrf_token' => Csrf::token(),
    ]);
}

public function forgotPasswordForm(): void
{
    $this->render('auth/forgot_password', [
        'title' => 'Forgot Password',
        'csrf_token' => Csrf::token()
    ]);
}

public function sendResetLink(): void
{
    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }

    $email = trim($_POST['email'] ?? '');

    $userModel = new User();
    $user = $userModel->findByEmail($email);

    if ($user) {

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->saveResetToken(
            $user['id'],
            $token,
            $expires
        );

        // DEV MODE LINK
        $resetLink = "http://localhost/sanawell_food_supply/public/index.php?route=reset_password&token=" . $token;

        $this->render('auth/forgot_password', [
            'title' => 'Forgot Password',
            'csrf_token' => Csrf::token(),
            'success' => 'Reset link generated successfully.',
            'dev_link' => $resetLink
        ]);

        return;
    }

    $this->render('auth/forgot_password', [
        'title' => 'Forgot Password',
        'csrf_token' => Csrf::token(),
        'error' => 'Email not found.'
    ]);
}


public function resetPasswordForm(): void
{
    $token = $_GET['token'] ?? null;

    if (!$token) {
        exit('Invalid or missing token.');
    }

    $userModel = new User();
    $user = $userModel->findByResetToken($token);

    if (!$user) {
        exit('Invalid reset token.');
    }

    if (strtotime($user['password_reset_expires']) < time()) {
        exit('Reset token expired.');
    }

    $this->render('auth/reset_password', [
        'title' => 'Reset Password',
        'csrf_token' => Csrf::token(),
        'token' => $token
    ]);
}

public function resetPassword(): void
{
    if (!Csrf::verify($_POST['_csrf_token'] ?? null)) {
        exit('Invalid CSRF token');
    }

    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$token) {
        $this->render('auth/reset_password', [
            'title' => 'Reset Password',
            'error' => 'Invalid token.',
            'token' => '',
            'csrf_token' => Csrf::token()
        ]);
        return;
    }

    if ($password !== $confirm) {
        $this->render('auth/reset_password', [
            'title' => 'Reset Password',
            'error' => 'Passwords do not match.',
            'token' => $token,
            'csrf_token' => Csrf::token()
        ]);
        return;
    }

    if (strlen($password) < 6) {
        $this->render('auth/reset_password', [
            'title' => 'Reset Password',
            'error' => 'Password must be at least 6 characters.',
            'token' => $token,
            'csrf_token' => Csrf::token()
        ]);
        return;
    }

    $userModel = new User();
    $user = $userModel->findByResetToken($token);

    if (!$user) {
        $this->render('auth/reset_password', [
            'title' => 'Reset Password',
            'error' => 'Invalid or expired reset token.',
            'token' => '',
            'csrf_token' => Csrf::token()
        ]);
        return;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $userModel->updatePasswordAndClearToken(
        $user['id'],
        $hash
    );

   $this->render('auth/reset_password', [
    'title' => 'Reset Password',
    'success' => 'Password updated successfully. Redirecting to login...',
    'redirect' => true
]);
}
public function logout(): void
{
    
    Auth::logout();

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
    }

    
    $this->redirect('index.php?route=login');
}
}