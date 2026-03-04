@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Products</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.export') }}" class="btn btn-outline-success">
                <i class="fas fa-file-export me-1"></i> Export CSV
            </a>
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i> Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-jabulani">Add New Product</a>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-3">Upload a CSV file with columns: ID, Name, Slug, SKU, Description,
                        Price, VAT Rate, Category, Brand, Featured.</p>
                    <input type="file" name="csv_file" class="form-control" required accept=".csv">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-jabulani">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Product Details</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Total Stock</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    @php
                                        $imagePath = $product->image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https'])) {
                                            if (Str::contains($imagePath, 'images/')) {
                                                $imagePath = asset($imagePath);
                                            } else {
                                                $imagePath = asset('storage/' . $imagePath);
                                            }
                                        } elseif (!$imagePath) {
                                            $imagePath = asset('images/placeholder.webp');
                                        }
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="{{ $product->name }}" width="50" class="rounded shadow-sm">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <div class="small text-muted">SKU: {{ $product->sku }}</div>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>R {{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-dark">
                                        {{ $product->stocks->sum('quantity') }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('product.detail', $product->slug) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection