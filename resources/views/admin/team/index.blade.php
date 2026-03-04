@extends('layouts.admin')

@section('title', 'Manage Team Members')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Team Members</h5>
        <a href="{{ route('admin.team.create') }}" class="btn btn-jabulani">Add New Member</a>
    </div>

    <div class="border-0 shadow-sm card">
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 align-middle table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Photo</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Bio Snippet</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td class="ps-4">
                                    @if($member->image)
                                        <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}" width="50"
                                            height="50" class="rounded-circle shadow-sm object-fit-cover">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold text-muted"
                                            style="width: 50px; height: 50px;">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $member->name }}</td>
                                <td><span class="badge bg-secondary">{{ $member->role }}</span></td>
                                <td>{{ \Illuminate\Support\Str::limit($member->bio, 50) }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.team.edit', $member->id) }}"
                                        class="btn btn-sm btn-outline-dark">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-muted">No team members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $members->links() }}
            </div>
        </div>
    </div>
@endsection