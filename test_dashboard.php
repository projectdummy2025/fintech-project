<?php
// Mock session
session_start();
$_SESSION['user_id'] = 1;

// Mock GET params
$_GET['year'] = 2025;
$_GET['month'] = 12;

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Controllers/ApiController.php';

// Mock header() and http_response_code() - NOT NEEDED for CLI usually, or use namespaces if needed.
// We will just let them run.

echo "Testing Dashboard API...\n";
$controller = new ApiController();
try {
    $controller->dashboard();
} catch (Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
