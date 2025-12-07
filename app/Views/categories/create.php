<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="/categories" class="p-2 hover:bg-gray-100 rounded-lg transition">
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

    <div class="card-custom p-8">
        <form method="post" class="space-y-6">
            <?= $csrf_field ?>
            
            <div>
                <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Name</label>
                <input type="text" class="input-custom" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required placeholder="e.g., Salary, Groceries, Entertainment">
            </div>
            
            <div>
                <label for="type" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Type</label>
                <select class="input-custom" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                </select>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-primary">
                    <i class="ph-bold ph-plus-circle"></i>
                    Create Category
                </button>
                <a href="/categories" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>