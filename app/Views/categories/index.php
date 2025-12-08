<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Categories' ?> - Personal Finance</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" x-data="categoryApp()">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2">
                        <i class="ph-fill ph-wallet text-teal-700 text-2xl"></i>
                        <span class="font-bold text-xl tracking-tight text-gray-900">Personal Finance</span>
                    </a>
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-8">
                        <a href="/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="/wallets" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Wallets
                        </a>
                        <a href="/categories" class="border-teal-600 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Categories
                        </a>
                        <a href="/transactions" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Transactions
                        </a>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="/transactions/create" class="relative inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-teal-700 shadow-sm hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-600 transition-all duration-200">
                            <i class="ph ph-plus-circle text-lg"></i>
                            <span>New Transaction</span>
                        </a>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6">
                        <a href="/logout" class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200" title="Logout">
                            <i class="ph ph-sign-out text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
                <button @click="openCreateModal()" class="btn btn-primary">
                    <i class="ph-bold ph-plus"></i>
                    Add Category
                </button>
            </div>

            <!-- Alerts -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                    <i class="ph-fill ph-check-circle text-xl text-emerald-600"></i>
                    <p class="font-medium text-sm"><?= $_SESSION['message'] ?></p>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <i class="ph-fill ph-warning-circle text-xl text-red-600"></i>
                    <p class="font-medium text-sm"><?= $_SESSION['error'] ?></p>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Income Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($incomeCategories as $category): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button @click="openEditModal(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>', '<?= $category['type'] ?>')" class="btn btn-ghost p-1.5 text-blue-600 hover:bg-blue-50" title="Edit">
                                                        <i class="ph ph-pencil-simple text-lg"></i>
                                                    </button>
                                                    <a href="/categories/delete/<?= $category['id'] ?>" class="btn btn-ghost p-1.5 text-red-600 hover:bg-red-50" onclick="return confirm('Are you sure?')" title="Delete">
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($expenseCategories as $category): ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button @click="openEditModal(<?= $category['id'] ?>, '<?= htmlspecialchars($category['name'], ENT_QUOTES) ?>', '<?= $category['type'] ?>')" class="btn btn-ghost p-1.5 text-blue-600 hover:bg-blue-50" title="Edit">
                                                        <i class="ph ph-pencil-simple text-lg"></i>
                                                    </button>
                                                    <a href="/categories/delete/<?= $category['id'] ?>" class="btn btn-ghost p-1.5 text-red-600 hover:bg-red-50" onclick="return confirm('Are you sure?')" title="Delete">
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
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> Personal Finance App. Minimalist & Professional.
            </p>
        </div>
    </footer>

    <!-- Modal Backdrop & Container -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal()"></div>
        
        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6"
                 @click.stop>
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900" x-text="modalTitle"></h3>
                    <button @click="closeModal()" class="p-1 text-gray-400 hover:text-gray-600 transition">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
                
                <!-- Modal Form -->
                <form :action="formAction" method="POST">
                    <?= Csrf::field() ?>
                    
                    <!-- Error Message -->
                    <div x-show="errorMessage" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700" x-text="errorMessage"></p>
                    </div>
                    
                    <!-- Category Name -->
                    <div class="form-group">
                        <label for="modal-name" class="form-label">Category Name</label>
                        <input type="text" 
                               id="modal-name" 
                               name="name" 
                               x-model="formName"
                               class="input-custom"
                               placeholder="e.g., Salary, Groceries"
                               required>
                    </div>
                    
                    <!-- Category Type -->
                    <div class="form-group mb-6">
                        <label for="modal-type" class="form-label">Category Type</label>
                        <select id="modal-type" 
                                name="type" 
                                x-model="formType"
                                class="input-custom"
                                required>
                            <option value="">Select Type</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="btn btn-primary flex-1">
                            <i class="ph-bold" :class="isEditMode ? 'ph-check-circle' : 'ph-plus-circle'"></i>
                            <span x-text="isEditMode ? 'Update' : 'Create'"></span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function categoryApp() {
            return {
                showModal: false,
                modalTitle: 'Add Category',
                isEditMode: false,
                formAction: '/categories/create',
                formName: '',
                formType: '',
                errorMessage: '',
                
                openCreateModal() {
                    this.isEditMode = false;
                    this.modalTitle = 'Add Category';
                    this.formAction = '/categories/create';
                    this.formName = '';
                    this.formType = '';
                    this.errorMessage = '';
                    this.showModal = true;
                },
                
                openEditModal(id, name, type) {
                    this.isEditMode = true;
                    this.modalTitle = 'Edit Category';
                    this.formAction = '/categories/edit/' + id;
                    this.formName = name;
                    this.formType = type;
                    this.errorMessage = '';
                    this.showModal = true;
                },
                
                closeModal() {
                    this.showModal = false;
                }
            }
        }
    </script>
</body>
</html>