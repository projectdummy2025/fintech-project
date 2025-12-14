<?php

require_once __DIR__ . '/../Core/Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all transactions for a user with optional filters
     *
     * @param int $userId
     * @param array $filters (month, year, category_id, wallet_id, type, search)
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAllByUser($userId, $filters = [], $limit = null, $offset = null) {
        $sql = "SELECT t.*, c.name as category_name, c.type as category_type, w.name as wallet_name
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN wallets w ON t.wallet_id = w.id
                WHERE t.user_id = :user_id";
        $params = [':user_id' => $userId];

        // Apply filters
        // Apply filters
        if (!empty($filters['start_date'])) {
            $sql .= " AND t.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND t.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (!empty($filters['month']) && !empty($filters['year']) && empty($filters['start_date']) && empty($filters['end_date'])) {
            $sql .= " AND YEAR(t.date) = :year AND MONTH(t.date) = :month";
            $params[':year'] = $filters['year'];
            $params[':month'] = $filters['month'];
        }

        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['wallet_id'])) {
            $sql .= " AND t.wallet_id = :wallet_id";
            $params[':wallet_id'] = $filters['wallet_id'];
        }

        if (!empty($filters['type']) && in_array($filters['type'], ['income', 'expense'])) {
            $sql .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (c.name LIKE :search OR w.name LIKE :search OR t.notes LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY t.`date` DESC, t.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
            $params[':limit'] = (int)$limit;
        }

        if ($offset !== null) {
            $sql .= " OFFSET :offset";
            $params[':offset'] = (int)$offset;
        }

        $stmt = $this->db->prepare($sql);

        // Bind parameters
        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Count all transactions for a user with optional filters
     *
     * @param int $userId
     * @param array $filters
     * @return int
     */
    public function countAllByUser($userId, $filters = []) {
        $sql = "SELECT COUNT(t.id)
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN wallets w ON t.wallet_id = w.id
                WHERE t.user_id = :user_id";
        $params = [':user_id' => $userId];

        // Apply filters (same as getAllByUser)
        if (!empty($filters['start_date'])) {
            $sql .= " AND t.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND t.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (!empty($filters['month']) && !empty($filters['year']) && empty($filters['start_date']) && empty($filters['end_date'])) {
            $sql .= " AND YEAR(t.date) = :year AND MONTH(t.date) = :month";
            $params[':year'] = $filters['year'];
            $params[':month'] = $filters['month'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['wallet_id'])) {
            $sql .= " AND t.wallet_id = :wallet_id";
            $params[':wallet_id'] = $filters['wallet_id'];
        }
        if (!empty($filters['type']) && in_array($filters['type'], ['income', 'expense'])) {
            $sql .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (c.name LIKE :search OR w.name LIKE :search OR t.notes LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get a transaction by ID and user
     *
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getByIdAndUser($id, $userId) {
        $sql = "SELECT t.*, c.name as category_name, c.type as category_type, w.name as wallet_name
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN wallets w ON t.wallet_id = w.id
                WHERE t.id = :id AND t.user_id = :user_id LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create a new transaction
     *
     * @param int $userId
     * @param int $walletId
     * @param int $categoryId
     * @param float $amount
     * @param string $type ('income' or 'expense')
     * @param string $notes
     * @param string $date (Y-m-d format)
     * @return bool
     */
    public function create($userId, $walletId, $categoryId, $amount, $type, $notes = null, $date = null) {
        // Validate type
        if (!in_array($type, ['income', 'expense'])) {
            return false;
        }

        // Set default date to today if not provided
        if ($date === null) {
            $date = date('Y-m-d');
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO transactions (user_id, wallet_id, category_id, amount, type, notes, `date`) 
                VALUES (:user_id, :wallet_id, :category_id, :amount, :type, :notes, :date)
            ");
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':wallet_id', $walletId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':date', $date);

            $result = $stmt->execute();

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Transaction Create Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a transaction
     *
     * @param int $id
     * @param int $userId
     * @param int $walletId
     * @param int $categoryId
     * @param float $amount
     * @param string $type ('income' or 'expense')
     * @param string $notes
     * @param string $date (Y-m-d format)
     * @return bool
     */
    public function update($id, $userId, $walletId, $categoryId, $amount, $type, $notes = null, $date = null) {
        // Validate type
        if (!in_array($type, ['income', 'expense'])) {
            return false;
        }

        // Set default date to today if not provided
        if ($date === null) {
            $date = date('Y-m-d');
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE transactions 
                SET wallet_id = :wallet_id, category_id = :category_id, 
                    amount = :amount, type = :type, notes = :notes, 
                    `date` = :date 
                WHERE id = :id AND user_id = :user_id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':wallet_id', $walletId);
            $stmt->bindParam(':category_id', $categoryId);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':date', $date);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Transaction Update Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a transaction
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':user_id', $userId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Transaction Delete Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a transaction belongs to a user
     *
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function belongsToUser($id, $userId) {
        $stmt = $this->db->prepare("SELECT id FROM transactions WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    /**
     * Get transaction summary for a user in a date range
     *
     * @param int $userId
     * @param string $startDate (Y-m-d format)
     * @param string $endDate (Y-m-d format)
     * @return array
     */
    public function getSummary($userId, $startDate = null, $endDate = null) {
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) AS total_income,
                    COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) AS total_expense,
                    COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END), 0) AS net_balance
                FROM transactions 
                WHERE user_id = :user_id";
        
        $params = [':user_id' => $userId];
        
        if ($startDate !== null) {
            $sql .= " AND `date` >= :start_date";
            $params[':start_date'] = $startDate;
        }
        
        if ($endDate !== null) {
            $sql .= " AND `date` <= :end_date";
            $params[':end_date'] = $endDate;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Get transactions grouped by category
     *
     * @param int $userId
     * @param string $startDate (Y-m-d format)
     * @param string $endDate (Y-m-d format)
     * @param string $type ('income' or 'expense' or null for both)
     * @return array
     */
    public function getGroupedByCategory($userId, $startDate = null, $endDate = null, $type = null) {
        $sql = "SELECT
                    c.id as category_id,
                    c.name as category_name,
                    c.type as category_type,
                    SUM(t.amount) as total_amount,
                    COUNT(t.id) as transaction_count
                FROM transactions t
                LEFT JOIN categories c ON t.category_id = c.id
                WHERE t.user_id = :user_id";
        
        $params = [':user_id' => $userId];
        
        if ($startDate !== null) {
            $sql .= " AND t.`date` >= :start_date";
            $params[':start_date'] = $startDate;
        }
        
        if ($endDate !== null) {
            $sql .= " AND t.`date` <= :end_date";
            $params[':end_date'] = $endDate;
        }
        
        if ($type !== null && in_array($type, ['income', 'expense'])) {
            $sql .= " AND t.type = :type";
            $params[':type'] = $type;
        }
        
        $sql .= " GROUP BY c.id, c.name, c.type ORDER BY total_amount DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get transactions grouped by wallet
     *
     * @param int $userId
     * @param string $startDate (Y-m-d format)
     * @param string $endDate (Y-m-d format)
     * @return array
     */
    public function getGroupedByWallet($userId, $startDate = null, $endDate = null) {
        $sql = "SELECT
                    w.id as wallet_id,
                    w.name as wallet_name,
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END) as net_balance,
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END) as total_expense
                FROM transactions t
                LEFT JOIN wallets w ON t.wallet_id = w.id
                WHERE t.user_id = :user_id";

        $params = [':user_id' => $userId];

        if ($startDate !== null) {
            $sql .= " AND t.`date` >= :start_date";
            $params[':start_date'] = $startDate;
        }

        if ($endDate !== null) {
            $sql .= " AND t.`date` <= :end_date";
            $params[':end_date'] = $endDate;
        }

        $sql .= " GROUP BY w.id, w.name ORDER BY net_balance DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get wallet balances up to a specific date (cumulative from the beginning)
     *
     * @param int $userId
     * @param string $endDate (Y-m-d format)
     * @return array
     */
    public function getWalletBalancesUpToDate($userId, $endDate = null) {
        $sql = "SELECT
                    w.id as wallet_id,
                    w.name as wallet_name,
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE -t.amount END) as net_balance,
                    SUM(CASE WHEN t.type = 'income' THEN t.amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN t.type = 'expense' THEN t.amount ELSE 0 END) as total_expense
                FROM wallets w
                LEFT JOIN transactions t ON w.id = t.wallet_id";

        $params = [':user_id' => $userId];

        if ($endDate !== null) {
            $sql .= " AND t.`date` <= :end_date";
            $params[':end_date'] = $endDate;
        }

        $sql .= " WHERE w.user_id = :user_id GROUP BY w.id, w.name ORDER BY net_balance DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get monthly transaction trends for the last N months
     *
     * @param int $userId
     * @param int $months Number of months to look back
     * @return array
     */
    public function getMonthlyTrends($userId, $months = 6) {
        // Calculate start date in PHP to be safe
        $startDate = date('Y-m-01', strtotime("-$months months"));
        
        $sql = "SELECT 
                    YEAR(`date`) as year, 
                    MONTH(`date`) as month, 
                    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
                FROM transactions 
                WHERE user_id = :user_id 
                AND `date` >= :start_date
                GROUP BY YEAR(`date`), MONTH(`date`)
                ORDER BY YEAR(`date`) ASC, MONTH(`date`) ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    /**
     * Count transactions by category ID
     *
     * @param int $categoryId
     * @return int
     */
    public function countByCategory($categoryId) {
        $sql = "SELECT COUNT(*) as count FROM transactions WHERE category_id = :category_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }
    /**
     * Get checksum for sync
     *
     * @param int $userId
     * @return array
     */
    public function getChecksum($userId) {
        $sql = "SELECT 
                    COUNT(id) as count, 
                    COALESCE(SUM(amount), 0) as sum, 
                    COALESCE(MAX(id), 0) as last_id 
                FROM transactions 
                WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
}