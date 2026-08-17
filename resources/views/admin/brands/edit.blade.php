@extends('layouts.admin')

@section('title', 'Edit Brand')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ $brand->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                value="{{ $brand->slug }}">
                        </div>
                        <div class="mb-3 text-center">
                            @if($brand->logo)
                                <div class="mb-2">
                                    <img src="{{ image_url($brand->logo) }}" alt="{{ $brand->name }}" width="100"
                                        class="rounded shadow-sm">
                                </div>
                            @endif
                            <label class="form-label d-block text-start">Change Logo (Optional)</label>
                            <input type="file" name="logo"
                                class="form-control @error('logo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png,.gif,.webp,.avif">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-start">JPG, PNG, GIF, WebP or AVIF &middot; max 8MB</div>
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();">Delete</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Brand</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
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