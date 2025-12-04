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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= $title ?></h1>
            <a href="/wallets/create" class="btn btn-primary">Add Wallet</a>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($wallets)): ?>
            <div class="row">
                <?php foreach ($wallets as $wallet): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($wallet['name']) ?></h5>
                                <p class="card-text">
                                    <?php if (!empty($wallet['description'])): ?>
                                        <?= htmlspecialchars($wallet['description']) ?>
                                    <?php else: ?>
                                        No description
                                    <?php endif; ?>
                                </p>
                                <p class="card-text">
                                    <strong>Balance: </strong>
                                    <span class="<?= $wallet['balance'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                        Rp <?= number_format($wallet['balance'], 2) ?>
                                    </span>
                                </p>
                                <small class="text-muted">Created: <?= date('M j, Y', strtotime($wallet['created_at'])) ?></small>
                                <div class="mt-2">
                                    <a href="/wallets/edit/<?= $wallet['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="/wallets/delete/<?= $wallet['id'] ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                You haven't created any wallets yet. <a href="/wallets/create">Create your first wallet</a>.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>