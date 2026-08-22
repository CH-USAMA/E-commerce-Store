@extends('layouts.admin')

@section('title', 'Add Category')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="e.g. Building Materials">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                placeholder="building-materials">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level)</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">
                                Display Order <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <input type="number" name="sort_order" id="sort_order" min="0"
                                class="form-control @error('sort_order') is-invalid @enderror"
                                value="{{ old('sort_order') }}" placeholder="Leave empty to add at the end">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Lowest number shows first. The order runs within one level &mdash;
                                a sub-category is ordered against its siblings under the same parent,
                                not against top-level categories. Leave empty and this category goes
                                last in its list; you can reorder any time with the arrows on the
                                categories list.
                            </div>
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-end">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Create Category</button>
                        </div>
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