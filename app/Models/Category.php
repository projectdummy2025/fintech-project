<?php

require_once __DIR__ . '/../Core/Database.php';

class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all categories for a user
     *
     * @param int $userId
     * @param string $type Optional: 'income' or 'expense'
     * @return array
     */
    public function getAllByUser($userId, $type = null) {
        $sql = "SELECT * FROM categories WHERE user_id = :user_id";
        $params = [':user_id' => $userId];
        
        if ($type !== null) {
            $sql .= " AND type = :type";
            $params[':type'] = $type;
        }
        
        $sql .= " ORDER BY name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get a category by ID and user
     *
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getByIdAndUser($id, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create a new category
     *
     * @param int $userId
     * @param string $name
     * @param string $type ('income' or 'expense')
     * @return bool
     */
    public function create($userId, $name, $type) {
        // Validate type
        if (!in_array($type, ['income', 'expense'])) {
            return false;
        }
        
        try {
            $stmt = $this->db->prepare("INSERT INTO categories (user_id, name, type) VALUES (:user_id, :name, :type)");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Category Create Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a category
     *
     * @param int $id
     * @param int $userId
     * @param string $name
     * @param string $type ('income' or 'expense')
     * @return bool
     */
    public function update($id, $userId, $name, $type) {
        // Validate type
        if (!in_array($type, ['income', 'expense'])) {
            return false;
        }
        
        try {
            $stmt = $this->db->prepare("UPDATE categories SET name = :name, type = :type WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':type', $type);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Category Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a category
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        try {
            $this->db->beginTransaction();

            // First, check if category has associated transactions
            $checkStmt = $this->db->prepare("SELECT COUNT(*) as count FROM transactions WHERE category_id = :category_id");
            $checkStmt->bindParam(':category_id', $id);
            $checkStmt->execute();
            $result = $checkStmt->fetch();

            if ($result['count'] > 0) {
                // Category has transactions, we need to handle them first
                // For safety, we'll prevent deletion if transactions exist
                $this->db->rollback();
                return false;
            }

            // If no transactions, delete the category
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            $result = $stmt->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Category Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a category and transfer its transactions to another category
     *
     * @param int $id
     * @param int $userId
     * @param int $newCategoryId The category to transfer transactions to
     * @return bool
     */
    public function deleteWithTransfer($id, $userId, $newCategoryId) {
        try {
            $this->db->beginTransaction();

            // Verify that both categories belong to the user and have the same type
            $categoryCheck = $this->db->prepare("
                SELECT c1.type as old_type, c2.type as new_type
                FROM categories c1
                JOIN categories c2 ON c2.id = :new_id AND c2.user_id = :user_id
                WHERE c1.id = :id AND c1.user_id = :user_id
            ");
            $categoryCheck->bindParam(':id', $id);
            $categoryCheck->bindParam(':new_id', $newCategoryId);
            $categoryCheck->bindParam(':user_id', $userId);
            $categoryCheck->execute();
            $result = $categoryCheck->fetch();

            if (!$result || $result['old_type'] !== $result['new_type']) {
                // Categories don't belong to the user or don't have the same type
                $this->db->rollback();
                return false;
            }

            // Update transactions to use the new category
            $updateStmt = $this->db->prepare("UPDATE transactions SET category_id = :new_category_id WHERE category_id = :category_id AND user_id = :user_id");
            $updateStmt->bindParam(':new_category_id', $newCategoryId);
            $updateStmt->bindParam(':category_id', $id);
            $updateStmt->bindParam(':user_id', $userId);
            $updateStmt->execute();

            // Delete the old category
            $deleteStmt = $this->db->prepare("DELETE FROM categories WHERE id = :id AND user_id = :user_id");
            $deleteStmt->bindParam(':id', $id);
            $deleteStmt->bindParam(':user_id', $userId);
            $result = $deleteStmt->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Category Delete With Transfer Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a category belongs to a user
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function belongsToUser($id, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM categories WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    /**
     * Get transaction count for a category
     *
     * @param int $categoryId
     * @return int
     */
    public function getTransactionCount($categoryId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM transactions WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)$result['count'];
    }

    /**
     * Get default categories for a new user
     *
     * @return array
     */
    public function getDefaultCategories() {
        return [
            ['name' => 'Salary', 'type' => 'income'],
            ['name' => 'Business', 'type' => 'income'],
            ['name' => 'Investment', 'type' => 'income'],
            ['name' => 'Gift', 'type' => 'income'],
            ['name' => 'Food & Drink', 'type' => 'expense'],
            ['name' => 'Transportation', 'type' => 'expense'],
            ['name' => 'Shopping', 'type' => 'expense'],
            ['name' => 'Entertainment', 'type' => 'expense'],
            ['name' => 'Healthcare', 'type' => 'expense'],
            ['name' => 'Education', 'type' => 'expense'],
            ['name' => 'Housing', 'type' => 'expense'],
            ['name' => 'Utilities', 'type' => 'expense'],
            ['name' => 'Insurance', 'type' => 'expense'],
            ['name' => 'Loan Payment', 'type' => 'expense'],
            ['name' => 'Other', 'type' => 'expense'],
        ];
    }

    /**
     * Create default categories for a new user
     *
     * @param int $userId
     * @return bool
     */
    public function createDefaultCategories($userId) {
        $defaultCategories = $this->getDefaultCategories();
        $success = true;
        
        foreach ($defaultCategories as $category) {
            if (!$this->create($userId, $category['name'], $category['type'])) {
                $success = false;
            }
        }
        
        return $success;
    }
}