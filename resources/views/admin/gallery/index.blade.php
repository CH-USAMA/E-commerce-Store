@extends('layouts.admin')

@section('title', 'Gallery')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Photo Gallery</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage website gallery images</div>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Image
        </a>
    </div>

    @if($items->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5" style="color: var(--text-muted);">
                <i class="fas fa-images fa-2x d-block mb-2 opacity-20"></i>
                No gallery items found.
            </div>
        </div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3 mb-3">
            @foreach($items as $item)
                <div class="col">
                    <div class="card h-100" style="overflow: hidden;">
                        <img src="{{ Str::startsWith($item->image, ['http', 'https']) ? $item->image : (Str::contains($item->image, 'images/') ? asset($item->image) : asset($item->image)) }}"
                             alt="{{ $item->title }}"
                             style="width: 100%; height: 160px; object-fit: cover; border-bottom: 1px solid var(--border-default);">
                        <div class="card-body" style="padding: 0.75rem !important;">
                            <div class="fw-semibold text-truncate mb-1" style="font-size: 0.82rem;">{{ $item->title }}</div>
                            <span class="badge badge-orange" style="font-size: 0.65rem;">{{ $item->category }}</span>
                            <div class="d-flex gap-1 mt-2">
                                <a href="{{ route('admin.gallery.edit', $item->id) }}"
                                   class="btn btn-outline-primary btn-sm flex-fill" title="Edit">
                                    <i class="fas fa-pencil-alt me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Delete this image?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div>{{ $items->links() }}</div>
    @endif

@endsection