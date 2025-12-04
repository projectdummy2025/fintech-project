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
            <a href="/categories/create" class="btn btn-primary">Add Category</a>
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

        <?php if (!empty($categories)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $incomeCategories = array_filter($categories, function($cat) { return $cat['type'] === 'income'; });
                        $expenseCategories = array_filter($categories, function($cat) { return $cat['type'] === 'expense'; });
                        ?>
                        
                        <?php if (!empty($incomeCategories)): ?>
                            <tr>
                                <th colspan="4" class="bg-success text-white text-center">Income Categories</th>
                            </tr>
                            <?php foreach ($incomeCategories as $category): ?>
                                <tr>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td><span class="badge bg-success"><?= ucfirst($category['type']) ?></span></td>
                                    <td><?= date('M j, Y', strtotime($category['created_at'])) ?></td>
                                    <td>
                                        <a href="/categories/edit/<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="/categories/delete/<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <?php if (!empty($expenseCategories)): ?>
                            <tr>
                                <th colspan="4" class="bg-danger text-white text-center">Expense Categories</th>
                            </tr>
                            <?php foreach ($expenseCategories as $category): ?>
                                <tr>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td><span class="badge bg-danger"><?= ucfirst($category['type']) ?></span></td>
                                    <td><?= date('M j, Y', strtotime($category['created_at'])) ?></td>
                                    <td>
                                        <a href="/categories/edit/<?= $category['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="/categories/delete/<?= $category['id'] ?>" class="btn btn-sm btn-outline-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                You haven't created any categories yet. <a href="/categories/create">Create your first category</a>.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>