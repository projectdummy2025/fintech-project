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
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 50%, #99f6e4 100%);
        }
        
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230d9488' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.05);
        }
        
        .stat-number {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 scroll-smooth">
    
    <!-- Main Transition Container -->
    <div id="swup-home" class="transition-main">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 group-hover:shadow-teal-500/50 transition-shadow">
                        <i class="ph-fill ph-wallet text-white text-xl"></i>
                    </div>
                    <span class="font-bold text-xl text-gray-900">Personal Finance</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-600 hover:text-teal-600 font-medium transition-colors">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-teal-600 font-medium transition-colors">How It Works</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-teal-600 font-medium transition-colors">Testimonials</a>
                </div>
                
                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="/login" class="px-4 py-2 text-gray-700 font-medium hover:text-teal-600 transition-colors">
                        Sign In
                    </a>
                    <a href="/register" class="px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/30 hover:shadow-teal-600/50">
                        Get Started Free
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
                <a href="#testimonials" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">Testimonials</a>
                <hr class="my-3">
                <a href="/login" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium">Sign In</a>
                <a href="/register" class="block px-4 py-3 bg-teal-600 text-white text-center rounded-lg font-semibold hover:bg-teal-700">Get Started Free</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient hero-pattern pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur rounded-full text-sm font-medium text-teal-700 mb-6 shadow-sm">
                        <i class="ph-fill ph-sparkle text-amber-500"></i>
                        Free forever for personal use
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                        Take Control of
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">Your Money</span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-gray-600 mb-8 max-w-xl mx-auto lg:mx-0">
                        Track your income and expenses, manage multiple wallets, and visualize your financial health — all in one simple, beautiful app.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="/register" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition-all shadow-xl shadow-teal-600/30 hover:shadow-teal-600/50 hover:-translate-y-0.5">
                            <span>Start Tracking Free</span>
                            <i class="ph ph-arrow-right text-xl"></i>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all border border-gray-200 shadow-sm">
                            <i class="ph ph-play-circle text-xl text-teal-600"></i>
                            <span>See How It Works</span>
                        </a>
                    </div>
                    
                    <!-- Social Proof -->
                    <div class="mt-10 pt-8 border-t border-teal-200/50">
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6">
                            <div class="flex -space-x-2">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">JD</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">MK</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-pink-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">AS</div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 border-2 border-white flex items-center justify-center text-white text-xs font-bold">+5K</div>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold text-gray-900">5,000+</span> users trust us with their finances
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Visual -->
                <div class="relative lg:pl-8">
                    <div class="relative z-10 animate-float">
                        <!-- Dashboard Preview Card -->
                        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
                            <!-- Mock Browser Bar -->
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                                <div class="flex-1 mx-4">
                                    <div class="bg-white rounded-lg px-3 py-1.5 text-xs text-gray-400 border border-gray-200">
                                        personalfinance.app/dashboard
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mock Dashboard Content -->
                            <div class="p-6">
                                <!-- Summary Cards -->
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="bg-emerald-50 rounded-lg p-3">
                                        <div class="text-xs text-emerald-600 font-medium mb-1">Income</div>
                                        <div class="text-lg font-bold text-gray-900 font-mono">Rp 8.5M</div>
                                    </div>
                                    <div class="bg-red-50 rounded-lg p-3">
                                        <div class="text-xs text-red-600 font-medium mb-1">Expenses</div>
                                        <div class="text-lg font-bold text-gray-900 font-mono">Rp 4.2M</div>
                                    </div>
                                    <div class="bg-teal-50 rounded-lg p-3">
                                        <div class="text-xs text-teal-600 font-medium mb-1">Savings</div>
                                        <div class="text-lg font-bold text-gray-900 font-mono">Rp 4.3M</div>
                                    </div>
                                </div>
                                
                                <!-- Mock Chart -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="flex items-end justify-between h-24 gap-2">
                                        <div class="flex-1 flex flex-col justify-end gap-1">
                                            <div class="bg-emerald-400 rounded-t h-12"></div>
                                            <div class="bg-red-400 rounded-t h-8"></div>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-end gap-1">
                                            <div class="bg-emerald-400 rounded-t h-16"></div>
                                            <div class="bg-red-400 rounded-t h-6"></div>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-end gap-1">
                                            <div class="bg-emerald-400 rounded-t h-10"></div>
                                            <div class="bg-red-400 rounded-t h-7"></div>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-end gap-1">
                                            <div class="bg-emerald-400 rounded-t h-20"></div>
                                            <div class="bg-red-400 rounded-t h-5"></div>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-end gap-1">
                                            <div class="bg-emerald-400 rounded-t h-14"></div>
                                            <div class="bg-red-400 rounded-t h-9"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Mock Transactions -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                                <i class="ph-fill ph-arrow-down-left text-emerald-600"></i>
                                            </div>
                                            <span class="text-sm font-medium">Salary</span>
                                        </div>
                                        <span class="text-sm font-bold text-emerald-600 font-mono">+Rp 8.5M</span>
                                    </div>
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="ph-fill ph-shopping-cart text-red-600"></i>
                                            </div>
                                            <span class="text-sm font-medium">Groceries</span>
                                        </div>
                                        <span class="text-sm font-bold text-red-600 font-mono">-Rp 850K</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-8 -right-8 w-64 h-64 bg-teal-200 rounded-full opacity-30 blur-3xl"></div>
                    <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-emerald-200 rounded-full opacity-40 blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-extrabold stat-number mb-1">5K+</div>
                    <div class="text-sm text-gray-500 font-medium">Active Users</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-extrabold stat-number mb-1">50K+</div>
                    <div class="text-sm text-gray-500 font-medium">Transactions Tracked</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-extrabold stat-number mb-1">99.9%</div>
                    <div class="text-sm text-gray-500 font-medium">Uptime</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl sm:text-4xl font-extrabold stat-number mb-1">4.9★</div>
                    <div class="text-sm text-gray-500 font-medium">User Rating</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-100 rounded-full text-sm font-semibold text-teal-700 mb-4">
                    <i class="ph-fill ph-star-four"></i>
                    Features
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
                    Everything You Need to Master Your Finances
                </h2>
                <p class="text-lg text-gray-600">
                    Simple yet powerful tools designed to help you understand where your money goes and how to save more.
                </p>
            </div>
            
            <!-- Features Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-teal-500/30">
                        <i class="ph-fill ph-list-checks text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Transaction Tracking</h3>
                    <p class="text-gray-600">
                        Easily record every income and expense. Categorize transactions and add notes for better context.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/30">
                        <i class="ph-fill ph-wallet text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Multiple Wallets</h3>
                    <p class="text-gray-600">
                        Manage cash, bank accounts, and e-wallets separately. See individual and combined balances instantly.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-amber-500/30">
                        <i class="ph-fill ph-chart-pie-slice text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Visual Reports</h3>
                    <p class="text-gray-600">
                        Beautiful charts showing spending patterns, income trends, and category breakdowns at a glance.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/30">
                        <i class="ph-fill ph-tag text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Custom Categories</h3>
                    <p class="text-gray-600">
                        Create your own income and expense categories. Organize finances the way that makes sense to you.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-emerald-500/30">
                        <i class="ph-fill ph-file-csv text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Export to CSV</h3>
                    <p class="text-gray-600">
                        Download your transaction data anytime. Perfect for backup or further analysis in spreadsheets.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/30">
                        <i class="ph-fill ph-shield-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Private</h3>
                    <p class="text-gray-600">
                        Your data is encrypted and protected. We never share your financial information with third parties.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-100 rounded-full text-sm font-semibold text-teal-700 mb-4">
                    <i class="ph-fill ph-path"></i>
                    How It Works
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
                    Start in 3 Simple Steps
                </h2>
                <p class="text-lg text-gray-600">
                    Getting started takes less than a minute. No complicated setup required.
                </p>
            </div>
            
            <!-- Steps -->
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-teal-500/30">
                        <span class="text-3xl font-extrabold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Account</h3>
                    <p class="text-gray-600">
                        Sign up with just a username and password. Free forever, no credit card needed.
                    </p>
                    <!-- Connector Line (hidden on mobile) -->
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-gradient-to-r from-teal-300 to-transparent"></div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-purple-500/30">
                        <span class="text-3xl font-extrabold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Add Your Wallets</h3>
                    <p class="text-gray-600">
                        Set up your cash, bank accounts, or e-wallets. Customize categories to match your lifestyle.
                    </p>
                    <!-- Connector Line (hidden on mobile) -->
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-gradient-to-r from-purple-300 to-transparent"></div>
                </div>
                
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30">
                        <span class="text-3xl font-extrabold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Track & Thrive</h3>
                    <p class="text-gray-600">
                        Record transactions daily. Watch insights appear on your dashboard and make smarter decisions.
                    </p>
                </div>
            </div>
            
            <!-- CTA -->
            <div class="text-center mt-12">
                <a href="/register" class="inline-flex items-center gap-2 px-8 py-4 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition-all shadow-xl shadow-teal-600/30 hover:shadow-teal-600/50">
                    <span>Get Started Now</span>
                    <i class="ph ph-arrow-right text-xl"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 lg:py-28 bg-gradient-to-br from-gray-900 to-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur rounded-full text-sm font-semibold text-teal-300 mb-4">
                    <i class="ph-fill ph-chat-circle-text"></i>
                    Testimonials
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">
                    Loved by Thousands
                </h2>
                <p class="text-lg text-gray-300">
                    See what our users say about transforming their financial habits.
                </p>
            </div>
            
            <!-- Testimonials Grid -->
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6">
                        "Finally, a finance app that doesn't overwhelm me! I've saved Rp 3 juta more per month just by seeing where my money actually goes."
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">AR</div>
                        <div>
                            <div class="font-semibold">Andi Rahmansyah</div>
                            <div class="text-sm text-gray-400">Software Developer</div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6">
                        "As a freelancer, tracking income from multiple clients was chaos. This app made it so easy. The export feature is a lifesaver for tax season!"
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">SP</div>
                        <div>
                            <div class="font-semibold">Sari Putri</div>
                            <div class="text-sm text-gray-400">Freelance Designer</div>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-300 mb-6">
                        "Me and my wife use this to manage our household budget. The charts help us discuss finances without arguing about numbers!"
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold">BW</div>
                        <div>
                            <div class="font-semibold">Budi Wicaksono</div>
                            <div class="text-sm text-gray-400">Marketing Manager</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 lg:py-28 bg-gradient-to-br from-teal-600 to-teal-700 text-white overflow-hidden relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 hero-pattern"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-6">
                Ready to Take Control of Your Finances?
            </h2>
            <p class="text-xl text-teal-100 mb-10 max-w-2xl mx-auto">
                Join thousands of users who have transformed their relationship with money. It's free, simple, and takes less than a minute to start.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-white text-teal-700 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-xl hover:-translate-y-0.5">
                    <span>Create Free Account</span>
                    <i class="ph ph-arrow-right text-xl"></i>
                </a>
                <a href="/login" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-teal-500/30 text-white font-semibold rounded-xl hover:bg-teal-500/50 transition-all border border-white/30">
                    <span>I Already Have an Account</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <i class="ph-fill ph-wallet text-white text-xl"></i>
                        </div>
                        <span class="font-bold text-xl text-white">Personal Finance</span>
                    </a>
                    <p class="text-sm max-w-sm">
                        A simple, powerful personal finance tracker to help you understand and manage your money better.
                    </p>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-teal-400 transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-teal-400 transition-colors">How It Works</a></li>
                        <li><a href="#testimonials" class="hover:text-teal-400 transition-colors">Testimonials</a></li>
                    </ul>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Account</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/login" class="hover:text-teal-400 transition-colors">Sign In</a></li>
                        <li><a href="/register" class="hover:text-teal-400 transition-colors">Create Account</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm">
                    &copy; <?= date('Y') ?> Personal Finance. All rights reserved.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="ph ph-github-logo text-xl"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="ph ph-twitter-logo text-xl"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                        <i class="ph ph-instagram-logo text-xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    </div><!-- End Transition Container -->

    <!-- Initialize Swup for SPA-like transitions -->
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

        // Re-initialize Alpine.js after page transition
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
