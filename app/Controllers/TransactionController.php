<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Transaction.php';
require_once __DIR__ . '/../Models/Wallet.php';
require_once __DIR__ . '/../Models/Category.php';

class TransactionController extends Controller {

    public function index() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $transactionModel = new Transaction();
        $walletModel = new Wallet();
        $categoryModel = new Category();

        // Get filters from query parameters - no defaults
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $categoryId = $_GET['category_id'] ?? null;
        $walletId = $_GET['wallet_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $search = $_GET['search'] ?? null;
        
        // Prepare filters array - only add non-empty values
        $filters = [];
        
        if (!empty($startDate)) {
            $filters['start_date'] = $startDate;
        }
        
        if (!empty($endDate)) {
            $filters['end_date'] = $endDate;
        }
        
        if (!empty($categoryId)) {
            $filters['category_id'] = $categoryId;
        }
        
        if (!empty($walletId)) {
            $filters['wallet_id'] = $walletId;
        }
        
        if (!empty($type) && in_array($type, ['income', 'expense'])) {
            $filters['type'] = $type;
        }
        
        if (!empty($search)) {
            $filters['search'] = $search;
        }

        // Get transactions with filters
        $transactions = $transactionModel->getAllByUser($_SESSION['user_id'], $filters);
        
        // Get all wallets and categories for filter dropdowns
        $wallets = $walletModel->getAllByUser($_SESSION['user_id']);
        $categories = $categoryModel->getAllByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Transactions',
            'transactions' => $transactions,
            'wallets' => $wallets,
            'categories' => $categories,
            'filters' => $filters
        ];

        $this->view('transactions/index', $data);
    }

    public function create() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $walletModel = new Wallet();
        $categoryModel = new Category();

        // Get all wallets and categories for the form with wallet balances
        $wallets = $walletModel->getBalancesByUser($_SESSION['user_id']);
        $categories = $categoryModel->getAllByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Add Transaction',
            'wallets' => $wallets,
            'categories' => $categories,
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $walletId = $_POST['wallet_id'] ?? null;
            $categoryId = $_POST['category_id'] ?? null;
            $amount = $_POST['amount'] ?? null;
            $type = $_POST['type'] ?? null;
            $notes = trim($_POST['notes'] ?? '');
            $date = $_POST['date'] ?? null;

            // Validation
            if (empty($walletId) || empty($categoryId) || empty($amount) || empty($type)) {
                $data['error'] = 'All fields are required.';
            } elseif (!is_numeric($amount) || $amount <= 0) {
                $data['error'] = 'Amount must be a positive number.';
            } elseif (!in_array($type, ['income', 'expense'])) {
                $data['error'] = 'Transaction type must be income or expense.';
            } elseif ($date && !strtotime($date)) {
                $data['error'] = 'Invalid date format.';
            } else {
                $transactionModel = new Transaction();

                // If date is not provided, use today's date
                if (empty($date)) {
                    $date = date('Y-m-d');
                }

                if ($transactionModel->create($_SESSION['user_id'], $walletId, $categoryId, $amount, $type, $notes, $date)) {
                    $data['success'] = 'Transaction added successfully!';
                    header('Location: /transactions');
                    exit;
                } else {
                    $data['error'] = 'Failed to add transaction. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('transactions/create', $data);
    }

    public function edit($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $transactionModel = new Transaction();
        $walletModel = new Wallet();
        $categoryModel = new Category();

        // Check if transaction belongs to user
        $transaction = $transactionModel->getByIdAndUser($id, $_SESSION['user_id']);
        if (!$transaction) {
            header('Location: /transactions');
            exit;
        }

        // Get all wallets and categories for the form
        $wallets = $walletModel->getAllByUser($_SESSION['user_id']);
        $categories = $categoryModel->getAllByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Edit Transaction',
            'transaction' => $transaction,
            'wallets' => $wallets,
            'categories' => $categories,
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $walletId = $_POST['wallet_id'] ?? null;
            $categoryId = $_POST['category_id'] ?? null;
            $amount = $_POST['amount'] ?? null;
            $type = $_POST['type'] ?? null;
            $notes = trim($_POST['notes'] ?? '');
            $date = $_POST['date'] ?? null;

            // Validation
            if (empty($walletId) || empty($categoryId) || empty($amount) || empty($type)) {
                $data['error'] = 'All fields are required.';
            } elseif (!is_numeric($amount) || $amount <= 0) {
                $data['error'] = 'Amount must be a positive number.';
            } elseif (!in_array($type, ['income', 'expense'])) {
                $data['error'] = 'Transaction type must be income or expense.';
            } elseif ($date && !strtotime($date)) {
                $data['error'] = 'Invalid date format.';
            } else {
                // If date is not provided, use today's date
                if (empty($date)) {
                    $date = date('Y-m-d');
                }

                if ($transactionModel->update($id, $_SESSION['user_id'], $walletId, $categoryId, $amount, $type, $notes, $date)) {
                    $data['success'] = 'Transaction updated successfully!';
                    $data['transaction'] = $transactionModel->getByIdAndUser($id, $_SESSION['user_id']); // Refresh transaction data
                } else {
                    $data['error'] = 'Failed to update transaction. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('transactions/edit', $data);
    }

    public function delete($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $transactionModel = new Transaction();

        // Check if transaction belongs to user
        if ($transactionModel->belongsToUser($id, $_SESSION['user_id'])) {
            if ($transactionModel->delete($id, $_SESSION['user_id'])) {
                $_SESSION['message'] = 'Transaction deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete transaction. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'Unauthorized: Transaction does not belong to you.';
        }

        header('Location: /transactions');
        exit;
    }
}