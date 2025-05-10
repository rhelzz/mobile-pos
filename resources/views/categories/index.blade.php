@extends('layouts.mobile')

@section('title', 'Categories')
@section('header-title', 'Categories')

@section('subheader')
<div class="w-full flex justify-end">
    <a href="{{ route('categories.create') }}" class="bg-white text-blue-600 p-1 rounded-full flex items-center justify-center">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>
@endsection

@section('content')
<div class="mb-4">
    <div class="flex items-center mb-4">
        <h2 class="text-lg font-medium">All Categories</h2>
        <div class="ml-auto text-sm">
            <span class="text-gray-500">{{ count($categories) }} categories</span>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="divide-y divide-gray-200">
            @forelse($categories as $category)
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h3 class="font-medium">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $category->products_count }} products</p>
                    </div>
                    <a href="{{ route('categories.edit', $category) }}" class="text-gray-500 hover:text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </div>
            @empty
                <div class="p-4 text-center">
                    <p class="text-gray-500">No categories found</p>
                    <a href="{{ route('categories.create') }}" class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                        Add First Category
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection