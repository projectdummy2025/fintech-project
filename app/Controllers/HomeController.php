<?php

require_once __DIR__ . '/../Core/Controller.php';

class HomeController extends Controller {
    public function index() {
        echo "<h1>Welcome to Personal Finance Webapp</h1>";
        echo "<p>Routing is working correctly!</p>";
        
        // Test Database Connection
        try {
            $db = Database::getConnection();
            echo "<p style='color:green'>Database connection successful!</p>";
        } catch (Exception $e) {
            echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
        }
    }
}
