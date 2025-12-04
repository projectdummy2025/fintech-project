<?php

require_once __DIR__ . '/../Core/Database.php';

class Wallet {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all wallets for a user
     *
     * @param int $userId
     * @return array
     */
    public function getAllByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get a wallet by ID and user
     *
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getByIdAndUser($id, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create a new wallet
     *
     * @param int $userId
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function create($userId, $name, $description = null) {
        try {
            $stmt = $this->db->prepare("INSERT INTO wallets (user_id, name, description) VALUES (:user_id, :name, :description)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Wallet Create Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a wallet
     *
     * @param int $id
     * @param int $userId
     * @param string $name
     * @param string $description
     * @return bool
     */
    public function update($id, $userId, $name, $description = null) {
        try {
            $stmt = $this->db->prepare("UPDATE wallets SET name = :name, description = :description WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Wallet Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a wallet
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        try {
            // Note: Due to foreign key constraint with ON DELETE RESTRICT,
            // we can't delete a wallet if it has associated transactions
            // Additional logic would be needed to handle this properly
            $stmt = $this->db->prepare("DELETE FROM wallets WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Wallet Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a wallet belongs to a user
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function belongsToUser($id, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM wallets WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    /**
     * Get wallet balance by calculating transactions
     *
     * @param int $walletId
     * @return float
     */
    public function getBalance($walletId) {
        $stmt = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) AS balance
            FROM transactions 
            WHERE wallet_id = :wallet_id
        ");
        $stmt->bindParam(':wallet_id', $walletId);
        $stmt->execute();
        $result = $stmt->fetch();
        return (float)$result['balance'];
    }
}