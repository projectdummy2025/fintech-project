<?php

// Start Session
// Secure Session Configuration
$sessionParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $sessionParams['lifetime'],
    'path' => $sessionParams['path'],
    'domain' => $sessionParams['domain'],
    'secure' => isset($_SERVER['HTTPS']), // Only send over HTTPS if available
    'httponly' => true, // Prevent JavaScript access to session cookie
    'samesite' => 'Strict' // Prevent CSRF
]);

session_start();

// Load Config
$appConfig = require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Error Handling
if ($appConfig['debug']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Custom Error Handler for Logging
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $logFile = __DIR__ . '/storage/logs/app.log';
    $message = "[" . date('Y-m-d H:i:s') . "] Error: $errstr in $errfile on line $errline" . PHP_EOL;
    error_log($message, 3, $logFile);
    
    // Don't prevent default handling if debug is on
    return false; 
});

// Load Core Classes
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Csrf.php';

// Load Models
require_once __DIR__ . '/app/Models/User.php';
require_once __DIR__ . '/app/Models/Wallet.php';
require_once __DIR__ . '/app/Models/Category.php';
require_once __DIR__ . '/app/Models/Transaction.php';

// Load Controllers
require_once __DIR__ . '/app/Controllers/HomeController.php';
require_once __DIR__ . '/app/Controllers/AuthController.php';
require_once __DIR__ . '/app/Controllers/DashboardController.php';
require_once __DIR__ . '/app/Controllers/WalletController.php';
require_once __DIR__ . '/app/Controllers/CategoryController.php';
require_once __DIR__ . '/app/Controllers/TransactionController.php';

// Initialize Router
$router = new Router();

// Define Routes
$router->get('/', ['HomeController', 'index']);
$router->get('/home', ['HomeController', 'index']);

// Auth Routes
$router->get('/login', ['AuthController', 'login']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/register', ['AuthController', 'register']);
$router->post('/register', ['AuthController', 'register']);
$router->get('/logout', ['AuthController', 'logout']);

// Dashboard Routes
$router->get('/dashboard', ['DashboardController', 'index']);

// Wallet Routes
$router->get('/wallets', ['WalletController', 'index']);
$router->get('/wallets/create', ['WalletController', 'create']);
$router->post('/wallets/create', ['WalletController', 'create']);
$router->get('/wallets/edit/(\d+)', ['WalletController', 'edit']);
$router->post('/wallets/edit/(\d+)', ['WalletController', 'edit']);
$router->get('/wallets/delete/(\d+)', ['WalletController', 'delete']);
$router->post('/wallets/transfer-and-delete', ['WalletController', 'transferAndDelete']);

// Category Routes
$router->get('/categories', ['CategoryController', 'index']);
$router->get('/categories/create', ['CategoryController', 'create']);
$router->post('/categories/create', ['CategoryController', 'create']);
$router->get('/categories/edit/(\d+)', ['CategoryController', 'edit']);
$router->post('/categories/edit/(\d+)', ['CategoryController', 'edit']);
$router->get('/categories/delete/(\d+)', ['CategoryController', 'delete']);
$router->post('/categories/transfer-and-delete', ['CategoryController', 'transferAndDelete']);

// Category API Routes (AJAX)
$router->post('/api/categories/create', ['CategoryController', 'apiCreate']);
$router->get('/api/categories/(\d+)', ['CategoryController', 'apiGet']);
$router->post('/api/categories/update/(\d+)', ['CategoryController', 'apiUpdate']);

// Transaction Routes
$router->get('/transactions', ['TransactionController', 'index']);
$router->get('/transactions/create', ['TransactionController', 'create']);
$router->post('/transactions/create', ['TransactionController', 'create']);
$router->get('/transactions/edit/(\d+)', ['TransactionController', 'edit']);
$router->post('/transactions/edit/(\d+)', ['TransactionController', 'edit']);
$router->get('/transactions/delete/(\d+)', ['TransactionController', 'delete']);

// Dispatch
$router->dispatch();
