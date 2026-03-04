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
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <span
                                        class="badge @if($qty < 10) bg-danger @elseif($qty < 30) bg-warning @else bg-success @endif">
                                        {{ $qty }} units
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <form action="{{ route('branch.stocks.update') }}" method="POST"
                                        class="d-flex align-items-center justify-content-end">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" name="quantity" class="form-control" value="{{ $qty }}"
                                                min="0">
                                            <button class="btn btn-jabulani" type="submit">Update</button>
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