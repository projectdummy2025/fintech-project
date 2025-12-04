<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Transaction.php';
require_once __DIR__ . '/../Models/Wallet.php';
require_once __DIR__ . '/../Models/Category.php';

class DashboardController extends Controller {

    public function index() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Get date range from query parameters or use this month
        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');

        // Validation for year and month
        if (!is_numeric($year) || !is_numeric($month) || $year < 1970 || $year > 2100 || $month < 1 || $month > 12) {
            $year = date('Y');
            $month = date('m');
        }

        $transactionModel = new Transaction();
        $walletModel = new Wallet();
        $categoryModel = new Category();

        // Calculate date range for the selected month
        $startDate = $year . '-' . $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of the month

        // Get dashboard summary
        $summary = $transactionModel->getSummary($userId, $startDate, $endDate);

        // Get recent transactions
        $recentTransactions = $transactionModel->getAllByUser($userId, [
            'month' => $month,
            'year' => $year
        ], 10, 0);

        // Get transactions grouped by category for expenses
        $expenseByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'expense');

        // Get transactions grouped by category for income
        $incomeByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'income');

        // Get transactions grouped by wallet
        $walletBalances = $transactionModel->getGroupedByWallet($userId, $startDate, $endDate);

        // Get all wallets for filter dropdown
        $wallets = $walletModel->getAllByUser($userId);

        // Get all categories for filter dropdown
        $categories = $categoryModel->getAllByUser($userId);

        $data = [
            'title' => 'Dashboard',
            'username' => $_SESSION['username'],
            'summary' => $summary,
            'recentTransactions' => $recentTransactions,
            'expenseByCategory' => $expenseByCategory,
            'incomeByCategory' => $incomeByCategory,
            'walletBalances' => $walletBalances,
            'wallets' => $wallets,
            'categories' => $categories,
            'currentYear' => $year,
            'currentMonth' => $month,
            'monthName' => date('F', mktime(0, 0, 0, $month, 10)) // Month name
        ];

        $this->view('dashboard/index', $data);
    }
}
