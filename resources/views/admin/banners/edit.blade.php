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
                            <input type="text" name="title" id="title" class="form-control" value="{{ $banner->title }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control"
                                value="{{ $banner->subtitle }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"
                                rows="3">{{ $banner->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">Current Image</label>
                            <img src="{{ (Str::contains($banner->image, 'images/') ? asset($banner->image) : asset('' . $banner->image)) }}"
                                alt="{{ $banner->title }}" class="img-thumbnail mb-2" width="200">
                            <input type="file" name="image" id="image" class="form-control">
                            <small class="text-muted">Leave empty to keep current image.</small>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (Optional)</label>
                            <input type="text" name="link" id="link" class="form-control" value="{{ $banner->link }}">
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