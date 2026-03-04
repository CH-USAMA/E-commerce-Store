@extends('layouts.admin')

@section('title', 'Manage Services')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Company Services</h5>
        <a href="{{ route('admin.services.create') }}" class="btn btn-jabulani">Add New Service</a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>Slug</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td class="ps-4">
                                    @if($service->image)
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" width="50"
                                            class="rounded shadow-sm">
                                    @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center fw-bold text-muted"
                                            style="width: 50px; height: 50px;">
                                            NA
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $service->title }}</td>
                                <td><i class="bi bi-{{ $service->icon }}"></i> <code>{{ $service->icon }}</code></td>
                                <td><code>{{ $service->slug }}</code></td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('services') }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary me-2">View</a>
                                    <a href="{{ route('admin.services.edit', $service->id) }}"
                                        class="btn btn-sm btn-outline-dark">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-muted">No services found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $services->links() }}
            </div>
        </div>
    </div>
@endsection