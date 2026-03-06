@extends('layouts.admin')

@section('title', 'Manage Categories')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Categories</h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-jabulani">Add New Category</a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    @if($category->image)
                                        <img src="{{ (Str::contains($category->image, 'images/') ? asset($category->image) : asset('storage/' . $category->image)) }}"
                                            alt="{{ $category->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center text-muted"
                                            style="width: 40px; height: 40px; border-radius: 8px;">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @if($category->parent_id)
                                        <span class="text-muted ms-3"><i class="fas fa-level-up-alt fa-rotate-90 me-2"></i></span>
                                    @endif
                                    {{ $category->name }}
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->parent->name ?? '--' }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="btn btn-sm btn-outline-dark">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-muted">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection