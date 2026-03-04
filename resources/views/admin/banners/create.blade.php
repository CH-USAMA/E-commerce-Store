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
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" name="image" id="image" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link (Optional)</label>
                            <input type="text" name="link" id="link" class="form-control">
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