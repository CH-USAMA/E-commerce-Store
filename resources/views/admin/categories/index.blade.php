@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Manage Categories</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Product taxonomy management</div>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Category
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    @if($category->image)
                                        <img src="{{ Str::contains($category->image, 'images/') ? asset($category->image) : asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             style="width: 36px; height: 36px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                    @else
                                        <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--surface-overlay); border: 1px solid var(--border-default); display:flex; align-items:center; justify-content:center;">
                                            <i class="fas fa-folder" style="font-size: 0.75rem; color: var(--text-muted);"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.83rem;">
                                        @if($category->parent_id)
                                            <i class="fas fa-level-up-alt fa-rotate-90 me-2" style="font-size: 0.7rem; color: var(--text-muted);"></i>
                                        @endif
                                        {{ $category->name }}
                                    </div>
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $category->parent->name ?? '—' }}</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-folder-open fa-2x d-block mb-2 opacity-20"></i>
                                    No categories found.
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