<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div id="wallets-page">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-sm text-gray-500 mt-1">Total Balance: <span id="total-wallet-balance" class="font-semibold text-gray-900">Rp <?= number_format(array_sum(array_column($wallets ?? [], 'balance')), 0, ',', '.') ?></span></p>
        </div>
        <a href="/wallets/create" class="btn btn-primary">
            <i class="ph-bold ph-plus"></i>
            Add Wallet
        </a>
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

    <!-- Wallets Container -->
    <div id="wallets-container">
        <?php if (!empty($wallets)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($wallets as $wallet): ?>
                    <div class="card-custom p-6 flex flex-col h-full relative group" data-id="<?= $wallet['id'] ?>">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-teal-50 rounded-xl">
                                <i class="ph-fill ph-wallet text-teal-700 text-2xl"></i>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/wallets/edit/<?= $wallet['id'] ?>" class="btn btn-ghost p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </a>
                                <button onclick="deleteWallet(<?= $wallet['id'] ?>)" class="btn btn-ghost p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-1"><?= htmlspecialchars($wallet['name']) ?></h3>
                        <p class="text-3xl font-bold tabular-nums <?= ($wallet['balance'] ?? 0) >= 0 ? 'text-gray-900' : 'text-red-600' ?> mb-4 tracking-tight">
                            Rp <?= number_format($wallet['balance'] ?? 0, 0, ',', '.') ?>
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
                            <span>Created <?= date('M Y', strtotime($wallet['created_at'])) ?></span>
                            <a href="/transactions?wallet_id=<?= $wallet['id'] ?>" class="text-teal-600 hover:text-teal-700 font-medium">
                                View Transactions →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Add New Wallet Card (Visual Cue) -->
                <a href="/wallets/create" class="border-2 border-dashed border-gray-300 rounded-xl p-6 flex flex-col items-center justify-center text-gray-400 hover:border-teal-500 hover:text-teal-600 hover:bg-teal-50 transition duration-200 h-full min-h-[200px]">
                    <i class="ph ph-plus-circle text-4xl mb-2"></i>
                    <span class="font-medium">Create New Wallet</span>
                </a>
            </div>
        <?php else: ?>
            <div class="card-custom max-w-lg mx-auto">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph-fill ph-wallet"></i>
                    </div>
                    <h4 class="empty-state-title">No Wallets Yet</h4>
                    <p class="empty-state-text">Create your first wallet to start tracking your finances.</p>
                    <a href="/wallets/create" class="btn btn-primary mt-4">Create Wallet</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Delete wallet via API
    async function deleteWallet(id) {
        const confirmed = await Swal.fire({
            title: 'Delete Wallet?',
            text: 'Make sure there are no transactions in this wallet.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        });

        if (confirmed.isConfirmed) {
            try {
                const response = await fetch(`/api/wallets/delete/${id}`, {
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
                        title: 'Wallet deleted successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    location.reload();
                } else {
                    throw new Error(data.error || 'Failed to delete');
                }
            } catch (error) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: error.message || 'Failed to delete wallet',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    }
</script>

<?= $this->endSection() ?>
