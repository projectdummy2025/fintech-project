<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
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

<!-- Wallets Grid -->
<?php if (!empty($wallets)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($wallets as $wallet): ?>
            <div class="card-custom p-6 flex flex-col h-full relative group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-50 rounded-xl">
                        <i class="ph-fill ph-wallet text-teal-700 text-2xl"></i>
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <a href="/wallets/edit/<?= $wallet['id'] ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                            <i class="ph ph-pencil-simple text-lg"></i>
                        </a>
                        <a href="/wallets/delete/<?= $wallet['id'] ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition" title="Delete">
                            <i class="ph ph-trash text-lg"></i>
                        </a>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900 mb-1"><?= htmlspecialchars($wallet['name']) ?></h3>
                <p class="text-3xl font-bold tabular-nums text-gray-900 mb-4 tracking-tight">
                    Rp <?= number_format($wallet['balance'] ?? 0, 0, ',', '.') ?>
                </p>
                
                <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-sm text-gray-500">
                    <span>Created <?= date('M Y', strtotime($wallet['created_at'])) ?></span>
                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-medium text-gray-600">ID: <?= $wallet['id'] ?></span>
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
    <div class="card-custom p-12 text-center max-w-lg mx-auto">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-50 mb-4">
            <i class="ph-fill ph-wallet text-teal-600 text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Wallets Yet</h3>
        <p class="text-gray-500 mb-6">Create your first wallet to start tracking your finances.</p>
        <a href="/wallets/create" class="btn btn-primary w-full justify-center">Create Wallet</a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>