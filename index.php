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

// Load Core Classes
require_once __DIR__ . '/app/Core/ErrorHandler.php';

// Initialize Error Handler
ErrorHandler::init($appConfig['debug']);
require_once __DIR__ . '/app/Core/Router.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Csrf.php';
require_once __DIR__ . '/app/Core/Validator.php';

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
require_once __DIR__ . '/app/Controllers/ExportController.php';
require_once __DIR__ . '/app/Controllers/ApiController.php';

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

// Transaction Routes
$router->get('/transactions', ['TransactionController', 'index']);
$router->get('/transactions/create', ['TransactionController', 'create']);
$router->post('/transactions/create', ['TransactionController', 'create']);
$router->get('/transactions/edit/(\d+)', ['TransactionController', 'edit']);
$router->post('/transactions/edit/(\d+)', ['TransactionController', 'edit']);
$router->get('/transactions/delete/(\d+)', ['TransactionController', 'delete']);

// Export Routes
$router->get('/export/transactions', ['ExportController', 'transactions']);

// ===== API Routes (JSON) =====
$router->get('/api/dashboard', ['ApiController', 'dashboard']);
$router->get('/api/transactions', ['ApiController', 'transactions']);
$router->post('/api/transactions/create', ['ApiController', 'transactionCreate']);
$router->post('/api/transactions/update/(\d+)', ['ApiController', 'transactionUpdate']);
$router->post('/api/transactions/delete/(\d+)', ['ApiController', 'transactionDelete']);
$router->get('/api/wallets', ['ApiController', 'wallets']);
$router->post('/api/wallets/create', ['ApiController', 'walletCreate']);
$router->post('/api/wallets/update/(\d+)', ['ApiController', 'walletUpdate']);
$router->post('/api/wallets/delete/(\d+)', ['ApiController', 'walletDelete']);
$router->get('/api/categories', ['ApiController', 'categories']);
$router->post('/api/categories/create', ['ApiController', 'categoryCreate']);
$router->post('/api/categories/update/(\d+)', ['ApiController', 'categoryUpdate']);
$router->post('/api/categories/delete/(\d+)', ['ApiController', 'categoryDelete']);
$router->get('/api/form-data', ['ApiController', 'formData']);
$router->get('/api/sync', ['ApiController', 'sync']);

// Dispatch
$router->dispatch();
