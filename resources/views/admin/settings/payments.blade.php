@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')

<form action="{{ route('admin.settings.payments.update') }}" method="POST">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Payment & Logistics Configuration</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage gateways, delivery and banking details</div>
        </div>
        <button type="submit" class="btn btn-jabulani btn-sm px-4">
            <i class="fas fa-save me-1"></i> Save All Settings
        </button>
    </div>

    {{-- Stripe --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="fab fa-stripe fa-lg" style="color: #635bff;"></i>
                <span class="fw-bold" style="font-size: 0.83rem;">Stripe Online Payments</span>
            </div>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1"
                       id="stripe_enabled" {{ ($settings['stripe_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="stripe_enabled" style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    Enable Gateway
                </label>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-3 py-2">
                <i class="fas fa-info-circle me-2" style="color: var(--info-color);"></i>
                Collecting payments online requires a Stripe account. Find your keys in the
                <a href="https://dashboard.stripe.com/apikeys" target="_blank"
                   style="color: var(--orange-400); text-decoration: underline; font-weight: 600;">Stripe Dashboard</a>.
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Stripe Public Key (Publishable)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="text" name="stripe_public_key" class="form-control"
                               value="{{ $settings['stripe_public_key'] ?? '' }}" placeholder="pk_test_...">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Stripe Secret Key</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="stripe_secret_key" class="form-control"
                               value="{{ $settings['stripe_secret_key'] ?? '' }}" placeholder="sk_test_...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delivery Logistics --}}
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-truck" style="color: var(--orange-400);"></i>
                <span class="fw-bold" style="font-size: 0.83rem;">Delivery Logistics</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Maximum Delivery Radius (KM)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-road"></i></span>
                        <input type="number" name="max_delivery_km" class="form-control"
                               value="{{ $settings['max_delivery_km'] ?? '300' }}" placeholder="300">
                        <span class="input-group-text">km</span>
                    </div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 6px;">
                        Customers outside this radius from the nearest branch will only be offered 'Warehouse Pickup'.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Static EFT Accounts --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-university" style="color: var(--orange-400);"></i>
                <span class="fw-bold" style="font-size: 0.83rem;">Static EFT Accounts (Local)</span>
            </div>
        </div>
        <div class="card-body">
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 1rem;">
                The following accounts are displayed to customers selecting Bank EFT. Currently modified via code to ensure institutional integrity.
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">FNB</td>
                            <td>Moin Hardware</td>
                            <td><code>62866895166</code></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">FNB</td>
                            <td>JB Builder Choice</td>
                            <td><code>63070014740</code></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Standard Bank</td>
                            <td>Moin Hardware</td>
                            <td><code>272322091</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</form>

@endsection
