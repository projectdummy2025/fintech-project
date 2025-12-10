<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Transaction.php';
require_once __DIR__ . '/../Models/Wallet.php';
require_once __DIR__ . '/../Models/Category.php';

class ApiController extends Controller {

    /**
     * Send JSON response
     */
    private function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Check authentication for API
     */
    private function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['error' => 'Unauthorized'], 401);
        }
        return $_SESSION['user_id'];
    }

    /**
     * GET /api/dashboard
     * Returns all dashboard data
     */
    public function dashboard() {
        $userId = $this->requireAuth();

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');

        if (!is_numeric($year) || !is_numeric($month) || $year < 1970 || $year > 2100 || $month < 1 || $month > 12) {
            $year = date('Y');
            $month = date('m');
        }

        $transactionModel = new Transaction();
        $walletModel = new Wallet();

        $startDate = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        // Get summary
        $summary = $transactionModel->getSummary($userId, $startDate, $endDate);

        // Get expense by category
        $expenseByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'expense');

        // Get income by category
        $incomeByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'income');

        // Get wallet balances (current, not filtered by date)
        $walletBalances = $transactionModel->getWalletBalancesUpToDate($userId, null);
        $totalWalletBalance = array_sum(array_column($walletBalances, 'net_balance'));

        // Get recent transactions
        $filters = ['month' => $month, 'year' => $year];
        $recentTransactions = $transactionModel->getAllByUser($userId, $filters, 10, 0);

        // Get monthly trends (last 6 months)
        $monthlyTrends = $transactionModel->getMonthlyTrends($userId, 6);

        // Fill missing months
        $filledTrends = [];
        $currentDate = new DateTime();
        $startDateObj = (clone $currentDate)->modify('-5 months')->modify('first day of this month');
        
        for ($i = 0; $i < 6; $i++) {
            $tYear = (int)$startDateObj->format('Y');
            $tMonth = (int)$startDateObj->format('n');
            
            $found = false;
            foreach ($monthlyTrends as $trend) {
                if ($trend['year'] == $tYear && $trend['month'] == $tMonth) {
                    $filledTrends[] = $trend;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $filledTrends[] = [
                    'year' => $tYear,
                    'month' => $tMonth,
                    'total_income' => 0,
                    'total_expense' => 0
                ];
            }
            
            $startDateObj->modify('+1 month');
        }

        $this->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_income' => (float)($summary['total_income'] ?? 0),
                    'total_expense' => (float)($summary['total_expense'] ?? 0),
                    'net_balance' => (float)($summary['net_balance'] ?? 0),
                    'total_wallet_balance' => (float)$totalWalletBalance
                ],
                'expense_by_category' => $expenseByCategory,
                'income_by_category' => $incomeByCategory,
                'wallet_balances' => $walletBalances,
                'recent_transactions' => $recentTransactions,
                'monthly_trends' => $filledTrends,
                'filters' => [
                    'year' => (int)$year,
                    'month' => (int)$month,
                    'month_name' => date('F', mktime(0, 0, 0, $month, 10))
                ]
            ]
        ]);
    }

    /**
     * GET /api/transactions
     * Returns transaction list with filters
     */
    public function transactions() {
        $userId = $this->requireAuth();

        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        $categoryId = $_GET['category_id'] ?? null;
        $walletId = $_GET['wallet_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $search = $_GET['search'] ?? null;

        if (!is_numeric($year) || !is_numeric($month) || $year < 1970 || $year > 2100 || $month < 1 || $month > 12) {
            $year = date('Y');
            $month = date('m');
        }

        $filters = [
            'month' => $month,
            'year' => $year
        ];

        if (!empty($categoryId)) $filters['category_id'] = $categoryId;
        if (!empty($walletId)) $filters['wallet_id'] = $walletId;
        if (!empty($type) && in_array($type, ['income', 'expense'])) $filters['type'] = $type;
        if (!empty($search)) $filters['search'] = $search;

        $transactionModel = new Transaction();
        $walletModel = new Wallet();
        $categoryModel = new Category();

        $transactions = $transactionModel->getAllByUser($userId, $filters);
        $wallets = $walletModel->getAllByUser($userId);
        $categories = $categoryModel->getAllByUser($userId);

        // Calculate totals
        $totalIncome = 0;
        $totalExpense = 0;
        foreach ($transactions as $t) {
            if ($t['type'] === 'income') {
                $totalIncome += $t['amount'];
            } else {
                $totalExpense += $t['amount'];
            }
        }

        $this->json([
            'success' => true,
            'data' => [
                'transactions' => $transactions,
                'wallets' => $wallets,
                'categories' => $categories,
                'totals' => [
                    'income' => (float)$totalIncome,
                    'expense' => (float)$totalExpense,
                    'net' => (float)($totalIncome - $totalExpense),
                    'count' => count($transactions)
                ],
                'filters' => [
                    'year' => (int)$year,
                    'month' => (int)$month,
                    'month_name' => date('F', mktime(0, 0, 0, $month, 10)),
                    'category_id' => $categoryId,
                    'wallet_id' => $walletId,
                    'type' => $type,
                    'search' => $search
                ]
            ]
        ]);
    }

    /**
     * POST /api/transactions/create
     */
    public function transactionCreate() {
        $userId = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        // Validate
        $walletId = $input['wallet_id'] ?? null;
        $categoryId = $input['category_id'] ?? null;
        $amount = $input['amount'] ?? null;
        $type = $input['type'] ?? null;
        $notes = $input['notes'] ?? '';
        $date = $input['date'] ?? date('Y-m-d');

        if (!$walletId || !$categoryId || !$amount || !$type) {
            $this->json(['error' => 'Missing required fields'], 400);
        }

        if (!in_array($type, ['income', 'expense'])) {
            $this->json(['error' => 'Invalid type'], 400);
        }

        $transactionModel = new Transaction();
        $result = $transactionModel->create($userId, $walletId, $categoryId, $amount, $type, $notes, $date);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Transaction created']);
        } else {
            $this->json(['error' => 'Failed to create transaction'], 500);
        }
    }

    /**
     * POST /api/transactions/update/{id}
     */
    public function transactionUpdate($id) {
        $userId = $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }

        $transactionModel = new Transaction();

        // Check ownership
        if (!$transactionModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $walletId = $input['wallet_id'] ?? null;
        $categoryId = $input['category_id'] ?? null;
        $amount = $input['amount'] ?? null;
        $type = $input['type'] ?? null;
        $notes = $input['notes'] ?? '';
        $date = $input['date'] ?? date('Y-m-d');

        if (!$walletId || !$categoryId || !$amount || !$type) {
            $this->json(['error' => 'Missing required fields'], 400);
        }

        $result = $transactionModel->update($id, $userId, $walletId, $categoryId, $amount, $type, $notes, $date);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Transaction updated']);
        } else {
            $this->json(['error' => 'Failed to update transaction'], 500);
        }
    }

    /**
     * POST /api/transactions/delete/{id}
     */
    public function transactionDelete($id) {
        $userId = $this->requireAuth();

        $transactionModel = new Transaction();

        if (!$transactionModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        $result = $transactionModel->delete($id, $userId);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Transaction deleted']);
        } else {
            $this->json(['error' => 'Failed to delete transaction'], 500);
        }
    }

    /**
     * GET /api/wallets
     */
    public function wallets() {
        $userId = $this->requireAuth();

        $walletModel = new Wallet();
        $transactionModel = new Transaction();

        $wallets = $walletModel->getAllByUser($userId);
        $balances = $transactionModel->getWalletBalancesUpToDate($userId, null);

        // Merge balances into wallets
        $walletsWithBalance = [];
        foreach ($wallets as $wallet) {
            $balance = 0;
            foreach ($balances as $b) {
                if ($b['wallet_id'] == $wallet['id']) {
                    $balance = $b['net_balance'];
                    break;
                }
            }
            $wallet['balance'] = (float)$balance;
            $walletsWithBalance[] = $wallet;
        }

        $totalBalance = array_sum(array_column($walletsWithBalance, 'balance'));

        $this->json([
            'success' => true,
            'data' => [
                'wallets' => $walletsWithBalance,
                'total_balance' => (float)$totalBalance,
                'count' => count($walletsWithBalance)
            ]
        ]);
    }

    /**
     * POST /api/wallets/create
     */
    public function walletCreate() {
        $userId = $this->requireAuth();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');

        if (empty($name)) {
            $this->json(['error' => 'Name is required'], 400);
        }

        $walletModel = new Wallet();
        $result = $walletModel->create($userId, $name, $description);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Wallet created']);
        } else {
            $this->json(['error' => 'Failed to create wallet'], 500);
        }
    }

    /**
     * POST /api/wallets/update/{id}
     */
    public function walletUpdate($id) {
        $userId = $this->requireAuth();

        $walletModel = new Wallet();

        if (!$walletModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');

        if (empty($name)) {
            $this->json(['error' => 'Name is required'], 400);
        }

        $result = $walletModel->update($id, $userId, $name, $description);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Wallet updated']);
        } else {
            $this->json(['error' => 'Failed to update wallet'], 500);
        }
    }

    /**
     * POST /api/wallets/delete/{id}
     */
    public function walletDelete($id) {
        $userId = $this->requireAuth();

        $walletModel = new Wallet();
        $transactionModel = new Transaction();

        if (!$walletModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        // Check if wallet has transactions
        if ($transactionModel->countByWallet($id) > 0) {
            $this->json(['error' => 'Cannot delete wallet with transactions. Transfer transactions first.'], 400);
        }

        $result = $walletModel->delete($id, $userId);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Wallet deleted']);
        } else {
            $this->json(['error' => 'Failed to delete wallet'], 500);
        }
    }

    /**
     * GET /api/categories
     */
    public function categories() {
        try {
            $userId = $this->requireAuth();

            $categoryModel = new Category();
            $categories = $categoryModel->getAllByUser($userId);

            // Get usage count for each category
            $transactionModel = new Transaction();
            foreach ($categories as &$category) {
                $category['usage_count'] = $transactionModel->countByCategory($category['id']);
            }

            $incomeCategories = array_filter($categories, fn($c) => $c['type'] === 'income');
            $expenseCategories = array_filter($categories, fn($c) => $c['type'] === 'expense');

            $this->json([
                'success' => true,
                'data' => [
                    'categories' => array_values($categories),
                    'income_categories' => array_values($incomeCategories),
                    'expense_categories' => array_values($expenseCategories),
                    'count' => count($categories)
                ]
            ]);
        } catch (Exception $e) {
            error_log("API Categories Error: " . $e->getMessage());
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * POST /api/categories/create
     */
    public function categoryCreate() {
        $userId = $this->requireAuth();

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $name = trim($input['name'] ?? '');
        $type = $input['type'] ?? '';

        if (empty($name)) {
            $this->json(['error' => 'Name is required'], 400);
        }

        if (!in_array($type, ['income', 'expense'])) {
            $this->json(['error' => 'Invalid type'], 400);
        }

        $categoryModel = new Category();
        $result = $categoryModel->create($userId, $name, $type);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Category created', 'id' => $result]);
        } else {
            $this->json(['error' => 'Failed to create category'], 500);
        }
    }

    /**
     * POST /api/categories/update/{id}
     */
    public function categoryUpdate($id) {
        $userId = $this->requireAuth();

        $categoryModel = new Category();

        if (!$categoryModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $name = trim($input['name'] ?? '');

        if (empty($name)) {
            $this->json(['error' => 'Name is required'], 400);
        }

        $type = $input['type'] ?? '';

        if (empty($name)) {
            $this->json(['error' => 'Name is required'], 400);
        }

        if (!in_array($type, ['income', 'expense'])) {
            $this->json(['error' => 'Category type must be income or expense.'], 400);
        }

        $result = $categoryModel->update($id, $userId, $name, $type);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Category updated']);
        } else {
            $this->json(['error' => 'Failed to update category'], 500);
        }
    }

    /**
     * POST /api/categories/delete/{id}
     */
    public function categoryDelete($id) {
        $userId = $this->requireAuth();

        $categoryModel = new Category();
        $transactionModel = new Transaction();

        if (!$categoryModel->belongsToUser($id, $userId)) {
            $this->json(['error' => 'Not found'], 404);
        }

        // Check if category has transactions
        if ($transactionModel->countByCategory($id) > 0) {
            $this->json(['error' => 'Cannot delete category with transactions. Transfer transactions first.'], 400);
        }

        $result = $categoryModel->delete($id, $userId);

        if ($result) {
            $this->json(['success' => true, 'message' => 'Category deleted']);
        } else {
            $this->json(['error' => 'Failed to delete category'], 500);
        }
    }

    /**
     * GET /api/form-data
     * Returns wallets and categories for forms
     */
    public function formData() {
        $userId = $this->requireAuth();

        $walletModel = new Wallet();
        $categoryModel = new Category();
        $transactionModel = new Transaction();

        $wallets = $walletModel->getAllByUser($userId);
        $categories = $categoryModel->getAllByUser($userId);

        // Get wallet balances
        $balances = $transactionModel->getWalletBalancesUpToDate($userId, null);
        foreach ($wallets as &$wallet) {
            $wallet['balance'] = 0;
            foreach ($balances as $b) {
                if ($b['wallet_id'] == $wallet['id']) {
                    $wallet['balance'] = (float)$b['net_balance'];
                    break;
                }
            }
        }

        $this->json([
            'success' => true,
            'data' => [
                'wallets' => $wallets,
                'categories' => $categories,
                'income_categories' => array_values(array_filter($categories, fn($c) => $c['type'] === 'income')),
                'expense_categories' => array_values(array_filter($categories, fn($c) => $c['type'] === 'expense'))
            ]
        ]);
    }
}
