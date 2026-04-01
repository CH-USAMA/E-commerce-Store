@extends('layouts.admin')

@section('title', 'Products')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem; color: var(--text-primary);">Manage Products</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $products->total() }} total products</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.export') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i> Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-jabulani btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Product
            </a>
        </div>
    </div>

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Import Products</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small mb-3" style="color: var(--text-muted);">
                        Upload a CSV with columns: ID, Name, Slug, SKU, Description, Price, VAT Rate, Category, Brand, Featured.
                    </p>
                    <input type="file" name="csv_file" class="form-control" required accept=".csv">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-jabulani btn-sm">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Total Stock</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    @php
                                        $imagePath = $product->image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https', 'images/'])) {
                                            $imagePath = 'images/' . $imagePath;
                                        }
                                        $finalUrl = $imagePath ? asset($imagePath) : asset('images/placeholder.webp');
                                    @endphp
                                    <img src="{{ $finalUrl }}" alt="{{ $product->name }}"
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-default);">
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.82rem;">{{ $product->name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted); font-family: var(--font-code);">SKU: {{ $product->sku }}</div>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $product->category->name }}</td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">R {{ number_format($product->price, 2) }}</td>
                                <td>
                                    @php $qty = $product->stocks->sum('quantity'); @endphp
                                    <span class="badge {{ $qty > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $qty }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('product.detail', $product->slug) }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View on site">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-box-open fa-2x d-block mb-2 opacity-20"></i>
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection