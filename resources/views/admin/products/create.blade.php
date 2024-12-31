@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Product</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h4>Basic Information</h4>
                                
                                <div class="form-group">
                                    <label for="name">Product Name *</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug *</label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" 
                                           value="{{ old('slug') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category *</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="highlights">Highlights</label>
                                    <textarea name="highlights" id="highlights" class="form-control @error('highlights') is-invalid @enderror" 
                                              rows="4">{{ old('highlights') }}</textarea>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="col-md-6">
                                <h4>Product Details</h4>
                                
                                <div class="form-group">
                                    <label for="sku">SKU *</label>
                                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" 
                                           value="{{ old('sku') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="shop_sku">Shop SKU</label>
                                    <input type="text" name="shop_sku" id="shop_sku" class="form-control @error('shop_sku') is-invalid @enderror" 
                                           value="{{ old('shop_sku') }}">
                                </div>

                                <div class="form-group">
                                    <label for="brand">Brand</label>
                                    <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" 
                                           value="{{ old('brand') }}">
                                </div>

                                <div class="form-group">
                                    <label for="model">Model</label>
                                    <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" 
                                           value="{{ old('model') }}">
                                </div>
                            </div>

                            <!-- Product Specifications -->
                            <div class="col-md-6">
                                <h4>Product Specifications</h4>
                                
                                <div class="form-group">
                                    <label for="texture">Texture</label>
                                    <input type="text" name="texture" id="texture" class="form-control @error('texture') is-invalid @enderror" 
                                           value="{{ old('texture') }}">
                                </div>

                                <div class="form-group">
                                    <label for="color_family">Color Family</label>
                                    <input type="text" name="color_family" id="color_family" class="form-control @error('color_family') is-invalid @enderror" 
                                           value="{{ old('color_family') }}">
                                </div>

                                <div class="form-group">
                                    <label for="material">Material</label>
                                    <input type="text" name="material" id="material" class="form-control @error('material') is-invalid @enderror" 
                                           value="{{ old('material') }}">
                                </div>

                                <div class="form-group">
                                    <label for="features">Features</label>
                                    <textarea name="features" id="features" class="form-control @error('features') is-invalid @enderror" 
                                              rows="4">{{ old('features') }}</textarea>
                                </div>
                            </div>

                            <!-- Pricing and Stock -->
                            <div class="col-md-6">
                                <h4>Pricing and Stock</h4>
                                
                                <div class="form-group">
                                    <label for="price">Price *</label>
                                    <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="special_price">Special Price</label>
                                    <input type="number" step="0.01" name="special_price" id="special_price" class="form-control @error('special_price') is-invalid @enderror" 
                                           value="{{ old('special_price') }}">
                                </div>

                                <div class="form-group">
                                    <label for="stock">Stock *</label>
                                    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" 
                                           value="{{ old('stock') }}" required>
                                </div>
                            </div>

                            <!-- Shipping Information -->
                            <div class="col-md-6">
                                <h4>Shipping Information</h4>
                                
                                <div class="form-group">
                                    <label for="weight">Weight (kg)</label>
                                    <input type="number" step="0.01" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" 
                                           value="{{ old('weight') }}">
                                </div>

                                <div class="form-group">
                                    <label for="package_weight">Package Weight (kg)</label>
                                    <input type="number" step="0.01" name="package_weight" id="package_weight" class="form-control @error('package_weight') is-invalid @enderror" 
                                           value="{{ old('package_weight') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_length">Length (cm)</label>
                                            <input type="number" step="0.01" name="package_length" id="package_length" class="form-control @error('package_length') is-invalid @enderror" 
                                                   value="{{ old('package_length') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_width">Width (cm)</label>
                                            <input type="number" step="0.01" name="package_width" id="package_width" class="form-control @error('package_width') is-invalid @enderror" 
                                                   value="{{ old('package_width') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_height">Height (cm)</label>
                                            <input type="number" step="0.01" name="package_height" id="package_height" class="form-control @error('package_height') is-invalid @enderror" 
                                                   value="{{ old('package_height') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <h4>Additional Information</h4>
                                
                                <div class="form-group">
                                    <label for="country_of_origin">Country of Origin</label>
                                    <input type="text" name="country_of_origin" id="country_of_origin" class="form-control @error('country_of_origin') is-invalid @enderror" 
                                           value="{{ old('country_of_origin') }}">
                                </div>

                                <div class="form-group">
                                    <label for="shelf_life">Shelf Life</label>
                                    <input type="text" name="shelf_life" id="shelf_life" class="form-control @error('shelf_life') is-invalid @enderror" 
                                           value="{{ old('shelf_life') }}">
                                </div>

                                <div class="form-group">
                                    <label for="express_delivery_countries">Express Delivery Countries</label>
                                    <select name="express_delivery_countries[]" id="express_delivery_countries" class="form-control @error('express_delivery_countries') is-invalid @enderror" multiple>
                                        <option value="US">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="CA">Canada</option>
                                        <option value="AU">Australia</option>
                                    </select>
                                </div>

                                <input type="hidden" name="is_draft" value="0">

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="dangerous_goods" name="dangerous_goods" value="1" 
                                               {{ old('dangerous_goods') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="dangerous_goods">Dangerous Goods</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Images -->
                            <div class="col-12">
                                <h4>Product Images</h4>
                                <div class="form-group">
                                    <label for="images">Upload Images</label>
                                    <input type="file" name="images[]" id="images" class="form-control-file @error('images.*') is-invalid @enderror" 
                                           multiple accept="image/*">
                                    <small class="form-text text-muted">You can upload multiple images. The first image will be set as the primary image.</small>
                                </div>
                            </div>

                            <!-- Product Variants -->
                            <div class="col-12">
                                <h4>Product Variants</h4>
                                <div id="variants-container">
                                    <!-- Variant template will be added here -->
                                </div>
                                <button type="button" class="btn btn-secondary" onclick="addVariant()">Add Variant</button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create Product</button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let variantCount = 0;

    // Initialize select2 for express delivery countries
    $(document).ready(function() {
        $('#express_delivery_countries').select2({
            placeholder: 'Select countries',
            allowClear: true
        });
    });

    function addVariant() {
        const container = document.getElementById('variants-container');
        const variantHtml = `
            <div class="variant-item border rounded p-3 mb-3">
                <h5>Variant #${variantCount + 1}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Variant Name *</label>
                            <input type="text" name="variants[${variantCount}][name]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Variant Value *</label>
                            <input type="text" name="variants[${variantCount}][value]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price *</label>
                            <input type="number" step="0.01" name="variants[${variantCount}][price]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Special Price</label>
                            <input type="number" step="0.01" name="variants[${variantCount}][special_price]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Stock *</label>
                            <input type="number" name="variants[${variantCount}][stock]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seller SKU</label>
                            <input type="text" name="variants[${variantCount}][seller_sku]" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="variants[${variantCount}][status]" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">Remove Variant</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', variantHtml);
        variantCount++;
    }

    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
