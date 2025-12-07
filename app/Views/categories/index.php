<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
    <a href="/categories/create" class="btn btn-primary">
        <i class="ph-bold ph-plus"></i>
        Add Category
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Income Categories -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-arrow-circle-down-left text-emerald-600 text-xl"></i>
                <h5 class="text-base font-bold text-gray-900">Income Categories</h5>
            </div>
            <span class="text-xs font-medium text-emerald-700 bg-emerald-100 px-2 py-1 rounded-full">
                <?= count(array_filter($categories, fn($c) => $c['type'] === 'income')) ?> Items
            </span>
        </div>
        <div class="p-0">
            <?php 
            $incomeCategories = array_filter($categories, fn($c) => $c['type'] === 'income');
            if (!empty($incomeCategories)): 
            ?>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incomeCategories as $category): ?>
                            <tr>
                                <td class="font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="/categories/edit/<?= $category['id'] ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <a href="/categories/delete/<?= $category['id'] ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="ph ph-trash text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500 text-sm">
                    No income categories found.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Expense Categories -->
    <div class="card-custom overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-red-50/50 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-arrow-circle-up-right text-red-600 text-xl"></i>
                <h5 class="text-base font-bold text-gray-900">Expense Categories</h5>
            </div>
            <span class="text-xs font-medium text-red-700 bg-red-100 px-2 py-1 rounded-full">
                <?= count(array_filter($categories, fn($c) => $c['type'] === 'expense')) ?> Items
            </span>
        </div>
        <div class="p-0">
            <?php 
            $expenseCategories = array_filter($categories, fn($c) => $c['type'] === 'expense');
            if (!empty($expenseCategories)): 
            ?>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenseCategories as $category): ?>
                            <tr>
                                <td class="font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="/categories/edit/<?= $category['id'] ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <a href="/categories/delete/<?= $category['id'] ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition" onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="ph ph-trash text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500 text-sm">
                    No expense categories found.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>