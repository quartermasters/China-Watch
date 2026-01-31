<?php
declare(strict_types=1);

namespace RedPulse\Controllers;

use RedPulse\Core\View;
use RedPulse\Services\AuthService;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    // Show login page or redirect to Google
    public function login(): void
    {
        if (AuthService::isLoggedIn()) {
            header('Location: /');
            exit;
        }

        // Check if we should auto-redirect to Google (e.g. if query param set or just default behavior)
        // For now, let's render a login page with a button
        View::render('auth/login', [
            'page_title' => 'Login // China Watch',
            'meta_description' => 'Login to China Watch Intelligence Platform'
        ]);
    }

    // Redirect to Google
    public function google(): void
    {
        $url = $this->auth->getAuthUrl();
        header('Location: ' . $url);
        exit;
    }

    // Google Callback
    public function cbGoogle(): void
    {
        if (isset($_GET['code'])) {
            $success = $this->auth->loginWithGoogle($_GET['code']);
            if ($success) {
                // Check if there is a pending redirect
                $redirect = $_SESSION['login_redirect'] ?? '/';
                unset($_SESSION['login_redirect']);
                header('Location: ' . $redirect);
                exit;
            }
        }

        // Failure
        $_SESSION['flash_error'] = 'Login failed. Please try again.';
        header('Location: /auth/login');
        exit;
    }

    public function logout(): void
    {
        AuthService::logout();
        header('Location: /');
        exit;
    }
}
