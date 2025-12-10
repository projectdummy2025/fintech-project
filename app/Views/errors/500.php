<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-2xl">
        <div class="mb-8">
            <i class="ph-fill ph-warning-circle text-red-600" style="font-size: 120px;"></i>
        </div>
        <h1 class="text-9xl font-bold text-red-100 mb-4">500</h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Internal Server Error</h2>
        <p class="text-gray-600 mb-8 text-lg">
            Oops! Something went wrong on our server. We're working to fix it.
        </p>
        <div class="flex justify-center gap-4">
            <a href="/" class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-lg shadow-teal-600/30">
                <i class="ph-bold ph-house"></i>
                Go Home
            </a>
            <button onclick="location.reload()" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition border border-gray-200">
                <i class="ph-bold ph-arrow-clockwise"></i>
                Try Again
            </button>
        </div>
    </div>
</body>
</html>
