<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-600 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-white text-2xl font-bold">💰 Personal Finance</a>
                <div class="flex space-x-6">
                    <a href="/dashboard" class="text-white font-semibold border-b-2 border-white pb-1">Dashboard</a>
                    <a href="/wallets" class="text-teal-100 hover:text-white transition">Wallets</a>
                    <a href="/categories" class="text-teal-100 hover:text-white transition">Categories</a>
                    <a href="/transactions" class="text-teal-100 hover:text-white transition">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <!-- Header with Month/Year Filter -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?> - <?= $monthName ?> <?= $currentYear ?></h1>
            <form method="GET" class="flex space-x-3">
                <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" onchange="this.form.submit()">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" onchange="this.form.submit()">
                    <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Income Card -->
            <div class="gradient-success rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-sm font-medium opacity-90">Total Income</h5>
                    <span class="text-3xl">💵</span>
                </div>
                <h2 class="text-4xl font-bold tabular-nums mb-1">Rp <?= number_format($summary['total_income'] ?? 0, 0, ',', '.') ?></h2>
                <p class="text-xs opacity-75">Income for <?= $monthName ?> <?= $currentYear ?></p>
            </div>

            <!-- Total Expenses Card -->
            <div class="gradient-danger rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-sm font-medium opacity-90">Total Expenses</h5>
                    <span class="text-3xl">💸</span>
                </div>
                <h2 class="text-4xl font-bold tabular-nums mb-1">Rp <?= number_format($summary['total_expense'] ?? 0, 0, ',', '.') ?></h2>
                <p class="text-xs opacity-75">Expenses for <?= $monthName ?> <?= $currentYear ?></p>
            </div>

            <!-- Net Balance Card -->
            <div class="<?= ($summary['net_balance'] ?? 0) >= 0 ? 'gradient-info' : 'bg-gradient-to-br from-amber-500 to-orange-500' ?> rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-sm font-medium opacity-90">Net Balance</h5>
                    <span class="text-3xl">💼</span>
                </div>
                <h2 class="text-4xl font-bold tabular-nums mb-1">Rp <?= number_format($summary['net_balance'] ?? 0, 0, ',', '.') ?></h2>
                <p class="text-xs opacity-75">Income - Expenses</p>
            </div>

            <!-- Total Wallets Balance Card -->
            <div class="gradient-primary rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition duration-200">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="text-sm font-medium opacity-90">Total Wallets</h5>
                    <span class="text-3xl">👛</span>
                </div>
                <h2 class="text-4xl font-bold tabular-nums mb-1">Rp <?= number_format($totalWalletBalance ?? 0, 0, ',', '.') ?></h2>
                <p class="text-xs opacity-75">Cumulative Balance</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Income vs Expenses</h3>
            <div class="chart-container">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Transactions -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-bold text-gray-800">Recent Transactions</h5>
                </div>
                <div class="p-6">
                    <?php if (!empty($recentTransactions)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($recentTransactions as $transaction): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm text-gray-700"><?= date('M j', strtotime($transaction['date'])) ?></td>
                                            <td class="px-4 py-3">
                                                <span class="<?= $transaction['category_type'] === 'income' ? 'badge-success' : 'badge-danger' ?>">
                                                    <?= htmlspecialchars($transaction['category_name']) ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right tabular-nums">
                                                <?php if ($transaction['type'] === 'income'): ?>
                                                    <span class="text-green-600 font-semibold">+<?= number_format($transaction['amount'] ?? 0, 0, ',', '.') ?></span>
                                                <?php else: ?>
                                                    <span class="text-red-600 font-semibold">-<?= number_format($transaction['amount'] ?? 0, ',', '.') ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <a href="/transactions" class="inline-block px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition">View All Transactions →</a>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">No transactions found. <a href="/transactions/create" class="text-teal-600 hover:underline">Add your first transaction</a>.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Wallet Balances -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-bold text-gray-800">Wallet Balances</h5>
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 text-xs font-semibold rounded-full">Total: <?= count($walletBalances ?? []) ?> wallets</span>
                </div>
                <div class="p-6">
                    <?php if (!empty($walletBalances)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Wallet</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($walletBalances as $wallet): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($wallet['wallet_name']) ?></td>
                                            <td class="px-4 py-3 text-right tabular-nums">
                                                <span class="<?= ($wallet['net_balance'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' ?> font-semibold">
                                                    Rp <?= number_format($wallet['net_balance'] ?? 0, 0, ',', '.') ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="bg-gray-100 font-bold">
                                        <td class="px-4 py-3 text-sm">TOTAL</td>
                                        <td class="px-4 py-3 text-right tabular-nums">Rp <?= number_format($totalWalletBalance ?? 0, 0, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">No wallets found. <a href="/wallets/create" class="text-teal-600 hover:underline">Add your first wallet</a>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Income/Expense by Category -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Expenses by Category -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-bold text-gray-800">Expenses by Category</h5>
                </div>
                <div class="p-6">
                    <?php if (!empty($expenseByCategory)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($expenseByCategory as $category): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($category['category_name']) ?></td>
                                            <td class="px-4 py-3 text-right text-red-600 font-semibold tabular-nums">-<?= number_format($category['total_amount'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">No expenses for this period.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Income by Category -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-bold text-gray-800">Income by Category</h5>
                </div>
                <div class="p-6">
                    <?php if (!empty($incomeByCategory)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($incomeByCategory as $category): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-sm text-gray-700"><?= htmlspecialchars($category['category_name']) ?></td>
                                            <td class="px-4 py-3 text-right text-green-600 font-semibold tabular-nums">+<?= number_format($category['total_amount'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-8">No income for this period.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart.js configuration for Income vs Expense
        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
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
                        'rgba(16, 185, 129, 0.8)',  // Green for income
                        'rgba(239, 68, 68, 0.8)',   // Red for expenses
                        'rgba(59, 130, 246, 0.8)'   // Blue for net balance
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(59, 130, 246, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
