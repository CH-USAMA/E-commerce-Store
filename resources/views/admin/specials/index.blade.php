@extends('layouts.admin')

@section('title', 'Specials')

@section('content')

    {{-- Page header background. A setting rather than a column: it belongs to the
         /specials page, not to any one special. Kept on this screen so it is found
         where it is used, instead of buried in System Settings. --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <img src="{{ image_url($heroImage) }}" alt="Specials page header"
                     style="width: 120px; height: 56px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                <div class="flex-grow-1" style="min-width: 220px;">
                    <div class="fw-bold" style="font-size: 0.8rem;">Page Header Image</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">
                        Background behind the &ldquo;Exclusive Deals&rdquo; title on the public
                        specials page. Shown at 10% opacity, so a busy image still reads well.
                    </div>
                </div>
                <form action="{{ route('admin.specials.hero') }}" method="POST"
                      enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                    @csrf
                    <input type="file" name="hero_image" required
                           class="form-control form-control-sm @error('hero_image') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.gif,.webp,.avif" style="max-width: 230px;">
                    <button type="submit" class="btn btn-outline-primary btn-sm">Replace</button>
                </form>
            </div>
            @error('hero_image')
                <div class="mt-2" style="font-size: 0.72rem; color: var(--danger-color);">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <div class="fw-bold" style="font-size: 0.83rem;">Seasonal Specials</div>
                <div style="font-size: 0.7rem; color: var(--text-muted);">
                    Branch flyers shown on the public specials page, in this order
                </div>
            </div>
            <a href="{{ route('admin.specials.create') }}" class="btn btn-jabulani btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Special
            </a>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 90px;">Order</th>
                            <th style="width: 100px;">Preview</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows are already in grid order, so $loop maps to the visible
                             sequence on the public page. --}}
                        @forelse($specials as $special)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;"
                                              title="Position {{ $loop->iteration }} of {{ $specials->count() }}">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="d-flex flex-column">
                                            <form action="{{ route('admin.specials.move', [$special, 'up']) }}"
                                                  method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                                                        style="line-height: 1;" title="Move up" @disabled($loop->first)>
                                                    <i class="fas fa-chevron-up" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.specials.move', [$special, 'down']) }}"
                                                  method="POST" class="m-0 mt-1">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-1"
                                                        style="line-height: 1;" title="Move down" @disabled($loop->last)>
                                                    <i class="fas fa-chevron-down" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ image_url($special->grid_image) }}" alt="{{ $special->title }}"
                                         style="width: 84px; height: 56px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                    @if($special->image)
                                        <div class="mt-1" style="font-size: 0.62rem; color: var(--success-color);">
                                            <i class="fas fa-bolt"></i> compressed
                                        </div>
                                    @else
                                        <div class="mt-1" style="font-size: 0.62rem; color: var(--warning-color, #d97706);"
                                             title="No thumbnail was generated, so the grid serves the full-size flyer">
                                            <i class="fas fa-triangle-exclamation"></i> full size
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.83rem;">{{ $special->title }}</div>
                                    @if($special->subtitle)
                                        <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $special->subtitle }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($special->is_active)
                                        <span class="badge bg-success" style="font-size: 0.65rem;">Visible</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.65rem;">Hidden</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('specials') }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View on site">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.specials.edit', $special) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.specials.destroy', $special) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this special?')">
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
                                    <i class="fas fa-tags fa-2x d-block mb-2 opacity-20"></i>
                                    No specials yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
