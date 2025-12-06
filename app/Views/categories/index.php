<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-600 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-white text-2xl font-bold">💰 Personal Finance</a>
                <div class="flex space-x-6">
                    <a href="/dashboard" class="text-teal-100 hover:text-white transition">Dashboard</a>
                    <a href="/wallets" class="text-teal-100 hover:text-white transition">Wallets</a>
                    <a href="/categories" class="text-white font-semibold border-b-2 border-white pb-1">Categories</a>
                    <a href="/transactions" class="text-teal-100 hover:text-white transition">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <a href="/categories/create" class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md">+ Add Category</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-custom alert-success mb-6">
                <p class="font-medium"><?= $_SESSION['message'] ?></p>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert-custom alert-danger mb-6">
                <p class="font-medium"><?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($categories)): ?>
            <?php
            $incomeCategories = array_filter($categories, function($cat) { return $cat['type'] === 'income'; });
            $expenseCategories = array_filter($categories, function($cat) { return $cat['type'] === 'expense'; });
            ?>

            <!-- Income Categories -->
            <?php if (!empty($incomeCategories)): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-4">
                        <h5 class="text-lg font-bold text-white">💵 Income Categories</h5>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Created</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($incomeCategories as $category): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= htmlspecialchars($category['name']) ?></td>
                                        <td class="px-6 py-4"><span class="badge-success"><?= ucfirst($category['type']) ?></span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('M j, Y', strtotime($category['created_at'])) ?></td>
                                        <td class="px-6 py-4 text-center space-x-2">
                                            <a href="/categories/edit/<?= $category['id'] ?>" class="inline-block px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lgover:bg-blue-200 transition">Edit</a>
                                            <a href="/categories/delete/<?= $category['id'] ?>" class="inline-block px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Expense Categories -->
            <?php if (!empty($expenseCategories)): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-red-500 to-rose-500 px-6 py-4">
                        <h5 class="text-lg font-bold text-white">💸 Expense Categories</h5>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Created</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($expenseCategories as $category): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= htmlspecialchars($category['name']) ?></td>
                                        <td class="px-6 py-4"><span class="badge-danger"><?= ucfirst($category['type']) ?></span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('M j, Y', strtotime($category['created_at'])) ?></td>
                                        <td class="px-6 py-4 text-center space-x-2">
                                            <a href="/categories/edit/<?= $category['id'] ?>" class="inline-block px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">Edit</a>
                                            <a href="/categories/delete/<?= $category['id'] ?>" class="inline-block px-4 py-2 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-6 rounded-lg">
                <p>You haven't created any categories yet. <a href="/categories/create" class="font-semibold underline">Create your first category</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>