<header class="sticky top-0 z-30 safe-top bg-blue-600 text-white shadow-md">
    <div class="px-4 py-3 flex items-center justify-between" x-data="{ isMenuOpen: false }">
        <!-- Page title -->
        <h1 class="text-xl font-bold truncate">@yield('header-title', config('app.name'))</h1>
        
        <!-- Hamburger button -->
        <button @click="isMenuOpen = !isMenuOpen" class="p-2 focus:outline-none focus:text-blue-200">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Overlay -->
        <div x-show="isMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isMenuOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40"
             x-cloak></div>
        
        <!-- Mobile menu -->
        <div x-show="isMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed top-0 right-0 bottom-0 w-64 bg-white shadow-lg z-50 overflow-y-auto safe-top safe-bottom"
             x-cloak>
            
            <div class="p-5 bg-blue-600 text-white flex items-center justify-between">
                <span class="text-lg font-bold">Menu</span>
                <button @click="isMenuOpen = false" class="p-2 focus:outline-none focus:text-blue-200">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- User info -->
            <div class="p-4 border-b">
                <p class="font-semibold">{{ Auth::user()->name ?? 'Guest' }}</p>
                <p class="text-sm text-gray-600">{{ Auth::user()->email ?? '' }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst(Auth::user()->role ?? '') }}</p>
            </div>
            
            <!-- Menu items -->
            <nav class="py-2">
                <a href="{{ route('dashboard') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('cashier') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Cashier
                </a>
                <a href="{{ route('products.index') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Products
                </a>
                <a href="{{ route('categories.index') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Categories
                </a>
                <a href="{{ route('transactions.index') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    Transactions
                </a>
                <a href="{{ route('stock.index') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                    Stock
                </a>
                <a href="{{ route('reports.sales') }}" class="text-black block px-4 py-3 hover:bg-blue-50 flex items-center">
                    <svg class="h-5 w-5 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>
                <hr class="my-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-black block w-full text-left px-4 py-3 hover:bg-blue-50 flex items-center text-red-600">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>
    
    <!-- Optional subheader area -->
    @hasSection('subheader')
        <div class="px-4 py-2 bg-blue-700 flex items-center">
            @yield('subheader')
        </div>
    @endif
</header>