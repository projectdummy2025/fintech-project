<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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
                    <a href="/wallets" class="text-white font-semibold border-b-2 border-white pb-1">Wallets</a>
                    <a href="/categories" class="text-teal-100 hover:text-white transition">Categories</a>
                    <a href="/transactions" class="text-teal-100 hover:text-white transition">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8"><?= $title ?></h1>

        <?php if (isset($error)): ?>
            <div class="alert-custom alert-danger mb-6">
                <p class="font-medium"><?= $error ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert-custom alert-success mb-6">
                <p class="font-medium"><?= $success ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="post" class="space-y-6">
                <?= $csrf_field ?>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Wallet Name</label>
                    <input type="text" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $wallet['name'] ?? '') ?>" required>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Balance</label>
                    <p class="text-3xl font-bold tabular-nums <?= $wallet['balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                        Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>
                    </p>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="description" name="description" rows="3"><?= htmlspecialchars($_POST['description'] ?? $wallet['description'] ?? '') ?></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md">Update Wallet</button>
                    <a href="/wallets" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>