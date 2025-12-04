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
            $this->db->beginTransaction();

            // First, check if wallet has associated transactions
            $checkStmt = $this->db->prepare("SELECT COUNT(*) as count FROM transactions WHERE wallet_id = :wallet_id");
            $checkStmt->bindParam(':wallet_id', $id);
            $checkStmt->execute();
            $result = $checkStmt->fetch();

            if ($result['count'] > 0) {
                // Wallet has transactions, we need to handle them first
                // Option 1: Prevent deletion if transactions exist
                $this->db->rollback();
                return false;

                // Option 2: Move transactions to another wallet (would need to be passed as parameter)
                // For now, we'll go with option 1 for safety
            }

            // If no transactions, delete the wallet
            $stmt = $this->db->prepare("DELETE FROM wallets WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            $result = $stmt->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Wallet Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a wallet and transfer its transactions to another wallet
     *
     * @param int $id
     * @param int $userId
     * @param int $newWalletId The wallet to transfer transactions to
     * @return bool
     */
    public function deleteWithTransfer($id, $userId, $newWalletId) {
        try {
            $this->db->beginTransaction();

            // Verify that both wallets belong to the user
            $walletCheck = $this->db->prepare("SELECT COUNT(*) as count FROM wallets WHERE id IN (:id, :new_id) AND user_id = :user_id");
            $walletCheck->bindParam(':id', $id);
            $walletCheck->bindParam(':new_id', $newWalletId);
            $walletCheck->bindParam(':user_id', $userId);
            $walletCheck->execute();
            $result = $walletCheck->fetch();

            if ($result['count'] != 2) {
                // One or both wallets don't belong to the user
                $this->db->rollback();
                return false;
            }

            // Update transactions to use the new wallet
            $updateStmt = $this->db->prepare("UPDATE transactions SET wallet_id = :new_wallet_id WHERE wallet_id = :wallet_id AND user_id = :user_id");
            $updateStmt->bindParam(':new_wallet_id', $newWalletId);
            $updateStmt->bindParam(':wallet_id', $id);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->execute();

            // Delete the old wallet
            $deleteStmt = $this->db->prepare("DELETE FROM wallets WHERE id = :id AND user_id = :user_id");
            $deleteStmt->bindParam(':id', $id);
            $deleteStmt->bindParam(':user_id', $userId);
            $result = $deleteStmt->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Wallet Delete With Transfer Error: " . $e->getMessage());
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