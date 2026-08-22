@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ $category->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                value="{{ $category->slug }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            @if($category->image)
                                <div class="mb-2">
                                    <img src="{{ image_url($category->image) }}"
                                        alt="{{ $category->name }}" class="img-fluid rounded shadow-sm"
                                        style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">JPG, PNG, GIF, WebP or AVIF &middot; max 8MB</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Display Order</label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order', $category->sort_order) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Lowest number shows first, within this category's own level.
                                Clear it and move the category to a different parent to send it to
                                the end of the new list.
                            </div>
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure? This will delete the category.')) document.getElementById('delete-form').submit();">Delete</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Category</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                        style="display: none;">
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