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
                            <th class="ps-4" style="width: 90px;">Order</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Link</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                {{-- Rows are already in slider order, so $loop maps to the
                                     visible sequence on the homepage. --}}
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;"
                                              title="Slide {{ $loop->iteration }} of {{ $banners->count() }}">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="d-flex flex-column">
                                            <form action="{{ route('admin.banners.move', [$banner, 'up']) }}"
                                                  method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                                                        style="line-height: 1;" title="Move up"
                                                        @disabled($loop->first)>
                                                    <i class="fas fa-chevron-up" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.banners.move', [$banner, 'down']) }}"
                                                  method="POST" class="m-0 mt-1">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                                                        style="line-height: 1;" title="Move down"
                                                        @disabled($loop->last)>
                                                    <i class="fas fa-chevron-down" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ image_url($banner->image) }}"
                                         alt="{{ $banner->title }}"
                                         style="width: 80px; height: 42px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                    @if($banner->image_mobile)
                                        <div class="mt-1" style="font-size: 0.62rem; color: var(--success-color);">
                                            <i class="fas fa-mobile-screen"></i> mobile set
                                        </div>
                                    @else
                                        <div class="mt-1" style="font-size: 0.62rem; color: var(--text-muted);">
                                            <i class="fas fa-mobile-screen"></i> desktop only
                                        </div>
                                    @endif
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
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
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
                                <td colspan="6" class="text-center py-5" style="color: var(--text-muted);">
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