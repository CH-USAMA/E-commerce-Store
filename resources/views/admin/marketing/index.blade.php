@extends('layouts.admin')

@section('title', 'Marketing Dashboard')

@section('content')

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-bold" style="font-size: 0.83rem; color: var(--text-primary);">Marketing Campaigns</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Manage and track your broadcast history</div>
        </div>
        <a href="{{ route('admin.marketing.create') }}" class="btn btn-jabulani btn-sm">
            <i class="fas fa-plus me-1"></i> New Campaign
        </a>
    </div>

    {{-- Campaigns Table --}}
    <div class="card">
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Campaign Info</th>
                            <th>Recipients</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th class="pe-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaigns as $campaign)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold" style="font-size: 0.83rem;">{{ $campaign->title }}</div>
                                    <div class="text-truncate" style="max-width: 280px; font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">
                                        {{ $campaign->message }}
                                    </div>
                                    @if($campaign->url)
                                        <a href="{{ $campaign->url }}" target="_blank"
                                           style="font-size: 0.7rem; color: var(--orange-400); text-decoration: none; margin-top: 4px; display: inline-block;">
                                            <i class="fas fa-link me-1"></i>{{ parse_url($campaign->url, PHP_URL_HOST) }}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-orange">
                                        <i class="fas fa-users me-1"></i> {{ $campaign->recipients_count }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = $campaign->status === 'sent' ? 'bg-success' :
                                                      ($campaign->status === 'pending' ? 'bg-warning' : 'bg-secondary');
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($campaign->status) }}</span>
                                </td>
                                <td style="font-size: 0.78rem; color: var(--text-muted);">
                                    @if($campaign->sent_at)
                                        {{ $campaign->sent_at->format('d M Y') }}<br>
                                        <span style="font-size: 0.68rem; opacity: 0.6;">{{ $campaign->sent_at->format('H:i') }}</span>
                                    @else
                                        <span style="color: var(--text-muted);">Pending</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <form action="{{ route('admin.marketing.resend', $campaign) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Resend">
                                                <i class="fas fa-redo-alt"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.marketing.destroy', $campaign) }}" method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Permanently remove this campaign?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-bullhorn fa-2x d-block mb-2 opacity-20"></i>
                                    <div class="fw-semibold mb-1" style="font-size: 0.83rem;">No Marketing Activity Detected</div>
                                    <div style="font-size: 0.75rem;">Broadcast your first campaign to engage with your customers.</div>
                                    <a href="{{ route('admin.marketing.create') }}" class="btn btn-jabulani btn-sm mt-3">
                                        Launch First Campaign
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($campaigns->hasPages())
                <div class="px-3 py-2 border-top" style="border-color: var(--border-default) !important;">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
