@extends('layouts.admin')

@section('title', 'Edit Blog Category')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.blog-categories.update', $blogCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ $blogCategory->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                value="{{ $blogCategory->slug }}">
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();">Delete</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.blog-categories.index') }}"
                                    class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Category</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.blog-categories.destroy', $blogCategory->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('name').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection