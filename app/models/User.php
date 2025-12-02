<?php

require_once __DIR__ . '/../core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
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
     * @param string $password
     * @return bool
     */
    public function create($username, $password) {
        // Hash password using bcrypt (default)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password_hash)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password_hash', $passwordHash);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Handle duplicate entry or other errors
            error_log("User Create Error: " . $e->getMessage());
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
}
