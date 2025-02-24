@extends('admin.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-tight text-gray-900">
                    Special Offers
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your special offers and promotions
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.special-offers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Offer
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($offers as $offer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $offer->title }}</div>
                                <div class="text-sm text-gray-500">{{ $offer->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($offer->discount_amount)
                                        ${{ number_format($offer->discount_amount, 2) }}
                                    @elseif($offer->discount_percentage)
                                        {{ $offer->discount_percentage }}%
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $offer->start_date->format('M d, Y') }} -
                                    {{ $offer->end_date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($offer->status === 'active')
                                        bg-green-100 text-green-800
                                    @elseif($offer->status === 'scheduled')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($offer->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center space-x-2">
                                    <button onclick="toggleFeatured({{ $offer->id }})" class="featured-star text-gray-400 hover:text-yellow-500 transition-colors {{ $offer->is_featured ? 'text-yellow-500' : '' }}">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.special-offers.edit', $offer->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="duplicateOffer({{ $offer->id }})" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-clone"></i>
                                    </button>
                                    <form action="{{ route('admin.special-offers.destroy', $offer->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this offer?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $offers->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFeatured(offerId) {
        fetch(`/admin/special-offers/${offerId}/toggle-featured`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const star = event.target.closest('.featured-star');
                star.classList.toggle('text-yellow-500');
                star.classList.toggle('text-gray-400');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to toggle featured status');
        });
    }

    function duplicateOffer(offerId) {
        if (confirm('Are you sure you want to duplicate this offer?')) {
            fetch(`/admin/special-offers/${offerId}/duplicate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to duplicate offer');
            });
        }
    }
</script>
@endpush
@endsection
