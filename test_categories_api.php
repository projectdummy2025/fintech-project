<?php
// Mock session
session_start();
$_SESSION['user_id'] = 1; // Assuming user 1

require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Controller.php';
require_once __DIR__ . '/app/Controllers/ApiController.php';

// Mock header() and http_response_code() - NOT NEEDED for CLI usually
// We will just let them run.

echo "Testing Categories API...\n";
$controller = new ApiController();
try {
    $controller->categories();
} catch (Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
