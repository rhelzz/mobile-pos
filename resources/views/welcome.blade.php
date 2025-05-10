<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} - Mobile POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>
</head>
<body class="bg-blue-600 text-white min-h-screen safe-top safe-bottom flex flex-col">
    <main class="flex-grow flex flex-col items-center justify-center p-6 text-center">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white text-blue-600 mb-6 shadow-lg">
            <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold mb-2">{{ config('app.name', 'Mobile POS') }}</h1>
        <p class="text-blue-200 text-lg mb-8">Point of Sale System for Mobile</p>
        
        <div class="w-full max-w-xs space-y-4">
            @auth
                <a href="{{ route('dashboard') }}" class="block w-full py-3 px-4 bg-white text-blue-600 font-medium rounded-lg shadow-lg hover:bg-blue-50 transition">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="block w-full py-3 px-4 bg-white text-blue-600 font-medium rounded-lg shadow-lg hover:bg-blue-50 transition">
                    Sign In
                </a>
                
                <a href="{{ route('register') }}" class="block w-full py-3 px-4 bg-blue-500 text-white font-medium rounded-lg shadow-lg hover:bg-blue-500 transition">
                    Register
                </a>
            @endauth
        </div>
    </main>
    
    <footer class="text-center p-6">
        <p class="text-blue-200 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'Mobile POS') }}. All rights reserved.
        </p>
        <p class="text-blue-200 text-xs mt-1">
            Version 1.0.0
        </p>
    </footer>
</body>
</html>