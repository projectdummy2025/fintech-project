<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?> - Personal Finance</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body>
    <div class="auth-container">
        <!-- Hero Section (Left) -->
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="auth-hero-icon">
                    <i class="ph-fill ph-wallet"></i>
                </div>
                <h1 class="auth-hero-title">Personal Finance</h1>
                <p class="auth-hero-text">Take control of your financial future with simple, powerful tracking tools.</p>
                
                <div class="auth-hero-features">
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Track income and expenses effortlessly</span>
                    </div>
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Manage multiple wallets and categories</span>
                    </div>
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Visualize your financial health</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Section (Right) -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2 class="auth-form-title">Welcome Back</h2>
                    <p class="auth-form-subtitle">Login to access your financial dashboard</p>
                </div>
                
                <!-- Alerts -->
                <?php if (!empty($error)): ?>
                    <div class="alert-custom alert-danger mb-6" role="alert" aria-live="polite">
                        <i class="ph-fill ph-warning-circle"></i>
                        <div>
                            <p class="font-medium">Error</p>
                            <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert-custom alert-success mb-6" role="alert" aria-live="polite">
                        <i class="ph-fill ph-check-circle"></i>
                        <div>
                            <p class="font-medium">Success</p>
                            <p class="text-sm"><?= htmlspecialchars($success) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="/login" class="space-y-5">
                    <?= Csrf::field() ?>

                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username or Email
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required 
                            autofocus
                            class="input-custom"
                            placeholder="Enter your username"
                            aria-label="Username or email address"
                            aria-required="true"
                        >
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="input-custom pr-12"
                                placeholder="Enter your password"
                                aria-label="Password"
                                aria-required="true"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 rounded"
                                title="Toggle password visibility"
                                aria-label="Toggle password visibility"
                            >
                                <i id="eye-icon" class="ph ph-eye text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn btn-primary w-full justify-center mt-6"
                    >
                        <i class="ph-bold ph-sign-in"></i>
                        Login to Dashboard
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="/register" class="text-teal-700 hover:text-teal-800 font-semibold hover:underline transition">
                            Create Account
                        </a>
                    </p>
                </div>
                
                <!-- Footer -->
                <p class="text-center text-gray-400 text-xs mt-8">
                    © <?= date('Y') ?> Personal Finance. Minimalist & Professional.
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'ph ph-eye-slash text-xl';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'ph ph-eye text-xl';
            }
        }
    </script>
</body>
</html>
