@extends('layouts.admin')

@section('title', 'Launch Campaign')

@section('content')

<div x-data="{ title: '', message: '', url: '' }">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.marketing.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <div>
            <div class="fw-bold" style="font-size: 0.83rem;">Create Broadcast Campaign</div>
            <div style="font-size: 0.72rem; color: var(--text-muted);">Push real-time notifications to all registered users</div>
        </div>
    </div>

    <div class="row g-3">

        {{-- Form Card --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 30px; height: 30px; border-radius: var(--radius-sm); background: rgba(255,140,0,0.12); display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-bullhorn fa-xs" style="color: var(--orange-400);"></i>
                        </div>
                        <span class="fw-bold" style="font-size: 0.83rem;">Campaign Details</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.marketing.push') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Campaign Title</label>
                            <input type="text" name="title" x-model="title" class="form-control"
                                   placeholder="e.g. Weekend Flash Sale! ⚡" required>
                            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                                Keep it short and catchy to grab attention.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message Content</label>
                            <textarea name="message" x-model="message" rows="4" class="form-control"
                                      placeholder="Describe your offer or update here..." required></textarea>
                            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                                Visible in the user's notification portal.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Action URL
                                <span style="color: var(--text-muted); font-weight: 400; text-transform: none; letter-spacing: 0;">(Optional)</span>
                            </label>
                            <input type="url" name="url" x-model="url" class="form-control"
                                   placeholder="https://jabulani.co.za/specials">
                            <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 4px;">
                                Where should users go when they click the notification?
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-jabulani px-5">
                                <i class="fas fa-paper-plane me-2"></i> Broadcast Campaign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Live Preview --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 1.5rem;">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Live Preview</div>
                    <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 2px;">How it appears to customers</div>
                </div>
                <div class="card-body">
                    {{-- Simulated notification card --}}
                    <div style="background: rgba(255,140,0,0.06); border: 1px solid rgba(255,140,0,0.15); border-radius: var(--radius-md); padding: 0.875rem; border-left: 3px solid var(--orange-500);">
                        <div class="d-flex gap-2 mb-2">
                            <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255,140,0,0.15); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="fas fa-bullhorn fa-xs" style="color: var(--orange-400);"></i>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fw-bold text-truncate" style="font-size: 0.83rem; color: var(--text-primary);"
                                     x-text="title || 'Your Campaign Title'"></div>
                                <div class="mt-1" style="font-size: 0.75rem; color: var(--text-secondary); word-break: break-word;"
                                     x-text="message || 'Type your message to see how it appears to customers...'"></div>
                                <template x-if="url">
                                    <div class="mt-2" style="font-size: 0.68rem; color: var(--orange-400);">
                                        <i class="fas fa-external-link-alt me-1"></i> Link attached
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="text-end" style="font-size: 0.65rem; color: var(--text-muted);">Just now</div>
                    </div>

                    <div class="mt-3 pt-3 border-top" style="font-size: 0.75rem; color: var(--text-muted);">
                        This is how your broadcast will appear in the customer notification portal.
                        <ul class="mt-2 ps-3" style="font-size: 0.72rem; color: var(--text-muted);">
                            <li>Real-time push delivery</li>
                            <li>Stored in user's persistent log</li>
                            <li>Clickable action links</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
