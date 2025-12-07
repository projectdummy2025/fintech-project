<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
    <a href="/transactions/create" class="btn btn-primary">
        <i class="ph-bold ph-plus"></i>
        Add Transaction
    </a>
</div>

<!-- Filters Card -->
<div class="card-custom p-6 mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div>
            <label for="month" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Month</label>
            <select name="month" id="month" class="input-custom">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m == $currentMonth ? 'selected' : '' ?>><?= date('M', mktime(0, 0, 0, $m, 10)) ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label for="year" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Year</label>
            <select name="year" id="year" class="input-custom">
                <?php for ($y = 2020; $y <= date('Y') + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $currentYear ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label for="wallet_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Wallet</label>
            <select name="wallet_id" id="wallet_id" class="input-custom">
                <option value="">All Wallets</option>
                <?php foreach ($wallets as $wallet): ?>
                    <option value="<?= $wallet['id'] ?>" <?= $wallet['id'] == ($filters['wallet_id'] ?? null) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($wallet['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="category_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Category</label>
            <select name="category_id" id="category_id" class="input-custom">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == ($filters['category_id'] ?? null) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="type" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Type</label>
            <select name="type" id="type" class="input-custom">
                <option value="">All Types</option>
                <option value="income" <?= ($filters['type'] ?? null) === 'income' ? 'selected' : '' ?>>Income</option>
                <option value="expense" <?= ($filters['type'] ?? null) === 'expense' ? 'selected' : '' ?>>Expense</option>
            </select>
        </div>
        <div>
            <label for="search" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Search</label>
            <input type="text" name="search" id="search" class="input-custom" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search...">
        </div>
        <div class="md:col-span-3 lg:col-span-6 flex gap-3 mt-2">
            <button type="submit" class="btn btn-primary">Apply Filters</button>
            <a href="/transactions" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert-custom alert-success mb-6">
        <i class="ph-fill ph-check-circle text-xl"></i>
        <p class="font-medium text-sm"><?= $_SESSION['message'] ?></p>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert-custom alert-danger mb-6">
        <i class="ph-fill ph-warning-circle text-xl"></i>
        <p class="font-medium text-sm"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<!-- Transactions Table -->
<?php if (!empty($transactions)): ?>
    <div class="card-custom overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Wallet</th>
                        <th>Notes</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td class="text-sm text-gray-600 whitespace-nowrap"><?= date('M j, Y', strtotime($transaction['date'])) ?></td>
                            <td>
                                <span class="badge <?= $transaction['category_type'] === 'income' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= htmlspecialchars($transaction['category_name']) ?>
                                </span>
                            </td>
                            <td class="text-sm text-gray-700"><?= htmlspecialchars($transaction['wallet_name']) ?></td>
                            <td class="text-sm text-gray-500 max-w-xs truncate">
                                <?php if (!empty($transaction['notes'])): ?>
                                    <?= htmlspecialchars($transaction['notes']) ?>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right tabular-nums whitespace-nowrap">
                                <?php if ($transaction['type'] === 'income'): ?>
                                    <span class="text-emerald-600 font-medium">+<?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-red-600 font-medium">-<?= number_format($transaction['amount'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/transactions/edit/<?= $transaction['id'] ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </a>
                                    <a href="/transactions/delete/<?= $transaction['id'] ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition" onclick="return confirm('Are you sure you want to delete this transaction?')" title="Delete">
                                        <i class="ph ph-trash text-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="card-custom p-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
            <i class="ph ph-magnifying-glass text-gray-400 text-2xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">No transactions found</h3>
        <p class="text-gray-500 text-sm mb-4">Try adjusting your filters or add a new transaction.</p>
        <a href="/transactions/create" class="btn btn-primary">Add Transaction</a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>