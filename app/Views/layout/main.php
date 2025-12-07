<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Personal Finance' ?> - Personal Finance</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons (Monochrome/Minimalist) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
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
                        <?php 
                        $uri = service('uri');
                        $currentSegment = $uri->getSegment(1); 
                        ?>
                        <a href="/dashboard" class="<?= ($currentSegment == 'dashboard' || $currentSegment == '') ? 'border-teal-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Dashboard
                        </a>
                        <a href="/wallets" class="<?= $currentSegment == 'wallets' ? 'border-teal-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Wallets
                        </a>
                        <a href="/categories" class="<?= $currentSegment == 'categories' ? 'border-teal-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
                            Categories
                        </a>
                        <a href="/transactions" class="<?= $currentSegment == 'transactions' ? 'border-teal-600 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200">
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
        
        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden border-t border-gray-200" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/dashboard" class="bg-teal-50 border-teal-500 text-teal-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Dashboard</a>
                <a href="/wallets" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Wallets</a>
                <a href="/categories" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Categories</a>
                <a href="/transactions" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Transactions</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <?= $this->renderSection('content') ?>
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

</body>
</html>
