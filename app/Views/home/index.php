<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Finance - Take Control of Your Money</title>
    <meta name="description" content="Personal Finance App - Track your income, expenses, and achieve your financial goals with our simple and powerful finance tracker.">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['Roboto Mono', 'monospace'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Swup.js for SPA-like transitions -->
    <script src="https://unpkg.com/swup@4"></script>
    <script src="https://unpkg.com/@swup/head-plugin@2"></script>
    <script src="https://unpkg.com/@swup/preload-plugin@3"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
    
    <style>
        /* Landing Page Specific Styles */
        .hero-gradient {
            background: linear-gradient(135deg, #111827 0%, #0f766e 100%);
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .feature-card {
            transition: transform 0.2s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-2px);
        }
        
        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 scroll-smooth bg-gray-50">
    
    <!-- Main Transition Container -->
    <div id="swup-home" class="transition-main">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-nav" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white">
                        <i class="ph-fill ph-wallet text-lg"></i>
                    </div>
                    <span class="font-bold text-lg text-gray-900">Personal Finance</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-colors">How It Works</a>
                </div>
                
                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="/login" class="text-sm font-medium text-gray-700 hover:text-teal-600 transition-colors">
                        Sign In
                    </a>
                    <a href="/register" class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ph ph-list text-2xl text-gray-700" x-show="!mobileOpen"></i>
                    <i class="ph ph-x text-2xl text-gray-700" x-show="mobileOpen" style="display: none;"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden bg-white border-t border-gray-100 shadow-lg" style="display: none;">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">Features</a>
                <a href="#how-it-works" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">How It Works</a>
                <hr class="my-3 border-gray-100">
                <a href="/login" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">Sign In</a>
                <a href="/register" class="block px-4 py-3 bg-teal-600 text-white text-center rounded-lg font-medium hover:bg-teal-700">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 lg:pt-48 lg:pb-32 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight mb-6">
                Master Your Money.
                <br class="hidden sm:block" />
                <span class="text-teal-300">Simple & Free.</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light">
                Track income, expenses, and multiple wallets in one clean interface. No clutter, just clarity.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-white text-teal-900 font-semibold rounded-lg hover:bg-teal-50 transition-colors">
                    <span>Start Tracking</span>
                    <i class="ph ph-arrow-right"></i>
                </a>
                <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-teal-800/50 text-white font-semibold rounded-lg hover:bg-teal-800/70 transition-colors border border-teal-700">
                    <span>Learn More</span>
                </a>
            </div>

            <!-- Simple Stats/Social Proof -->
            <div class="mt-16 pt-8 border-t border-white/10 max-w-4xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Free</div>
                        <div class="text-sm text-teal-200">Forever</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Secure</div>
                        <div class="text-sm text-teal-200">Encrypted Data</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Simple</div>
                        <div class="text-sm text-teal-200">Easy to Use</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-white mb-1">Mobile</div>
                        <div class="text-sm text-teal-200">Responsive</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything You Need</h2>
                <p class="text-gray-600">Essential tools to keep your finances in check.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph-fill ph-list-checks text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Transaction Tracking</h3>
                    <p class="text-sm text-gray-600">Record every penny. Categorize and add notes easily.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph-fill ph-wallet text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Multiple Wallets</h3>
                    <p class="text-sm text-gray-600">Manage cash, bank, and e-wallets in one place.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph-fill ph-chart-pie-slice text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Visual Insight</h3>
                    <p class="text-sm text-gray-600">See where your money goes with simple charts.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Get Started in Minutes</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-5xl font-bold text-teal-100 mb-4">1</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Sign Up</h3>
                    <p class="text-sm text-gray-600">Create a free account. No credit card required.</p>
                </div>
                <div>
                    <div class="text-5xl font-bold text-teal-100 mb-4">2</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Add Wallets</h3>
                    <p class="text-sm text-gray-600">Set up your accounts and categories.</p>
                </div>
                <div>
                    <div class="text-5xl font-bold text-teal-100 mb-4">3</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Track</h3>
                    <p class="text-sm text-gray-600">Log transactions and watch your savings grow.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white">
                    <i class="ph-fill ph-wallet text-lg"></i>
                </div>
                <span class="font-bold text-gray-900">Personal Finance</span>
            </div>
            
            <div class="text-sm text-gray-500">
                &copy; <?= date('Y') ?> Personal Finance. All rights reserved.
            </div>
            
            <div class="flex gap-4">
                <a href="#" class="text-gray-400 hover:text-teal-600 transition-colors"><i class="ph-fill ph-github-logo text-xl"></i></a>
                <a href="#" class="text-gray-400 hover:text-teal-600 transition-colors"><i class="ph-fill ph-twitter-logo text-xl"></i></a>
            </div>
        </div>
    </footer>

    </div><!-- End Transition Container -->

    <!-- Initialize Swup -->
    <script>
        const swup = new Swup({
            containers: ['body'],
            animationSelector: '[class*="transition-"]',
            cache: true,
            plugins: [
                new SwupHeadPlugin(),
                new SwupPreloadPlugin()
            ]
        });

        swup.hooks.on('page:view', () => {
            if (typeof Alpine !== 'undefined') {
                document.querySelectorAll('[x-data]').forEach(el => {
                    if (!el._x_dataStack) {
                        Alpine.initTree(el);
                    }
                });
            }
        });
    </script>
</body>
</html>
