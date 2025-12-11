<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto" x-data="transactionForm">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="/transactions" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="ph ph-arrow-left text-xl text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-sm text-gray-500">Modify transaction details</p>
        </div>
    </div>

    <!-- Error Alert -->
    <?php if (isset($error)): ?>
        <div class="alert-custom alert-danger mb-6">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            <div>
                <p class="font-medium">Error</p>
                <p class="text-sm"><?= $error ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card-custom p-8">
        <form method="post" class="space-y-6">
            <?= $csrf_field ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Wallet Selection -->
                <div class="form-group">
                    <label for="wallet_id" class="form-label">Wallet <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select class="input-custom appearance-none" id="wallet_id" name="wallet_id" required>
                            <option value="">Select Wallet</option>
                            <?php foreach ($wallets as $wallet): ?>
                                <option value="<?= $wallet['id'] ?>" <?= ($wallet['id'] == ($_POST['wallet_id'] ?? $transaction['wallet_id'] ?? '')) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($wallet['name']) ?> (Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Transaction Type -->
                <div class="form-group">
                    <label for="type" class="form-label">Transaction Type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select class="input-custom appearance-none" 
                                id="type" 
                                name="type" 
                                x-model="type"
                                x-init="type = $el.value"
                                @change="filterCategories"
                                required>
                            <option value="">Select Type</option>
                            <option value="income" <?= ('income' == ($_POST['type'] ?? $transaction['type'] ?? '')) ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= ('expense' == ($_POST['type'] ?? $transaction['type'] ?? '')) ? 'selected' : '' ?>>Expense</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id" class="form-label">Category <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select class="input-custom appearance-none" 
                                id="category_id" 
                                name="category_id" 
                                required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        data-type="<?= $category['type'] ?>"
                                        <?= ($category['id'] == ($_POST['category_id'] ?? $transaction['category_id'] ?? '')) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Amount -->
                <div class="form-group">
                    <label for="amount" class="form-label">Amount <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">Rp</span>
                        </div>
                        <input type="number" 
                               class="input-custom pl-10" 
                               id="amount" 
                               name="amount" 
                               value="<?= htmlspecialchars($_POST['amount'] ?? $transaction['amount'] ?? '') ?>"
                               placeholder="0" 
                               min="0" 
                               step="0.01" 
                               required>
                    </div>
                </div>

                <!-- Date -->
                <div class="form-group">
                    <label for="date" class="form-label">Date <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-calendar text-gray-500"></i>
                        </div>
                        <input type="date" 
                               class="input-custom pl-10" 
                               id="date" 
                               name="date" 
                               value="<?= htmlspecialchars($_POST['date'] ?? $transaction['date'] ?? date('Y-m-d')) ?>"
                               required>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes" class="form-label">Notes <span class="text-xs text-gray-400 font-normal ml-1">(Optional)</span></label>
                <textarea class="input-custom" 
                          id="notes" 
                          name="notes" 
                          rows="3" 
                          placeholder="Add any additional notes..."><?= htmlspecialchars($_POST['notes'] ?? $transaction['notes'] ?? '') ?></textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                <button type="button" 
                        @click="deleteTransaction(<?= $transaction['id'] ?>)"
                        class="btn btn-outline-danger">
                    <i class="ph ph-trash"></i>
                    Delete
                </button>
                
                <div class="flex gap-3">
                    <a href="/transactions" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="btn-save">
                        <i class="ph-bold ph-check-circle"></i>
                        Update Transaction
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>