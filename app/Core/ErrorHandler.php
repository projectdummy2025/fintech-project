<?php

class ErrorHandler {
    private static $logFile = __DIR__ . '/../../storage/logs/app.log';
    
    /**
     * Initialize error handler
     */
    public static function init($debugMode = false) {
        // Set error reporting based on debug mode
        if ($debugMode) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }
        
        // Set custom error handler
        set_error_handler([self::class, 'handleError']);
        
        // Set custom exception handler
        set_exception_handler([self::class, 'handleException']);
        
        // Set shutdown handler for fatal errors
        register_shutdown_function([self::class, 'handleShutdown']);
    }
    
    /**
     * Handle errors
     */
    public static function handleError($errno, $errstr, $errfile, $errline) {
        $message = "[" . date('Y-m-d H:i:s') . "] Error[$errno]: $errstr in $errfile on line $errline";
        self::log($message);
        
        // Don't execute PHP internal error handler
        return true;
    }
    
    /**
     * Handle exceptions
     */
    public static function handleException($exception) {
        $message = "[" . date('Y-m-d H:i:s') . "] Exception: " . $exception->getMessage() . 
                   " in " . $exception->getFile() . " on line " . $exception->getLine() . 
                   "\nStack trace:\n" . $exception->getTraceAsString();
        self::log($message);
        
        // Show error page
        self::showErrorPage(500, 'Internal Server Error', 'An unexpected error occurred. Please try again later.');
    }
    
    /**
     * Handle fatal errors
     */
    public static function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $message = "[" . date('Y-m-d H:i:s') . "] Fatal Error: " . $error['message'] . 
                       " in " . $error['file'] . " on line " . $error['line'];
            self::log($message);
            
            self::showErrorPage(500, 'Server Error', 'A critical error occurred. Please contact support.');
        }
    }
    
    /**
     * Log message to file
     */
    public static function log($message) {
        // Ensure logs directory exists
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        error_log($message . PHP_EOL, 3, self::$logFile);
    }
    
    /**
     * Show error page
     */
    public static function showErrorPage($code = 500, $title = 'Error', $message = 'An error occurred') {
        http_response_code($code);
        
        // Check if error view exists
        $errorView = __DIR__ . '/../Views/errors/' . $code . '.php';
        if (file_exists($errorView)) {
            require_once $errorView;
        } else {
            // Fallback simple error page
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $code . ' - ' . htmlspecialchars($title) . '</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-gray-300">' . $code . '</h1>
        <h2 class="text-3xl font-semibold text-gray-800 mt-4">' . htmlspecialchars($title) . '</h2>
        <p class="text-gray-600 mt-2">' . htmlspecialchars($message) . '</p>
        <a href="/" class="mt-6 inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
            Go Home
        </a>
    </div>
</body>
</html>';
        }
        exit;
    }
    
    /**
     * Log info message
     */
    public static function info($message) {
        self::log("[INFO] $message");
    }
    
    /**
     * Log warning message
     */
    public static function warning($message) {
        self::log("[WARNING] $message");
    }
    
    /**
     * Log debug message
     */
    public static function debug($message) {
        self::log("[DEBUG] $message");
    }
}
