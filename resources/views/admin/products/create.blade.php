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
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="e.g. AfriSam All Purpose Cement 50kg">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" name="slug" id="slug" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-control" required placeholder="JAB-CEM-001">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5"></textarea>
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
                                    <input type="number" name="stocks[{{ $store->id }}]" class="form-control" value="0" min="0">
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
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">VAT Rate (%)</label>
                            <input type="number" step="0.01" name="vat_rate" class="form-control" value="15.00" required>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                id="featuredSwitch">
                            <label class="form-check-label" for="featuredSwitch">Featured Product</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_top_selling" value="1"
                                id="topSellingSwitch">
                            <label class="form-check-label" for="topSellingSwitch">Top Selling Item</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_new_arrival" value="1"
                                id="newArrivalSwitch">
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
                                    <option value="{{ $category->id }}" data-children="{{ $category->children->toJson() }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-Category</label>
                            <select name="subcategory_id" id="sub-category" class="form-select">
                                <option value="">Select Sub-Category</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Product Image</h6>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="grid d-grid">
                    <button type="submit" class="btn btn-jabulani btn-lg">Save Product</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        document.getElementById('name').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });

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
    </script>
@endsection