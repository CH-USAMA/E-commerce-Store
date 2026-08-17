@extends('layouts.admin')

@section('title', 'Edit Banner')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Banner: {{ $banner->title }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $banner->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle', $banner->subtitle) }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3">{{ old('description', $banner->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Desktop Image</label>
                            <img src="{{ image_url($banner->image) }}"
                                alt="{{ $banner->title }}" class="img-thumbnail mb-2" width="200">
                            <input type="file" name="image" id="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave empty to keep current image.</small>
                            <div class="form-text">
                                JPG, PNG, GIF, WebP or AVIF &middot; max 8MB &middot;
                                landscape, around 1920&times;1080
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">
                                Mobile Image <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            @if($banner->image_mobile)
                                <img src="{{ image_url($banner->image_mobile) }}"
                                    alt="{{ $banner->title }} (mobile)" class="img-thumbnail mb-2"
                                    style="max-height: 160px; width: auto;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1"
                                        name="remove_image_mobile" id="remove_image_mobile">
                                    <label class="form-check-label" for="remove_image_mobile"
                                        style="font-size: 0.8rem;">
                                        Remove the mobile image and fall back to the desktop one
                                    </label>
                                </div>
                            @else
                                <div class="mb-2" style="font-size: 0.78rem; color: var(--text-muted);">
                                    <i class="fas fa-mobile-screen me-1"></i>
                                    None set &mdash; phones currently show the desktop image,
                                    cropped to its centre.
                                </div>
                            @endif
                            <input type="file" name="image_mobile" id="image_mobile"
                                class="form-control @error('image_mobile') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image_mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Portrait crop for phones, around 1080&times;1350.</div>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (Optional)</label>
                            <input type="text" name="link" id="link"
                                class="form-control @error('link') is-invalid @enderror"
                                value="{{ old('link', $banner->link) }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', $banner->sort_order) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Lowest number shows first in the slider. The arrows on the
                                banners list are usually easier than editing this by hand.
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-jabulani">Update Banner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
