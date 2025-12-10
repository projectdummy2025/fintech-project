<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Transaction.php';

class ExportController extends Controller {

    public function transactions() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $transactionModel = new Transaction();
        
        // Get filters from query parameters (same as TransactionController index)
        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? date('m');
        $categoryId = $_GET['category_id'] ?? null;
        $walletId = $_GET['wallet_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $search = $_GET['search'] ?? null;
        
        // Validation for year and month
        if (!is_numeric($year) || !is_numeric($month) || $year < 1970 || $year > 2100 || $month < 1 || $month > 12) {
            $year = date('Y');
            $month = date('m');
        }

        // Prepare filters array
        $filters = [
            'month' => $month,
            'year' => $year
        ];
        
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

        // Get all transactions with filters (no pagination for export)
        $transactions = $transactionModel->getAllByUser($_SESSION['user_id'], $filters);

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="transactions_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add BOM for UTF-8 (helps with Excel)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV Headers
        fputcsv($output, ['Date', 'Type', 'Category', 'Wallet', 'Amount', 'Notes']);

        // Add transaction data
        foreach ($transactions as $transaction) {
            fputcsv($output, [
                $transaction['date'],
                ucfirst($transaction['type']),
                $transaction['category_name'] ?? 'Uncategorized',
                $transaction['wallet_name'] ?? 'Unknown',
                number_format($transaction['amount'], 2, '.', ''),
                $transaction['notes'] ?? ''
            ]);
        }

        fclose($output);
        exit;
    }
}
