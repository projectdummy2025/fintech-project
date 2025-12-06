<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance</title>
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
            <h1><?= $title ?> - <?= $monthName ?> <?= $currentYear ?></h1>
            <form method="GET" class="d-flex">
                <select name="month" class="form-select me-2" onchange="this.form.submit()">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Income</h5>
                        <h3 class="card-text">Rp <?= number_format($summary['total_income'] ?? 0, 2) ?></h3>
                        <small class="text-light">Income for <?= $monthName ?> <?= $currentYear ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Total Expenses</h5>
                        <h3 class="card-text">Rp <?= number_format($summary['total_expense'] ?? 0, 2) ?></h3>
                        <small class="text-light">Expenses for <?= $monthName ?> <?= $currentYear ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white <?= ($summary['net_balance'] ?? 0) >= 0 ? 'bg-primary' : 'bg-warning text-dark' ?>">
                    <div class="card-body">
                        <h5 class="card-title">Net Balance</h5>
                        <h3 class="card-text">Rp <?= number_format($summary['net_balance'] ?? 0, 2) ?></h3>
                        <small class="text-light">Income minus expenses for <?= $monthName ?> <?= $currentYear ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Wallets Balance</h5>
                        <h3 class="card-text">Rp <?= number_format($totalWalletBalance ?? 0, 2) ?></h3>
                        <?php if (isset($balanceChange)): ?>
                            <small class="text-light">
                                <?php if (($balanceChange ?? 0) >= 0): ?>
                                    <span class="text-success">↑ +Rp <?= number_format($balanceChange ?? 0, 2) ?> (<?= number_format($balanceChangePercent ?? 0, 1) ?>%)</span>
                                <?php else: ?>
                                    <span class="text-danger">↓ Rp <?= number_format($balanceChange ?? 0, 2) ?> (<?= number_format($balanceChangePercent ?? 0, 1) ?>%)</span>
                                <?php endif; ?>
                            </small>
                        <?php endif; ?>
                        <small class="text-light d-block">Cumulative all wallets</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Transactions -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Transactions</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentTransactions)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentTransactions as $transaction): ?>
                                            <tr>
                                                <td><?= date('M j', strtotime($transaction['date'])) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $transaction['category_type'] === 'income' ? 'success' : 'danger' ?>">
                                                        <?= htmlspecialchars($transaction['category_name']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <?php if ($transaction['type'] === 'income'): ?>
                                                        <span class="text-success">+<?= number_format($transaction['amount'] ?? 0, 2) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-danger">-<?= number_format($transaction['amount'] ?? 0, 2) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <nav>
                                    <ul class="pagination pagination-sm justify-content-end">
                                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $currentPage - 1 ?>&month=<?= $currentMonth ?>&year=<?= $currentYear ?>">Previous</a>
                                        </li>
                                        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?page=<?= $currentPage + 1 ?>&month=<?= $currentMonth ?>&year=<?= $currentYear ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>

                            <a href="/transactions" class="btn btn-sm btn-outline-primary">View All Transactions</a>
                        <?php else: ?>
                            <p class="text-muted">No transactions found for this period. <a href="/transactions/create">Add your first transaction</a>.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Wallet Balances -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Wallet Balances</h5>
                        <span class="badge bg-primary">Total: <?= count($walletBalances ?? []) ?> wallets</span>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($walletBalances)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Wallet</th>
                                            <th class="text-end">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($walletBalances as $wallet): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($wallet['wallet_name']) ?></td>
                                                <td class="text-end">
                                                    <span class="<?= ($wallet['net_balance'] ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                                                        Rp <?= number_format($wallet['net_balance'] ?? 0, 2) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-light fw-bold">
                                            <td>TOTAL</td>
                                            <td class="text-end">Rp <?= number_format($totalWalletBalance ?? 0, 2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No wallets or transactions found. <a href="/wallets/create">Add your first wallet</a>.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Expense by Category -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Expenses by Category</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($expenseByCategory)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($expenseByCategory as $category): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                                <td class="text-end text-danger">-<?= number_format($category['total_amount'] ?? 0, 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No expenses found for this period.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Income by Category -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Income by Category</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($incomeByCategory)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($incomeByCategory as $category): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($category['category_name']) ?></td>
                                                <td class="text-end text-success">+<?= number_format($category['total_amount'] ?? 0, 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No income found for this period.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
