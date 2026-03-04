@extends('layouts.admin')

@section('title', 'Add Team Member')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <form action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Job Role</label>
                            <input type="text" name="role" class="form-control" required
                                placeholder="e.g. Operations Manager">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biography</label>
                            <textarea name="bio" class="form-control" rows="5"
                                placeholder="Short professional bio..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Photo (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="mt-4 gap-2 d-flex justify-content-end">
                            <a href="{{ route('admin.team.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Save Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection