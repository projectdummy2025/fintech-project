<?php

class Database {
    /**
     * Get Database Connection
     * 
     * @return PDO
     */
    public static function getConnection() {
        $config = require __DIR__ . '/../../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        
        try {
            return new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            // Log error
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database Connection Failed");
        }
    }
}
