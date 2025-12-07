<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="/transactions" class="p-2 hover:bg-gray-100 rounded-lg transition">
            <i class="ph ph-arrow-left text-xl text-gray-600"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert-custom alert-danger mb-6">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            <p class="font-medium text-sm"><?= $error ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert-custom alert-success mb-6">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <p class="font-medium text-sm"><?= $success ?></p>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card-custom p-8">
        <form method="post" class="space-y-6">
            <?= $csrf_field ?>

            <!-- 2-Column Grid for Desktop -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Wallet -->
                <div>
                    <label for="wallet_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Wallet</label>
                    <select class="input-custom" id="wallet_id" name="wallet_id" required>
                        <option value="">Select Wallet</option>
                        <?php foreach ($wallets as $wallet): ?>
                            <option value="<?= $wallet['id'] ?>" <?= ($wallet['id'] == ($_POST['wallet_id'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($wallet['name']) ?> (Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Transaction Type -->
                <div>
                    <label for="type" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Transaction Type</label>
                    <select class="input-custom" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                        <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category</label>
                    <select class="input-custom" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"
                                data-type="<?= $category['type'] ?>"
                                <?= ($category['id'] == ($_POST['category_id'] ?? '')) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Amount with Prefix -->
                <div>
                    <label for="amount" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-sm">Rp</span>
                        <input type="number" step="0.01" class="input-custom pl-12 tabular-nums" id="amount" name="amount"
                               value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" required placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Date (Full Width) -->
            <div>
                <label for="date" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Date</label>
                <input type="date" class="input-custom" id="date" name="date"
                       value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>" required>
            </div>

            <!-- Notes (Full Width) -->
            <div>
                <label for="notes" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Notes (Optional)</label>
                <textarea class="input-custom" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-primary">
                    <i class="ph-bold ph-plus-circle"></i>
                    Add Transaction
                </button>
                <a href="/transactions" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Update category options based on transaction type
    document.getElementById('type').addEventListener('change', function() {
        const selectedType = this.value;
        const categorySelect = document.getElementById('category_id');
        const options = categorySelect.querySelectorAll('option');

        options.forEach(option => {
            if (option.value === '') {
                return; // Skip the "Select Category" option
            }

            const optionType = option.getAttribute('data-type');
            if (selectedType && optionType !== selectedType) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    });

    // Trigger change event on page load if a type is already selected
    document.getElementById('type').dispatchEvent(new CustomEvent('change'));
</script>

<?= $this->endSection() ?>