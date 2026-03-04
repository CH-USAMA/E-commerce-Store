@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">General Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ $product->name }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" required
                                    value="{{ $product->slug }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" required value="{{ $product->sku }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control"
                                rows="5">{{ $product->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Inventory per Store</h6>
                        <div class="row">
                            @foreach($stores as $store)
                                @php
                                    $stock = $store->stocks->first();
                                @endphp
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $store->name }} (Quantity)</label>
                                    <input type="number" name="stocks[{{ $store->id }}]" class="form-control"
                                        value="{{ $stock->quantity ?? 0 }}" min="0">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Pricing & Status</h6>
                        <div class="mb-3">
                            <label class="form-label">Base Price (R)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required
                                value="{{ $product->price }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Rate (%)</label>
                            <input type="number" step="0.01" name="vat_rate" class="form-control"
                                value="{{ $product->vat_rate }}" required>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredSwitch"
                                {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label" for="featuredSwitch">Featured Product</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Organization</h6>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Product Image</h6>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ (Str::contains($product->image, 'images/') ? asset($product->image) : asset('storage/' . $product->image)) }}"
                                    alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="grid d-grid gap-2">
                    <button type="submit" class="btn btn-jabulani btn-lg">Update Product</button>
                    <button type="button" class="btn btn-outline-danger"
                        onclick="if(confirm('Delete product?')) document.getElementById('delete-form').submit();">Delete
                        Product</button>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.getElementById('name').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection