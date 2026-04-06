@extends('layouts.admin')

@section('title', 'Add New User')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Create User Account</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="role" class="form-label fw-bold">Assign Primary Role</label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (System Wide)</option>
                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Branch Manager</option>
                                    <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Standard Customer</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-block mb-3">Module Permissions <small class="text-muted fw-normal">(Only for Admin/Staff roles)</small></label>
                            <div class="row g-3">
                                @php
                                    $availablePermissions = [
                                        'manage_products' => 'Products & Catalog',
                                        'manage_orders'   => 'Orders & Sales',
                                        'manage_content'  => 'Website Content',
                                        'manage_users'    => 'Staff Management',
                                        'manage_settings' => 'System Settings',
                                        'view_analytics'  => 'Analytics & Reports',
                                    ];
                                @endphp
                                @foreach($availablePermissions as $key => $label)
                                    <div class="col-md-6">
                                        <div class="form-check p-3 rounded-3 border border-white/5 bg-white/5 hover:bg-white/10 transition-all cursor-pointer">
                                            <input class="form-check-input ms-0 me-3" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}">
                                            <label class="form-check-label w-100 cursor-pointer" for="perm_{{ $key }}">
                                                <span class="d-block fw-bold" style="font-size: 0.85rem;">{{ $label }}</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Access to {{ strtolower($label) }}</small>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-jabulani px-4">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection