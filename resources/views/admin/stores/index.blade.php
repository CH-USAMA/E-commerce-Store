@extends('layouts.admin')

@section('title', 'Manage Stores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0 fw-bold">Store List</h5>
        <a href="{{ route('admin.stores.create') }}" class="btn btn-jabulani">Add New Store</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Province</th>
                            <th>Manager</th>
                            <th>Location (Lat/Lng)</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $store->name }}</td>
                                <td>{{ $store->province }}</td>
                                <td>
                                    @foreach($store->managers as $manager)
                                        <span class="badge bg-secondary">{{ $manager->name }}</span>
                                    @endforeach
                                    @if($store->managers->isEmpty())
                                        <span class="text-muted small">Unassigned</span>
                                    @endif
                                </td>
                                <td>{{ $store->lat ?? '0' }}, {{ $store->lng ?? '0' }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('store.detail', $store->id) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('admin.stores.edit', $store->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this store?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No stores found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $stores->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection