@extends('layouts.admin')

@section('title', 'Blog Categories')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Blog Categories</h5>
        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-jabulani">Add New Category</a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Slug</th>
                            <th>Posts Count</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ $category->posts_count }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.blog-categories.edit', $category->id) }}"
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