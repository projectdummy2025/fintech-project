<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div id="transactions-page" x-data="{ showFilters: false, activeFiltersCount: <?= count(array_filter($filters ?? [])) ?> }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-sm text-gray-500 mt-1">Manage your income and expenses</p>
        </div>
        <div class="flex items-center gap-3">
            <button @click="showFilters = !showFilters" 
                    class="btn btn-secondary relative"
                    :class="{ 'ring-2 ring-teal-500 ring-offset-2': showFilters }">
                <i class="ph ph-funnel"></i>
                <span>Filters</span>
                <span x-show="activeFiltersCount > 0" 
                      class="absolute -top-2 -right-2 w-5 h-5 bg-teal-600 text-white text-xs rounded-full flex items-center justify-center font-medium"
                      x-text="activeFiltersCount"></span>
            </button>
            <a href="/export/transactions?<?= http_build_query($_GET) ?>" class="btn btn-ghost">
                <i class="ph ph-download-simple"></i>
                Export CSV
            </a>
            <a href="/transactions/create" class="btn btn-primary">
                <i class="ph-bold ph-plus"></i>
                Add Transaction
            </a>
        </div>
    </div>

    <!-- Collapsible Filters Card -->
    <div x-show="showFilters" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="card-custom p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-funnel text-teal-600"></i>
                <h3 class="font-semibold text-gray-900">Filter Transactions</h3>
            </div>
            <button @click="showFilters = false" class="p-1 hover:bg-gray-100 rounded-lg transition">
                <i class="ph ph-x text-gray-400"></i>
            </button>
        </div>
        <form method="GET" id="filter-form" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="input-custom" value="<?= htmlspecialchars($filters['start_date']) ?>">
            </div>
            <div>
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="input-custom" value="<?= htmlspecialchars($filters['end_date']) ?>">
            </div>
            <div>
                <label for="wallet_id" class="form-label">Wallet</label>
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
                <label for="category_id" class="form-label">Category</label>
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
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="input-custom">
                    <option value="">All Types</option>
                    <option value="income" <?= ($filters['type'] ?? null) === 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= ($filters['type'] ?? null) === 'expense' ? 'selected' : '' ?>>Expense</option>
                </select>
            </div>
            <div>
                <label for="search" class="form-label">Search</label>
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" id="search" class="input-custom pl-10" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search notes...">
                </div>
            </div>
            <div class="md:col-span-3 lg:col-span-6 flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ph ph-funnel"></i>
                    Apply Filters
                </button>
                <button type="button" id="reset-filters" class="btn btn-secondary">
                    <i class="ph ph-arrow-counter-clockwise"></i>
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Active Filters Summary -->
    <div id="active-filters-container">
        <?php 
        $activeFilters = array_filter($filters ?? []);
        if (!empty($activeFilters) && count($activeFilters) > 0): 
        ?>
            <div class="flex items-center gap-2 mb-6 flex-wrap">
                <span class="text-sm text-gray-500">Active filters:</span>
                <?php if (!empty($filters['start_date']) && !empty($filters['end_date'])): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">
                        <i class="ph ph-calendar"></i>
                        <?= date('d M Y', strtotime($filters['start_date'])) ?> - <?= date('d M Y', strtotime($filters['end_date'])) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filters['wallet_id'])): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">
                        <i class="ph ph-wallet"></i>
                        Wallet
                    </span>
                <?php endif; ?>
                <?php if (!empty($filters['category_id'])): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">
                        <i class="ph ph-tag"></i>
                        Category
                    </span>
                <?php endif; ?>
                <?php if (!empty($filters['type'])): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">
                        <i class="ph ph-arrows-left-right"></i>
                        <?= ucfirst($filters['type']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($filters['search'])): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">
                        <i class="ph ph-magnifying-glass"></i>
                        "<?= htmlspecialchars($filters['search']) ?>"
                    </span>
                <?php endif; ?>
                <button type="button" id="clear-all-filters" class="text-sm text-gray-400 hover:text-gray-600 underline">Clear all</button>
            </div>
        <?php endif; ?>
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

    <!-- Transactions Container -->
    <div id="transactions-container">
        <?php if (!empty($transactions)): ?>
            <div class="card-custom overflow-hidden">
                <!-- Table Header with Summary -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-teal-100 rounded-lg">
                            <i class="ph-fill ph-list-bullets text-teal-600"></i>
                        </div>
                        <div>
                            <h5 class="text-base font-bold text-gray-900" id="transaction-count"><?= count($transactions) ?> Transactions</h5>
                            <p class="text-xs text-gray-500">
                                <?= date('d M Y', strtotime($filters['start_date'])) ?> - <?= date('d M Y', strtotime($filters['end_date'])) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Wallet</th>
                                <th>Notes</th>
                                <th class="text-right">Amount</th>
                                <th class="text-center w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="hover:bg-gray-50/50 transition-colors group" data-id="<?= $transaction['id'] ?>">
                                    <td class="whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg text-xs font-semibold text-gray-600">
                                                <?= date('d', strtotime($transaction['date'])) ?>
                                            </div>
                                            <span class="text-sm text-gray-500"><?= date('M Y', strtotime($transaction['date'])) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium <?= $transaction['category_type'] === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' ?>">
                                            <i class="ph-fill <?= $transaction['category_type'] === 'income' ? 'ph-arrow-circle-down' : 'ph-arrow-circle-up' ?>"></i>
                                            <?= htmlspecialchars($transaction['category_name']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-credit-card text-gray-400"></i>
                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($transaction['wallet_name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="max-w-xs">
                                        <?php if (!empty($transaction['notes'])): ?>
                                            <span class="text-sm text-gray-500 truncate block" title="<?= htmlspecialchars($transaction['notes']) ?>">
                                                <?= htmlspecialchars($transaction['notes']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-sm text-gray-300 italic">No notes</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right whitespace-nowrap">
                                        <?php if ($transaction['type'] === 'income'): ?>
                                            <span class="text-emerald-600 font-semibold tabular-nums">
                                                +Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-red-600 font-semibold tabular-nums">
                                                -Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="/transactions/edit/<?= $transaction['id'] ?>" 
                                               class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                               title="Edit">
                                                <i class="ph ph-pencil-simple"></i>
                                            </a>
                                            <button onclick="deleteTransaction(<?= $transaction['id'] ?>)" 
                                               class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" 
                                               title="Delete">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer with Totals -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex flex-wrap gap-6 justify-end">
                        <?php 
                        $totalIncome = 0;
                        $totalExpense = 0;
                        foreach ($transactions as $t) {
                            if ($t['type'] === 'income') $totalIncome += $t['amount'];
                            else $totalExpense += $t['amount'];
                        }
                        ?>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Total Income</p>
                            <p class="text-lg font-bold text-emerald-600 tabular-nums" id="total-income">+Rp <?= number_format($totalIncome, 0, ',', '.') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Total Expense</p>
                            <p class="text-lg font-bold text-red-600 tabular-nums" id="total-expense">-Rp <?= number_format($totalExpense, 0, ',', '.') ?></p>
                        </div>
                        <div class="text-right border-l border-gray-200 pl-6">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Net</p>
                            <p class="text-lg font-bold <?= ($totalIncome - $totalExpense) >= 0 ? 'text-gray-900' : 'text-red-600' ?> tabular-nums" id="total-net">
                                <?= ($totalIncome - $totalExpense) >= 0 ? '+' : '' ?>Rp <?= number_format($totalIncome - $totalExpense, 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card-custom">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ph-magnifying-glass"></i>
                    </div>
                    <h4 class="empty-state-title">No transactions found</h4>
                    <p class="empty-state-text">Try adjusting your filters or add a new transaction.</p>
                    <a href="/transactions/create" class="btn btn-primary mt-4">
                        <i class="ph-bold ph-plus"></i>
                        Add Transaction
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Delete transaction via API
    async function deleteTransaction(id) {
        const confirmed = await Swal.fire({
            title: 'Delete Transaction?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        });

        if (confirmed.isConfirmed) {
            try {
                const response = await fetch(`/api/transactions/delete/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Transaction deleted successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    // Refresh the page to show updated data
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to delete');
                }
            } catch (error) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: error.message || 'Failed to delete transaction',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    }
</script>

<?= $this->endSection() ?>
