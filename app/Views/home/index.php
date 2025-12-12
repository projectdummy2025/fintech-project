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
            background: linear-gradient(135deg, #0f172a 0%, #0f766e 50%, #14b8a6 100%);
            position: relative;
            overflow: hidden;
        }
        
        /* Animated gradient background */
        .hero-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.3) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .glass-nav.scrolled {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Feature cards with depth */
        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .feature-card:hover::before {
            left: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Animated buttons */
        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3);
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Fade in animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        
        /* Stats counter animation */
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: scale(1.05);
        }
        
        /* Scroll smooth */
        .scroll-smooth {
            scroll-behavior: smooth;
        }
        
        /* Pulse animation for CTA */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(13, 148, 136, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(13, 148, 136, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(13, 148, 136, 0);
            }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s infinite;
        }

        /* Tab styles */
        .tab-button {
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: white;
        }

        /* Accordion styles */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .accordion-content.active {
            max-height: 500px;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 scroll-smooth bg-gray-50">
    
    <!-- Main Transition Container -->
    <div id="swup-home" class="transition-main">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-nav" 
         x-data="{ mobileOpen: false, scrolled: false }" 
         @scroll.window="scrolled = window.pageYOffset > 20"
         :class="{ 'scrolled': scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <i class="ph-fill ph-wallet text-lg"></i>
                    </div>
                    <span class="font-bold text-lg text-gray-900 transition-colors group-hover:text-teal-600">Personal Finance</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-105">Features</a>
                    <a href="#benefits" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-105">Benefits</a>
                    <a href="#testimonials" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-105">Testimonials</a>
                    <a href="#faq" class="text-sm font-medium text-gray-600 hover:text-teal-600 transition-all duration-300 hover:scale-105">FAQ</a>
                </div>
                
                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <a href="/login" class="text-sm font-medium text-gray-700 hover:text-teal-600 transition-all duration-300">
                        Sign In
                    </a>
                    <a href="/register" class="btn-primary px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-all duration-300 relative z-10">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-all duration-300">
                    <i class="ph ph-list text-2xl text-gray-700 transition-transform duration-300" 
                       x-show="!mobileOpen"
                       :class="{ 'rotate-90': mobileOpen }"></i>
                    <i class="ph ph-x text-2xl text-gray-700 transition-transform duration-300" 
                       x-show="mobileOpen" 
                       style="display: none;"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 -translate-y-4" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden bg-white border-t border-gray-100 shadow-lg" 
             style="display: none;">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300 hover:translate-x-2">Features</a>
                <a href="#benefits" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300 hover:translate-x-2">Benefits</a>
                <a href="#testimonials" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300 hover:translate-x-2">Testimonials</a>
                <a href="#faq" @click="mobileOpen = false" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300 hover:translate-x-2">FAQ</a>
                <hr class="my-3 border-gray-100">
                <a href="/login" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300">Sign In</a>
                <a href="/register" class="block px-4 py-3 bg-teal-600 text-white text-center rounded-lg font-medium hover:bg-teal-700 transition-all duration-300">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20 lg:pt-48 lg:pb-32 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight mb-6 fade-in-up">
                Master Your Money.
                <br class="hidden sm:block" />
                <span class="text-teal-300">Simple & Free.</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light fade-in-up delay-100">
                Track income, expenses, and multiple wallets in one clean interface. No clutter, just clarity.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center fade-in-up delay-200">
                <a href="/register" class="btn-primary pulse-ring inline-flex items-center justify-center gap-2 px-8 py-3 bg-white text-teal-900 font-semibold rounded-lg hover:bg-teal-50 transition-all duration-300 relative z-10">
                    <span>Start Tracking</span>
                    <i class="ph ph-arrow-right transition-transform duration-300 group-hover:translate-x-1"></i>
                </a>
                <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-teal-800/50 text-white font-semibold rounded-lg hover:bg-teal-800/70 transition-all duration-300 border border-teal-700 hover:border-teal-500 hover:scale-105">
                    <span>Learn More</span>
                </a>
            </div>

            <!-- Simple Stats/Social Proof -->
            <div class="mt-16 pt-8 border-t border-white/10 max-w-4xl mx-auto fade-in-up delay-300">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="stat-card cursor-default">
                        <div class="text-3xl font-bold text-white mb-1">Free</div>
                        <div class="text-sm text-teal-200">Forever</div>
                    </div>
                    <div class="stat-card cursor-default">
                        <div class="text-3xl font-bold text-white mb-1">Secure</div>
                        <div class="text-sm text-teal-200">Encrypted Data</div>
                    </div>
                    <div class="stat-card cursor-default">
                        <div class="text-3xl font-bold text-white mb-1">Simple</div>
                        <div class="text-sm text-teal-200">Easy to Use</div>
                    </div>
                    <div class="stat-card cursor-default">
                        <div class="text-3xl font-bold text-white mb-1">Mobile</div>
                        <div class="text-sm text-teal-200">Responsive</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating decoration -->
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-teal-400/10 rounded-full blur-3xl float-animation hidden lg:block"></div>
        <div class="absolute top-20 left-10 w-40 h-40 bg-blue-400/10 rounded-full blur-3xl float-animation hidden lg:block" style="animation-delay: -3s;"></div>
    </section>

    <!-- Features Section with Tabs -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-4 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-semibold mb-4">FEATURES</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Powerful Tools for Complete Financial Control</h2>
                <p class="text-gray-600">Everything you need to manage your personal finances effectively, all in one place.</p>
            </div>
            
            <!-- Feature Tabs -->
            <div class="mb-12" x-data="{ activeTab: 'tracking' }">
                <div class="flex flex-wrap justify-center gap-3 mb-8">
                    <button @click="activeTab = 'tracking'" 
                            :class="activeTab === 'tracking' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="tab-button px-6 py-3 rounded-lg font-medium">
                        <i class="ph-fill ph-list-checks mr-2"></i>Transaction Tracking
                    </button>
                    <button @click="activeTab = 'wallets'" 
                            :class="activeTab === 'wallets' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="tab-button px-6 py-3 rounded-lg font-medium">
                        <i class="ph-fill ph-wallet mr-2"></i>Multi-Wallet
                    </button>
                    <button @click="activeTab = 'reports'" 
                            :class="activeTab === 'reports' ? 'active' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="tab-button px-6 py-3 rounded-lg font-medium">
                        <i class="ph-fill ph-chart-line mr-2"></i>Reports & Analytics
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <!-- Tracking Tab -->
                    <div x-show="activeTab === 'tracking'" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Transaction Management</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Quick Entry</strong>
                                        <p class="text-sm text-gray-600">Record transactions in seconds with intuitive forms</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Custom Categories</strong>
                                        <p class="text-sm text-gray-600">Organize spending with personalized categories</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Notes & Tags</strong>
                                        <p class="text-sm text-gray-600">Add context to every transaction for better tracking</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Search & Filter</strong>
                                        <p class="text-sm text-gray-600">Find any transaction instantly with powerful filters</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-lg">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                                            <i class="ph-fill ph-arrow-down-left"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Salary</div>
                                            <div class="text-xs text-gray-500">Income • Cash</div>
                                        </div>
                                    </div>
                                    <div class="text-emerald-600 font-bold font-mono">+Rp 8,500,000</div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center text-white">
                                            <i class="ph-fill ph-shopping-cart"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Groceries</div>
                                            <div class="text-xs text-gray-500">Food • E-Wallet</div>
                                        </div>
                                    </div>
                                    <div class="text-red-600 font-bold font-mono">-Rp 850,000</div>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                            <i class="ph-fill ph-gas-pump"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Fuel</div>
                                            <div class="text-xs text-gray-500">Transport • Bank</div>
                                        </div>
                                    </div>
                                    <div class="text-red-600 font-bold font-mono">-Rp 300,000</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wallets Tab -->
                    <div x-show="activeTab === 'wallets'" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Manage Multiple Wallets</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Unlimited Wallets</strong>
                                        <p class="text-sm text-gray-600">Create separate wallets for cash, bank accounts, and e-wallets</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Real-time Balance</strong>
                                        <p class="text-sm text-gray-600">See your total balance across all wallets instantly</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Transfer Between Wallets</strong>
                                        <p class="text-sm text-gray-600">Move money between accounts with ease</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Custom Icons & Colors</strong>
                                        <p class="text-sm text-gray-600">Personalize each wallet for quick identification</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
                                <i class="ph-fill ph-bank text-3xl mb-3 opacity-80"></i>
                                <div class="text-sm opacity-90 mb-1">BCA Account</div>
                                <div class="text-2xl font-bold font-mono">Rp 12.5M</div>
                            </div>
                            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-5 text-white shadow-lg">
                                <i class="ph-fill ph-money text-3xl mb-3 opacity-80"></i>
                                <div class="text-sm opacity-90 mb-1">Cash</div>
                                <div class="text-2xl font-bold font-mono">Rp 2.3M</div>
                            </div>
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
                                <i class="ph-fill ph-device-mobile text-3xl mb-3 opacity-80"></i>
                                <div class="text-sm opacity-90 mb-1">GoPay</div>
                                <div class="text-2xl font-bold font-mono">Rp 850K</div>
                            </div>
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-5 text-white shadow-lg">
                                <i class="ph-fill ph-credit-card text-3xl mb-3 opacity-80"></i>
                                <div class="text-sm opacity-90 mb-1">OVO</div>
                                <div class="text-2xl font-bold font-mono">Rp 1.2M</div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div x-show="activeTab === 'reports'" x-transition class="grid md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Insightful Analytics</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Visual Charts</strong>
                                        <p class="text-sm text-gray-600">Beautiful graphs showing income vs expenses trends</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Category Breakdown</strong>
                                        <p class="text-sm text-gray-600">See exactly where your money goes by category</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Monthly Reports</strong>
                                        <p class="text-sm text-gray-600">Track your financial progress month over month</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="ph-fill ph-check-circle text-teal-600 text-xl mt-1"></i>
                                    <div>
                                        <strong class="text-gray-900">Export to CSV</strong>
                                        <p class="text-sm text-gray-600">Download your data for further analysis</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-xl p-6 shadow-lg">
                            <div class="mb-4">
                                <div class="text-sm text-gray-500 mb-2">Monthly Overview</div>
                                <div class="flex items-end justify-between h-32 gap-2">
                                    <div class="flex-1 flex flex-col justify-end gap-1">
                                        <div class="bg-emerald-400 rounded-t h-16 relative group cursor-pointer hover:bg-emerald-500 transition-colors"></div>
                                        <div class="bg-red-400 rounded-t h-10 relative group cursor-pointer hover:bg-red-500 transition-colors"></div>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-end gap-1">
                                        <div class="bg-emerald-400 rounded-t h-20 relative group cursor-pointer hover:bg-emerald-500 transition-colors"></div>
                                        <div class="bg-red-400 rounded-t h-8 relative group cursor-pointer hover:bg-red-500 transition-colors"></div>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-end gap-1">
                                        <div class="bg-emerald-400 rounded-t h-14 relative group cursor-pointer hover:bg-emerald-500 transition-colors"></div>
                                        <div class="bg-red-400 rounded-t h-9 relative group cursor-pointer hover:bg-red-500 transition-colors"></div>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-end gap-1">
                                        <div class="bg-emerald-400 rounded-t h-24 relative group cursor-pointer hover:bg-emerald-500 transition-colors"></div>
                                        <div class="bg-red-400 rounded-t h-7 relative group cursor-pointer hover:bg-red-500 transition-colors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 pt-4 border-t">
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Total Income</div>
                                    <div class="text-lg font-bold text-emerald-600 font-mono">Rp 8.5M</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xs text-gray-500 mb-1">Total Expenses</div>
                                    <div class="text-lg font-bold text-red-600 font-mono">Rp 4.2M</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-4 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold mb-4">WHY CHOOSE US</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Built for Your Financial Success</h2>
                <p class="text-gray-600">Join thousands who have transformed their financial habits with our platform.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="text-center p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-4xl font-bold text-teal-600 mb-2">100%</div>
                    <div class="text-sm font-semibold text-gray-900 mb-1">Free Forever</div>
                    <div class="text-xs text-gray-600">No hidden fees or premium tiers</div>
                </div>
                <div class="text-center p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-4xl font-bold text-teal-600 mb-2">5K+</div>
                    <div class="text-sm font-semibold text-gray-900 mb-1">Active Users</div>
                    <div class="text-xs text-gray-600">Trusted by thousands daily</div>
                </div>
                <div class="text-center p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-4xl font-bold text-teal-600 mb-2">99.9%</div>
                    <div class="text-sm font-semibold text-gray-900 mb-1">Uptime</div>
                    <div class="text-xs text-gray-600">Always available when you need it</div>
                </div>
                <div class="text-center p-6 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-4xl font-bold text-teal-600 mb-2">4.9★</div>
                    <div class="text-sm font-semibold text-gray-900 mb-1">User Rating</div>
                    <div class="text-xs text-gray-600">Highly rated by our community</div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="feature-card p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="feature-icon w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-lg flex items-center justify-center mb-4 shadow-lg shadow-green-500/30">
                        <i class="ph-fill ph-shield-check text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Bank-Level Security</h3>
                    <p class="text-sm text-gray-600">Your financial data is encrypted and protected with industry-standard security protocols.</p>
                </div>
                <div class="feature-card p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="feature-icon w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-lg flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30">
                        <i class="ph-fill ph-devices text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Cross-Platform</h3>
                    <p class="text-sm text-gray-600">Access your finances from any device - desktop, tablet, or mobile. Fully responsive design.</p>
                </div>
                <div class="feature-card p-6 bg-white rounded-xl border border-gray-100 shadow-sm">
                    <div class="feature-icon w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-lg flex items-center justify-center mb-4 shadow-lg shadow-orange-500/30">
                        <i class="ph-fill ph-lightning text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Lightning Fast</h3>
                    <p class="text-sm text-gray-600">Optimized performance ensures smooth experience even with thousands of transactions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-4 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold mb-4">TESTIMONIALS</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Loved by Users Worldwide</h2>
                <p class="text-gray-600">See what our community has to say about their experience.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Finally, a finance app that doesn't overwhelm me! I've saved Rp 3 juta more per month just by seeing where my money actually goes."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">AR</div>
                        <div>
                            <div class="font-semibold text-gray-900">Andi Rahmansyah</div>
                            <div class="text-sm text-gray-500">Software Developer</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"As a freelancer, tracking income from multiple clients was chaos. This app made it so easy. The export feature is a lifesaver for tax season!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">SP</div>
                        <div>
                            <div class="font-semibold text-gray-900">Sari Putri</div>
                            <div class="text-sm text-gray-500">Freelance Designer</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-1 text-amber-400 mb-4">
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                        <i class="ph-fill ph-star"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Me and my wife use this to manage our household budget. The charts help us discuss finances without arguing about numbers!"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold">BW</div>
                        <div>
                            <div class="font-semibold text-gray-900">Budi Wicaksono</div>
                            <div class="text-sm text-gray-500">Marketing Manager</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">FAQ</span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-600">Everything you need to know about Personal Finance.</p>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Is Personal Finance really free?</span>
                        <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 1 }"></i>
                    </button>
                    <div x-show="openFaq === 1" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 text-sm">Yes! Personal Finance is 100% free forever. There are no hidden fees, premium tiers, or subscription plans. We believe everyone should have access to quality financial management tools.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Is my financial data secure?</span>
                        <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 2 }"></i>
                    </button>
                    <div x-show="openFaq === 2" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 text-sm">Absolutely. We use bank-level encryption to protect your data. Your information is stored securely and we never share it with third parties. You can also export and delete your data at any time.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Can I use it on my phone?</span>
                        <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 3 }"></i>
                    </button>
                    <div x-show="openFaq === 3" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 text-sm">Yes! Personal Finance is fully responsive and works perfectly on all devices - smartphones, tablets, and desktop computers. Access your finances anywhere, anytime.</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">How do I get started?</span>
                        <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 4 }"></i>
                    </button>
                    <div x-show="openFaq === 4" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 text-sm">Simply click "Get Started" to create your free account. Set up your wallets and categories, then start tracking your transactions. The whole process takes less than 5 minutes!</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-gray-900">Can I export my data?</span>
                        <i class="ph-bold ph-caret-down text-gray-400 transition-transform" :class="{ 'rotate-180': openFaq === 5 }"></i>
                    </button>
                    <div x-show="openFaq === 5" x-transition class="px-6 pb-4">
                        <p class="text-gray-600 text-sm">Yes! You can export all your transaction data to CSV format anytime. This is perfect for creating backups, tax preparation, or further analysis in spreadsheet software.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 bg-gradient-to-br from-teal-600 to-teal-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6">
                Ready to Transform Your Financial Life?
            </h2>
            <p class="text-xl text-teal-100 mb-10 max-w-2xl mx-auto">
                Join thousands of users who have taken control of their finances. Start your journey today - it's free forever.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="btn-primary inline-flex items-center justify-center gap-2 px-10 py-4 bg-white text-teal-700 font-bold rounded-xl hover:bg-gray-100 transition-all shadow-xl hover:-translate-y-1 relative z-10">
                    <span>Create Free Account</span>
                    <i class="ph ph-arrow-right text-xl"></i>
                </a>
                <a href="/login" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-teal-500/30 text-white font-semibold rounded-xl hover:bg-teal-500/50 transition-all border border-white/30 hover:border-white/50">
                    <span>I Already Have an Account</span>
                </a>
            </div>
            <p class="mt-8 text-sm text-teal-200">
                <i class="ph-fill ph-check-circle mr-1"></i>
                No credit card required • 
                <i class="ph-fill ph-check-circle mr-1 ml-2"></i>
                Free forever • 
                <i class="ph-fill ph-check-circle mr-1 ml-2"></i>
                Cancel anytime
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4 group">
                        <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                            <i class="ph-fill ph-wallet text-lg"></i>
                        </div>
                        <span class="font-bold text-lg text-white">Personal Finance</span>
                    </div>
                    <p class="text-sm max-w-sm mb-4">
                        A simple, powerful personal finance tracker to help you understand and manage your money better.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-teal-600 transition-all hover:scale-110">
                            <i class="ph-fill ph-github-logo text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-teal-600 transition-all hover:scale-110">
                            <i class="ph-fill ph-twitter-logo text-lg"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-teal-600 transition-all hover:scale-110">
                            <i class="ph-fill ph-instagram-logo text-lg"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Product Links -->
                <div>
                    <h4 class="font-semibold text-white mb-4">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-teal-400 transition-colors">Features</a></li>
                        <li><a href="#benefits" class="hover:text-teal-400 transition-colors">Benefits</a></li>
                        <li><a href="#testimonials" class="hover:text-teal-400 transition-colors">Testimonials</a></li>
                        <li><a href="#faq" class="hover:text-teal-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <!-- Account Links -->
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
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-teal-400 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-teal-400 transition-colors">Terms of Service</a>
                </div>
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
