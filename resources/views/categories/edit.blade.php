@extends('layouts.mobile')

@section('title', 'Edit Category')
@section('header-title', 'Edit Category')

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
        <h2 class="text-xl font-bold text-gray-800">Edit Category</h2>
        <p class="text-gray-500">Update category information</p>
    </div>
    
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Category Stats -->
            @if($category->products_count > 0)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">{{ $category->products_count }}</span>
                            <span class="text-gray-700">{{ Str::plural('product', $category->products_count) }} in this category</span>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Category name -->
            <div>
                <label for="name" class="block text-base font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $category->name) }}" 
                    class="w-full rounded-lg border-gray-300 py-3 px-4 text-base focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                    placeholder="Enter category name"
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
                >{{ old('description', $category->description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">A good description helps you and your staff understand what products belong in this category.</p>
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
                    class="px-5 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm"
                >
                    Update Category
                </button>
            </div>
        </div>
    </form>
    
    <!-- Danger Zone -->
    <div class="mt-10 border-t-2 border-red-100 pt-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-5">
            <h3 class="text-lg font-medium text-red-800 mb-1">Danger Zone</h3>
            <p class="text-sm text-red-600 mb-4">
                Deleting this category cannot be undone.
                @if($category->products_count > 0)
                    <strong>This category has {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }} associated with it.</strong>
                @endif
            </p>
            
            <!-- Separate form for delete to avoid conflicting with update -->
            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? {{ $category->products_count > 0 ? 'This category contains '.$category->products_count.' products that may be affected.' : '' }} This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button 
                    type="submit" 
                    class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-3 border border-transparent font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Category
                </button>
            </form>
        </div>
    </div>
</div>
@endsection