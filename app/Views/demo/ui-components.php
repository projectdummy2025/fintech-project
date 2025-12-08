<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI Components Demo - Personal Finance</title>
    
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
<body class="bg-gray-50 text-gray-800 font-sans antialiased p-8">
    
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">UI Components Demo</h1>
        
        <!-- Alerts Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Alerts</h2>
            
            <div class="alert-custom alert-success">
                <i class="ph-fill ph-check-circle"></i>
                <div>
                    <p class="font-medium">Success!</p>
                    <p class="text-sm">Your changes have been saved successfully.</p>
                </div>
            </div>
            
            <div class="alert-custom alert-danger">
                <i class="ph-fill ph-warning-circle"></i>
                <div>
                    <p class="font-medium">Error!</p>
                    <p class="text-sm">There was a problem processing your request.</p>
                </div>
            </div>
            
            <div class="alert-custom alert-info">
                <i class="ph-fill ph-info"></i>
                <div>
                    <p class="font-medium">Information</p>
                    <p class="text-sm">This is an informational message for you.</p>
                </div>
            </div>
            
            <div class="alert-custom alert-warning">
                <i class="ph-fill ph-warning"></i>
                <div>
                    <p class="font-medium">Warning!</p>
                    <p class="text-sm">Please review your input before proceeding.</p>
                </div>
            </div>
        </section>
        
        <!-- Loading States Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Loading States</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Spinner -->
                <div class="card-custom p-6">
                    <h3 class="font-bold mb-4">Spinners</h3>
                    <div class="flex items-center gap-4">
                        <div class="spinner-sm"></div>
                        <div class="spinner"></div>
                        <div class="spinner-lg"></div>
                    </div>
                </div>
                
                <!-- Button Loading -->
                <div class="card-custom p-6">
                    <h3 class="font-bold mb-4">Button Loading</h3>
                    <button class="btn btn-primary btn-loading">
                        Save Changes
                    </button>
                </div>
                
                <!-- Loading Overlay -->
                <div class="card-custom p-6 relative" style="min-height: 150px;">
                    <h3 class="font-bold mb-4">Loading Overlay</h3>
                    <p class="text-sm text-gray-500">Content behind overlay</p>
                    <div class="loading-overlay">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Skeleton Screens Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Skeleton Screens</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Skeleton Card -->
                <div class="card-custom p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="skeleton skeleton-avatar"></div>
                        <div class="flex-1">
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-text" style="width: 80%;"></div>
                        </div>
                    </div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text" style="width: 60%;"></div>
                </div>
                
                <!-- Skeleton List -->
                <div class="card-custom p-6">
                    <div class="skeleton skeleton-title mb-4"></div>
                    <div class="space-y-3">
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text"></div>
                        <div class="skeleton skeleton-text" style="width: 70%;"></div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Empty & Error States Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Empty & Error States</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Empty State -->
                <div class="card-custom">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="ph ph-folder-open"></i>
                        </div>
                        <h4 class="empty-state-title">No Items Found</h4>
                        <p class="empty-state-text">You haven't created any items yet. Click the button below to get started.</p>
                        <button class="btn btn-primary mt-4">Create Item</button>
                    </div>
                </div>
                
                <!-- Error State -->
                <div class="card-custom">
                    <div class="error-state">
                        <div class="error-state-icon">
                            <i class="ph ph-warning-circle"></i>
                        </div>
                        <h4 class="error-state-title">Something Went Wrong</h4>
                        <p class="error-state-text">We couldn't load your data. Please try again or contact support if the problem persists.</p>
                        <button class="btn btn-primary mt-4">Try Again</button>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Badges Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Badges</h2>
            
            <div class="card-custom p-6">
                <div class="flex flex-wrap gap-3">
                    <span class="badge badge-success">Success</span>
                    <span class="badge badge-danger">Danger</span>
                    <span class="badge badge-info">Info</span>
                    <span class="badge badge-primary">Primary</span>
                    <span class="badge badge-secondary">Secondary</span>
                </div>
            </div>
        </section>
        
    </div>
    
</body>
</html>
