<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../models/User.php';

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
                $user = $userModel->findByUsername($username);

                if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {
                    // Login Success
                    session_regenerate_id(true); // Prevent Session Fixation
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    header('Location: /dashboard');
                    exit;
                } else {
                    $data['error'] = 'Invalid username or password.';
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
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($username) || empty($password)) {
                $data['error'] = 'All fields are required.';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm_password) {
                $data['error'] = 'Passwords do not match.';
            } else {
                $userModel = new User();
                
                // Check if username exists
                if ($userModel->findByUsername($username)) {
                    $data['error'] = 'Username already taken.';
                } else {
                    // Create User
                    if ($userModel->create($username, $password)) {
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
