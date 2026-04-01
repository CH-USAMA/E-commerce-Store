@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Blog Posts</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Create, manage and publish website articles</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-tags me-1"></i> Manage Categories
            </a>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-jabulani btn-sm">
                <i class="fas fa-plus me-1"></i> Write New Post
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Feature Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td class="ps-4">
                                    @php
                                        $imagePath = $post->feature_image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https'])) {
                                            $imagePath = Str::contains($imagePath, 'images/') ? asset($imagePath) : asset($imagePath);
                                        } elseif (!$imagePath) {
                                            $imagePath = asset('images/placeholder.webp');
                                        }
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="{{ $post->title }}"
                                         style="width: 52px; height: 36px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border-default);">
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.83rem;">{{ $post->title }}</div>
                                    <code style="font-size: 0.68rem;">{{ $post->slug }}</code>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $post->category->name }}</td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $post->author->name }}</td>
                                <td>
                                    @if($post->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td style="font-size: 0.78rem; color: var(--text-muted);">{{ $post->created_at->format('d M Y') }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('blog.detail', $post->slug) }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View post">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.blog.edit', $post->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this blog post?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-newspaper fa-2x d-block mb-2 opacity-20"></i>
                                    No blog posts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $posts->links() }}
            </div>
        </div>
    </div>

@endsection