@extends('layouts.mobile')

@section('title', 'Cashier')
@section('header-title', 'Cashier')

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
</style>
@endpush

@section('content')
<div x-data="cashier()">
    <!-- Product search -->
    <div class="mb-4">
        <div class="relative">
            <input 
                type="text" 
                x-model="searchQuery" 
                placeholder="Search products..." 
                class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Product categories (horizontal scrollable) -->
    <div class="mb-4 -mx-4 px-4 overflow-x-auto flex items-center whitespace-nowrap pb-2">
        <button 
            @click="selectedCategory = null" 
            :class="selectedCategory === null ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
            class="px-4 py-2 rounded-full text-sm font-medium mr-2 focus:outline-none"
        >
            All
        </button>
        @foreach($categories ?? [] as $category)
            <button 
                @click="selectedCategory = {{ $category->id }}" 
                :class="selectedCategory === {{ $category->id }} ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-full text-sm font-medium mr-2 focus:outline-none"
            >
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Product grid - 2 columns for mobile -->
    <div class="grid grid-cols-2 gap-3 mb-4" x-show="!showCart">
        <template x-for="product in filteredProducts" :key="product.id">
            <div 
                @click="addToCart(product)"
                class="bg-white rounded-lg border border-gray-200 overflow-hidden flex flex-col shadow-sm hover:shadow-md cursor-pointer"
            >
                <div class="h-24 bg-gray-100 flex items-center justify-center p-2">
                    <template x-if="product.image_path">
                        <img :src="'/storage/' + product.image_path" alt="Product" class="h-full object-contain">
                    </template>
                    <template x-if="!product.image_path">
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </template>
                </div>
                <div class="p-2">
                    <p class="font-medium text-sm truncate" x-text="product.name"></p>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-blue-600 font-bold" x-text="formatCurrency(product.price)"></p>
                        <span class="text-xs text-gray-500" x-text="'Stock: ' + product.stock"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Shopping cart button -->
    <div class="sticky bottom-16 right-0 z-10 flex justify-end px-4" x-show="!showCart && cart.items.length > 0">
        <button 
            @click="showCart = true" 
            class="bg-blue-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg"
        >
            <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs" x-text="cart.items.length"></span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
            <button @click="clearCart()" class="text-sm" x-show="cart.items.length > 0">
                Clear All
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-grow overflow-y-auto p-4" x-show="cart.items.length > 0">
            <template x-for="(item, index) in cart.items" :key="index">
                <div class="bg-white rounded-lg shadow mb-3 overflow-hidden">
                    <div class="p-3 flex items-start">
                        <div class="flex-grow">
                            <p class="font-medium" x-text="item.name"></p>
                            <p class="text-sm text-gray-600" x-text="formatCurrency(item.price) + ' × ' + item.quantity"></p>
                        </div>
                        <p class="font-semibold text-blue-600" x-text="formatCurrency(item.price * item.quantity)"></p>
                    </div>
                    <div class="bg-gray-50 px-3 py-2 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <button 
                                @click="decreaseQuantity(index)" 
                                class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center focus:outline-none"
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
                                class="bg-gray-200 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center focus:outline-none"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <button @click="removeItem(index)" class="text-red-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty Cart State -->
        <div class="flex-grow flex flex-col items-center justify-center p-4" x-show="cart.items.length === 0">
            <svg class="h-20 w-20 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500 font-medium">Your cart is empty</p>
            <button 
                @click="showCart = false" 
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg"
            >
                Add Products
            </button>
        </div>

        <!-- Cart Summary -->
        <div class="bg-white border-t border-gray-200 p-4" x-show="cart.items.length > 0">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium" x-text="formatCurrency(calculateSubtotal())"></span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600">Tax (10%)</span>
                <span class="font-medium" x-text="formatCurrency(calculateTax())"></span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Discount</span>
                <div class="flex items-center">
                    <input 
                        type="number" 
                        x-model="cart.discount" 
                        min="0" 
                        step="1000" 
                        class="w-20 text-right rounded border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                </div>
            </div>
            <div class="flex justify-between items-center mb-4 text-lg font-bold">
                <span>Total</span>
                <span class="text-blue-600" x-text="formatCurrency(calculateTotal())"></span>
            </div>

            <!-- Checkout Button -->
            <button 
                @click="checkout()" 
                class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium flex items-center justify-center"
                :disabled="isProcessing"
            >
                <span x-show="!isProcessing">Complete Sale</span>
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50" x-show="showSuccessModal" x-cloak>
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all" x-show="showSuccessModal">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sale Completed!</h3>
                <p class="text-gray-600 mb-4">Transaction has been processed successfully.</p>
                <div class="border border-gray-200 rounded-lg p-4 mb-4">
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600 text-sm">Invoice:</span>
                        <span class="font-medium text-sm" x-text="lastTransaction.invoice_number"></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span class="text-gray-600 text-sm">Amount:</span>
                        <span class="font-medium text-sm" x-text="formatCurrency(lastTransaction.final_amount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 text-sm">Date:</span>
                        <span class="font-medium text-sm" x-text="formatDate(lastTransaction.created_at)"></span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button @click="startNewSale()" class="flex-1 py-2 px-4 bg-blue-600 text-white rounded-lg">
                        New Sale
                    </button>
                    <a :href="'/transactions/' + lastTransaction.id" class="flex-1 py-2 px-4 bg-gray-200 text-gray-800 rounded-lg text-center">
                        View Details
                    </a>
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
        cart: {
            items: [],
            discount: 0
        },
        showCart: false,
        isProcessing: false,
        showSuccessModal: false,
        lastTransaction: null,

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

        addToCart(product) {
            // Check if product already in cart
            const existingItem = this.cart.items.find(item => item.id === product.id);
            
            if (existingItem) {
                // Increase quantity if already in cart
                if (existingItem.quantity < product.stock) {
                    existingItem.quantity += 1;
                }
            } else {
                // Add new item to cart
                this.cart.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    stock: product.stock
                });
            }
        },

        removeItem(index) {
            this.cart.items.splice(index, 1);
        },

        increaseQuantity(index) {
            const item = this.cart.items[index];
            if (item.quantity < item.stock) {
                item.quantity += 1;
            }
        },

        decreaseQuantity(index) {
            const item = this.cart.items[index];
            if (item.quantity > 1) {
                item.quantity -= 1;
            } else {
                this.removeItem(index);
            }
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
        },

        clearCart() {
            this.cart.items = [];
            this.cart.discount = 0;
        },

        calculateSubtotal() {
            return this.cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        calculateTax() {
            return this.calculateSubtotal() * 0.1; // 10% tax
        },

        calculateTotal() {
            const subtotal = this.calculateSubtotal();
            const tax = this.calculateTax();
            const discount = parseFloat(this.cart.discount) || 0;
            
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

        async checkout() {
            if (this.cart.items.length === 0) return;
            
            this.isProcessing = true;
            
            try {
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
                        payment_method: 'cash', // Default to cash
                        tax_percent: 10,        // Default 10%
                        discount_amount: this.cart.discount,
                        notes: ''
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.lastTransaction = result.transaction;
                    this.showSuccessModal = true;
                    this.clearCart();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error processing transaction:', error);
                alert('Failed to process transaction. Please try again.');
            } finally {
                this.isProcessing = false;
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