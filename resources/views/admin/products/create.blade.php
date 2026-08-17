@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">General Information</h6>
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" required
                                value="{{ old('name') }}"
                                placeholder="e.g. AfriSam All Purpose Cement 50kg">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug"
                                    class="form-control @error('slug') is-invalid @enderror" required
                                    value="{{ old('slug') }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku"
                                    class="form-control @error('sku') is-invalid @enderror" required
                                    value="{{ old('sku') }}" placeholder="JAB-CEM-001">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Inventory per Store</h6>
                        <div class="row">
                            @foreach($stores as $store)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ $store->name }} (Quantity)</label>
                                    <input type="number" name="stocks[{{ $store->id }}]"
                                        class="form-control @error('stocks.'.$store->id) is-invalid @enderror"
                                        value="{{ old('stocks.'.$store->id, 0) }}" min="0">
                                    @error('stocks.'.$store->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Pricing &amp; Status</h6>
                        <div class="mb-3">
                            <label class="form-label">Base Price (R)</label>
                            <input type="number" step="0.01" name="price"
                                class="form-control @error('price') is-invalid @enderror" required
                                value="{{ old('price') }}">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Rate (%)</label>
                            <input type="number" step="0.01" name="vat_rate"
                                class="form-control @error('vat_rate') is-invalid @enderror" required
                                value="{{ old('vat_rate', '0.00') }}">
                            @error('vat_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                id="featuredSwitch" @checked(old('is_featured'))>
                            <label class="form-check-label" for="featuredSwitch">Featured Product</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_top_selling" value="1"
                                id="topSellingSwitch" @checked(old('is_top_selling'))>
                            <label class="form-check-label" for="topSellingSwitch">Top Selling Item</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1"
                                id="newArrivalSwitch" @checked(old('is_new_arrival'))>
                            <label class="form-check-label" for="newArrivalSwitch">New Arrival</label>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Organization</h6>
                        <div class="mb-3">
                            <label class="form-label">Main Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="main-category"
                                class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-children="{{ $category->children->toJson() }}"
                                        @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-Category</label>
                            {{-- Options are filled in by the script below from the selected main
                                 category's children; data-selected restores it after a failed submit. --}}
                            <select name="subcategory_id" id="sub-category"
                                class="form-select @error('subcategory_id') is-invalid @enderror"
                                data-selected="{{ old('subcategory_id') }}">
                                <option value="">Select Sub-Category</option>
                            </select>
                            @error('subcategory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Product Image</h6>
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
                        <div class="form-text text-warning">
                            A re-selected file cannot be remembered if the form is rejected &mdash;
                            pick the image again if you see an error above.
                        </div>
                    </div>
                </div>

                <div class="grid d-grid">
                    <button type="submit" class="btn btn-jabulani btn-lg">Save Product</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        nameInput.addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            slugInput.value = slug;
        });

        const mainCategory = document.getElementById('main-category');
        const subSelect = document.getElementById('sub-category');

        function populateSubCategories(preselect) {
            const selected = mainCategory.options[mainCategory.selectedIndex];
            const children = JSON.parse(selected.dataset.children || '[]');
            subSelect.innerHTML = '<option value="">Select Sub-Category</option>';
            children.forEach(function (child) {
                const opt = document.createElement('option');
                opt.value = child.id;
                opt.textContent = child.name;
                if (preselect && String(child.id) === String(preselect)) {
                    opt.selected = true;
                }
                subSelect.appendChild(opt);
            });
        }

        mainCategory.addEventListener('change', function () {
            populateSubCategories(null);
        });

        // Restore the sub-category after a validation failure bounced the form back.
        if (mainCategory.value) {
            populateSubCategories(subSelect.dataset.selected);
        }
    </script>
@endsection
