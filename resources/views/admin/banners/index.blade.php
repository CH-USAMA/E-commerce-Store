@extends('layouts.admin')

@section('title', 'Banner Management (BM)')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Banners</h5>
            <a href="{{ route('admin.banners.create') }}" class="btn btn-jabulani">Add New Banner</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td>
                                    <img src="{{ (Str::contains($banner->image, 'uploads/') || Str::contains($banner->image, 'images/')) ? asset($banner->image) : asset('' . $banner->image) }}"
                                        alt="{{ $banner->title }}" width="100">
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ $banner->subtitle }}</td>
                                <td>{{ $banner->link }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('home') }}" target="_blank"
                                            class="btn btn-sm btn-outline-info">View</a>
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection