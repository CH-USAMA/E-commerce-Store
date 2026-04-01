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
                                                    value="{{ $stock->quantity ?? 0 }}" min="0">
                                            </td>
                                            <td>
                                                <input type="number" name="stocks[{{ $store->id }}][incoming]" 
                                                    class="form-control form-control-sm border-info-subtle bg-info-subtle/5" 
                                                    value="{{ $stock->incoming ?? 0 }}" min="0">
                                            </td>
                                            <td>
                                                <input type="number" name="stocks[{{ $store->id }}][reserved]" 
                                                    class="form-control form-control-sm border-warning-subtle bg-warning-subtle/5" 
                                                    value="{{ $stock->reserved ?? 0 }}" min="0">
                                            </td>
                                            <td class="pe-3">
                                                <input type="number" name="stocks[{{ $store->id }}][damaged]" 
                                                    class="form-control form-control-sm border-danger-subtle bg-danger-subtle/5" 
                                                    value="{{ $stock->damaged ?? 0 }}" min="0">
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
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_top_selling" value="1"
                                id="topSellingSwitch" {{ $product->is_top_selling ? 'checked' : '' }}>
                            <label class="form-check-label" for="topSellingSwitch">Top Selling Item</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1"
                                id="newArrivalSwitch" {{ $product->is_new_arrival ? 'checked' : '' }}>
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
                                    <option value="{{ $category->id }}" data-children="{{ $category->children->toJson() }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
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
                                        <option value="{{ $child->id }}" {{ $product->subcategory_id == $child->id ? 'selected' : '' }}>
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
                                <img src="{{ (Str::contains($product->image, 'images/') ? asset($product->image) : asset('' . $product->image)) }}"
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