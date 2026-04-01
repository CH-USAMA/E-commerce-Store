@extends('layouts.admin')

@section('title', 'Stores')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Store List</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Branch and distribution point management</div>
        </div>
        <a href="{{ route('admin.stores.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Store
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Province</th>
                            <th>Manager</th>
                            <th>Location (Lat / Lng)</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold" style="font-size: 0.83rem;">{{ $store->name }}</div>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $store->province }}</td>
                                <td>
                                    @foreach($store->managers as $manager)
                                        <span class="badge bg-secondary me-1">{{ $manager->name }}</span>
                                    @endforeach
                                    @if($store->managers->isEmpty())
                                        <span style="font-size: 0.75rem; color: var(--text-muted);">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <code style="font-size: 0.75rem;">{{ $store->lat ?? '0' }}, {{ $store->lng ?? '0' }}</code>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('store.detail', $store) }}" target="_blank"
                                           class="btn btn-outline-secondary btn-sm" title="View on site">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('admin.stores.edit', $store) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.stores.destroy', $store) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this store?')">
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
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-store fa-2x d-block mb-2 opacity-20"></i>
                                    No stores found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $stores->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection