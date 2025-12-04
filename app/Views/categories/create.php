<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Personal Finance Webapp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">Personal Finance</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/dashboard">Dashboard</a>
                <a class="nav-link" href="/wallets">Wallets</a>
                <a class="nav-link" href="/categories">Categories</a>
                <a class="nav-link" href="/transactions">Transactions</a>
                <a class="nav-link" href="/logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1><?= $title ?></h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <?= $csrf_field ?>
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Category Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="income" <?= ($_POST['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= ($_POST['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="/categories" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>