@extends('layouts.mobile')

@section('title', 'Cashier')
@section('header-title', 'Point of Sale')

@section('head')
    <!-- Script untuk Midtrans dengan atribut async dan defer dihilangkan -->
    <script type="text/javascript" 
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
@endsection

@push('styles')
<style>
    /* Hide number input spinners */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
    
    /* Product card hover effect */
    .product-card {
        transition: all 0.2s ease;
        height: 100%;
    }
    .product-card:active {
        transform: scale(0.98);
    }
    
    /* Custom scrollbar for product grid */
    .product-grid::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
    .product-grid::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    
    /* Custom backdrop for modals */
    .modal-backdrop {
        backdrop-filter: blur(2px);
    }
    
    /* Slide up animation for quantity selector */
    @keyframes slideUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .slide-up {
        animation: slideUp 0.2s ease-out;
    }
    
    /* Tax and Discount Badges */
    .tax-badge, .discount-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        line-height: 1;
    }
    
    .tax-badge {
        background-color: #EFF6FF;
        color: #3B82F6;
    }
    
    .discount-badge {
        background-color: #F0FDF4;
        color: #22C55E;
    }
</style>
@endpush

@section('subheader')
<div class="flex items-center justify-between">
    <div class="text-sm text-blue-100">
        <span class="bg-blue-700 bg-opacity-50 px-3 py-1 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
            </svg>
            POS Mode
        </span>
    </div>
    <div class="text-sm text-white">
        {{ now()->format('d M Y') }}
    </div>
</div>
@endsection

