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
<body class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-700 rounded-2xl mb-4">
                    <i class="ph-fill ph-wallet text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                <p class="text-gray-500">Login to manage your finances</p>
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

                <form method="POST" action="/login" class="space-y-5">
                    <?= Csrf::field() ?>

                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">
                            Username or Email
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required 
                            autofocus
                            class="input-custom"
                            placeholder="Enter your username or email"
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
                                class="input-custom pr-12"
                                placeholder="Enter your password"
                            >
                            <button 
                                type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition p-1"
                                title="Toggle password visibility"
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
                        Login
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="/register" class="text-teal-700 hover:text-teal-800 font-semibold hover:underline">
                            Register
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
