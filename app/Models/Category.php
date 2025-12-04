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
            // Note: Due to foreign key constraint with ON DELETE RESTRICT,
            // we can't delete a category if it has associated transactions
            // Additional logic would be needed to handle this properly
            $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Category Delete Error: " . $e->getMessage());
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