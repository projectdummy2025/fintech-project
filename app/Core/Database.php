<?php

class Database {
    /**
     * Get Database Connection
     * 
     * @return PDO
     */
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            // Try to load config from environment files in order of priority
            // 1. .env.production.php (for production/InfinityFree)
            // 2. .env.php (for local development)
            // 3. config/database.php (fallback)
            
            $prodEnvPath = __DIR__ . '/../../.env.production.php';
            $devEnvPath = __DIR__ . '/../../.env.php';
            
            if (file_exists($prodEnvPath)) {
                $env = require $prodEnvPath;
                $config = $env['database'];
            } elseif (file_exists($devEnvPath)) {
                $env = require $devEnvPath;
                $config = $env['database'];
            } else {
                $config = require __DIR__ . '/../../config/database.php';
            }

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
            $options = $config['options'] ?? [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // Log error
                error_log("Database Connection Error: " . $e->getMessage());
                throw new Exception("Database Connection Failed: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
