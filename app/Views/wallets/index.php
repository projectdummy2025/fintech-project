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
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-teal-600 to-cyan-600 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-white text-2xl font-bold">💰 Personal Finance</a>
                <div class="flex space-x-6">
                    <a href="/dashboard" class="text-teal-100 hover:text-white transition">Dashboard</a>
                    <a href="/wallets" class="text-white font-semibold border-b-2 border-white pb-1">Wallets</a>
                    <a href="/categories" class="text-teal-100 hover:text-white transition">Categories</a>
                    <a href="/transactions" class="text-teal-100 hover:text-white transition">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800"><?= $title ?></h1>
            <a href="/wallets/create" class="px-6 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md">+ Add Wallet</a>
        </div>

        <!-- Alerts -->
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

        <?php if (!empty($wallets)): ?>
            <!-- Wallet Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($wallets as $wallet): ?>
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-1 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h5 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($wallet['name']) ?></h5>
                                <span class="text-2xl">👛</span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 min-h-[40px]">
                                <?php if (!empty($wallet['description'])): ?>
                                    <?= htmlspecialchars($wallet['description']) ?>
                                <?php else: ?>
                                    No description
                                <?php endif; ?>
                            </p>
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Balance</p>
                                <p class="text-3xl font-bold tabular-nums <?= $wallet['balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>
                                </p>
                            </div>
                            <p class="text-xs text-gray-500 mb-4">Created: <?= date('M j, Y', strtotime($wallet['created_at'])) ?></p>
                            <div class="flex gap-2">
                                <a href="/wallets/edit/<?= $wallet['id'] ?>" class="flex-1 text-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium text-sm">Edit</a>
                                <a href="/wallets/delete/<?= $wallet['id'] ?>" class="flex-1 text-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium text-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-6 rounded-lg">
                <p>You haven't created any wallets yet. <a href="/wallets/create" class="font-semibold underline">Create your first wallet</a>.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>