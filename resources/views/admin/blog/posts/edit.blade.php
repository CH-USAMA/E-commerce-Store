@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <div class="mb-3">
                            <label class="form-label">Post Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" required
                                value="{{ $post->title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                value="{{ $post->slug }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="15" required>{{ $post->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <h6 class="mb-3 fw-bold">Publish Options</h6>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="blog_category_id" class="form-select" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch"
                                {{ $post->is_published ? 'checked' : '' }}>
                            <label class="form-check-label" for="publishSwitch">Published</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feature Image</label>
                            @if($post->feature_image)
                                <div class="mb-2">
                                    <img src="{{ (Str::contains($post->feature_image, 'images/') ? asset($post->feature_image) : asset('' . $post->feature_image)) }}"
                                        class="img-fluid rounded shadow-sm">
                                </div>
                            @endif
                            <input type="file" name="feature_image" class="form-control">
                        </div>
                        <div class="grid d-grid gap-2">
                            <button type="submit" class="btn btn-jabulani">Update Post</button>
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Delete post?')) document.getElementById('delete-form').submit();">Delete
                                Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.getElementById('title').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection