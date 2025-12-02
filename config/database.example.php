<?php

return [
    'host' => 'localhost',      // Database host (e.g., 127.0.0.1)
    'dbname' => 'fintech_db',   // Database name
    'username' => 'root',       // Database username
    'password' => '',           // Database password
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];
