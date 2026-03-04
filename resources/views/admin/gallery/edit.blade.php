@extends('layouts.admin')

@section('title', 'Edit Gallery Image')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.gallery.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Image Title</label>
                            <input type="text" name="title" class="form-control" required value="{{ $item->title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Projects" {{ $item->category == 'Projects' ? 'selected' : '' }}>Projects
                                </option>
                                <option value="Store" {{ $item->category == 'Store' ? 'selected' : '' }}>Store</option>
                                <option value="Products" {{ $item->category == 'Products' ? 'selected' : '' }}>Products
                                </option>
                                <option value="Events" {{ $item->category == 'Events' ? 'selected' : '' }}>Events</option>
                            </select>
                        </div>
                        <div class="mb-3 text-center">
                            <img src="{{ (Str::contains($item->image, 'images/') ? asset($item->image) : asset('storage/' . $item->image)) }}"
                                class="img-fluid rounded shadow-sm mb-3" style="max-height: 300px;">
                            <label class="form-label d-block text-start">Replace Image (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-end">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Update Image</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection