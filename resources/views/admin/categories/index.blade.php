@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Manage Categories</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">
                Product taxonomy &middot; listed in the order shoppers see them
            </div>
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
                            <th class="ps-4" style="width: 90px;">Order</th>
                            <th style="width: 70px;">Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Rows come back in storefront order (Category::ordered), so
                             $loop->iteration is the position a shopper actually sees.
                             Sub-categories are ordered within their own parent, which is
                             why each nested @foreach gets its own $loop boundaries. --}}
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    @include('admin.categories.partials.move', [
                                        'row'      => $category,
                                        'position' => $loop->iteration,
                                        'total'    => $categories->count(),
                                        'isFirst'  => $loop->first,
                                        'isLast'   => $loop->last,
                                    ])
                                </td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ image_url($category->image) }}"
                                             alt="{{ $category->name }}"
                                             style="width: 36px; height: 36px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                    @else
                                        <div style="width: 36px; height: 36px; border-radius: var(--radius-sm); background: var(--surface-overlay); border: 1px solid var(--border-default); display:flex; align-items:center; justify-content:center;">
                                            <i class="fas fa-folder" style="font-size: 0.75rem; color: var(--text-muted);"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.83rem;">{{ $category->name }}</div>
                                </td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td style="font-size: 0.82rem; color: var(--text-muted);">&mdash;</td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                </td>
                            </tr>

                            @foreach($category->children as $child)
                                <tr>
                                    <td class="ps-4">
                                        @include('admin.categories.partials.move', [
                                            'row'      => $child,
                                            'position' => $loop->parent->iteration . '.' . $loop->iteration,
                                            'total'    => $category->children->count(),
                                            'isFirst'  => $loop->first,
                                            'isLast'   => $loop->last,
                                        ])
                                    </td>
                                    <td>
                                        @if($child->image)
                                            <img src="{{ image_url($child->image) }}"
                                                 alt="{{ $child->name }}"
                                                 style="width: 30px; height: 30px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                        @else
                                            <div style="width: 30px; height: 30px; border-radius: var(--radius-sm); background: var(--surface-overlay); border: 1px solid var(--border-default); display:flex; align-items:center; justify-content:center;">
                                                <i class="fas fa-folder" style="font-size: 0.65rem; color: var(--text-muted);"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                            <i class="fas fa-level-up-alt fa-rotate-90 me-2" style="font-size: 0.7rem; color: var(--text-muted);"></i>
                                            {{ $child->name }}
                                        </div>
                                    </td>
                                    <td><code>{{ $child->slug }}</code></td>
                                    <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $category->name }}</td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.categories.edit', $child) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-folder-open fa-2x d-block mb-2 opacity-20"></i>
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
