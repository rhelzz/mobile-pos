@extends('layouts.mobile')

@section('title', 'Categories')
@section('header-title', 'Categories')

@section('subheader')
<div class="w-full flex justify-between items-center">
    <div class="text-sm text-blue-100">
        <span class="bg-blue-700 bg-opacity-50 px-3 py-1 rounded-full">
            {{ count($categories) }} {{ Str::plural('category', count($categories)) }}
        </span>
    </div>
    <a href="{{ route('categories.create') }}" class="bg-white text-blue-600 p-2 rounded-full flex items-center justify-center shadow-sm hover:bg-blue-50 transition-all">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection

@section('content')
<div class="mb-6">
    <!-- Header Section -->
    <div class="mb-5">
        <h2 class="text-xl font-bold text-gray-800">Categories</h2>
        <p class="text-gray-500">Manage your product categories</p>
    </div>
    
    <!-- Categories List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($categories as $category)
                <div class="group hover:bg-blue-50 transition-colors duration-150">
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center">
                            <!-- Category Icon -->
                            <div class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $category->name }}</h3>
                                <div class="flex items-center mt-1">
                                    <span class="inline-flex items-center text-xs {{ $category->products_count > 0 ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-100' }} px-2 py-0.5 rounded-full">
                                        <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        {{ $category->products_count }} {{ Str::plural('product', $category->products_count) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('categories.edit', $category) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-100 rounded-full transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-1">No Categories Found</h3>
                    <p class="text-gray-500 mb-4">Start by creating your first category</p>
                    <a href="{{ route('categories.create') }}" class="inline-flex items-center px-5 py-3 bg-blue-600 text-white rounded-lg font-medium shadow-sm hover:bg-blue-700 transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create First Category
                    </a>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Quick Help Card -->
    @if(count($categories) > 0)
        <div class="mt-5 bg-blue-50 rounded-lg p-4 border border-blue-100">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Tips</h3>
                    <div class="mt-1 text-sm text-blue-700">
                        <p>Click on <span class="font-medium">+</span> to add new categories or the <span class="font-medium">pencil icon</span> to edit existing ones.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection