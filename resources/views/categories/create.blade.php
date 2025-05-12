@extends('layouts.mobile')

@section('title', 'Add Category')
@section('header-title', 'Add Category')

@section('content')
<div class="bg-white rounded-lg shadow-md p-5 mb-5">
    @if ($errors->any())
        <div class="mb-5 p-4 bg-red-50 text-red-600 rounded-lg border border-red-200">
            <h3 class="font-medium mb-1">Please check the following errors:</h3>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Form Header -->
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800">New Category</h2>
        <p class="text-gray-500">Create a new category to organize your products</p>
    </div>
    
    <form action="{{ route('categories.store') }}" method="POST" id="categoryForm">
        @csrf
        
        <div class="space-y-6">
            <!-- Category Icon -->
            <div class="flex justify-center mb-2">
                <div class="h-16 w-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            
            <!-- Category name -->
            <div>
                <label for="name" class="block text-base font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                    placeholder="Enter category name"
                    autofocus
                >
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-base font-medium text-gray-700 mb-2">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Enter category description (optional)"
                >{{ old('description') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">A good description helps you and your staff understand what products belong in this category.</p>
            </div>
            
            <!-- Category Tips -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Category Tips</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Use clear, specific names for your categories</li>
                                <li>Group similar products together for easier inventory management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="mt-8 pt-5 border-t border-gray-200">
            <div class="flex items-center justify-end space-x-3">
                <a 
                    href="{{ route('categories.index') }}" 
                    class="px-5 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    id="saveButton"
                    class="px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
                >
                    Save Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('categoryForm');
        const saveButton = document.getElementById('saveButton');
        
        // Prevent double submission
        form.addEventListener('submit', function() {
            saveButton.disabled = true;
            saveButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        });
    });
</script>
@endpush