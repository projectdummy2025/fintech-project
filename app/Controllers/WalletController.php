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
        $wallets = $walletModel->getAllByUser($_SESSION['user_id']);

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
                    $data['wallet'] = $walletModel->getByIdAndUser($id, $_SESSION['user_id']); // Refresh wallet data
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
            // Note: Deletion might fail due to foreign key constraints if transactions exist
            if ($walletModel->delete($id, $_SESSION['user_id'])) {
                $_SESSION['message'] = 'Wallet deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete wallet. It may have associated transactions.';
            }
        }

        header('Location: /wallets');
        exit;
    }
}