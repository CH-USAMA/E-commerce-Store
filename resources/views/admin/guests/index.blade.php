@extends('layouts.admin')

@section('title', 'Guest Customer Management')

@section('content')

    {{-- Header / Statistics Card --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="ap-stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info/10 text-info">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Unique Guests</div>
                        <div class="stat-value">{{ $guests->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card h-100 bg-gradient-to-r from-orange-500/10 to-transparent border-orange-500/20">
                <div class="card-body d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h6 class="fw-bold mb-1 text-white">POPIA Compliance Data Purge</h6>
                        <p class="text-muted small mb-0">Anonymize guest records older than 7 years (2555 days) to comply with South African data retention laws.</p>
                    </div>
                    <form action="{{ route('admin.guests.purge-old') }}" method="POST" onsubmit="return confirm('Are you sure you want to anonymize ALL guest data older than 7 years? This action cannot be undone.')">
                        @csrf
                        <button type="submit" class="btn btn-premium-gold btn-sm">
                            <i class="fas fa-shield-virus me-2"></i> Purge Records > 7 Years
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Guests Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold" style="font-size: 0.83rem;">Guest Customer List (Grouped by Email)</div>
            <div class="text-muted small">Showing {{ $guests->firstItem() }} - {{ $guests->lastItem() }} of {{ $guests->total() }} guests</div>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Guest Details</th>
                            <th>Contact</th>
                            <th>Orders</th>
                            <th>Last Activity</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guests as $guest)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-white" style="font-size: 0.85rem;">{{ $guest->customer_name ?: 'Unknown' }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem; font-family: var(--font-code);">{{ $guest->customer_email }}</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.82rem;">{{ $guest->customer_phone ?: 'N/A' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-dark border border-white/10 text-white">
                                        {{ $guest->order_count }} {{ Str::plural('Order', $guest->order_count) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem; color: var(--text-secondary);">
                                        {{ \Carbon\Carbon::parse($guest->last_order_at)->format('d M Y') }}
                                    </div>
                                    <div style="font-size: 0.65rem; color: var(--text-muted);">
                                        {{ \Carbon\Carbon::parse($guest->last_order_at)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="{{ route('admin.guests.purge') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to anonymize this guest\'s personal data? Historical order totals will be preserved, but names and emails will be removed.')">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ $guest->customer_email }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-user-slash me-1"></i> Purge PII
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-shield fa-3x d-block mb-3 opacity-20"></i>
                                    No guest customer records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($guests->hasPages())
                <div class="px-4 py-3 border-top border-white/5">
                    {{ $guests->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4 p-4 glass-panel rounded-3 border border-white/5">
        <h6 class="text-orange font-bold uppercase tracking-widest text-[10px] mb-2"><i class="fas fa-info-circle me-1"></i> Why is this here?</h6>
        <p class="text-muted small mb-0">Under South African POPIA and GDPR, you are required to minimize the retention of personal information. Purging guest records removes their name, email, and phone number from the database while keeping order totals intact for financial auditing and reporting.</p>
    </div>

@endsection
