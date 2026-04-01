@extends('layouts.admin')

@section('title', 'Team Members')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Team Members</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage staff displayed on the website</div>
        </div>
        <a href="{{ route('admin.team.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-user-plus me-1"></i> Add New Member
        </a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Photo</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Bio Snippet</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="ps-4">
                                    @php
                                        $imagePath = $member->image;
                                        if ($imagePath && !Str::startsWith($imagePath, ['http', 'https', 'images/'])) {
                                            $imagePath = 'images/' . $imagePath;
                                        }
                                        $finalUrl = $imagePath ? asset($imagePath) : asset('images/placeholder.webp');
                                    @endphp
                                    <img src="{{ $finalUrl }}" alt="{{ $member->name }}"
                                         style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%; border: 1px solid var(--border-default);">
                                </td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">{{ $member->name }}</td>
                                <td>
                                    <span class="badge badge-orange">{{ $member->role }}</span>
                                </td>
                                <td style="font-size: 0.78rem; color: var(--text-secondary); max-width: 220px;" class="text-truncate">
                                    {{ \Illuminate\Support\Str::limit($member->bio, 60) }}
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.team.edit', $member->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.team.destroy', $member->id) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Remove this team member?')">
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
                                    <i class="fas fa-user-friends fa-2x d-block mb-2 opacity-20"></i>
                                    No team members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $members->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

@endsection