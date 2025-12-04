<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance Webapp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Personal Finance</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/dashboard">Dashboard</a>
                <a class="nav-link" href="/wallets">Wallets</a>
                <a class="nav-link" href="/categories">Categories</a>
                <a class="nav-link" href="/transactions">Transactions</a>
                <a class="nav-link" href="/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1><?= $title ?></h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-warning">
            <h5>Warning: This wallet has <?= $transactionCount ?> transaction(s)</h5>
            <p>You cannot delete this wallet because it has transactions. Please transfer the transactions to another wallet first.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Wallet to Delete: <?= htmlspecialchars($walletToDelete['name']) ?></h5>
                <p class="card-text">
                    <strong>Description:</strong> 
                    <?php if (!empty($walletToDelete['description'])): ?>
                        <?= htmlspecialchars($walletToDelete['description']) ?>
                    <?php else: ?>
                        No description
                    <?php endif; ?>
                </p>
                
                <form method="post" action="/wallets/transfer-and-delete">
                    <input type="hidden" name="wallet_id" value="<?= $walletToDelete['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="new_wallet_id" class="form-label">Transfer transactions to:</label>
                        <select class="form-control" id="new_wallet_id" name="new_wallet_id" required>
                            <option value="">Select a wallet</option>
                            <?php foreach ($otherWallets as $wallet): ?>
                                <option value="<?= $wallet['id'] ?>"><?= htmlspecialchars($wallet['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <p><strong>Note:</strong> All transactions from "<?= htmlspecialchars($walletToDelete['name']) ?>" will be moved to the selected wallet, then "<?= htmlspecialchars($walletToDelete['name']) ?>" will be deleted.</p>
                    </div>
                    
                    <button type="submit" class="btn btn-danger">Transfer and Delete Wallet</button>
                    <a href="/wallets" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>