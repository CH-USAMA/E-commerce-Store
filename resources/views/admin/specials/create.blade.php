@extends('layouts.admin')

@section('title', 'Add Special')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.specials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" required
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" placeholder="e.g. Mt Frere Specials">
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
                                value="{{ old('subtitle', 'Available at Branch Only') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Small line under the title on the card.</div>
                        </div>

                        <div class="mb-3">
                            <label for="image_full" class="form-label">Flyer Image</label>
                            <input type="file" name="image_full" id="image_full" required
                                class="form-control @error('image_full') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image_full')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Upload the full-resolution flyer &mdash; this is what opens when a
                                visitor clicks the card. A compressed copy is generated automatically
                                for the grid, so the page stays fast without you resizing anything.
                                <br>JPG, PNG, GIF, WebP or AVIF &middot; max 8MB.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sort_order" class="form-label">
                                Display Order <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order') }}" placeholder="Leave empty to add at the end">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Lowest number shows first. Leave empty and this special goes last
                                &mdash; you can reorder any time with the arrows on the specials list.
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="form-check-input" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">
                                Show on the website
                            </label>
                            <div class="form-text">
                                Uncheck to keep the special here but hide it from visitors &mdash;
                                useful when a season ends and you expect it back.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.specials.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-jabulani px-4">Save Special</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
