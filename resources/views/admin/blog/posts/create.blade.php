@extends('layouts.admin')

@section('title', 'Write New Post')

@section('content')
    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4 border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        <div class="mb-3">
                            <label class="form-label">Post Title</label>
                            <input type="text" name="title" id="title" class="form-control form-control-lg" required
                                placeholder="Enter post title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required placeholder="post-slug">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" rows="15" required
                                placeholder="Write your content here..."></textarea>
                            <small class="text-muted">HTML tags are supported for formatting.</small>
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
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishSwitch"
                                checked>
                            <label class="form-check-label" for="publishSwitch">Publish Immediately</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feature Image</label>
                            <input type="file" name="feature_image" class="form-control">
                        </div>
                        <div class="grid d-grid">
                            <button type="submit" class="btn btn-jabulani">Save Post</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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