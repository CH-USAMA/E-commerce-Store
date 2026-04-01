@extends('layouts.admin')

@section('title', 'Blog Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Blog Categories</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Organise blog posts by topic</div>
        </div>
        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Category
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Slug</th>
                            <th>Posts Count</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4 fw-semibold" style="font-size: 0.83rem;">{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <span class="badge badge-orange">{{ $category->posts_count }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.blog-categories.edit', $category->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-tags fa-2x d-block mb-2 opacity-20"></i>
                                    No blog categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

@endsection