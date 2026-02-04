<?php
class AuthController extends BaseController
{
    public function showLogin(): void
    {
        $this->render('auth/login', [
            'title' => 'Login',
            'csrf_token' => Csrf::token(),
        ]);
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

        Auth::login($user);
        $this->redirect('index.php?route=dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect('index.php?route=login');
    }
}
