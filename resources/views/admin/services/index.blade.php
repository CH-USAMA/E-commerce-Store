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
                                    @php
                                        $imagePath = $service->image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https', 'images/'])) {
                                            $imagePath = 'images/' . $imagePath;
                                        }
                                        $finalUrl = $imagePath ? asset($imagePath) : asset('images/placeholder.webp');
                                    @endphp
                                    <img src="{{ $finalUrl }}" alt="{{ $service->title }}" width="50" class="rounded shadow-sm">
                                </td>
                                <td class="fw-bold">{{ $service->title }}</td>
                                <td><i class="bi bi-{{ $service->icon }}"></i> <code>{{ $service->icon }}</code></td>
                                <td><code>{{ $service->slug }}</code></td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('services') }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('admin.services.edit', $service->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this service?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
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
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection