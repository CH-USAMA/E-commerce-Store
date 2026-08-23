@extends('layouts.admin')

@section('title', 'Edit Special')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.specials.update', $special) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" required
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $special->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subtitle" class="form-label">
                                Subtitle <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <input type="text" name="subtitle" id="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $special->subtitle) }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_full" class="form-label">Flyer Image</label>

                            <div class="mb-2 d-flex align-items-start gap-3 flex-wrap">
                                <div>
                                    <img src="{{ image_url($special->image_full) }}" alt="{{ $special->title }}"
                                        class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                    <div class="mt-1 text-center" style="font-size: 0.65rem; color: var(--text-muted);">
                                        Full flyer (lightbox)
                                    </div>
                                </div>
                                <div>
                                    <img src="{{ image_url($special->grid_image) }}" alt="{{ $special->title }} thumbnail"
                                        class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                                    <div class="mt-1 text-center" style="font-size: 0.65rem;
                                        color: {{ $special->image ? 'var(--success-color)' : 'var(--text-muted)' }};">
                                        @if($special->image)
                                            Generated thumbnail (grid)
                                        @else
                                            No thumbnail &mdash; grid uses the full flyer
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <input type="file" name="image_full" id="image_full"
                                class="form-control @error('image_full') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image_full')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Leave empty to keep the current flyer. Uploading a new one replaces
                                both images &mdash; the compressed grid copy is regenerated automatically.
                                <br>JPG, PNG, GIF, WebP or AVIF &middot; max 8MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', $special->sort_order) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Lowest number shows first.</div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="form-check-input" {{ old('is_active', $special->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Show on the website</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Delete this special?')) document.getElementById('delete-form').submit();">
                                Delete
                            </button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.specials.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Special</button>
                            </div>
                        </div>
                    </form>

                    <form id="delete-form" action="{{ route('admin.specials.destroy', $special) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
