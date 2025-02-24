@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Popular Products</h1>
                <p class="mt-2 text-gray-600">Manage your popular products listing</p>
            </div>
            <a href="{{ route('admin.popular-products.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Add New Product
            </a>
        </div>

        <!-- Products List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                @if($popularProducts->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-fire text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Popular Products Yet</h3>
                        <p class="text-gray-600">Start by adding some products to your popular products list.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full" id="sortable-table">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4">#</th>
                                    <th class="text-left py-3 px-4">Product</th>
                                    <th class="text-left py-3 px-4">Category</th>
                                    <th class="text-left py-3 px-4">Price</th>
                                    <th class="text-left py-3 px-4">Stock Status</th>
                                    <th class="text-right py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="sortable">
                                @foreach($popularProducts as $item)
                                    <tr class="border-b border-gray-200 cursor-move" data-id="{{ $item->id }}">
                                        <td class="py-3 px-4">
                                            <i class="fas fa-grip-vertical text-gray-400 mr-2"></i>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                @if($item->product->primary_image)
                                                    <img src="{{ asset('storage/' . $item->product->primary_image) }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="w-12 h-12 object-cover rounded-lg">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <span class="font-medium">{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">
                                            {{ $item->product->category->name ?? 'No Category' }}
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="text-blue-600 font-medium">${{ number_format($item->product->price, 2) }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($item->product->stock > 0)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">In Stock</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Out of Stock</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <form action="{{ route('admin.popular-products.destroy', $item) }}"
                                                  method="POST"
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to remove this product from popular products?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortableTable = document.querySelector('.sortable');
    if (sortableTable) {
        new Sortable(sortableTable, {
            handle: '.fa-grip-vertical',
            animation: 150,
            onEnd: function() {
                const items = document.querySelectorAll('[data-id]');
                const ids = Array.from(items).map(item => item.dataset.id);

                fetch('{{ route("admin.popular-products.update-positions") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ positions: ids })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optional: Show success message
                    }
                })
                .catch(error => {
                    console.error('Error updating positions:', error);
                });
            }
        });
    }
});
</script>
@endpush
@endsection
