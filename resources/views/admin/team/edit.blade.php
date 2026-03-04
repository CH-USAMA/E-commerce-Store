@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.team.update', $member->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ $member->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Role</label>
                            <input type="text" name="role" class="form-control" required value="{{ $member->role }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biography</label>
                            <textarea name="bio" class="form-control" rows="5">{{ $member->bio }}</textarea>
                        </div>
                        <div class="mb-3 text-center">
                            @if($member->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $member->image) }}" alt="{{ $member->name }}" width="120"
                                        height="120"
                                        class="rounded-circle shadow-sm object-fit-cover border border-2 border-warning">
                                </div>
                            @endif
                            <label class="form-label d-block text-start">Change Profile Photo (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();">Remove
                                Member</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.team.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Member</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.team.destroy', $member->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection