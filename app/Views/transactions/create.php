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
                    <a href="/wallets" class="text-teal-100 hover:text-white transition">Wallets</a>
                    <a href="/categories" class="text-teal-100 hover:text-white transition">Categories</a>
                    <a href="/transactions" class="text-white font-semibold border-b-2 border-white pb-1">Transactions</a>
                    <a href="/logout" class="text-teal-100 hover:text-white transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
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

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <form method="post" class="space-y-6">
                <?= $csrf_field ?>

                <!-- 2-Column Grid for Desktop -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Wallet -->
                    <div>
                        <label for="wallet_id" class="block text-sm font-medium text-gray-700 mb-2">Wallet</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="wallet_id" name="wallet_id" required>
                            <option value="">Select Wallet</option>
                            <?php foreach ($wallets as $wallet): ?>
                                <option value="<?= $wallet['id'] ?>" <?= ($wallet['id'] == ($_POST['wallet_id'] ?? '')) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($wallet['name']) ?> (Rp <?= number_format($wallet['balance'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Transaction Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                            <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"
                                    data-type="<?= $category['type'] ?>"
                                    <?= ($category['type'] == ($_POST['type'] ?? '')) ? 'selected' : '' ?>>
                                    [<?= ucfirst($category['type']) ?>] <?= htmlspecialchars($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Amount with Prefix -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" step="0.01" class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 tabular-nums" id="amount" name="amount"
                                   value="<?= htmlspecialchars($_POST['amount'] ?? '') ?>" required placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Date (Full Width) -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="date" name="date"
                           value="<?= htmlspecialchars($_POST['date'] ?? date('Y-m-d')) ?>" required>
                </div>

                <!-- Notes (Full Width) -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md">Add Transaction</button>
                    <a href="/transactions" class="px-8 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update category options based on transaction type
        document.getElementById('type').addEventListener('change', function() {
            const selectedType = this.value;
            const categorySelect = document.getElementById('category_id');
            const options = categorySelect.querySelectorAll('option');

            options.forEach(option => {
                if (option.value === '') {
                    return; // Skip the "Select Category" option
                }

                const optionType = option.getAttribute('data-type');
                if (selectedType && optionType !== selectedType) {
                    option.style.display = 'none';
                } else {
                    option.style.display = '';
                }
            });
        });

        // Trigger change event on page load if a type is already selected
        document.getElementById('type').dispatchEvent(new CustomEvent('change'));
    </script>
</body>
</html>