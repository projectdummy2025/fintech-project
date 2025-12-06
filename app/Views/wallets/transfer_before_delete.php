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

        <div class="alert-custom alert-warning mb-6">
            <h5 class="font-bold text-lg mb-2">⚠️ Warning: This wallet has <?= $transactionCount ?> transaction(s)</h5>
            <p>You cannot delete this wallet because it has transactions. Please transfer the transactions to another wallet first.</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-8 mb-6">
            <h5 class="text-xl font-bold text-gray-800 mb-4">Wallet to Delete: <?= htmlspecialchars($walletToDelete['name']) ?></h5>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Balance:</p>
                    <p class="text-3xl font-bold tabular-nums <?= $walletToDelete['balance'] >= 0 ? 'text-green-600' : 'text-red-600' ?>">
                        Rp <?= number_format($walletToDelete['balance'], 0, ',', '.') ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Description:</p>
                    <p class="text-gray-700">
                        <?php if (!empty($walletToDelete['description'])): ?>
                            <?= htmlspecialchars($walletToDelete['description']) ?>
                        <?php else: ?>
                            No description
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="post" action="/wallets/transfer-and-delete" class="space-y-6">
                <input type="hidden" name="wallet_id" value="<?= $walletToDelete['id'] ?>">
                
                <div>
                    <label for="new_wallet_id" class="block text-sm font-medium text-gray-700 mb-2">Transfer transactions to:</label>
                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="new_wallet_id" name="new_wallet_id" required>
                        <option value="">Select a wallet</option>
                        <?php foreach ($otherWallets as $wallet): ?>
                            <option value="<?= $wallet['id'] ?>"><?= htmlspecialchars($wallet['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 rounded">
                    <p><strong>Note:</strong> All transactions from "<?= htmlspecialchars($walletToDelete['name']) ?>" will be moved to the selected wallet, then "<?= htmlspecialchars($walletToDelete['name']) ?>" will be deleted.</p>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition shadow-md" onclick="return confirm('Are you sure you want to transfer and delete this wallet?')">Transfer and Delete Wallet</button>
                    <a href="/wallets" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>