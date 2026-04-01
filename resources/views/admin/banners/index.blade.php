@extends('layouts.admin')

@section('title', 'Banners')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold" style="font-size: 0.83rem;">All Banners</div>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-jabulani btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Banner
            </a>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Link</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td class="ps-4">
                                    <img src="{{ (Str::contains($banner->image, 'uploads/') || Str::contains($banner->image, 'images/')) ? asset($banner->image) : asset($banner->image) }}"
                                         alt="{{ $banner->title }}"
                                         style="width: 80px; height: 42px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                </td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">{{ $banner->title }}</td>
                                <td style="font-size: 0.78rem; color: var(--text-secondary); max-width: 200px;" class="text-truncate">{{ $banner->subtitle }}</td>
                                <td>
                                    @if($banner->link)
                                        <a href="{{ $banner->link }}" target="_blank"
                                           style="font-size: 0.75rem; color: var(--orange-400); text-decoration: none;">
                                            <i class="fas fa-link me-1"></i>{{ Str::limit($banner->link, 30) }}
                                        </a>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 0.75rem;">—</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('home') }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View on site">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this banner?')">
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
                                    <i class="fas fa-image fa-2x d-block mb-2 opacity-20"></i>
                                    No banners found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection