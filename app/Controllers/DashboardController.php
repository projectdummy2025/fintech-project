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

        // Pagination
        $perPage = 10;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $offset = ($currentPage - 1) * $perPage;

        // Filters based on month and year for transactions
        $filters = [
            'month' => $month,
            'year' => $year
        ];

        // Get total transactions for pagination
        $totalTransactions = $transactionModel->countAllByUser($userId, $filters);
        $totalPages = ceil($totalTransactions / $perPage);

        // Get recent transactions with pagination
        $recentTransactions = $transactionModel->getAllByUser($userId, $filters, $perPage, $offset);

        // Get transactions grouped by category for expenses
        $expenseByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'expense');

        // Get transactions grouped by category for income
        $incomeByCategory = $transactionModel->getGroupedByCategory($userId, $startDate, $endDate, 'income');

        // Get wallet balances for the selected month (for recent transactions display)
        $walletBalancesForMonth = $transactionModel->getGroupedByWallet($userId, $startDate, $endDate);

        // Get current wallet balances (not affected by time filter) for both table and summary card
        $walletBalances = $transactionModel->getWalletBalancesUpToDate($userId, null);
        $totalWalletBalance = array_sum(array_column($walletBalances, 'net_balance'));

        // Calculate previous month's balance for comparison (this creates the "change" metric)
        $prevMonth = $month - 1;
        $prevYear = $year;
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear = $year - 1;
        }

        $prevEndDate = date('Y-m-t', mktime(0, 0, 0, $prevMonth, 1)); // Last day of previous month

        $prevWalletBalances = $transactionModel->getWalletBalancesUpToDate($userId, $prevEndDate);
        $prevTotalWalletBalance = array_sum(array_column($prevWalletBalances, 'net_balance'));

        // Calculate change from previous selected period to current (now)
        $balanceChange = $totalWalletBalance - $prevTotalWalletBalance;
        $balanceChangePercent = $prevTotalWalletBalance != 0 ? ($balanceChange / $prevTotalWalletBalance) * 100 : 0;

        // Get all wallets for filter dropdown with balances
        $wallets = $walletModel->getBalancesByUser($userId);

        // Get all categories for filter dropdown
        $categories = $categoryModel->getAllByUser($userId);

        // Get monthly trends for the last 6 months
        $monthlyTrends = $transactionModel->getMonthlyTrends($userId, 6);

        $data = [
            'title' => 'Dashboard',
            'username' => $_SESSION['username'],
            'summary' => $summary,
            'recentTransactions' => $recentTransactions,
            'expenseByCategory' => $expenseByCategory,
            'incomeByCategory' => $incomeByCategory,
            'monthlyTrends' => $monthlyTrends,
            'walletBalances' => $walletBalances,
            'totalWalletBalance' => $totalWalletBalance,
            'prevTotalWalletBalance' => $prevTotalWalletBalance,
            'balanceChange' => $balanceChange,
            'balanceChangePercent' => $balanceChangePercent,
            'wallets' => $wallets,
            'categories' => $categories,
            'currentYear' => $year,
            'currentMonth' => $month,
            'monthName' => date('F', mktime(0, 0, 0, $month, 10)), // Month name
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ];

        $this->view('dashboard/index', $data);
    }
}
