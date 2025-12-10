<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Personal Finance' ?> - Personal Finance</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons (Monochrome/Minimalist) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <!-- Skip to Main Content - Accessibility -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>
    
    <!-- Navbar -->
    <nav class="bg-white/95 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50 shadow-sm" role="navigation" aria-label="Main navigation" x-data="{ mobileMenuOpen: false, profileOpen: false }">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Desktop Menu -->
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center gap-2 group" aria-label="Personal Finance Home">
                        <i class="ph-fill ph-wallet text-teal-700 text-2xl group-hover:scale-110 transition-transform duration-200"></i>
                        <span class="font-bold text-xl tracking-tight text-gray-900">Personal Finance</span>
                    </a>
                    
                    <!-- Desktop Menu -->
                    <div class="hidden md:ml-8 md:flex md:space-x-1">
                        <?php 
                        $uri = service('uri');
                        $currentSegment = $uri->getSegment(1); 
                        ?>
                        <a href="/dashboard" class="<?= ($currentSegment == 'dashboard' || $currentSegment == '') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200" <?= ($currentSegment == 'dashboard' || $currentSegment == '') ? 'aria-current="page"' : '' ?>>
                            <i class="ph ph-house text-lg mr-2"></i>
                            Dashboard
                        </a>
                        <a href="/wallets" class="<?= $currentSegment == 'wallets' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200" <?= $currentSegment == 'wallets' ? 'aria-current="page"' : '' ?>>
                            <i class="ph ph-wallet text-lg mr-2"></i>
                            Wallets
                        </a>
                        <a href="/categories" class="<?= $currentSegment == 'categories' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200" <?= $currentSegment == 'categories' ? 'aria-current="page"' : '' ?>>
                            <i class="ph ph-tag text-lg mr-2"></i>
                            Categories
                        </a>
                        <a href="/transactions" class="<?= $currentSegment == 'transactions' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200" <?= $currentSegment == 'transactions' ? 'aria-current="page"' : '' ?>>
                            <i class="ph ph-list-bullets text-lg mr-2"></i>
                            Transactions
                        </a>
                    </div>
                </div>
                
                <!-- Right: Actions & Profile -->
                <div class="flex items-center gap-3">
                    <!-- New Transaction Button (Hidden on mobile) -->
                    <div class="hidden sm:block">
                        <a href="/transactions/create" class="btn btn-primary btn-sm" aria-label="Create new transaction">
                            <i class="ph ph-plus-circle text-lg"></i>
                            <span>New</span>
                        </a>
                    </div>
                    
                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors duration-200" aria-label="User menu" aria-expanded="false">
                            <!-- Avatar Placeholder -->
                            <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center">
                                <i class="ph-fill ph-user text-teal-700 text-lg"></i>
                            </div>
                            <i class="ph ph-caret-down text-gray-500 text-sm hidden sm:block" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100"
                             style="display: none;">
                            <div class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <?= $_SESSION['username'] ?? 'User' ?>
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">Manage your account</p>
                            </div>
                            <div class="py-1">
                                <a href="/dashboard" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="ph ph-house text-lg mr-3 text-gray-400"></i>
                                    Dashboard
                                </a>
                                <a href="/wallets" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="ph ph-wallet text-lg mr-3 text-gray-400"></i>
                                    My Wallets
                                </a>
                                <a href="/categories" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="ph ph-tag text-lg mr-3 text-gray-400"></i>
                                    Categories
                                </a>
                            </div>
                            <div class="py-1">
                                <a href="/logout" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="ph ph-sign-out text-lg mr-3"></i>
                                    Sign out
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors" aria-label="Toggle menu">
                        <i class="ph ph-list text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="ph ph-x text-2xl" x-show="mobileMenuOpen" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden border-t border-gray-200 bg-white"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/dashboard" class="<?= ($currentSegment == 'dashboard' || $currentSegment == '') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <i class="ph ph-house text-xl mr-3"></i>
                    Dashboard
                </a>
                <a href="/wallets" class="<?= $currentSegment == 'wallets' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <i class="ph ph-wallet text-xl mr-3"></i>
                    Wallets
                </a>
                <a href="/categories" class="<?= $currentSegment == 'categories' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <i class="ph ph-tag text-xl mr-3"></i>
                    Categories
                </a>
                <a href="/transactions" class="<?= $currentSegment == 'transactions' ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' ?> flex items-center px-3 py-2 rounded-md text-base font-medium">
                    <i class="ph ph-list-bullets text-xl mr-3"></i>
                    Transactions
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-4 flex items-center">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-user text-teal-700 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= $_SESSION['username'] ?? 'User' ?></div>
                        <div class="text-sm text-gray-500">View profile</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="/transactions/create" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-teal-700 hover:bg-teal-50">
                        <i class="ph ph-plus-circle text-xl mr-3"></i>
                        New Transaction
                    </a>
                    <a href="/logout" class="flex items-center px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">
                        <i class="ph ph-sign-out text-xl mr-3"></i>
                        Sign out
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" class="py-10" role="main">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto" role="contentinfo">
        <div class="container mx-auto px-4 py-6 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> Personal Finance App. Minimalist & Professional.
            </p>
        </div>
    </footer>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form Submission Loading State
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Don't show loading if the form has the 'no-loading' class
                    if (this.classList.contains('no-loading')) return;

                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        // Add loading state
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;
                        
                        // Optional: Restore button state after a timeout (in case of validation error or no navigation)
                        // setTimeout(() => {
                        //     submitBtn.classList.remove('btn-loading');
                        //     submitBtn.disabled = false;
                        // }, 5000);
                    }
                });
            });

            // Toast Notification Auto-dismiss
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.classList.add('toast-leaving');
                    toast.addEventListener('animationend', () => {
                        toast.remove();
                    });
                }, 5000);
            });
        });
    </script>
</body>
</html>
