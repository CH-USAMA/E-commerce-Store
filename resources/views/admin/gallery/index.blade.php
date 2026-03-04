@extends('layouts.admin')

@section('title', 'Gallery Management')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Photo Gallery</h5>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-jabulani">Add New Image</a>
    </div>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($items as $item)
            <div class="col">
                <div class="h-100 border-0 shadow-sm card">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top object-fit-cover"
                        alt="{{ $item->title }}" style="height: 200px;">
                    <div class="p-3 card-body">
                        <h6 class="mb-1 card-title fw-bold text-truncate">{{ $item->title }}</h6>
                        <span class="badge bg-light text-dark mb-3">{{ $item->category }}</span>
                        <div class="gap-2 d-flex">
                            <a href="{{ route('admin.gallery.edit', $item->id) }}"
                                class="btn btn-sm btn-outline-dark flex-grow-1">Edit</a>
                            <form action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Delete this image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Del</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                No gallery items found.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection