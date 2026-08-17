@extends('layouts.admin')

@section('title', 'Add New Banner')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create Banner</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle"
                                class="form-control @error('subtitle') is-invalid @enderror"
                                value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description"
                                class="form-control @error('description') is-invalid @enderror"
                                rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            {{-- accept= filters the picker so the wrong format is caught before
                                 the upload starts. Explicit list rather than image/*, which lets
                                 iPhone HEIC through only to be rejected server-side. --}}
                            <input type="file" name="image" id="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                JPG, PNG, GIF, WebP or AVIF &middot; max 8MB &middot;
                                landscape, around 1920&times;1080
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image_mobile" class="form-label">
                                Mobile Image <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <input type="file" name="image_mobile" id="image_mobile"
                                class="form-control @error('image_mobile') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image_mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                A portrait crop for phones, around 1080&times;1350. Leave empty
                                and the desktop image is used &mdash; but on a narrow screen it
                                gets cropped to the centre, which can cut off the subject.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (Optional)</label>
                            <input type="text" name="link" id="link"
                                class="form-control @error('link') is-invalid @enderror"
                                value="{{ old('link') }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button type="submit" class="btn btn-jabulani">Save Banner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
