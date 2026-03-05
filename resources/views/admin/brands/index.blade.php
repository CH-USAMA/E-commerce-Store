@extends('layouts.admin')

@section('title', 'Manage Brands')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Brands</h5>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-jabulani">Add New Brand</a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Logo</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                            <tr>
                                <td class="ps-4">
                                    @if($brand->logo)
                                        <img src="{{ asset('' . $brand->logo) }}" alt="{{ $brand->name }}" width="50"
                                            class="rounded shadow-sm">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center fw-bold text-muted"
                                            style="width: 50px; height: 50px;">
                                            {{ substr($brand->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $brand->name }}</td>
                                <td><code>{{ $brand->slug }}</code></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.brands.edit', $brand->id) }}"
                                        class="btn btn-sm btn-outline-dark">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-muted">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
@endsection