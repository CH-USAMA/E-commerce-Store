@extends('layouts.admin')

@section('title', 'Services')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Company Services</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage services displayed on the website</div>
        </div>
        <a href="{{ route('admin.services.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Service
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>Slug</th>
                            <th class="pe-4 text-end">Action</th>
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
                                    <img src="{{ $finalUrl }}" alt="{{ $service->title }}"
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                </td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">{{ $service->title }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-{{ $service->icon }}" style="color: var(--orange-400);"></i>
                                        <code>{{ $service->icon }}</code>
                                    </div>
                                </td>
                                <td><code>{{ $service->slug }}</code></td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('services') }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View on site">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this service?')">
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
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-concierge-bell fa-2x d-block mb-2 opacity-20"></i>
                                    No services found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection