@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">System Users</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-jabulani">Add New User</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $user->name }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleClass = [
                                            'admin' => 'bg-danger text-white',
                                            'manager' => 'bg-primary text-white',
                                            'receptionist' => 'bg-info text-white',
                                            'customer' => 'bg-secondary text-white',
                                        ][$user->role] ?? 'bg-dark text-white';
                                    @endphp
                                    <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-dark">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" {{ $user->id === auth()->id() ? 'disabled' : '' }}>Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection