@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.services.update', $service->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" id="title" class="form-control" required
                                value="{{ $service->title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" required
                                value="{{ $service->slug }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Icon Class (Bootstrap Icons)</label>
                            <input type="text" name="icon" class="form-control" value="{{ $service->icon }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="5"
                                required>{{ $service->description }}</textarea>
                        </div>
                        <div class="mb-3 text-center">
                            @if($service->image)
                                <div class="mb-2">
                                    <img src="{{ (Str::contains($service->image, 'images/') ? asset($service->image) : asset('' . $service->image)) }}"
                                        alt="{{ $service->title }}" class="img-fluid rounded shadow-sm"
                                        style="max-height: 200px;">
                                </div>
                            @endif
                            <label class="form-label d-block text-start">Change Image (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();">Delete</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Service</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('title').addEventListener('input', function () {
            let slug = this.value.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
@endsection