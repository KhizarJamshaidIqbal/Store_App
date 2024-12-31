@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Products</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            Add New Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if($product->images->where('is_primary', true)->first())
                                                <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->image_path) }}"
                                                     alt="{{ $product->name }}"
                                                     class="img-thumbnail"
                                                     style="max-width: 50px;">
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>
                                            @if($product->special_price)
                                                <del>${{ number_format($product->price, 2) }}</del>
                                                <br>
                                                <span class="text-danger">${{ number_format($product->special_price, 2) }}</span>
                                            @else
                                                ${{ number_format($product->price, 2) }}
                                            @endif
                                        </td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            <span class="badge badge-{{ $product->status === 'active' ? 'success' : ($product->status === 'draft' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="btn btn-sm btn-info">
                                                    Edit
                                                </a>
                                                @if(!$product->is_draft)
                                                    <form action="{{ route('admin.products.draft', $product) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            Save as Draft
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.products.destroy', $product) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
