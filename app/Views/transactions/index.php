<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-600 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-white text-2xl font-bold">💰 Personal Finance</a>
                <div class="flex space-x-6">
                    <a href="/dashboard" class="text-teal-100 hover:text-white transition">Dashboard</a>
                    <a href="/wallets" class="text-teal-100 hover:text-white transition">Wallets</a>
                    <a href="/categories" class="text-teal-100 hover:text-white transition">Categories</a>
                    <a href="/transactions" class="text-white font-semibold border-b-2 border-white pb-1">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <a href="/transactions/create" class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md">+ Add Transaction</a>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                    <select name="month" id="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                    <select name="year" id="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                            <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label for="wallet_id" class="block text-sm font-medium text-gray-700 mb-2">Wallet</label>
                    <select name="wallet_id" id="wallet_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Wallets</option>
                        <?php foreach ($wallets as $wallet): ?>
                            <option value="<?= $wallet['id'] ?>" <?= $wallet['id'] == ($filters['wallet_id'] ?? null) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($wallet['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == ($filters['category_id'] ?? null) ? 'selected' : '' ?>>
                                [<?= ucfirst($category['type']) ?>] <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="">All Types</option>
                        <option value="income" <?= ($filters['type'] ?? null) === 'income' ? 'selected' : '' ?>>Income</option>
                        <option value="expense" <?= ($filters['type'] ?? null) === 'expense' ? 'selected' : '' ?>>Expense</option>
                    </select>
                </div>
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search...">
                </div>
                <div class="md:col-span-3 lg:col-span-6 flex gap-3">
                    <button type="submit" class="px-6 py-2 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition">Apply Filters</button>
                    <a href="/transactions" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition">Clear Filters</a>
                </div>
            </form>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-custom alert-success mb-6">
                <p class="font-medium"><?= $_SESSION['message'] ?></p>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-custom alert-danger mb-6">
                <p class="font-medium"><?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Transactions Table -->
        <?php if (!empty($transactions)): ?>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Wallet</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Notes</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= date('M j, Y', strtotime($transaction['date'])) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="<?= $transaction['category_type'] === 'income' ? 'badge-success' : 'badge-danger' ?>">
                                            <?= htmlspecialchars($transaction['category_name']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($transaction['wallet_name']) ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php if (!empty($transaction['notes'])): ?>
                                            <?= htmlspecialchars(substr($transaction['notes'], 0, 50)) ?>
                                            <?php if (strlen($transaction['notes']) > 50): ?>...<?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right tabular-nums">
                                        <?php if ($transaction['type'] === 'income'): ?>
                                            <span class="text-green-600 font-semibold">+<?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-semibold">-<?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="/transactions/edit/<?= $transaction['id'] ?>" class="inline-block px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">Edit</a>
                                        <a href="/transactions/delete/<?= $transaction['id'] ?>" class="inline-block px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-6 rounded-lg">
                <p>No transactions found for the selected filters. <a href="/transactions/create" class="font-semibold underline">Add your first transaction</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>