<?php
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Models/Category.php';
require_once __DIR__ . '/app/Models/Transaction.php';

// Mock session for user_id if needed, or just pass a known ID
$userId = 1; // Assuming user ID 1 exists

echo "Testing Category Model...\n";
$categoryModel = new Category();
$categories = $categoryModel->getAllByUser($userId);
echo "Found " . count($categories) . " categories.\n";

echo "Testing Transaction Model countByCategory...\n";
$transactionModel = new Transaction();
foreach ($categories as $category) {
    try {
        $count = $transactionModel->countByCategory($category['id']);
        echo "Category {$category['name']} (ID: {$category['id']}) has {$count} transactions.\n";
    } catch (Exception $e) {
        echo "Error counting transactions for category {$category['id']}: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
