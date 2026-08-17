@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">General Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" required
                                value="{{ old('name', $product->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug"
                                    class="form-control @error('slug') is-invalid @enderror" required
                                    value="{{ old('slug', $product->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku"
                                    class="form-control @error('sku') is-invalid @enderror" required
                                    value="{{ old('sku', $product->sku) }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0 fw-bold">Regional Inventory Tracking</h6>
                            <span class="badge bg-soft-info text-info uppercase tracking-widest text-[9px] font-black">WMS Integrated</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle border-light">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 py-2 text-[10px] uppercase font-bold">Branch / Store</th>
                                        <th class="py-2 text-[10px] uppercase font-bold text-success">Physical</th>
                                        <th class="py-2 text-[10px] uppercase font-bold text-info">Incoming</th>
                                        <th class="py-2 text-[10px] uppercase font-bold text-warning">Reserved</th>
                                        <th class="pe-3 py-2 text-[10px] uppercase font-bold text-danger">Damaged</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stores as $store)
                                        @php
                                            $stock = $store->stocks->first();
                                        @endphp
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold" style="font-size: 0.82rem;">{{ $store->name }}</div>
                                                <small class="text-muted" style="font-size: 0.7rem;">{{ $store->province }}</small>
                                            </td>
                                            <td>
                                                <input type="number" name="stocks[{{ $store->id }}][quantity]"
                                                    class="form-control form-control-sm border-success-subtle bg-success-subtle/5"
                                                    value="{{ old('stocks.'.$store->id.'.quantity', $stock->quantity ?? 0) }}" min="0">
                                            </td>
                                            <td>
                                                <input type="number" name="stocks[{{ $store->id }}][incoming]"
                                                    class="form-control form-control-sm border-info-subtle bg-info-subtle/5"
                                                    value="{{ old('stocks.'.$store->id.'.incoming', $stock->incoming ?? 0) }}" min="0">
                                            </td>
                                            <td>
                                                <input type="number" name="stocks[{{ $store->id }}][reserved]"
                                                    class="form-control form-control-sm border-warning-subtle bg-warning-subtle/5"
                                                    value="{{ old('stocks.'.$store->id.'.reserved', $stock->reserved ?? 0) }}" min="0">
                                            </td>
                                            <td class="pe-3">
                                                <input type="number" name="stocks[{{ $store->id }}][damaged]"
                                                    class="form-control form-control-sm border-danger-subtle bg-danger-subtle/5"
                                                    value="{{ old('stocks.'.$store->id.'.damaged', $stock->damaged ?? 0) }}" min="0">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
                            <input type="number" step="0.01" name="price"
                                class="form-control @error('price') is-invalid @enderror" required
                                value="{{ old('price', $product->price) }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Rate (%)</label>
                            <input type="number" step="0.01" name="vat_rate"
                                class="form-control @error('vat_rate') is-invalid @enderror"
                                value="{{ old('vat_rate', $product->vat_rate ?? '0.00') }}" required>
                            @error('vat_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected(old('status', $product->status) === 'active')>
                                    Active &mdash; visible on the storefront
                                </option>
                                <option value="inactive" @selected(old('status', $product->status) === 'inactive')>
                                    Inactive &mdash; hidden from customers
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Inactive products 404 on their own URL and drop out of listings,
                                search, the homepage and "recently viewed".
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredSwitch"
                                @checked(old('is_featured', $product->is_featured))>
                            <label class="form-check-label" for="featuredSwitch">Featured Product</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_top_selling" value="1"
                                id="topSellingSwitch" @checked(old('is_top_selling', $product->is_top_selling))>
                            <label class="form-check-label" for="topSellingSwitch">Top Selling Item</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1"
                                id="newArrivalSwitch" @checked(old('is_new_arrival', $product->is_new_arrival))>
                            <label class="form-check-label" for="newArrivalSwitch">New Arrival</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Organization</h6>
                        <div class="mb-3">
                            <label class="form-label">Main Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="main-category" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-children="{{ $category->children->toJson() }}"
                                        @selected(old('category_id', $product->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-Category</label>
                            <select name="subcategory_id" id="sub-category" class="form-select">
                                <option value="">Select Sub-Category</option>
                                @if($product->category && $product->category->children)
                                    @foreach($product->category->children as $child)
                                        <option value="{{ $child->id }}"
                                            @selected(old('subcategory_id', $product->subcategory_id) == $child->id)>
                                            {{ $child->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        @selected(old('brand_id', $product->brand_id) == $brand->id)>
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
                                <img src="{{ image_url($product->image) }}"
                                    alt="{{ $product->name }}" class="img-fluid rounded shadow-sm">
                            </div>
                        @endif
                        {{-- accept= filters the picker so the wrong format is caught before the
                             upload starts. Explicit list rather than image/*, which lets iPhone
                             HEIC through only to be rejected server-side. --}}
                        <input type="file" name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">JPG, PNG, GIF, WebP or AVIF &middot; max 8MB</div>
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

    <form id="delete-form" action="{{ route('admin.products.destroy', $product) }}" method="POST"
        style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.getElementById('main-category').addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const children = JSON.parse(selected.dataset.children || '[]');
            const subSelect = document.getElementById('sub-category');
            subSelect.innerHTML = '<option value="">Select Sub-Category</option>';
            children.forEach(function (child) {
                const opt = document.createElement('option');
                opt.value = child.id;
                opt.textContent = child.name;
                subSelect.appendChild(opt);
            });
        });

        document.getElementById('name').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection