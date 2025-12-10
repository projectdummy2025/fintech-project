<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div x-data="categoryApp()">
            
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
                <div class="card-custom overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-arrow-circle-down-left text-emerald-600 text-xl"></i>
                            <h5 class="text-base font-bold text-gray-900">Income Categories</h5>
                        </div>
                        <span class="badge badge-success">
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
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($incomeCategories as $category): ?>
                                        <tr>
                                            <td class="font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                            <td class="text-center">
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
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ph ph-folder-open"></i>
                                </div>
                                <h4 class="empty-state-title">No Income Categories</h4>
                                <p class="empty-state-text">You haven't created any income categories yet. Click the button above to add one.</p>
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
                        <span class="badge badge-danger">
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
                                <tbody class="divide-y divide-gray-100">
                                    <?php foreach ($expenseCategories as $category): ?>
                                        <tr>
                                            <td class="font-medium text-gray-700"><?= htmlspecialchars($category['name']) ?></td>
                                            <td class="text-center">
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
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="ph ph-folder-open"></i>
                                </div>
                                <h4 class="empty-state-title">No Expense Categories</h4>
                                <p class="empty-state-text">You haven't created any expense categories yet. Click the button above to add one.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

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
</div>

<?= $this->endSection() ?>