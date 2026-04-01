@extends('layouts.admin')

@section('title', 'Staff')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold" style="font-size: 0.83rem;">System Users</div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-jabulani btn-sm">
                <i class="fas fa-user-plus me-1"></i> Add New User
            </a>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=FF8C00&color=fff&bold=true&size=28"
                                             width="28" height="28" class="rounded-circle flex-shrink-0" alt="{{ $user->name }}">
                                        <span class="fw-semibold" style="font-size: 0.83rem;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td style="font-size: 0.82rem; color: var(--text-secondary);">{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleClass = [
                                            'admin'        => 'bg-danger',
                                            'manager'      => 'bg-primary',
                                            'receptionist' => 'bg-info',
                                            'customer'     => 'bg-secondary',
                                        ][$user->role] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td style="font-size: 0.78rem; color: var(--text-muted);">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm('Delete this user?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-users fa-2x d-block mb-2 opacity-20"></i>
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

@endsection