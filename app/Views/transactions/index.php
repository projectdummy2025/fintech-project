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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= $title ?></h1>
            <a href="/transactions/create" class="btn btn-primary">Add Transaction</a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label for="month" class="form-label">Month</label>
                        <select name="month" id="month" class="form-control">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Year</label>
                        <select name="year" id="year" class="form-control">
                            <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                                <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="wallet_id" class="form-label">Wallet</label>
                        <select name="wallet_id" id="wallet_id" class="form-control">
                            <option value="">All Wallets</option>
                            <?php foreach ($wallets as $wallet): ?>
                                <option value="<?= $wallet['id'] ?>" <?= $wallet['id'] == ($filters['wallet_id'] ?? null) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($wallet['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $category['id'] == ($filters['category_id'] ?? null) ? 'selected' : '' ?>>
                                    [<?= ucfirst($category['type']) ?>] <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="income" <?= ($filters['type'] ?? null) === 'income' ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= ($filters['type'] ?? null) === 'expense' ? 'selected' : '' ?>>Expense</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search...">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="/transactions" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Wallet</th>
                            <th>Notes</th>
                            <th class="text-end">Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($transaction['date'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $transaction['category_type'] === 'income' ? 'success' : 'danger' ?>">
                                        <?= htmlspecialchars($transaction['category_name']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($transaction['wallet_name']) ?></td>
                                <td>
                                    <?php if (!empty($transaction['notes'])): ?>
                                        <?= htmlspecialchars(substr($transaction['notes'], 0, 50)) ?>
                                        <?php if (strlen($transaction['notes']) > 50): ?>...<?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ($transaction['type'] === 'income'): ?>
                                        <span class="text-success">+<?= number_format($transaction['amount'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">-<?= number_format($transaction['amount'], 2) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/transactions/edit/<?= $transaction['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="/transactions/delete/<?= $transaction['id'] ?>" class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No transactions found for the selected filters. <a href="/transactions/create">Add your first transaction</a>.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>