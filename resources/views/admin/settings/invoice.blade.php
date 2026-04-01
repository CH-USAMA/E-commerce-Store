@extends('layouts.admin')

@section('title', 'Invoice Settings')

@section('content')

<form action="{{ route('admin.settings.invoice.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Billing & Invoice Configuration</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage company details, branding and EFT accounts for invoices</div>
        </div>
        <button type="submit" class="btn btn-jabulani btn-sm px-4">
            <i class="fas fa-save me-1"></i> Save Changes
        </button>
    </div>

    <div class="row g-3">
        {{-- Company Branding --}}
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-building" style="color: var(--orange-400);"></i>
                        <span class="fw-bold" style="font-size: 0.83rem;">Company Information</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="invoice_company_name" class="form-control"
                                   value="{{ $settings['invoice_company_name'] ?? 'Jabulani Group' }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="invoice_company_address" class="form-control" rows="3">{{ $settings['invoice_company_address'] ?? '' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="invoice_company_phone" class="form-control"
                                   value="{{ $settings['invoice_company_phone'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="invoice_company_email" class="form-control"
                                   value="{{ $settings['invoice_company_email'] ?? '' }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Registration / Tax Number</label>
                            <input type="text" name="invoice_registration_number" class="form-control"
                                   value="{{ $settings['invoice_registration_number'] ?? '' }}" placeholder="Reg No: 2023/123456/07">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Invoice Footer Text</label>
                            <textarea name="invoice_footer_text" class="form-control" rows="2">{{ $settings['invoice_footer_text'] ?? 'Thank you for your business.' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Branding/Logo --}}
        <div class="col-lg-5">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-image" style="color: var(--orange-400);"></i>
                        <span class="fw-bold" style="font-size: 0.83rem;">Invoice Branding</span>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(!empty($settings['invoice_logo']))
                            <img src="{{ asset('storage/' . $settings['invoice_logo']) }}"
                                 alt="Invoice Logo" class="img-fluid rounded mb-2" style="max-height: 100px; background: var(--carbon-800); padding: 10px;">
                        @else
                            <div class="p-4 rounded mb-2" style="background: var(--carbon-800); border: 1px dashed var(--border-default);">
                                <i class="fas fa-image fa-2x opacity-20"></i>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 5px;">No logo uploaded</div>
                            </div>
                        @endif
                    </div>
                    <label class="form-label d-block text-start">Upload Invoice Logo</label>
                    <input type="file" name="invoice_logo" class="form-control mb-2">
                    <div style="font-size: 0.68rem; color: var(--text-muted);" class="text-start">
                        Recommended size: 300x100px. Max 1MB (PNG, JPG).
                    </div>
                </div>
            </div>

            {{-- EFT Accounts --}}
            <div class="card" x-data="{ accounts: {{ count($eft_accounts) > 0 ? json_encode($eft_accounts) : '[{bank: \'\', name: \'\', number: \'\', code: \'\'}]' }} }">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-university" style="color: var(--orange-400);"></i>
                        <span class="fw-bold" style="font-size: 0.83rem;">EFT Settlement Accounts</span>
                    </div>
                    <button type="button" @click="accounts.push({bank: '', name: '', number: '', code: ''})" class="btn btn-outline-jabulani btn-xs">
                        <i class="fas fa-plus me-1"></i> Add Account
                    </button>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    <template x-for="(account, index) in accounts" :key="index">
                        <div class="p-3 rounded mb-3 position-relative" style="background: var(--carbon-800); border: 1px solid var(--border-default);">
                            <button type="button" @click="accounts.splice(index, 1)"
                                    class="btn btn-link p-0 position-absolute top-0 end-0 mt-2 me-2 text-danger opacity-50 hover-opacity-100"
                                    x-show="accounts.length > 1">
                                <i class="fas fa-times-circle"></i>
                            </button>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label" style="font-size: 0.65rem;">Bank / Institution</label>
                                    <input type="text" :name="'eft_accounts['+index+'][bank]'" x-model="account.bank" class="form-control form-control-sm" placeholder="FNB">
                                </div>
                                <div class="col-6">
                                    <label class="form-label" style="font-size: 0.65rem;">Account Name</label>
                                    <input type="text" :name="'eft_accounts['+index+'][name]'" x-model="account.name" class="form-control form-control-sm" placeholder="Moin Hardware">
                                </div>
                                <div class="col-8">
                                    <label class="form-label" style="font-size: 0.65rem;">Account Number</label>
                                    <input type="text" :name="'eft_accounts['+index+'][number]'" x-model="account.number" class="form-control form-control-sm" placeholder="62866895166">
                                </div>
                                <div class="col-4">
                                    <label class="form-label" style="font-size: 0.65rem;">Branch Code</label>
                                    <input type="text" :name="'eft_accounts['+index+'][code]'" x-model="account.code" class="form-control form-control-sm" placeholder="628">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