@section('content')
<div x-data="cashier()" x-init="initCashier()" x-cloak>
    <!-- Product search and view toggle -->
    <div class="mb-4">
        <div class="relative flex items-center">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="searchQuery" 
                    placeholder="Search products..." 
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div class="ml-2 flex">
                <button @click="viewMode = viewMode === 'grid' ? 'list' : 'grid'" class="p-2 bg-gray-100 rounded-lg">
                    <svg x-show="viewMode === 'grid'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <svg x-show="viewMode === 'list'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Product categories (horizontal scrollable) -->
    <div class="mb-4 -mx-4 px-4 overflow-x-auto">
        <div class="flex items-center space-x-2 pb-2 whitespace-nowrap">
            <button 
                @click="selectedCategory = null" 
                :class="selectedCategory === null ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all focus:outline-none"
            >
                All Items
            </button>
            @foreach($categories ?? [] as $category)
                <button 
                    @click="selectedCategory = {{ $category->id }}" 
                    :class="selectedCategory === {{ $category->id }} ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all focus:outline-none"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- No products found message -->
    <div 
        class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4 text-center"
        x-show="filteredProducts.length === 0"
    >
        <p class="text-yellow-700" x-text="searchQuery.length > 0 ? 'No products match your search' : 'No products available'"></p>
    </div>

    <!-- Product Display Area -->
    <div class="h-[calc(100vh-270px)] overflow-y-auto product-grid pb-20" x-show="!showCart">
        <!-- Grid View -->
        <div 
            class="grid grid-cols-2 sm:grid-cols-3 gap-3" 
            x-show="viewMode === 'grid'"
        >
            <template x-for="product in filteredProducts" :key="product.id">
                <div class="aspect-w-1 aspect-h-1">
                    <div 
                        @click="selectProduct(product)"
                        class="product-card bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col h-full shadow-sm hover:shadow-md cursor-pointer"
                    >
                        <div class="h-32 bg-gray-50 flex items-center justify-center p-2 relative">
                            <template x-if="product.image_path">
                                <img :src="'/storage/' + product.image_path" alt="Product" class="w-full h-full object-cover" 
                                    onerror="this.onerror=null; this.src='/images/no-image.png';">
                            </template>
                            <template x-if="!product.image_path">
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </template>
                            
                            <!-- Stock indicator -->
                            <div class="absolute top-2 right-2 text-xs bg-gray-800 bg-opacity-70 text-white px-1.5 py-0.5 rounded-full" x-text="product.stock"></div>
                        </div>
                        <div class="p-3 flex-grow flex flex-col">
                            <h3 class="font-medium text-sm leading-tight mb-1 line-clamp-2" x-text="product.name"></h3>
                            <div class="mt-auto pt-1">
                                <p class="text-blue-600 font-bold" x-text="formatCurrency(product.price)"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- List View -->
        <div class="space-y-2" x-show="viewMode === 'list'">
            <template x-for="product in filteredProducts" :key="product.id">
                <div 
                    @click="selectProduct(product)"
                    class="product-card bg-white rounded-lg border border-gray-200 overflow-hidden flex shadow-sm hover:shadow-md cursor-pointer"
                >
                    <div class="w-20 h-20 bg-gray-50 flex-shrink-0 flex items-center justify-center relative">
                        <template x-if="product.image_path">
                            <img :src="'/storage/' + product.image_path" alt="Product" class="h-full w-full object-contain p-1" 
                                onerror="this.onerror=null; this.src='/images/no-image.png';">
                        </template>
                        <template x-if="!product.image_path">
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div class="p-3 flex-grow flex flex-col justify-between">
                        <div>
                            <h3 class="font-medium text-sm" x-text="product.name"></h3>
                            <p class="text-xs text-gray-500" x-text="'Stock: ' + product.stock"></p>
                        </div>
                        <p class="text-blue-600 font-bold" x-text="formatCurrency(product.price)"></p>
                    </div>
                    <div class="flex items-center pr-3">
                        <button class="p-2 text-gray-500 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Quantity Selector Modal -->
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop z-50 flex items-end justify-center"
        x-show="showQuantityModal" 
        @click.self="showQuantityModal = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white rounded-t-xl w-full max-w-md slide-up shadow-lg border-t border-gray-200 p-4"
            x-show="showQuantityModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform translate-y-10"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-10"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" x-text="selectedProduct.name"></h3>
                <button @click="showQuantityModal = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-700">Price</p>
                <p class="font-semibold text-blue-600" x-text="formatCurrency(selectedProduct.price)"></p>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 mb-2">Quantity (Max: <span x-text="selectedProduct.stock"></span>)</p>
                <div class="flex items-center">
                    <button 
                        @click="tempQuantity > 1 ? tempQuantity-- : null" 
                        class="bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-l-lg"
                        :class="tempQuantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>
                    <input 
                        type="number" 
                        x-model.number="tempQuantity" 
                        min="1" 
                        :max="selectedProduct.stock"
                        class="w-16 text-center py-2 border-t border-b border-gray-300"
                        @input="validateQuantity()"
                    >
                    <button 
                        @click="tempQuantity < selectedProduct.stock ? tempQuantity++ : null" 
                        class="bg-gray-100 text-gray-600 hover:bg-gray-200 p-2 rounded-r-lg"
                        :class="tempQuantity >= selectedProduct.stock ? 'opacity-50 cursor-not-allowed' : ''"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center justify-between mb-4">
                <p class="text-gray-700">Subtotal</p>
                <p class="font-bold text-blue-600" x-text="formatCurrency(selectedProduct.price * tempQuantity)"></p>
            </div>
            
            <div class="flex space-x-2">
                <button 
                    @click="showQuantityModal = false" 
                    class="w-1/3 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium"
                >
                    Cancel
                </button>
                <button 
                    @click="addToCart(selectedProduct, tempQuantity); showQuantityModal = false;" 
                    class="w-2/3 py-3 bg-blue-600 text-white rounded-lg font-medium"
                >
                    Add to Cart
                </button>
            </div>
        </div>
    </div>

    <!-- Shopping cart button -->
    <div class="fixed bottom-20 right-4 z-10" x-show="!showCart && cart.items.length > 0">
        <button 
            @click="showCart = true" 
            class="bg-blue-600 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:bg-blue-700 transition-colors"
        >
            <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold" x-text="getTotalItems()"></span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
    </div>

    <!-- Shopping Cart -->
    <div class="fixed inset-0 bg-white z-40 flex flex-col" x-show="showCart">
        <!-- Cart Header -->
        <div class="bg-blue-600 text-white py-3 px-4 flex justify-between items-center">
            <div class="flex items-center">
                <button @click="showCart = false" class="mr-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <h2 class="text-lg font-medium">Shopping Cart</h2>
            </div>
            <button @click="confirmClearCart()" class="text-sm flex items-center" x-show="cart.items.length > 0">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Clear All
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-grow overflow-y-auto p-4" x-show="cart.items.length > 0">
            <template x-for="(item, index) in cart.items" :key="index">
                <div class="bg-white rounded-lg shadow mb-3 overflow-hidden">
                    <div class="p-3 flex items-center">
                        <div class="w-12 h-12 flex-shrink-0 mr-3 bg-gray-50 rounded overflow-hidden">
                            <template x-if="item.image_path">
                                <img :src="'/storage/' + item.image_path" alt="Product" class="h-full w-full object-contain" 
                                    onerror="this.onerror=null; this.src='/images/no-image.png';">
                            </template>
                            <template x-if="!item.image_path">
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <div class="flex-grow">
                            <p class="font-medium" x-text="item.name"></p>
                            <p class="text-sm text-gray-600" x-text="formatCurrency(item.price) + ' × ' + item.quantity"></p>
                        </div>
                        <p class="font-semibold text-blue-600 ml-2" x-text="formatCurrency(item.price * item.quantity)"></p>
                    </div>
                    <div class="bg-gray-50 px-3 py-2 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <button 
                                @click="decreaseQuantity(index)" 
                                class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center focus:outline-none hover:bg-gray-300"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <input 
                                type="number" 
                                x-model.number="item.quantity" 
                                min="1" 
                                :max="item.stock"
                                @change="updateQuantity(index, $event)" 
                                class="w-12 text-center rounded border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            >
                            <button 
                                @click="increaseQuantity(index)" 
                                class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center focus:outline-none hover:bg-gray-300"
                                :class="item.quantity >= item.stock ? 'opacity-50 cursor-not-allowed' : ''"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <button @click="removeItem(index)" class="text-red-500 hover:text-red-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty Cart State -->
        <div class="flex-grow flex flex-col items-center justify-center p-4" x-show="cart.items.length === 0">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-medium text-gray-700 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 text-center mb-5">Add items to your cart to proceed with your purchase</p>
            <button 
                @click="showCart = false" 
                class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium flex items-center"
            >
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Products
            </button>
        </div>

        <!-- Cart Summary -->
        <div class="bg-white border-t border-gray-200 p-4" x-show="cart.items.length > 0">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium" x-text="formatCurrency(calculateSubtotal())"></span>
            </div>
            
            <!-- Improved Tax Rate Selector -->
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Tax</span>
                    <div class="inline-flex relative">
                        <select 
                            x-model="cart.taxRate" 
                            class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 pl-2 pr-6 py-1 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-blue-300"
                        >
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1.5 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <span class="font-medium text-sm flex items-center">
                    <span x-show="parseFloat(cart.taxRate) > 0" class="tax-badge mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span x-text="cart.taxRate + '%'"></span>
                    </span>
                    <span x-text="formatCurrency(calculateTax())"></span>
                </span>
            </div>
            
            <!-- Improved Discount Percentage Selector -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Discount</span>
                    <div class="inline-flex relative">
                        <select 
                            x-model="cart.discountPercent" 
                            class="appearance-none bg-gray-50 border border-gray-200 text-gray-700 pl-2 pr-6 py-1 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-300 focus:border-blue-300"
                        >
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                            <option value="25">25%</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1.5 pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <span class="font-medium text-sm text-green-600 flex items-center">
                    <span x-show="parseFloat(cart.discountPercent) > 0" class="discount-badge mr-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span x-text="cart.discountPercent + '%'"></span>
                    </span>
                    <span x-text="'-' + formatCurrency(calculateDiscount())"></span>
                </span>
            </div>
            
            <!-- Summary breakdown -->
            <div class="bg-gray-50 p-3 rounded-lg mb-4">
                <div class="flex justify-between items-center mb-1 text-sm">
                    <span class="text-gray-500">Items total:</span>
                    <span class="text-gray-700" x-text="formatCurrency(calculateSubtotal())"></span>
                </div>
                <template x-if="parseFloat(cart.taxRate) > 0">
                    <div class="flex justify-between items-center mb-1 text-sm">
                        <span class="text-gray-500" x-text="'Tax (' + cart.taxRate + '%)'"></span>
                        <span class="text-gray-700" x-text="formatCurrency(calculateTax())"></span>
                    </div>
                </template>
                <template x-if="parseFloat(cart.discountPercent) > 0">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500" x-text="'Discount (' + cart.discountPercent + '%)'"></span>
                        <span class="text-green-600" x-text="'-' + formatCurrency(calculateDiscount())"></span>
                    </div>
                </template>
            </div>
            
            <div class="flex justify-between items-center mb-4 text-lg font-bold">
                <span>Total</span>
                <span class="text-blue-600" x-text="formatCurrency(calculateTotal())"></span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelanggan (Opsional)</label>
                <input 
                    type="text" 
                    x-model="customerName" 
                    placeholder="Masukkan nama pelanggan" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="paymentMethod = 'cash'"
                        :class="paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="py-2 rounded-lg font-medium text-center text-sm transition-colors"
                    >
                        Cash
                    </button>
                    <button
                        @click="paymentMethod = 'transfer'"
                        :class="paymentMethod === 'transfer' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="py-2 rounded-lg font-medium text-center text-sm transition-colors"
                    >
                        Transfer
                    </button>
                    <button
                        @click="paymentMethod = 'card'"
                        :class="paymentMethod === 'card' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="py-2 rounded-lg font-medium text-center text-sm transition-colors"
                    >
                        Card/EDC
                    </button>
                    <!-- Tambahkan opsi Midtrans -->
                    <button
                        @click="paymentMethod = 'midtrans'"
                        :class="paymentMethod === 'midtrans' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                        class="py-2 rounded-lg font-medium text-center text-sm transition-colors"
                    >
                        Midtrans
                    </button>
                </div>
            </div>

            <!-- Checkout Button -->
            <button 
                @click="checkout()" 
                class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium flex items-center justify-center hover:bg-blue-700 transition-colors"
                :disabled="isProcessing || cart.items.length === 0"
                :class="isProcessing || cart.items.length === 0 ? 'opacity-70 cursor-not-allowed' : ''"
            >
                <span x-show="!isProcessing" class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    Complete Sale
                </span>
                <span x-show="isProcessing" class="flex items-center">
                    <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 modal-backdrop flex items-center justify-center p-4 z-50" 
        x-show="showSuccessModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all" 
            x-show="showSuccessModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
        >
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-5">
                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Sale Completed!</h3>
                <p class="text-gray-500 mb-5">The transaction has been processed successfully.</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-5 text-left">
                    <div class="grid grid-cols-2 gap-y-2">
                        <span class="text-gray-600">Invoice:</span>
                        <span class="font-medium text-right" x-text="lastTransaction.invoice_number"></span>
                        
                        <!-- Tambahkan baris untuk nama pelanggan jika ada -->
                        <template x-if="lastTransaction.customer_name">
                            <div class="contents">
                                <span class="text-gray-600">Pelanggan:</span>
                                <span class="font-medium text-right" x-text="lastTransaction.customer_name"></span>
                            </div>
                        </template>
                        
                        <span class="text-gray-600">Payment:</span>
                        <span class="font-medium capitalize text-right" x-text="lastTransaction.payment_method"></span>
                        
                        <span class="text-gray-600">Total:</span>
                        <span class="font-medium text-blue-600 text-right" x-text="formatCurrency(lastTransaction.final_amount)"></span>
                        
                        <span class="text-gray-600">Date:</span>
                        <span class="font-medium text-right" x-text="formatDate(lastTransaction.created_at)"></span>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button @click="printReceipt()" class="flex-1 py-3 px-4 bg-gray-200 text-gray-800 rounded-lg border border-gray-300">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                            </svg>
                            Print
                        </div>
                    </button>
                    <button @click="startNewSale()" class="flex-1 py-3 px-4 bg-blue-600 text-white rounded-lg">
                        <div class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Sale
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cashier() {
    return {
        products: @json($products ?? []),
        categories: @json($categories ?? []),
        searchQuery: '',
        selectedCategory: null,
        viewMode: 'grid', // 'grid' or 'list'
        cart: {
            items: [],
            taxRate: '10', // Default tax rate (as string for select binding)
            discountPercent: '0' // Default discount percent (as string for select binding)
        },
        customerName: '',
        showCart: false,
        isProcessing: false,
        showSuccessModal: false,
        lastTransaction: null,
        showQuantityModal: false,
        selectedProduct: {},
        tempQuantity: 1,
        paymentMethod: 'cash',

        initCashier() {
            // Check for saved cart in localStorage
            const savedCart = localStorage.getItem('pos_cart');
            if (savedCart) {
                try {
                    this.cart = JSON.parse(savedCart);
                    
                    // Ensure we have the new taxRate and discountPercent properties
                    if (this.cart.taxRate === undefined) {
                        this.cart.taxRate = '10';
                    }
                    if (this.cart.discountPercent === undefined) {
                        this.cart.discountPercent = '0';
                    }
                    
                    // Remove old discount amount if it exists
                    if (this.cart.discount !== undefined) {
                        delete this.cart.discount;
                    }
                } catch (e) {
                    console.error('Error loading saved cart', e);
                    this.clearCart();
                }
            }
            
            // Listen for browser close/refresh to save cart
            window.addEventListener('beforeunload', () => {
                localStorage.setItem('pos_cart', JSON.stringify(this.cart));
            });
        },

        get filteredProducts() {
            return this.products.filter(product => {
                // Filter by search query
                const matchesSearch = this.searchQuery === '' || 
                    product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                // Filter by category
                const matchesCategory = this.selectedCategory === null || 
                    product.category_id === this.selectedCategory;
                
                // Only show products with stock > 0 and active
                const isAvailable = product.stock > 0 && product.is_active;
                
                return matchesSearch && matchesCategory && isAvailable;
            });
        },

        selectProduct(product) {
            this.selectedProduct = product;
            this.tempQuantity = 1;
            this.showQuantityModal = true;
        },

        validateQuantity() {
            if (isNaN(this.tempQuantity) || this.tempQuantity < 1) {
                this.tempQuantity = 1;
            } else if (this.tempQuantity > this.selectedProduct.stock) {
                this.tempQuantity = this.selectedProduct.stock;
            }
        },

        addToCart(product, quantity) {
            // Check if product already in cart
            const existingItemIndex = this.cart.items.findIndex(item => item.id === product.id);
            
            if (existingItemIndex >= 0) {
                // Increase quantity if already in cart
                const existingItem = this.cart.items[existingItemIndex];
                const newQuantity = existingItem.quantity + quantity;
                
                if (newQuantity <= product.stock) {
                    existingItem.quantity = newQuantity;
                } else {
                    existingItem.quantity = product.stock;
                }
            } else {
                // Add new item to cart
                this.cart.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image_path: product.image_path,
                    quantity: quantity,
                    stock: product.stock
                });
            }
            
            // Save to localStorage
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        getTotalItems() {
            return this.cart.items.reduce((total, item) => total + item.quantity, 0);
        },

        removeItem(index) {
            this.cart.items.splice(index, 1);
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        increaseQuantity(index) {
            const item = this.cart.items[index];
            if (item.quantity < item.stock) {
                item.quantity += 1;
                localStorage.setItem('pos_cart', JSON.stringify(this.cart));
            }
        },

        decreaseQuantity(index) {
            const item = this.cart.items[index];
            if (item.quantity > 1) {
                item.quantity -= 1;
            } else {
                this.removeItem(index);
            }
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        updateQuantity(index, event) {
            const item = this.cart.items[index];
            const newQuantity = parseInt(event.target.value);
            
            if (isNaN(newQuantity) || newQuantity < 1) {
                item.quantity = 1;
            } else if (newQuantity > item.stock) {
                item.quantity = item.stock;
            } else {
                item.quantity = newQuantity;
            }
            
            localStorage.setItem('pos_cart', JSON.stringify(this.cart));
        },

        confirmClearCart() {
            if (confirm('Are you sure you want to clear the cart?')) {
                this.clearCart();
            }
        },

        clearCart() {
            this.cart.items = [];
            this.cart.taxRate = '10';
            this.cart.discountPercent = '0';
            localStorage.removeItem('pos_cart');
        },

        calculateSubtotal() {
            return this.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        calculateTax() {
            const subtotal = this.calculateSubtotal();
            return subtotal * (parseFloat(this.cart.taxRate) / 100);
        },
        
        calculateDiscount() {
            const subtotal = this.calculateSubtotal();
            return subtotal * (parseFloat(this.cart.discountPercent) / 100);
        },

        calculateTotal() {
            const subtotal = this.calculateSubtotal();
            const tax = this.calculateTax();
            const discount = this.calculateDiscount();
            
            // Apply both tax and discount to the subtotal
            return Math.max(0, subtotal + tax - discount);
        },

        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(value);
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        },

        printReceipt() {
            // Redirect to transaction detail or print view
            if (this.lastTransaction && this.lastTransaction.id) {
                window.open(`/transactions/${this.lastTransaction.id}/print`, '_blank');
            }
        },

        async checkout() {
            if (this.cart.items.length === 0) return;
            
            this.isProcessing = true;
            
            try {
                // Log payment method before making the request
                console.log('Using payment method:', this.paymentMethod);
                
                const response = await fetch('/transactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        products: this.cart.items.map(item => ({
                            id: item.id,
                            quantity: item.quantity
                        })),
                        customer_name: this.customerName,
                        payment_method: this.paymentMethod,
                        tax_percent: parseFloat(this.cart.taxRate),
                        discount_percent: parseFloat(this.cart.discountPercent),
                        notes: ''
                    })
                });
                
                // Log raw response for debugging
                const responseText = await response.text();
                console.log('Transaction response (raw):', responseText);
                
                // Parse response as JSON
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error('Error parsing transaction response:', e);
                    alert('Server returned invalid JSON. Please check your server logs.');
                    this.isProcessing = false;
                    return;
                }
                
                console.log('Transaction result:', result);
                
                if (result.success) {
                    this.lastTransaction = result.transaction;
                    
                    // Log transaction and Midtrans flag
                    console.log('Transaction saved:', this.lastTransaction);
                    console.log('Use Midtrans?', result.use_midtrans && this.paymentMethod === 'midtrans');
                    
                    // Cek jika menggunakan Midtrans
                    if (result.use_midtrans && this.paymentMethod === 'midtrans') {
                        console.log('Starting Midtrans payment flow');
                        await this.processMidtransPayment(result.transaction.id);
                    } else {
                        this.showSuccessModal = true;
                        this.clearCart();
                        this.customerName = '';
                    }
                } else {
                    alert('Error: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error processing transaction:', error);
                alert('Failed to process transaction. Please try again: ' + error.message);
            } finally {
                this.isProcessing = false;
            }
        },
        
        async processMidtransPayment(transactionId) {
            try {
                console.log('Requesting token for transaction:', transactionId);
                
                // Request token dari server
                const tokenResponse = await fetch('{{ route('midtrans.token') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        transaction_id: transactionId
                    })
                });
                
                // Log HTTP status for debugging
                console.log('Token request status:', tokenResponse.status);
                
                // Get raw response first
                const responseText = await tokenResponse.text();
                console.log('Token raw response:', responseText);
                
                // Try to parse as JSON
                let tokenResult;
                try {
                    tokenResult = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse token response:', e);
                    alert('Server returned invalid JSON response. Please check server logs.');
                    return;
                }
                
                console.log('Token response parsed:', tokenResult);
                
                if (tokenResult.success && tokenResult.snap_token) {
                    console.log('Using snap token:', tokenResult.snap_token);
                    
                    // Verify snap object is available
                    if (!window.snap) {
                        console.error('Midtrans Snap.js is not loaded properly');
                        alert('Payment gateway not initialized properly. Please refresh the page and try again.');
                        return;
                    }
                    
                    // Tampilkan Snap Payment Page
                    window.snap.pay(tokenResult.snap_token, {
                        onSuccess: (result) => {
                            console.log('Payment success:', result);
                            // Redirect to success page
                            window.location.href = '{{ url("/midtrans/success") }}/' + this.lastTransaction.id;
                        },
                        onPending: (result) => {
                            console.log('Payment pending:', result);
                            alert("Menunggu pembayaran Anda!");
                            this.clearCart();
                            this.customerName = '';
                            window.location.href = '{{ route('transactions.index') }}';
                        },
                        onError: (result) => {
                            console.error('Payment error:', result);
                            alert("Pembayaran gagal: " + (result.message || 'Unknown error'));
                        },
                        onClose: () => {
                            console.log('Snap payment closed');
                            alert("Anda menutup popup tanpa menyelesaikan pembayaran");
                        }
                    });
                } else {
                    console.error('Failed to get token:', tokenResult);
                    alert('Gagal mendapatkan token pembayaran: ' + (tokenResult.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Midtrans payment error:', error);
                alert('Terjadi kesalahan pada payment gateway. Silakan coba lagi: ' + error.message);
            }
        },

        startNewSale() {
            this.showSuccessModal = false;
            this.showCart = false;
        }
    };
}
</script>
@endpush