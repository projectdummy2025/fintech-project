<?php

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/Wallet.php';

class WalletController extends Controller {

    public function index() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $walletModel = new Wallet();
        $wallets = $walletModel->getBalancesByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Wallets',
            'wallets' => $wallets
        ];

        $this->view('wallets/index', $data);
    }

    public function create() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $data = [
            'title' => 'Create Wallet',
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                $data['error'] = 'Wallet name is required.';
            } else {
                $walletModel = new Wallet();

                if ($walletModel->create($_SESSION['user_id'], $name, $description)) {
                    $data['success'] = 'Wallet created successfully!';
                    header('Location: /wallets');
                    exit;
                } else {
                    $data['error'] = 'Failed to create wallet. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('wallets/create', $data);
    }

    public function edit($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $walletModel = new Wallet();

        // Check if wallet belongs to user
        $wallet = $walletModel->getByIdAndUser($id, $_SESSION['user_id']);
        if (!$wallet) {
            header('Location: /wallets');
            exit;
        }

        // Get the wallet balance
        $wallet['balance'] = $walletModel->getBalance($id);

        $data = [
            'title' => 'Edit Wallet',
            'wallet' => $wallet,
            'error' => null,
            'success' => null
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if (empty($name)) {
                $data['error'] = 'Wallet name is required.';
            } else {
                if ($walletModel->update($id, $_SESSION['user_id'], $name, $description)) {
                    $data['success'] = 'Wallet updated successfully!';
                    // Refresh wallet data and balance
                    $data['wallet'] = $walletModel->getByIdAndUser($id, $_SESSION['user_id']);
                    $data['wallet']['balance'] = $walletModel->getBalance($id);
                } else {
                    $data['error'] = 'Failed to update wallet. Please try again.';
                }
            }
        }

        $data['csrf_field'] = Csrf::field();

        $this->view('wallets/edit', $data);
    }

    public function delete($id) {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $walletModel = new Wallet();

        // Check if wallet belongs to user
        if ($walletModel->belongsToUser($id, $_SESSION['user_id'])) {
            // Get all other wallets for the user to transfer transactions to
            $allWallets = $walletModel->getAllByUser($_SESSION['user_id']);

            // Check if wallet has transactions
            $transactionCount = $walletModel->getTransactionCount($id);

            if ($transactionCount > 0) {
                // Wallet has transactions, show transfer form
                $otherWallets = array_filter($allWallets, function($wallet) use ($id) {
                    return $wallet['id'] != $id;
                });

                // If no other wallets exist, we can't transfer
                if (empty($otherWallets)) {
                    $_SESSION['error'] = 'Cannot delete wallet because it has transactions and no other wallets to transfer to. Create another wallet first.';
                    header('Location: /wallets');
                    exit;
                }

                // Get the wallet to delete with its balance
                $walletToDelete = $walletModel->getByIdAndUser($id, $_SESSION['user_id']);
                $walletToDelete['balance'] = $walletModel->getBalance($id);

                $data = [
                    'title' => 'Transfer Transactions and Delete Wallet',
                    'walletToDelete' => $walletToDelete,
                    'otherWallets' => $otherWallets,
                    'transactionCount' => $transactionCount,
                    'error' => null
                ];

                $data['csrf_field'] = Csrf::field();

                $this->view('wallets/transfer_before_delete', $data);
                return;
            } else {
                // No transactions, safe to delete
                if ($walletModel->delete($id, $_SESSION['user_id'])) {
                    $_SESSION['message'] = 'Wallet deleted successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to delete wallet.';
                }
                header('Location: /wallets');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Unauthorized: Wallet does not belong to you.';
            header('Location: /wallets');
            exit;
        }
    }

    public function transferAndDelete() {
        // Auth Middleware: Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Verify CSRF
            if (!Csrf::verify($_POST['csrf_token'] ?? '')) {
                die("CSRF Token Mismatch");
            }

            $walletModel = new Wallet();

            $walletId = $_POST['wallet_id'] ?? null;
            $newWalletId = $_POST['new_wallet_id'] ?? null;

            if ($walletModel->deleteWithTransfer($walletId, $_SESSION['user_id'], $newWalletId)) {
                $_SESSION['message'] = 'Wallet deleted successfully and transactions transferred!';
            } else {
                $_SESSION['error'] = 'Failed to delete wallet and transfer transactions.';
            }
        }

        header('Location: /wallets');
        exit;
    }
}