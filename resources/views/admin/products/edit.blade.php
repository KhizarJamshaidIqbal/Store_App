@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Product: {{ $product->name }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Basic Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Product Name *</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category *</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Current Images</label>
                                    <div class="row">
                                        @foreach($product->images as $image)
                                            <div class="col-md-2">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="Product Image">
                                                    <div class="card-body">
                                                        <form action="{{ route('admin.products.images.destroy', $image) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Add More Images</label>
                                    <div class="custom-file">
                                        <input type="file" name="images[]" class="custom-file-input @error('images') is-invalid @enderror" multiple>
                                        <label class="custom-file-label">Choose files (max 7 total)</label>
                                    </div>
                                    @error('images')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Video</label>
                                    @if($product->videos->isNotEmpty())
                                        <div class="mb-2">
                                            @if($product->videos->first()->video_path)
                                                <video width="320" height="240" controls>
                                                    <source src="{{ asset('storage/' . $product->videos->first()->video_path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif($product->videos->first()->youtube_link)
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe class="embed-responsive-item" src="{{ $product->videos->first()->youtube_link }}" allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="custom-file mb-2">
                                        <input type="file" name="video" class="custom-file-input @error('video') is-invalid @enderror">
                                        <label class="custom-file-label">Choose new video file</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="youtube_link" class="form-control" placeholder="Or enter YouTube link" 
                                               value="{{ old('youtube_link', optional($product->videos->first())->youtube_link) }}">
                                    </div>
                                    @error('video')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Product Specification -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Product Specification</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <x-product-specifications :specifications="$product->specifications ?? []" />
                            </div>
                        </div>

                        <!-- Price & Stock -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Price, Stock & Variants</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Price *</label>
                                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                                   step="0.01" value="{{ old('price', $product->price) }}" required>
                                            @error('price')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="special_price">Special Price</label>
                                            <input type="number" name="special_price" id="special_price" class="form-control" 
                                                   step="0.01" value="{{ old('special_price', $product->special_price) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock">Stock *</label>
                                            <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" 
                                                   value="{{ old('stock', $product->stock) }}" required>
                                            @error('stock')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sku">SKU *</label>
                                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" 
                                                   value="{{ old('sku', $product->sku) }}" required>
                                            @error('sku')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div id="variants-container">
                                    @foreach($product->variants as $index => $variant)
                                        <div class="card mt-3">
                                            <div class="card-body">
                                                <h5>Variant {{ $index + 1 }}</h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Name</label>
                                                            <input type="text" name="variants[{{ $index }}][name]" class="form-control" 
                                                                   value="{{ $variant->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Value</label>
                                                            <input type="text" name="variants[{{ $index }}][value]" class="form-control" 
                                                                   value="{{ $variant->value }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="number" name="variants[{{ $index }}][price]" class="form-control" 
                                                                   step="0.01" value="{{ $variant->price }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Stock</label>
                                                            <input type="number" name="variants[{{ $index }}][stock]" class="form-control" 
                                                                   value="{{ $variant->stock }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>SKU</label>
                                                            <input type="text" name="variants[{{ $index }}][sku]" class="form-control" 
                                                                   value="{{ $variant->sku }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Remove Variant</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-secondary" onclick="addVariant()">Add Variant</button>
                            </div>
                        </div>

                        <!-- Shipping & Warranty -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4>Shipping & Warranty</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="package_weight">Package Weight (kg) *</label>
                                            <input type="number" name="package_weight" id="package_weight" class="form-control @error('package_weight') is-invalid @enderror" 
                                                   step="0.01" value="{{ old('package_weight', $product->package_weight) }}" required>
                                            @error('package_weight')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_length">Length (cm) *</label>
                                            <input type="number" name="package_length" id="package_length" class="form-control @error('package_length') is-invalid @enderror" 
                                                   step="0.01" value="{{ old('package_length', $product->package_length) }}" required>
                                            @error('package_length')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_width">Width (cm) *</label>
                                            <input type="number" name="package_width" id="package_width" class="form-control @error('package_width') is-invalid @enderror" 
                                                   step="0.01" value="{{ old('package_width', $product->package_width) }}" required>
                                            @error('package_width')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="package_height">Height (cm) *</label>
                                            <input type="number" name="package_height" id="package_height" class="form-control @error('package_height') is-invalid @enderror" 
                                                   step="0.01" value="{{ old('package_height', $product->package_height) }}" required>
                                            @error('package_height')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="dangerous_goods">Dangerous Goods</label>
                                    <select name="dangerous_goods" id="dangerous_goods" class="form-control">
                                        <option value="none" {{ old('dangerous_goods', $product->dangerous_goods) == 'none' ? 'selected' : '' }}>None</option>
                                        <option value="battery" {{ old('dangerous_goods', $product->dangerous_goods) == 'battery' ? 'selected' : '' }}>Contains Battery</option>
                                        <option value="flammable" {{ old('dangerous_goods', $product->dangerous_goods) == 'flammable' ? 'selected' : '' }}>Flammable</option>
                                        <option value="liquid" {{ old('dangerous_goods', $product->dangerous_goods) == 'liquid' ? 'selected' : '' }}>Liquid</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warranty_type">Warranty Type</label>
                                            <input type="text" name="warranty_type" id="warranty_type" class="form-control" 
                                                   value="{{ old('warranty_type', $product->warranty_type) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warranty_period">Warranty Period</label>
                                            <input type="text" name="warranty_period" id="warranty_period" class="form-control" 
                                                   value="{{ old('warranty_period', $product->warranty_period) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Product</button>
                            <button type="submit" name="is_draft" value="1" class="btn btn-secondary">Save as Draft</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addVariant() {
        const container = document.getElementById('variants-container');
        const variantCount = container.children.length;
        
        const variantHtml = `
            <div class="card mt-3">
                <div class="card-body">
                    <h5>Variant ${variantCount + 1}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="variants[${variantCount}][name]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Value</label>
                                <input type="text" name="variants[${variantCount}][value]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="variants[${variantCount}][price]" class="form-control" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="number" name="variants[${variantCount}][stock]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" name="variants[${variantCount}][sku]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Remove Variant</button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', variantHtml);
    }
</script>
@endpush
@endsection
