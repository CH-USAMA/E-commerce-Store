@extends('layouts.admin')

@section('title', 'Add Gallery Image')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Image Title</label>
                            <input type="text" name="title" class="form-control" required
                                placeholder="e.g. Storefront at Night">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Projects">Projects</option>
                                <option value="Store">Store</option>
                                <option value="Products">Products</option>
                                <option value="Events">Events</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Image</label>
                            <input type="file" name="image" class="form-control" required>
                            <small class="text-muted">High resolution images recommended.</small>
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-end">
                            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection