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

    {{-- Gateway Selection --}}
    <div class="card mb-3 border-jabulani">
        <div class="card-header bg-jabulani-light">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-toggle-on text-jabulani"></i>
                <span class="fw-bold" style="font-size: 0.83rem;">Active Gateway Strategy</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="fw-bold mb-1" style="font-size: 0.8rem;">Preferred Online Gateway</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted);">Choose which provider processes the "Online Payment" option on checkout.</div>
                </div>
                <div class="col-md-5">
                    <select name="preferred_online_gateway" class="form-select border-jabulani">
                        <option value="stripe" {{ ($settings['preferred_online_gateway'] ?? 'stripe') == 'stripe' ? 'selected' : '' }}>Stripe (Global / Cards)</option>
                        <option value="paystack" {{ ($settings['preferred_online_gateway'] ?? '') == 'paystack' ? 'selected' : '' }}>Paystack (Africa / Multi-Option)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            {{-- Stripe --}}
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fab fa-stripe fa-lg" style="color: #635bff;"></i>
                        <span class="fw-bold" style="font-size: 0.83rem;">Stripe Config</span>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1"
                               id="stripe_enabled" {{ ($settings['stripe_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="stripe_enabled" style="font-size: 0.65rem; font-weight: 700;">
                            ENABLE
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.75rem;">Public Key</label>
                        <input type="text" name="stripe_public_key" class="form-control form-control-sm"
                               value="{{ $settings['stripe_public_key'] ?? '' }}" placeholder="pk_test_...">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.75rem;">Secret Key</label>
                        <input type="password" name="stripe_secret_key" class="form-control form-control-sm"
                               value="{{ $settings['stripe_secret_key'] ?? '' }}" placeholder="sk_test_...">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            {{-- Paystack --}}
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://paystack.com/assets/img/login/paystack-logo.png" height="18" alt="Paystack" style="filter: brightness(0) invert(1);">
                        <span class="fw-bold" style="font-size: 0.83rem;">Paystack Config</span>
                    </div>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="paystack_enabled" value="1"
                               id="paystack_enabled" {{ ($settings['paystack_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="paystack_enabled" style="font-size: 0.65rem; font-weight: 700;">
                            ENABLE
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.75rem;">Public Key</label>
                        <input type="text" name="paystack_public_key" class="form-control form-control-sm"
                               value="{{ $settings['paystack_public_key'] ?? '' }}" placeholder="pk_test_...">
                    </div>
                    <div>
                        <label class="form-label" style="font-size: 0.75rem;">Secret Key</label>
                        <input type="password" name="paystack_secret_key" class="form-control form-control-sm"
                               value="{{ $settings['paystack_secret_key'] ?? '' }}" placeholder="sk_test_...">
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
