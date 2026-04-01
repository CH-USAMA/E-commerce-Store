@extends('layouts.admin')

@section('title', 'Brands')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Manage Brands</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Product brand registry</div>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Brand
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Logo</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td class="ps-4">
                                    @if($brand->logo)
                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}"
                                             style="width: 36px; height: 36px; object-fit: contain; border-radius: var(--radius-sm); background: rgba(255,255,255,0.05); padding: 3px; border: 1px solid var(--border-default);">
                                    @else
                                        <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: rgba(255,140,0,0.1); border: 1px solid rgba(255,140,0,0.2); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.75rem; color: var(--orange-400);">
                                            {{ strtoupper(substr($brand->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">{{ $brand->name }}</td>
                                <td><code>{{ $brand->slug }}</code></td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-tag fa-2x d-block mb-2 opacity-20"></i>
                                    No brands found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $brands->links() }}
            </div>
        </div>
    </div>

@endsection