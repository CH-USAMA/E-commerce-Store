@extends('layouts.admin')

@section('title', 'Marketing Dashboard')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Marketing Campaigns</h4>
            <p class="text-muted small">Manage and track your broadcast history</p>
        </div>
        <a href="{{ route('admin.marketing.create') }}" class="btn btn-jabulani shadow-sm">
            <i class="fas fa-plus me-2"></i> New Campaign
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fa-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Campaign Info</th>
                            <th class="py-3 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Stats</th>
                            <th class="py-3 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Status</th>
                            <th class="py-3 text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Sent At</th>
                            <th class="pe-4 py-3 text-end text-uppercase text-muted fw-bold small" style="letter-spacing: 0.1em;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td class="ps-4 py-4">
                                    <h6 class="mb-1 fw-bold">{{ $campaign->title }}</h6>
                                    <p class="text-muted small mb-0 text-truncate" style="max-width: 300px;">{{ $campaign->message }}</p>
                                    @if($campaign->url)
                                        <div class="mt-2">
                                            <a href="{{ $campaign->url }}" target="_blank" class="text-[10px] text-primary text-decoration-none fw-bold uppercase">
                                                <i class="fas fa-link me-1"></i> {{ parse_url($campaign->url, PHP_URL_HOST) }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-users-viewfinder fa-xs"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-bold small">{{ $campaign->recipients_count }}</p>
                                            <p class="text-[10px] text-muted mb-0 uppercase tracking-wider">Recipients</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-bold uppercase tracking-wider" style="font-size: 0.65rem;">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y') : 'Pending' }}
                                    <div class="text-[10px] opacity-50">{{ $campaign->sent_at ? $campaign->sent_at->format('H:i') : '' }}</div>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-2 text-end">
                                        <form action="{{ route('admin.marketing.resend', $campaign) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-3 px-3 fw-bold small uppercase" title="Resend Notification">
                                                <i class="fas fa-redo-alt me-1"></i> Resend
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.marketing.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently remove this campaign log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3" title="Delete Log">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    <div class="mb-3 opacity-20">
                                        <i class="fas fa-bullhorn fa-4x"></i>
                                    </div>
                                    <h6 class="fw-bold">No Marketing Activity Detected</h6>
                                    <p class="small">Broadcast your first campaign to engage with your customers.</p>
                                    <a href="{{ route('admin.marketing.create') }}" class="btn btn-jabulani mt-3 shadow-sm">
                                        Launch First Campaign
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($campaigns->hasPages())
                <div class="p-4 border-top bg-light">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
