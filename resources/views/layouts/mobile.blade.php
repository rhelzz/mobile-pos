<!DOCTYPE html>
<html lang="en">
<!-- File: resources/views/layouts/mobile.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    @include('components.meta-tags')
    <title>{{ config('app.name') }} - @yield('title', 'Mobile POS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tambahkan yield untuk bagian head di sini -->
    @yield('head')
    
    <!-- Additional styles -->
    <style>
        [x-cloak] { display: none !important; }
        body {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900">
    <!-- Mobile Container -->
    <div class="min-h-screen flex flex-col max-w-md mx-auto bg-white shadow-lg">
        <!-- Header area -->
        @includeWhen(!isset($hideNav), 'components.mobile-header')
        
        <!-- Main content -->
        <main class="flex-grow p-4">
            @include('components.flash-message')
            @yield('content')
        </main>
        
        <!-- Mobile navigation -->
        @includeWhen(!isset($hideNav), 'components.mobile-nav')
    </div>

    @stack('scripts')
</body>
</html>