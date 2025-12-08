<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Header with Month/Year Filter -->
<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
        <p class="text-sm text-gray-500 mt-1">Overview for <?= $monthName ?> <?= $currentYear ?></p>
    </div>
    <form method="GET" class="flex space-x-3 bg-white p-1 rounded-lg border border-gray-200 shadow-sm">
        <select name="month" class="px-3 py-1.5 bg-transparent border-none text-sm font-medium text-gray-700 focus:ring-0 cursor-pointer hover:text-teal-700" onchange="this.form.submit()">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
            <?php endfor; ?>
        </select>
        <div class="w-px bg-gray-200 my-1"></div>
        <select name="year" class="px-3 py-1.5 bg-transparent border-none text-sm font-medium text-gray-700 focus:ring-0 cursor-pointer hover:text-teal-700" onchange="this.form.submit()">
            <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Income Card -->
    <div class="card-custom p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-emerald-50 rounded-lg">
                <i class="ph-fill ph-arrow-circle-down-left text-emerald-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">Income</span>
        </div>
        <h2 class="text-2xl font-bold tabular-nums text-gray-900 mb-1">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></h2>
        <p class="text-xs text-gray-500">Total received this month</p>
    </div>

    <!-- Total Expenses Card -->
    <div class="card-custom p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-red-50 rounded-lg">
                <i class="ph-fill ph-arrow-circle-up-right text-red-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">Expense</span>
        </div>
        <h2 class="text-2xl font-bold tabular-nums text-gray-900 mb-1">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></h2>
        <p class="text-xs text-gray-500">Total spent this month</p>
    </div>

    <!-- Net Balance Card -->
    <div class="card-custom p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-blue-50 rounded-lg">
                <i class="ph-fill ph-scales text-blue-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Net</span>
        </div>
        <h2 class="text-2xl font-bold tabular-nums text-gray-900 mb-1">Rp <?= number_format($summary['net_balance'] ?? 0, 0, ',', '.') ?></h2>
        <p class="text-xs text-gray-500">Income minus Expenses</p>
    </div>

    <!-- Total Wallets Balance Card -->
    <div class="card-custom p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-teal-50 rounded-lg">
                <i class="ph-fill ph-wallet text-teal-600 text-xl"></i>
            </div>
            <span class="text-xs font-medium text-teal-600 bg-teal-50 px-2 py-1 rounded-full">Total Assets</span>
        </div>
        <h2 class="text-2xl font-bold tabular-nums text-gray-900 mb-1">Rp <?= number_format($totalWalletBalance ?? 0, 0, ',', '.') ?></h2>
        <p class="text-xs text-gray-500">Across all wallets</p>
    </div>
</div>

<!-- Chart Section -->
<div class="card-custom p-6 mb-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900">Income vs Expenses</h3>
        <button class="text-gray-400 hover:text-gray-600">
            <i class="ph ph-dots-three text-xl"></i>
        </button>
    </div>
    <div class="chart-container">
        <canvas id="incomeExpenseChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Transactions -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h5 class="text-base font-bold text-gray-900">Recent Transactions</h5>
            <a href="/transactions" class="text-sm text-teal-600 hover:text-teal-700 font-medium">View All</a>
        </div>
        <div class="p-0">
            <?php if (!empty($recentTransactions)): ?>
                <div class="overflow-x-auto">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentTransactions as $transaction): ?>
                                <tr>
                                    <td class="text-sm text-gray-600"><?= date('M j', strtotime($transaction['date'])) ?></td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full <?= $transaction['category_type'] === 'income' ? 'bg-emerald-500' : 'bg-red-500' ?>"></div>
                                            <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($transaction['category_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-right tabular-nums">
                                        <?php if ($transaction['type'] === 'income'): ?>
                                            <span class="text-emerald-600 font-medium">+<?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?></span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-medium">-<?= number_format($transaction['amount'] ?? 0, ',', '.') ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ph-receipt"></i>
                    </div>
                    <h4 class="empty-state-title">No Transactions Yet</h4>
                    <p class="empty-state-text">Start tracking your finances by adding your first transaction.</p>
                    <a href="/transactions/create" class="btn btn-primary btn-sm mt-4">Add Transaction</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Wallet Balances -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h5 class="text-base font-bold text-gray-900">Wallet Balances</h5>
            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-md"><?= count($walletBalances ?? []) ?> Wallets</span>
        </div>
        <div class="p-0">
            <?php if (!empty($walletBalances)): ?>
                <div class="overflow-x-auto">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Wallet</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($walletBalances as $wallet): ?>
                                <tr>
                                    <td class="font-medium text-gray-700"><?= htmlspecialchars($wallet['wallet_name']) ?></td>
                                    <td class="text-right tabular-nums">
                                        <span class="<?= ($wallet['net_balance'] ?? 0) >= 0 ? 'text-gray-900' : 'text-red-600' ?> font-medium">
                                            Rp <?= number_format($wallet['net_balance'] ?? 0, 0, ',', '.') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ph-wallet"></i>
                    </div>
                    <h4 class="empty-state-title">No Wallets Yet</h4>
                    <p class="empty-state-text">Create your first wallet to start managing your finances.</p>
                    <a href="/wallets/create" class="btn btn-primary btn-sm mt-4">Add Wallet</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Income/Expense by Category -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Expenses by Category -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h5 class="text-base font-bold text-gray-900">Expenses by Category</h5>
        </div>
        <div class="p-0">
            <?php if (!empty($expenseByCategory)): ?>
                <div class="overflow-x-auto">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expenseByCategory as $category): ?>
                                <tr>
                                    <td class="text-gray-700"><?= htmlspecialchars($category['category_name']) ?></td>
                                    <td class="text-right text-red-600 font-medium tabular-nums">-<?= number_format($category['total_amount'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ph-chart-bar"></i>
                    </div>
                    <p class="empty-state-text">No expenses for this period.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Income by Category -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h5 class="text-base font-bold text-gray-900">Income by Category</h5>
        </div>
        <div class="p-0">
            <?php if (!empty($incomeByCategory)): ?>
                <div class="overflow-x-auto">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incomeByCategory as $category): ?>
                                <tr>
                                    <td class="text-gray-700"><?= htmlspecialchars($category['category_name']) ?></td>
                                    <td class="text-right text-emerald-600 font-medium tabular-nums">+<?= number_format($category['total_amount'] ?? 0, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ph-chart-bar"></i>
                    </div>
                    <p class="empty-state-text">No income for this period.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Chart.js configuration for Income vs Expense
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
    
    // Minimalist Chart Config
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6B7280';
    
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Income', 'Expenses', 'Net Balance'],
            datasets: [{
                label: 'Amount (Rp)',
                data: [
                    <?= $summary['total_income'] ?? 0 ?>,
                    <?= $summary['total_expense'] ?? 0 ?>,
                    <?= $summary['net_balance'] ?? 0 ?>
                ],
                backgroundColor: [
                    '#10B981',  // Emerald 500
                    '#EF4444',  // Red 500
                    '#3B82F6'   // Blue 500
                ],
                borderRadius: 4,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1F2937',
                    padding: 12,
                    titleFont: {
                        size: 13,
                        weight: 600
                    },
                    bodyFont: {
                        size: 12
                    },
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) return (value/1000000).toFixed(1) + 'M';
                            if (value >= 1000) return (value/1000).toFixed(0) + 'k';
                            return value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>
