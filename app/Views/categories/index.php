<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
    <button onclick="showCreateModal()" class="btn btn-primary">
        <i class="ph-bold ph-plus"></i>
        Add Category
    </button>
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
                                        <button onclick="showEditModal(<?= $category['id'] ?>)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>
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
                                        <button onclick="showEditModal(<?= $category['id'] ?>)" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </button>
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

<!-- CSRF Token for AJAX -->
<input type="hidden" id="csrf_token" value="<?= Csrf::token() ?>">

<script>
// Show Create Category Modal
function showCreateModal() {
    Swal.fire({
        title: 'Add Category',
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Name</label>
                    <input type="text" id="swal-name" class="swal2-input !m-0 !w-full" placeholder="e.g., Salary, Groceries">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Type</label>
                    <select id="swal-type" class="swal2-select !m-0 !w-full">
                        <option value="">Select Type</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="ph-bold ph-plus-circle"></i> Create',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#0F766E',
        cancelButtonColor: '#6B7280',
        focusConfirm: false,
        customClass: {
            popup: 'rounded-xl',
            confirmButton: 'px-6 py-2.5 text-sm font-medium',
            cancelButton: 'px-6 py-2.5 text-sm font-medium'
        },
        preConfirm: () => {
            const name = document.getElementById('swal-name').value;
            const type = document.getElementById('swal-type').value;
            
            if (!name) {
                Swal.showValidationMessage('Category name is required');
                return false;
            }
            if (!type) {
                Swal.showValidationMessage('Please select a category type');
                return false;
            }
            
            return { name, type };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitCreate(result.value.name, result.value.type);
        }
    });
}

// Show Edit Category Modal
function showEditModal(id) {
    // Show loading
    Swal.fire({
        title: 'Loading...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch category data
    fetch(`/api/categories/${id}`)
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                Swal.fire('Error', data.message, 'error');
                return;
            }

            const category = data.category;

            Swal.fire({
                title: 'Edit Category',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Name</label>
                            <input type="text" id="swal-name" class="swal2-input !m-0 !w-full" value="${category.name}">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Category Type</label>
                            <select id="swal-type" class="swal2-select !m-0 !w-full">
                                <option value="income" ${category.type === 'income' ? 'selected' : ''}>Income</option>
                                <option value="expense" ${category.type === 'expense' ? 'selected' : ''}>Expense</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="ph-bold ph-check-circle"></i> Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#0F766E',
                cancelButtonColor: '#6B7280',
                focusConfirm: false,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'px-6 py-2.5 text-sm font-medium',
                    cancelButton: 'px-6 py-2.5 text-sm font-medium'
                },
                preConfirm: () => {
                    const name = document.getElementById('swal-name').value;
                    const type = document.getElementById('swal-type').value;
                    
                    if (!name) {
                        Swal.showValidationMessage('Category name is required');
                        return false;
                    }
                    
                    return { name, type };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitUpdate(id, result.value.name, result.value.type);
                }
            });
        })
        .catch(error => {
            Swal.fire('Error', 'Failed to load category data', 'error');
        });
}

// Submit Create
function submitCreate(name, type) {
    const csrfToken = document.getElementById('csrf_token').value;
    const formData = new FormData();
    formData.append('name', name);
    formData.append('type', type);
    formData.append('csrf_token', csrfToken);

    fetch('/api/categories/create', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#0F766E',
                customClass: {
                    popup: 'rounded-xl'
                }
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#DC2626',
                customClass: {
                    popup: 'rounded-xl'
                }
            });
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Something went wrong', 'error');
    });
}

// Submit Update
function submitUpdate(id, name, type) {
    const csrfToken = document.getElementById('csrf_token').value;
    const formData = new FormData();
    formData.append('name', name);
    formData.append('type', type);
    formData.append('csrf_token', csrfToken);

    fetch(`/api/categories/update/${id}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#0F766E',
                customClass: {
                    popup: 'rounded-xl'
                }
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#DC2626',
                customClass: {
                    popup: 'rounded-xl'
                }
            });
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Something went wrong', 'error');
    });
}
</script>

<style>
/* SweetAlert2 Custom Styles for Minimalist Design */
.swal2-popup {
    font-family: 'Inter', sans-serif !important;
}

.swal2-title {
    font-size: 1.25rem !important;
    font-weight: 600 !important;
    color: #111827 !important;
}

.swal2-input, .swal2-select {
    border: 1px solid #E5E7EB !important;
    border-radius: 0.5rem !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s !important;
}

.swal2-input:focus, .swal2-select:focus {
    border-color: #0F766E !important;
    box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1) !important;
    outline: none !important;
}

.swal2-validation-message {
    background-color: #FEF2F2 !important;
    color: #DC2626 !important;
    font-size: 0.875rem !important;
}
</style>

<?= $this->endSection() ?>