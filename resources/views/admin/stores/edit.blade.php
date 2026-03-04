@extends('layouts.admin')

@section('title', 'Edit Store')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.stores.update', $store->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Name</label>
                                <input type="text" name="name" class="form-control" required value="{{ $store->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Slug</label>
                                <input type="text" name="slug" class="form-control" required value="{{ $store->slug }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Image</label>
                            @if($store->image)
                                <div class="mb-2">
                                    <img src="{{ asset($store->image) }}" alt="Store" class="rounded shadow-sm"
                                        style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Address</label>
                            <textarea name="address" class="form-control" required rows="3">{{ $store->address }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Province</label>
                                <select name="province" class="form-select" required>
                                    <option value="">Select Province</option>
                                    @foreach(['Gauteng', 'KwaZulu-Natal', 'Western Cape', 'Eastern Cape', 'Free State', 'Limpopo', 'Mpumalanga', 'Northern Cape', 'North West'] as $province)
                                        <option value="{{ $province }}" {{ $store->province == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Store Managers</label>
                                <select name="manager_ids[]" class="form-select select2" multiple>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ $store->managers->contains($manager->id) ? 'selected' : '' }}>
                                            {{ $manager->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">You can select multiple managers for this branch.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="any" name="lat" class="form-control" value="{{ $store->lat }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="any" name="lng" class="form-control" value="{{ $store->lng }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Details</label>
                            <input type="text" name="contact_details" class="form-control"
                                value="{{ $store->contact_details }}">
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();">Delete
                                Store</button>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-jabulani px-4">Update Store</button>
                            </div>
                        </div>
                    </form>
                    <form id="delete-form" action="{{ route('admin.stores.destroy', $store->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection