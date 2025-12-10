<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-4 mb-8">
        <a href="/wallets" class="p-2 hover:bg-gray-100 rounded-lg transition">
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
                <label for="name" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Wallet Name</label>
                <input type="text" class="input-custom" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $wallet['name'] ?? '') ?>" required autofocus>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Current Balance</label>
                <p class="text-3xl font-bold tabular-nums <?= $wallet['balance'] >= 0 ? 'text-gray-900' : 'text-red-600' ?>">
                    Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>
                </p>
                <p class="text-xs text-gray-400 mt-2">Balance is calculated from transactions</p>
            </div>
            
            <div>
                <label for="description" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Description (Optional)</label>
                <textarea class="input-custom" id="description" name="description" rows="3" placeholder="Add a description for this wallet..."><?= htmlspecialchars($_POST['description'] ?? $wallet['description'] ?? '') ?></textarea>
            </div>
            
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-primary">
                    <i class="ph-bold ph-check-circle"></i>
                    Update Wallet
                </button>
                <a href="/wallets" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>