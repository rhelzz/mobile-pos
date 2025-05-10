@extends('layouts.mobile')

@section('title', 'Edit Category')
@section('header-title', 'Edit Category')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-4 mb-4">
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg border border-red-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="space-y-4">
            <!-- Category name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name *</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $category->name) }}" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
            </div>
            
            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="3" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                >{{ old('description', $category->description) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex items-center justify-between">
            <!-- Delete category button -->
            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 border border-red-500 text-red-500 rounded-lg text-sm font-medium hover:bg-red-500 hover:text-white transition-colors">
                    Delete
                </button>
            </form>
            
            <div>
                <a href="{{ route('categories.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 mr-2">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    Update Category
                </button>
            </div>
        </div>
    </form>
</div>
@endsection