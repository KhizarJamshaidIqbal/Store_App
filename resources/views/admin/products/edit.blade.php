@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Product: {{ $product->name }}</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="section">
                            <h4>Basic Information</h4>
                            <div class="form-group">
                                <label for="name">Product Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug *</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category *</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="highlights">Highlights</label>
                                <textarea class="form-control" id="highlights" name="highlights" rows="3">{{ old('highlights', $product->highlights) }}</textarea>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="section">
                            <h4>Product Details</h4>
                            <div class="form-group">
                                <label for="sku">SKU *</label>
                                <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="shop_sku">Shop SKU</label>
                                <input type="text" class="form-control" id="shop_sku" name="shop_sku" value="{{ old('shop_sku', $product->shop_sku) }}">
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $product->model) }}">
                            </div>

                            <div class="form-group">
                                <label for="texture">Texture</label>
                                <input type="text" class="form-control" id="texture" name="texture" value="{{ old('texture', $product->texture) }}">
                            </div>

                            <div class="form-group">
                                <label for="color_family">Color Family</label>
                                <input type="text" class="form-control" id="color_family" name="color_family" value="{{ old('color_family', $product->color_family) }}">
                            </div>

                            <div class="form-group">
                                <label for="country_of_origin">Country of Origin</label>
                                <input type="text" class="form-control" id="country_of_origin" name="country_of_origin" value="{{ old('country_of_origin', $product->country_of_origin) }}">
                            </div>

                            <div class="form-group">
                                <label for="pack_type">Pack Type</label>
                                <input type="text" class="form-control" id="pack_type" name="pack_type" value="{{ old('pack_type', $product->pack_type) }}">
                            </div>

                            <div class="form-group">
                                <label for="volume">Volume</label>
                                <input type="text" class="form-control" id="volume" name="volume" value="{{ old('volume', $product->volume) }}">
                            </div>

                            <div class="form-group">
                                <label for="weight">Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="{{ old('weight', $product->weight) }}">
                            </div>

                            <div class="form-group">
                                <label for="material">Material</label>
                                <input type="text" class="form-control" id="material" name="material" value="{{ old('material', $product->material) }}">
                            </div>

                            <div class="form-group">
                                <label for="features">Features</label>
                                <textarea class="form-control" id="features" name="features" rows="3">{{ old('features', $product->features) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="brand_classification">Brand Classification</label>
                                <input type="text" class="form-control" id="brand_classification" name="brand_classification" value="{{ old('brand_classification', $product->brand_classification) }}">
                            </div>

                            <div class="form-group">
                                <label for="shelf_life">Shelf Life</label>
                                <input type="text" class="form-control" id="shelf_life" name="shelf_life" value="{{ old('shelf_life', $product->shelf_life) }}">
                            </div>
                        </div>

                        <!-- Pricing and Stock -->
                        <div class="section">
                            <h4>Pricing & Stock</h4>
                            <div class="form-group">
                                <label for="price">Price *</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="special_price">Special Price</label>
                                <input type="number" step="0.01" class="form-control" id="special_price" name="special_price" value="{{ old('special_price', $product->special_price) }}">
                            </div>

                            <div class="form-group">
                                <label for="stock">Stock *</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                            </div>
                        </div>

                        <!-- Package Information -->
                        <div class="section">
                            <h4>Package Information</h4>
                            <div class="form-group">
                                <label for="package_weight">Package Weight (kg)</label>
                                <input type="number" step="0.01" class="form-control" id="package_weight" name="package_weight" value="{{ old('package_weight', $product->package_weight) }}">
                            </div>

                            <div class="form-group">
                                <label for="package_length">Package Length (cm)</label>
                                <input type="number" step="0.01" class="form-control" id="package_length" name="package_length" value="{{ old('package_length', $product->package_length) }}">
                            </div>

                            <div class="form-group">
                                <label for="package_width">Package Width (cm)</label>
                                <input type="number" step="0.01" class="form-control" id="package_width" name="package_width" value="{{ old('package_width', $product->package_width) }}">
                            </div>

                            <div class="form-group">
                                <label for="package_height">Package Height (cm)</label>
                                <input type="number" step="0.01" class="form-control" id="package_height" name="package_height" value="{{ old('package_height', $product->package_height) }}">
                            </div>
                        </div>

                        <!-- Status and Options -->
                        <div class="section">
                            <h4>Status & Options</h4>
                            <div class="form-group">
                                <label for="status">Status *</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="dangerous_goods" name="dangerous_goods" value="1" {{ old('dangerous_goods', $product->dangerous_goods) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="dangerous_goods">Dangerous Goods</label>
                                </div>
                            </div>
                        </div>

                        <!-- Product Images -->
                        <div class="section">
                            <h4>Product Images</h4>
                            <!-- Current Images -->
                            <div class="row mb-3">
                                @foreach($product->images as $image)
                                    <div class="col-md-2 mb-3">
                                        <div class="card">
                                            <img src="{{ Storage::url($image->image_path) }}" class="card-img-top" alt="Product Image">
                                            <div class="card-body">
                                                <p class="card-text">{{ $image->is_primary ? 'Primary Image' : 'Additional Image' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- New Images -->
                            <div class="form-group">
                                <label for="images">Add New Images</label>
                                <input type="file" class="form-control-file" id="images" name="images[]" multiple accept="image/*">
                                <small class="form-text text-muted">You can select multiple images. Supported formats: JPEG, PNG, JPG, GIF, SVG</small>
                            </div>
                        </div>

                        <!-- Product Variants -->
                        <div class="section">
                            <h4>Product Variants</h4>
                            <div id="variants-container">
                                @foreach($product->variants as $variant)
                                    <div class="variant-item border p-3 mb-3">
                                        <div class="form-group">
                                            <label>Variant Name</label>
                                            <input type="text" class="form-control" name="variants[{{ $loop->index }}][name]" value="{{ $variant->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Value</label>
                                            <input type="text" class="form-control" name="variants[{{ $loop->index }}][value]" value="{{ $variant->value }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" step="0.01" class="form-control" name="variants[{{ $loop->index }}][price]" value="{{ $variant->price }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Special Price</label>
                                            <input type="number" step="0.01" class="form-control" name="variants[{{ $loop->index }}][special_price]" value="{{ $variant->special_price }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Stock</label>
                                            <input type="number" class="form-control" name="variants[{{ $loop->index }}][stock]" value="{{ $variant->stock }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control" name="variants[{{ $loop->index }}][status]" required>
                                                <option value="active" {{ $variant->status == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ $variant->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-variant">Add Variant</button>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Handle dynamic variant addition
    document.getElementById('add-variant').addEventListener('click', function() {
        const variantsContainer = document.getElementById('variants-container');
        const variantCount = variantsContainer.children.length;
        
        const variantHtml = `
            <div class="variant-item border p-3 mb-3">
                <div class="form-group">
                    <label>Variant Name</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][name]" required>
                </div>
                <div class="form-group">
                    <label>Value</label>
                    <input type="text" class="form-control" name="variants[${variantCount}][value]" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" class="form-control" name="variants[${variantCount}][price]" required>
                </div>
                <div class="form-group">
                    <label>Special Price</label>
                    <input type="number" step="0.01" class="form-control" name="variants[${variantCount}][special_price]">
                </div>
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" class="form-control" name="variants[${variantCount}][stock]" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="variants[${variantCount}][status]" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="button" class="btn btn-danger remove-variant">Remove Variant</button>
            </div>
        `;
        
        variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
    });

    // Handle variant removal
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-item').remove();
        }
    });
</script>
@endpush

<style>
    .section {
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }
    
    .section h4 {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .variant-item {
        background-color: white;
        border-radius: 0.25rem;
    }
</style>
@endsection
