@extends('layouts.branch')

@section('title', 'Manage Inventory - ' . $store->name)

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold">Stock Inventory</h5>
            <small class="text-muted">Prices and details are managed by Super Admin. You manage local quantities.</small>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th>SKU</th>
                            <th>Current Quantity</th>
                            <th class="text-end pe-4">Update Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $stock = $product->stocks->first();
                                $qty = $stock->quantity ?? 0;
                                $incoming = $stock->incoming ?? 0;
                                $reserved = $stock->reserved ?? 0;
                                $damaged = $stock->damaged ?? 0;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td class="p-0">
                                    <form action="{{ route('branch.stocks.update') }}" method="POST" class="row g-2 align-items-center p-3 m-0">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <!-- Physical Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label class="form-label mb-1 text-[10px] uppercase font-bold text-success">Physical</label>
                                                <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $qty }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Incoming Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label class="form-label mb-1 text-[10px] uppercase font-bold text-info">Incoming</label>
                                                <input type="number" name="incoming" class="form-control form-control-sm" value="{{ $incoming }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Reserved Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group mb-0">
                                                <label class="form-label mb-1 text-[10px] uppercase font-bold text-warning">Reserved</label>
                                                <input type="number" name="reserved" class="form-control form-control-sm" value="{{ $reserved }}" min="0">
                                            </div>
                                        </div>

                                        <!-- Damaged Stock -->
                                        <div class="col-md-3">
                                            <div class="form-group mb-1">
                                                <label class="form-label mb-1 text-[10px] uppercase font-bold text-danger">Damaged</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" name="damaged" class="form-control" value="{{ $damaged }}" min="0">
                                                    <button class="btn btn-jabulani" type="submit">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No products available in the system yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-top">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection