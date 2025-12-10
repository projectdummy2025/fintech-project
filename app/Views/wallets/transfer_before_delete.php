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

    <div class="alert-custom alert-warning mb-6">
        <i class="ph-fill ph-warning text-xl"></i>
        <div>
            <h5 class="font-bold text-sm mb-1">Cannot Delete Wallet</h5>
            <p class="text-sm">This wallet contains <strong><?= $transactionCount ?></strong> transaction(s). You must transfer them to another wallet before deleting.</p>
        </div>
    </div>

    <div class="card-custom p-8 mb-6">
        <h5 class="text-lg font-bold text-gray-900 mb-4">Wallet to Delete</h5>
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
            <div>
                <p class="text-sm text-gray-500 mb-1">Name</p>
                <p class="font-bold text-gray-900"><?= htmlspecialchars($walletToDelete['name']) ?></p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 mb-1">Current Balance</p>
                <p class="font-bold tabular-nums <?= $walletToDelete['balance'] >= 0 ? 'text-gray-900' : 'text-red-600' ?>">
                    Rp <?= number_format($walletToDelete['balance'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
        <?php if (!empty($walletToDelete['description'])): ?>
            <div class="mt-4">
                <p class="text-sm text-gray-500 mb-1">Description</p>
                <p class="text-gray-700 text-sm"><?= htmlspecialchars($walletToDelete['description']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-custom p-8">
        <form method="post" action="/wallets/transfer-and-delete" class="space-y-6">
            <?= $csrf_field ?>
            <input type="hidden" name="wallet_id" value="<?= $walletToDelete['id'] ?>">
            
            <div>
                <label for="new_wallet_id" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Transfer transactions to</label>
                <select class="input-custom" id="new_wallet_id" name="new_wallet_id" required>
                    <option value="">Select a destination wallet</option>
                    <?php foreach ($otherWallets as $wallet): ?>
                        <option value="<?= $wallet['id'] ?>"><?= htmlspecialchars($wallet['name']) ?> (Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>)</option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-xs text-gray-500">
                    <i class="ph-fill ph-info text-blue-500 mr-1"></i>
                    All transactions will be moved to the selected wallet. The original wallet will be permanently deleted.
                </p>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="btn btn-danger w-full md:w-auto" onclick="return confirm('Are you sure you want to transfer transactions and delete this wallet? This action cannot be undone.')">
                    <i class="ph-bold ph-trash"></i>
                    Transfer & Delete
                </button>
                <a href="/wallets" class="btn btn-secondary w-full md:w-auto">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>