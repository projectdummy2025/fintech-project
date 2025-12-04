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
            <h5>Warning: This category has <?= $transactionCount ?> transaction(s)</h5>
            <p>You cannot delete this category because it has transactions. Please transfer the transactions to another category first.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    Category to Delete: <?= htmlspecialchars($categoryToDelete['name']) ?> 
                    <span class="badge bg-<?= $categoryToDelete['type'] === 'income' ? 'success' : 'danger' ?>">
                        <?= ucfirst($categoryToDelete['type']) ?>
                    </span>
                </h5>
                
                <form method="post" action="/categories/transfer-and-delete">
                    <input type="hidden" name="category_id" value="<?= $categoryToDelete['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="new_category_id" class="form-label">Transfer transactions to:</label>
                        <select class="form-control" id="new_category_id" name="new_category_id" required>
                            <option value="">Select a category</option>
                            <?php foreach ($otherCategories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="alert alert-info">
                        <p><strong>Note:</strong> All transactions from "<?= htmlspecialchars($categoryToDelete['name']) ?>" will be moved to the selected category, then "<?= htmlspecialchars($categoryToDelete['name']) ?>" will be deleted.</p>
                    </div>
                    
                    <button type="submit" class="btn btn-danger">Transfer and Delete Category</button>
                    <a href="/categories" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>