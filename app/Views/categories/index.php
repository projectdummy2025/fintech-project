<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div id="categories-page" x-data="categoryApp()" x-init="loadCategories()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-sm text-gray-500 mt-1">Organize your income and expenses</p>
        </div>
        <button @click="openCreateModal()" class="btn btn-primary">
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

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-20">
        <div class="flex items-center gap-3 text-gray-500">
            <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Loading categories...</span>
        </div>
    </div>

    <div x-show="!loading">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total -->
            <div class="card-custom p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                    <i class="ph-fill ph-squares-four text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Categories</p>
                    <h3 class="text-2xl font-bold text-gray-900" x-text="categories.length"></h3>
                </div>
            </div>
            <!-- Income -->
            <div class="card-custom p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="ph-fill ph-arrow-circle-down-left text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Income</p>
                    <h3 class="text-2xl font-bold text-gray-900" x-text="categories.filter(c => c.type === 'income').length"></h3>
                </div>
            </div>
            <!-- Expense -->
            <div class="card-custom p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                    <i class="ph-fill ph-arrow-circle-up-right text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-medium">Expense</p>
                    <h3 class="text-2xl font-bold text-gray-900" x-text="categories.filter(c => c.type === 'expense').length"></h3>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card-custom overflow-hidden">
            <!-- Tabs -->
            <div class="border-b border-gray-200 px-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'all'"
                        :class="activeTab === 'all' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        All Categories
                    </button>
                    <button @click="activeTab = 'income'"
                        :class="activeTab === 'income' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Income
                    </button>
                    <button @click="activeTab = 'expense'"
                        :class="activeTab === 'expense' ? 'border-teal-500 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Expense
                    </button>
                </nav>
            </div>

            <!-- Table Container -->
            <div class="p-0">
                <template x-if="filteredCategories.length > 0">
                    <table class="table-custom w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="pl-6">Name</th>
                                <th>Type</th>
                                <th class="text-center">Usage</th>
                                <th class="text-center pr-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="category in filteredCategories" :key="category.id">
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="pl-6 font-medium text-gray-900" x-text="category.name"></td>
                                    <td>
                                        <span :class="category.type === 'income' ? 'badge badge-success' : 'badge badge-danger'"
                                              class="capitalize">
                                            <span x-text="category.type"></span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full" x-text="category.usage_count + ' transactions'"></span>
                                    </td>
                                    <td class="text-center pr-6">
                                        <div class="flex items-center justify-center gap-2">
                                            <button @click="openEditModal(category)" 
                                                    class="btn btn-ghost p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                                <i class="ph ph-pencil-simple text-lg"></i>
                                            </button>
                                            <button @click="deleteCategory(category.id, category.usage_count)" 
                                                    class="btn btn-ghost p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </template>

                <!-- Empty State -->
                <template x-if="filteredCategories.length === 0">
                    <div class="py-16 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                            <i class="ph ph-magnifying-glass text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">No Categories Found</h4>
                        <p class="text-gray-500 max-w-sm">
                            There are no categories in this view. Try switching tabs or add a new category.
                        </p>
                    </div>
                </template>
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
                <form @submit.prevent="submitForm()">
                    <!-- Category Name -->
                    <div class="form-group">
                        <label for="modal-name" class="form-label">Category Name</label>
                        <input type="text" 
                               id="modal-name" 
                               x-model="formName"
                               class="input-custom"
                               placeholder="e.g., Salary, Groceries"
                               required>
                    </div>
                    
                    <!-- Category Type -->
                    <div class="form-group mb-6">
                        <label for="modal-type" class="form-label">Category Type</label>
                        <select id="modal-type" 
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
                        <button type="submit" class="btn btn-primary flex-1" :disabled="submitting">
                            <template x-if="submitting">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                            <i class="ph-bold" :class="isEditMode ? 'ph-check-circle' : 'ph-plus-circle'" x-show="!submitting"></i>
                            <span x-text="isEditMode ? 'Update' : 'Create'"></span>
                        </button>
                        <button type="button" @click="closeModal()" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function categoryApp() {
    return {
        loading: true,
        submitting: false,
        categories: [],
        activeTab: 'all', // 'all', 'income', 'expense'
        showModal: false,
        modalTitle: 'Add Category',
        isEditMode: false,
        editId: null,
        formName: '',
        formType: '',

        get filteredCategories() {
            if (this.activeTab === 'all') {
                return this.categories;
            }
            return this.categories.filter(c => c.type === this.activeTab);
        },

        async loadCategories() {
            this.loading = true;
            try {
                const response = await fetch('/api/categories', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                
                if (data.success) {
                    this.categories = data.data.categories || [];
                } else {
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return;
                    }
                    throw new Error(data.error || 'Failed to load categories');
                }
            } catch (error) {
                console.error('Failed to load categories:', error);
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to load categories',
                    showConfirmButton: false,
                    timer: 3000
                });
            } finally {
                this.loading = false;
            }
        },

        openCreateModal() {
            this.isEditMode = false;
            this.editId = null;
            this.modalTitle = 'Add Category';
            this.formName = '';
            this.formType = '';
            this.showModal = true;
        },

        openEditModal(category) {
            this.isEditMode = true;
            this.editId = category.id;
            this.modalTitle = 'Edit Category';
            this.formName = category.name;
            this.formType = category.type;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        async submitForm() {
            if (!this.formName.trim() || !this.formType) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please fill all fields',
                    showConfirmButton: false,
                    timer: 3000
                });
                return;
            }

            this.submitting = true;
            const url = this.isEditMode 
                ? `/api/categories/edit/${this.editId}` 
                : '/api/categories/create';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: this.formName,
                        type: this.formType
                    })
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: this.isEditMode ? 'Category updated' : 'Category created',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    this.closeModal();
                    await this.loadCategories();
                } else {
                    throw new Error(data.error || 'Operation failed');
                }
            } catch (error) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: error.message || 'Operation failed',
                    showConfirmButton: false,
                    timer: 3000
                });
            } finally {
                this.submitting = false;
            }
        },

        async deleteCategory(id, usageCount) {
            if (usageCount > 0) {
                const result = await Swal.fire({
                    title: 'Cannot Delete',
                    text: `This category has ${usageCount} transaction(s). Transfer them first or delete them manually.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Transfer Transactions',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    window.location.href = `/categories/delete/${id}`;
                }
                return;
            }

            const confirmed = await Swal.fire({
                title: 'Delete Category?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            });

            if (confirmed.isConfirmed) {
                try {
                    const response = await fetch(`/api/categories/delete/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Category deleted',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        await this.loadCategories();
                    } else {
                        throw new Error(data.error || 'Failed to delete');
                    }
                } catch (error) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: error.message || 'Failed to delete',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        }
    }
}
</script>

<?= $this->endSection() ?>
