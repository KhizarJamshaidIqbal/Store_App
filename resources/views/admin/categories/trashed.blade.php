@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header -->
        <div class="p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Deleted Categories</h1>
                    <p class="mt-1 text-sm text-gray-500">View and restore deleted categories</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Categories
                </a>
            </div>

            <!-- Deleted Categories List -->
            <div class="mt-6">
                @forelse($trashedCategories as $category)
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg mb-3 transition-colors duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-folder text-gray-400"></i>
                            <div>
                                <span class="font-medium text-gray-700">{{ $category->name }}</span>
                                <div class="text-sm text-gray-500">
                                    Deleted at: {{ $category->deleted_at->format('Y-m-d H:i:s') }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center space-x-1 px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200">
                                    <i class="fas fa-undo"></i>
                                    <span>Restore</span>
                                </button>
                            </form>
                            <form action="{{ route('admin.categories.force-delete', $category->id) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to permanently delete this category? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center space-x-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                    <span>Delete Permanently</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-6xl mb-4">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h3 class="text-xl font-medium text-gray-600 mb-1">No Deleted Categories</h3>
                        <p class="text-gray-500">There are no categories in the trash at the moment</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
