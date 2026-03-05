@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Blog Posts</h5>
        <div class="gap-2 d-flex">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-dark">Manage Categories</a>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-jabulani">Write New Post</a>
        </div>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Feature Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td class="ps-4">
                                    @php
                                        $imagePath = $post->feature_image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https'])) {
                                            if (Str::contains($imagePath, 'images/')) {
                                                $imagePath = asset($imagePath);
                                            } else {
                                                $imagePath = asset('' . $imagePath);
                                            }
                                        } elseif (!$imagePath) {
                                            $imagePath = asset('images/placeholder.webp');
                                        }
                                    @endphp
                                    <img src="{{ $imagePath }}" alt="{{ $post->title }}" width="60" class="rounded shadow-sm">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $post->title }}</div>
                                    <small class="text-muted"><code>{{ $post->slug }}</code></small>
                                </td>
                                <td>{{ $post->category->name }}</td>
                                <td>{{ $post->author->name }}</td>
                                <td>
                                    @if($post->is_published)
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('blog.detail', $post->slug) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('admin.blog.edit', $post->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-muted">No blog posts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection