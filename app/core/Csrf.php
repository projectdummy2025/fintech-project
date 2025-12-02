<?php

class Csrf {
    /**
     * Generate CSRF Token
     * 
     * @return string
     */
    public static function generate() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF Token
     * 
     * @param string $token
     * @return bool
     */
    public static function verify($token) {
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }

    /**
     * Get HTML Input Field
     * 
     * @return string
     */
    public static function field() {
        $token = self::generate();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}
