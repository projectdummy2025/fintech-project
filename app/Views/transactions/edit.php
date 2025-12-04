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

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <?= $csrf_field ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="wallet_id" class="form-label">Wallet</label>
                        <select class="form-control" id="wallet_id" name="wallet_id" required>
                            <option value="">Select Wallet</option>
                            <?php foreach ($wallets as $wallet): ?>
                                <option value="<?= $wallet['id'] ?>" <?= ($wallet['id'] == ($_POST['wallet_id'] ?? $transaction['wallet_id'] ?? '')) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($wallet['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">Transaction Type</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="income" <?= ($_POST['type'] ?? $transaction['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= ($_POST['type'] ?? $transaction['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                    data-type="<?= $category['type'] ?>"
                                    <?= ($category['id'] == ($_POST['category_id'] ?? $transaction['category_id'] ?? '')) ? 'selected' : '' ?>>
                                    [<?= ucfirst($category['type']) ?>] <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                               value="<?= htmlspecialchars($_POST['amount'] ?? $transaction['amount'] ?? '') ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" 
                       value="<?= htmlspecialchars($_POST['date'] ?? $transaction['date'] ?? date('Y-m-d')) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($_POST['notes'] ?? $transaction['notes'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Transaction</button>
            <a href="/transactions" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update category options based on transaction type
        document.getElementById('type').addEventListener('change', function() {
            const selectedType = this.value;
            const categorySelect = document.getElementById('category_id');
            const options = categorySelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') {
                    return; // Skip the "Select Category" option
                }
                
                const optionType = option.getAttribute('data-type');
                if (selectedType && optionType !== selectedType) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        });
        
        // Trigger change event on page load to filter categories based on selected type
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
</body>
</html>