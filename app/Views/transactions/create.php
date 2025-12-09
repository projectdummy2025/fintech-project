<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto" x-data="transactionForm()">
    <div class="flex items-center gap-4 mb-8">
        <a href="/transactions" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="ph ph-arrow-left text-xl text-gray-600"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-sm text-gray-500">Add a new income or expense record</p>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert-custom alert-danger mb-6">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            <div>
                <p class="font-medium">Error</p>
                <p class="text-sm"><?= $error ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert-custom alert-success mb-6">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <div>
                <p class="font-medium">Success</p>
                <p class="text-sm"><?= $success ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card-custom overflow-hidden">
        <div class="px-8 py-4 bg-gray-50/50 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-teal-100 rounded-lg">
                    <i class="ph-fill ph-plus-circle text-teal-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Transaction Details</h3>
            </div>
        </div>
        
        <form method="post" class="p-8" @submit="handleSubmit">
            <?= $csrf_field ?>

            <!-- 2-Column Grid for Desktop -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Wallet -->
                <div class="form-group">
                    <label for="wallet_id" class="form-label">Wallet <span class="text-red-500">*</span></label>
                    <select class="input-custom" id="wallet_id" name="wallet_id" required x-model="formData.wallet_id">
                        <option value="">Select Wallet</option>
                        <?php foreach ($wallets as $wallet): ?>
                            <option value="<?= $wallet['id'] ?>" <?= ($wallet['id'] == ($_POST['wallet_id'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($wallet['name']) ?> (Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Choose which wallet to affect</p>
                </div>

                <!-- Transaction Type -->
                <div class="form-group">
                    <label for="type" class="form-label">Transaction Type <span class="text-red-500">*</span></label>
                    <select class="input-custom" id="type" name="type" required x-model="formData.type" @change="filterCategories()">
                        <option value="">Select Type</option>
                        <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>
                            💰 Income
                        </option>
                        <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>
                            💸 Expense
                        </option>
                    </select>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label for="category_id" class="form-label">Category <span class="text-red-500">*</span></label>
                    <select class="input-custom" id="category_id" name="category_id" required x-model="formData.category_id">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                data-type="<?= $category['type'] ?>"
                                <?= ($category['id'] == ($_POST['category_id'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-400 mt-1" x-show="!formData.type">Select type first to see matching categories</p>
                </div>

                <!-- Amount with Prefix -->
                <div class="form-group">
                    <label for="amount" class="form-label">Amount <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold text-sm bg-gray-100 px-2 py-0.5 rounded">Rp</span>
                        <input type="number" step="1" min="0"
                               class="input-custom pl-16 tabular-nums text-lg font-semibold" 
                               id="amount" name="amount"
                               x-model="formData.amount"
                               value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" 
                               required placeholder="0">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Enter amount without decimals</p>
                </div>
            </div>

            <!-- Date -->
            <div class="form-group mb-6">
                <label for="date" class="form-label">Date <span class="text-red-500">*</span></label>
                <div class="relative max-w-xs">
                    <i class="ph ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="date" class="input-custom pl-12" id="date" name="date"
                           x-model="formData.date"
                           value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>" required>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group mb-6">
                <label for="notes" class="form-label">Notes <span class="text-gray-400 font-normal">(Optional)</span></label>
                <textarea class="input-custom" id="notes" name="notes" rows="3" 
                          x-model="formData.notes"
                          placeholder="Add any additional notes about this transaction..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
            </div>

            <!-- Summary Preview -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6" x-show="formData.amount > 0 && formData.type">
                <h4 class="text-sm font-medium text-gray-500 mb-2">Transaction Summary</h4>
                <p class="text-lg font-bold" :class="formData.type === 'income' ? 'text-emerald-600' : 'text-red-600'">
                    <span x-text="formData.type === 'income' ? '+' : '-'"></span>Rp 
                    <span x-text="Number(formData.amount).toLocaleString('id-ID')"></span>
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="isSubmitting"
                        :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
                    <template x-if="!isSubmitting">
                        <span class="flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle"></i>
                            Add Transaction
                        </span>
                    </template>
                    <template x-if="isSubmitting">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </template>
                </button>
                <a href="/transactions" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function transactionForm() {
        return {
            formData: {
                wallet_id: '<?= $_POST['wallet_id'] ?? '' ?>',
                type: '<?= $_POST['type'] ?? '' ?>',
                category_id: '<?= $_POST['category_id'] ?? '' ?>',
                amount: '<?= $_POST['amount'] ?? '' ?>',
                date: '<?= $_POST['date'] ?? date('Y-m-d') ?>',
                notes: '<?= addslashes($_POST['notes'] ?? '') ?>'
            },
            isSubmitting: false,
            
            filterCategories() {
                const selectedType = this.formData.type;
                const categorySelect = document.getElementById('category_id');
                const options = categorySelect.querySelectorAll('option');

                options.forEach(option => {
                    if (option.value === '') return;
                    
                    const optionType = option.getAttribute('data-type');
                    if (selectedType && optionType !== selectedType) {
                        option.style.display = 'none';
                        if (option.selected) {
                            this.formData.category_id = '';
                        }
                    } else {
                        option.style.display = '';
                    }
                });
            },
            
            handleSubmit(e) {
                this.isSubmitting = true;
                // Form will submit normally, this just shows loading state
            },
            
            init() {
                this.filterCategories();
            }
        }
    }
</script>

<?= $this->endSection() ?>