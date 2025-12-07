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
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/custom.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-700 rounded-2xl mb-4">
                    <i class="ph-fill ph-wallet text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h1>
                <p class="text-gray-500">Start managing your finances today</p>
            </div>

            <!-- Card -->
            <div class="card-custom p-8">
                <?php if (!empty($error)): ?>
                    <div class="alert-custom alert-danger mb-6">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <p class="font-medium text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert-custom alert-success mb-6">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <p class="font-medium text-sm"><?= htmlspecialchars($success) ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/register" class="space-y-4">
                    <?= Csrf::field() ?>

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                            Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required
                            class="input-custom"
                            placeholder="Choose a username"
                        >
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
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

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
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
                                placeholder="Minimum 6 characters"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1"
                                title="Toggle password visibility"
                            >
                                <i id="eye-icon-password" class="ph ph-eye text-xl"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="confirm_password" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
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
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword('confirm_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1"
                                title="Toggle password visibility"
                            >
                                <i id="eye-icon-confirm_password" class="ph ph-eye text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn btn-primary w-full justify-center mt-6"
                    >
                        <i class="ph-bold ph-user-plus"></i>
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="/login" class="text-teal-700 hover:text-teal-800 font-semibold hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-gray-400 text-xs mt-8">
                © <?= date('Y') ?> Personal Finance. Minimalist & Professional.
            </p>
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
    </script>
</body>
</html>
