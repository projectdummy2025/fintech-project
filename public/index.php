<?php

// Start Session
session_start();

// Load Config
$appConfig = require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

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
    $logFile = __DIR__ . '/../storage/logs/app.log';
    $message = "[" . date('Y-m-d H:i:s') . "] Error: $errstr in $errfile on line $errline" . PHP_EOL;
    error_log($message, 3, $logFile);
    
    // Don't prevent default handling if debug is on
    return false; 
});

// Load Core Classes
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php';

// Load Controllers
require_once __DIR__ . '/../app/controllers/HomeController.php';

// Initialize Router
$router = new Router();

// Define Routes
$router->get('/', ['HomeController', 'index']);
$router->get('/home', ['HomeController', 'index']);

// Dispatch
$router->dispatch();
