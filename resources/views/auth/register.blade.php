<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
</head>
<body class="bg-blue-600 safe-top safe-bottom">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo and app name -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white text-blue-600 mb-4 shadow-lg">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h1 class="text-white text-3xl font-bold">{{ config('app.name', 'Mobile POS') }}</h1>
                <p class="text-blue-200 mt-2">Create a new account</p>
            </div>
            
            <!-- Register form -->
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                @if ($errors->any())
                    <div class="bg-red-50 px-4 py-3 border-b border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-600">
                                    {{ $errors->first() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Your name">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Email address">
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Password">
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Confirm your password">
                    </div>
                    
                    <button type="submit" class="w-full flex justify-center py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Register
                    </button>
                </form>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
            
            <p class="mt-8 text-center text-xs text-blue-200">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>