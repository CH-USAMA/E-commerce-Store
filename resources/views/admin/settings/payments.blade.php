@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')
    <div class="col-md-12">
        <form action="{{ route('admin.settings.payments.update') }}" method="POST">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">Payment & Logistics Configuration</h4>
                <button type="submit" class="btn btn-jabulani px-5 py-2">SAVE ALL SETTINGS</button>
            </div>
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fab fa-stripe text-primary me-2 fa-lg"></i> Stripe Online Payments
                    </h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="stripe_enabled" value="1" id="stripe_enabled" {{ ($settings['stripe_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold text-uppercase" for="stripe_enabled" style="font-size: 0.7rem;">Enable Gateway</label>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="background: rgba(103, 114, 229, 0.05); color: #6772e5;">
                        <i class="fas fa-info-circle me-2"></i> 
                        Collecting payments online requires a Stripe account. Find your keys in the <a href="https://dashboard.stripe.com/apikeys" target="_blank" class="fw-bold text-decoration-underline">Stripe Dashboard</a>.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-uppercase text-muted" style="font-size: 0.7rem;">Stripe Public Key (Publishable)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                            <input type="text" name="stripe_public_key" class="form-control border-start-0" value="{{ $settings['stripe_public_key'] ?? '' }}" placeholder="pk_test_...">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-uppercase text-muted" style="font-size: 0.7rem;">Stripe Secret Key</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input type="password" name="stripe_secret_key" class="form-control border-start-0" value="{{ $settings['stripe_secret_key'] ?? '' }}" placeholder="sk_test_...">
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 p-4 pt-0 text-end">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-truck text-warning me-2 fa-lg"></i> Delivery Logistics
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold text-uppercase text-muted" style="font-size: 0.7rem;">Maximum Delivery Radius (KM)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-road text-muted"></i></span>
                            <input type="number" name="max_delivery_km" class="form-control border-start-0" value="{{ $settings['max_delivery_km'] ?? '300' }}" placeholder="300">
                            <span class="input-group-text bg-light">Kilometers</span>
                        </div>
                        <small class="text-muted italic" style="font-size: 0.65rem;">Customers outside this radius from the nearest branch will only be offered 'Warehouse Pickup'.</small>
                    </div>
                </div>
            </div>

            <!-- EFT Information (Read Only Display) -->
            <div class="card border-0 shadow-sm" style="background: #fdfdfd;">
                <div class="card-header bg-transparent py-3">
                    <h6 class="mb-0 text-dark fw-bold text-uppercase tracking-wider" style="font-size: 0.8rem;">
                        <i class="fas fa-university text-warning me-2"></i> Static EFT Accounts (Local)
                    </h6>
                </div>
                <div class="card-body p-4">
                    <p class="text-sm text-muted mb-4">The following accounts are displayed to customers selecting Bank EFT. Currently modified via code to ensure institutional integrity.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <thead class="text-uppercase text-muted" style="font-size: 0.65rem;">
                                <tr>
                                    <th>Institution</th>
                                    <th>Account Name</th>
                                    <th>Account Number</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.85rem;">
                                <tr>
                                    <td class="fw-bold">FNB</td>
                                    <td>Moin Hardware</td>
                                    <td class="font-monospace">62866895166</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">FNB</td>
                                    <td>JB BUILDER CHOICE</td>
                                    <td class="font-monospace">63070014740</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Standard Bank</td>
                                    <td>MOIN HARDWERE</td>
                                    <td class="font-monospace">272322091</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
