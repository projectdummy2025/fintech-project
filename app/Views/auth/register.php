<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Register' ?> - Personal Finance</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter & Roboto Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Swup.js for SPA-like transitions -->
    <script src="https://unpkg.com/swup@4"></script>
    <script src="https://unpkg.com/@swup/head-plugin@2"></script>
    <script src="https://unpkg.com/@swup/preload-plugin@3"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body>
    <div class="auth-container transition-main">
        <!-- Hero Section (Left) -->
        <div class="auth-hero">
            <div class="auth-hero-content">
                <div class="auth-hero-icon">
                    <i class="ph-fill ph-wallet"></i>
                </div>
                <h1 class="auth-hero-title">Join Personal Finance</h1>
                <p class="auth-hero-text">Start your journey to better financial management today.</p>
                
                <div class="auth-hero-features">
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Free forever, no hidden fees</span>
                    </div>
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Secure and private data</span>
                    </div>
                    <div class="auth-hero-feature">
                        <i class="ph-fill ph-check-circle"></i>
                        <span>Get started in under 2 minutes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Section (Right) -->
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="auth-form-header">
                    <h2 class="auth-form-title">Create Account</h2>
                    <p class="auth-form-subtitle">Get started with your financial journey</p>
                </div>
                
                <!-- Alerts -->
                <?php if (!empty($error)): ?>
                    <div class="alert-custom alert-danger mb-6">
                        <i class="ph-fill ph-warning-circle"></i>
                        <div>
                            <p class="font-medium">Error</p>
                            <p class="text-sm"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert-custom alert-success mb-6">
                        <i class="ph-fill ph-check-circle"></i>
                        <div>
                            <p class="font-medium">Success</p>
                            <p class="text-sm"><?= htmlspecialchars($success) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Register Form -->
                <form method="POST" action="/register" class="space-y-4">
                    <?= Csrf::field() ?>

                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            class="input-custom"
                            placeholder="Choose a unique username"
                        >
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="input-custom"
                            placeholder="your@email.com"
                        >
                    </div>

                    <!-- Password Field with Strength Indicator -->
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
                                minlength="6"
                                class="input-custom pr-12"
                                placeholder="Create a strong password"
                                oninput="checkPasswordStrength()"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 rounded"
                                title="Toggle password visibility"
                            >
                                <i id="eye-icon-password" class="ph ph-eye text-xl"></i>
                            </button>
                        </div>
                        
                        <!-- Password Strength Meter -->
                        <div class="password-strength-container">
                            <div class="password-strength-meter" id="strength-meter">
                                <div class="password-strength-bar" id="strength-bar"></div>
                            </div>
                            <p class="password-strength-text" id="strength-text"></p>
                            
                            <!-- Password Criteria -->
                            <div class="password-criteria">
                                <div class="password-criterion" id="criterion-length">
                                    <i class="ph ph-circle"></i>
                                    <span>At least 6 characters</span>
                                </div>
                                <div class="password-criterion" id="criterion-upper">
                                    <i class="ph ph-circle"></i>
                                    <span>Contains uppercase letter</span>
                                </div>
                                <div class="password-criterion" id="criterion-lower">
                                    <i class="ph ph-circle"></i>
                                    <span>Contains lowercase letter</span>
                                </div>
                                <div class="password-criterion" id="criterion-number">
                                    <i class="ph ph-circle"></i>
                                    <span>Contains number</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                required
                                class="input-custom pr-12"
                                placeholder="Re-enter your password"
                                oninput="checkPasswordMatch()"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('confirm_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1 rounded"
                                title="Toggle password visibility"
                            >
                                <i id="eye-icon-confirm_password" class="ph ph-eye text-xl"></i>
                            </button>
                        </div>
                        <p class="text-xs mt-1" id="match-message"></p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn btn-primary w-full justify-center mt-6"
                        id="submit-btn"
                    >
                        <i class="ph-bold ph-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="/login" class="text-teal-700 hover:text-teal-800 font-semibold hover:underline transition">
                            Login
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-icon-' + fieldId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'ph ph-eye-slash text-xl';
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'ph ph-eye text-xl';
            }
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const meter = document.getElementById('strength-meter');
            const text = document.getElementById('strength-text');
            
            // Criteria checks
            const hasLength = password.length >= 6;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            
            // Update criteria UI
            updateCriterion('length', hasLength);
            updateCriterion('upper', hasUpper);
            updateCriterion('lower', hasLower);
            updateCriterion('number', hasNumber);
            
            // Calculate strength
            let strength = 0;
            if (hasLength) strength++;
            if (hasUpper) strength++;
            if (hasLower) strength++;
            if (hasNumber) strength++;
            
            // Update strength meter
            meter.className = 'password-strength-meter';
            if (strength === 0 || strength === 1) {
                meter.classList.add('password-strength-weak');
                text.textContent = 'Weak password';
                text.className = 'password-strength-text';
                meter.classList.add('password-strength-weak');
            } else if (strength === 2 || strength === 3) {
                meter.classList.add('password-strength-medium');
                text.textContent = 'Medium strength';
                text.className = 'password-strength-text';
                meter.classList.add('password-strength-medium');
            } else {
                meter.classList.add('password-strength-strong');
                text.textContent = 'Strong password';
                text.className = 'password-strength-text';
                meter.classList.add('password-strength-strong');
            }
            
            // Also check password match
            checkPasswordMatch();
        }
        
        function updateCriterion(type, met) {
            const criterion = document.getElementById('criterion-' + type);
            const icon = criterion.querySelector('i');
            
            if (met) {
                criterion.classList.add('met');
                icon.className = 'ph-fill ph-check-circle';
            } else {
                criterion.classList.remove('met');
                icon.className = 'ph ph-circle';
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchMessage = document.getElementById('match-message');
            
            if (confirmPassword.length === 0) {
                matchMessage.textContent = '';
                matchMessage.className = 'text-xs mt-1';
                return;
            }
            
            if (password === confirmPassword) {
                matchMessage.textContent = '✓ Passwords match';
                matchMessage.className = 'text-xs mt-1 text-emerald-600 font-medium';
            } else {
                matchMessage.textContent = '✗ Passwords do not match';
                matchMessage.className = 'text-xs mt-1 text-red-600 font-medium';
            }
        }

        // Initialize Swup for SPA-like transitions
        const swup = new Swup({
            containers: ['body'],
            animationSelector: '[class*="transition-"]',
            cache: true,
            plugins: [
                new SwupHeadPlugin(),
                new SwupPreloadPlugin()
            ]
        });
    </script>
</body>
</html>
