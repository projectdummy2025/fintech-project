<?php

require_once __DIR__ . '/../Core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Find user by username OR email
     * 
     * @param string $identifier
     * @return array|false
     */
    public function findByUsernameOrEmail($identifier) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->bindParam(':username', $identifier);
        $stmt->bindParam(':email', $identifier);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create new user
     * 
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function create($username, $email, $password) {
        // Hash password using bcrypt (default)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password_hash', $passwordHash);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            // Handle duplicate entry or other errors
            error_log("User Create Error: ". $e->getMessage());
            return false;
        }
    }

    /**
     * Verify password
     * 
     * @param string $password Input password
     * @param string $hash Stored hash
     * @return bool
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Check if email is taken
     * 
     * @param string $email
     * @return bool
     */
    public function isEmailTaken($email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    /**
     * Update last login timestamp
     * 
     * @param int $userId
     * @return bool
     */
    public function updateLastLogin($userId) {
        $stmt = $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    // ==========================================
    // Brute Force Protection Methods
    // ==========================================

    /**
     * Record a failed login attempt
     */
    public function recordLoginAttempt($ip, $username) {
        $stmt = $this->db->prepare("INSERT INTO login_attempts (ip_address, username) VALUES (:ip, :username)");
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':username', $username);
        return $stmt->execute();
    }

    /**
     * Get number of failed attempts in the last X minutes
     */
    public function getLoginAttemptsCount($ip, $username, $minutes = 15) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM login_attempts 
            WHERE ip_address = :ip 
            AND username = :username 
            AND attempt_time > (NOW() - INTERVAL :minutes MINUTE)
        ");
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':minutes', $minutes);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }

    /**
     * Clear login attempts on successful login
     */
    public function clearLoginAttempts($ip, $username) {
        $stmt = $this->db->prepare("DELETE FROM login_attempts WHERE ip_address = :ip AND username = :username");
        $stmt->bindParam(':ip', $ip);
        $stmt->bindParam(':username', $username);
        return $stmt->execute();
    }
}
