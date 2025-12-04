<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Wallet.php';
require_once __DIR__ . '/../Models/Category.php';

class AuthController extends Controller {

    public function login() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'title' => 'Login',
            'error' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $data['error'] = 'Username and password are required.';
            } else {
                $userModel = new User();
                $ip_address = $_SERVER['REMOTE_ADDR'];

                // 1. Check Brute Force Limit (Max 5 attempts in 15 minutes)
                $attempts = $userModel->getLoginAttemptsCount($ip_address, $username, 15);
                if ($attempts >= 5) {
                    $data['error'] = 'Too many failed attempts. Please try again in 15 minutes.';
                    $this->view('auth/login', $data);
                    return;
                }

                $user = $userModel->findByUsernameOrEmail($username);

                if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {
                    // Login Success
                    session_regenerate_id(true); // Prevent Session Fixation
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Update Last Login & Clear Attempts
                    $userModel->updateLastLogin($user['id']);
                    $userModel->clearLoginAttempts($ip_address, $username);

                    header('Location: /dashboard');
                    exit;
                } else {
                    // Login Failed
                    $userModel->recordLoginAttempt($ip_address, $username);
                    $remaining = 5 - ($attempts + 1);
                    $data['error'] = "Invalid username or password. ($remaining attempts remaining)";
                }
            }
        }

        $this->view('auth/login', $data);
    }

    public function register() {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'title' => 'Register',
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($username) || empty($email) || empty($password)) {
                $data['error'] = 'All fields are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Invalid email format.';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm_password) {
                $data['error'] = 'Passwords do not match.';
            } else {
                $userModel = new User();

                // Check if username or email exists
                if ($userModel->findByUsername($username)) {
                    $data['error'] = 'Username already taken.';
                } elseif ($userModel->isEmailTaken($email)) {
                    $data['error'] = 'Email already registered.';
                } else {
                    // Create User
                    if ($userModel->create($username, $email, $password)) {
                        // Get the user ID of the newly created user
                        $userId = $userModel->findByUsername($username)['id'];

                        // Create default wallet and categories for the new user
                        $walletModel = new Wallet();
                        $categoryModel = new Category();

                        // Create default wallet
                        $walletModel->create($userId, 'Cash', 'Default cash wallet');

                        // Create default categories
                        $categoryModel->createDefaultCategories($userId);

                        $data['success'] = 'Registration successful! Please login.';
                        // Optional: Auto login here
                    } else {
                        $data['error'] = 'Registration failed. Please try again.';
                    }
                }
            }
        }

        $this->view('auth/register', $data);
    }

    public function logout() {
        // Destroy session securely
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header('Location: /login');
        exit;
    }
}
