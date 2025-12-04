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
            // Load config from .env.php if available, else fallback to config/database.php
            $envPath = __DIR__ . '/../../.env.php';
            if (file_exists($envPath)) {
                $env = require $envPath;
                $config = $env['database'];
            } else {
                $config = require __DIR__ . '/../../config/database.php';
            }

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
            $options = $config['options'] ?? [];

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                // Log error
                error_log("Database Connection Error: " . $e->getMessage());
                throw new Exception("Database Connection Failed");
            }
        }
        return self::$instance;
    }
}
