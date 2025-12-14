<?php
/**
 * Database Connection Test for InfinityFree
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to /public/test-db.php
 * 2. Access via: https://yoursite.com/public/test-db.php
 * 3. Check which tests pass/fail
 * 4. DELETE this file after testing (security risk!)
 */

// Load database config
$configPath = __DIR__ . '/../config/database.php';
$envProdPath = __DIR__ . '/../.env.production.php';

echo "<h1>Database Connection Test</h1>";
echo "<style>body{font-family:monospace;padding:20px;} .pass{color:green;} .fail{color:red;}</style>";

// Test 1: Check which config file exists
echo "<h2>Test 1: Config File Detection</h2>";
if (file_exists($envProdPath)) {
    echo "<p class='pass'>✓ .env.production.php EXISTS</p>";
    $env = require $envProdPath;
    $config = $env['database'];
} elseif (file_exists($configPath)) {
    echo "<p class='pass'>✓ config/database.php EXISTS</p>";
    $config = require $configPath;
} else {
    echo "<p class='fail'>✗ NO CONFIG FILE FOUND!</p>";
    exit;
}

// Test 2: Show config (hide password)
echo "<h2>Test 2: Database Configuration</h2>";
echo "<pre>";
echo "Host: " . ($config['host'] ?? 'NOT SET') . "\n";
echo "Database: " . ($config['dbname'] ?? 'NOT SET') . "\n";
echo "Username: " . ($config['username'] ?? 'NOT SET') . "\n";
echo "Password: " . (isset($config['password']) ? str_repeat('*', strlen($config['password'])) : 'NOT SET') . "\n";
echo "</pre>";

// Test 3: Try to connect
echo "<h2>Test 3: Database Connection</h2>";
try {
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "<p class='pass'>✓ DATABASE CONNECTION SUCCESSFUL!</p>";
} catch (PDOException $e) {
    echo "<p class='fail'>✗ CONNECTION FAILED: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Test 4: Check if tables exist
echo "<h2>Test 4: Table Existence Check</h2>";
$requiredTables = ['users', 'wallets', 'categories', 'transactions', 'login_attempts'];
$missingTables = [];

foreach ($requiredTables as $table) {
    try {
        $stmt = $pdo->query("SELECT 1 FROM `$table` LIMIT 1");
        echo "<p class='pass'>✓ Table '$table' exists</p>";
    } catch (PDOException $e) {
        echo "<p class='fail'>✗ Table '$table' MISSING!</p>";
        $missingTables[] = $table;
    }
}

// Test 5: Try to create a test user (simulate registration)
if (empty($missingTables)) {
    echo "<h2>Test 5: Simulate User Registration</h2>";
    try {
        $pdo->beginTransaction();
        
        // Create test user
        $testUsername = 'test_' . time();
        $testEmail = 'test_' . time() . '@example.com';
        $testPassword = password_hash('test123', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$testUsername, $testEmail, $testPassword]);
        $userId = $pdo->lastInsertId();
        echo "<p class='pass'>✓ User created (ID: $userId)</p>";
        
        // Create test wallet
        $stmt = $pdo->prepare("INSERT INTO wallets (user_id, name, description) VALUES (?, ?, ?)");
        $stmt->execute([$userId, 'Test Wallet', 'Test']);
        echo "<p class='pass'>✓ Wallet created</p>";
        
        // Create test category
        $stmt = $pdo->prepare("INSERT INTO categories (user_id, name, type) VALUES (?, ?, ?)");
        $stmt->execute([$userId, 'Test Category', 'expense']);
        echo "<p class='pass'>✓ Category created</p>";
        
        // Rollback (don't actually save test data)
        $pdo->rollBack();
        echo "<p class='pass'>✓ Test data rolled back (not saved)</p>";
        
        echo "<h3 style='color:green;'>✓ REGISTRATION FLOW WORKS!</h3>";
        echo "<p>Your database is properly configured. The error might be elsewhere.</p>";
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<p class='fail'>✗ REGISTRATION TEST FAILED: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>This is likely the cause of your 500 error!</p>";
    }
} else {
    echo "<h2>⚠️ MISSING TABLES!</h2>";
    echo "<p class='fail'>You need to import the database schema first!</p>";
    echo "<p>Missing tables: " . implode(', ', $missingTables) . "</p>";
    echo "<p><strong>Solution:</strong> Import file/schema_production.sql via phpMyAdmin</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ SECURITY WARNING:</strong> DELETE this file after testing!</p>";
echo "<p>File location: /public/test-db.php</p>";
?>
