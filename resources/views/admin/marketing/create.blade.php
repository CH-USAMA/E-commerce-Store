@extends('layouts.admin')

@section('title', 'Launch Campaign')

@section('content')
<div class="container-fluid px-0" x-data="{ title: '', message: '', url: '' }">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                            <i class="fas fa-bullhorn fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Create Broadcast Campaign</h5>
                            <p class="text-muted small mb-0">Push real-time notifications to all registered users</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.marketing.push') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase tracking-wider">Campaign Title</label>
                            <input type="text" name="title" x-model="title" class="form-control form-control-lg border-2" placeholder="e.g. Weekend Flash Sale! ⚡" required>
                            <div class="form-text">Keep it short and catchy to grab attention.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase tracking-wider">Message Content</label>
                            <textarea name="message" x-model="message" rows="4" class="form-control border-2" placeholder="Describe your offer or update here..." required></textarea>
                            <div class="form-text">Visible in the user's notification portal.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase tracking-wider">Action URL <span class="text-muted fw-normal">(Optional)</span></label>
                            <input type="url" name="url" x-model="url" class="form-control border-2" placeholder="https://jabulani.co.za/specials">
                            <div class="form-text">Where should users go when they click the notification?</div>
                        </div>

                        <hr class="my-5 opacity-10">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.marketing.index') }}" class="btn btn-outline-secondary px-4 py-3 border-0 fw-bold small uppercase">
                                <i class="fas fa-arrow-left me-2"></i> Back to Logs
                            </a>
                            <button type="submit" class="btn btn-jabulani px-5 py-3 shadow-lg">
                                <span class="d-flex align-items-center gap-2">
                                    Broadcast Campaign <i class="fas fa-paper-plane"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark text-white border-0 shadow-sm rounded-4 h-100 overflow-hidden sticky-top" style="top: 2rem;">
                <div class="card-body p-5 relative">
                    <div class="relative z-10">
                        <h6 class="text-warning text-uppercase fw-bold tracking-widest mb-4" style="font-size: 0.7rem;">Live User Preview</h6>
                        
                        <!-- Reactive Notification Card -->
                        <div class="bg-white bg-opacity-10 rounded-4 p-4 border border-white border-opacity-10 shadow-2xl transition-all duration-300 hover:bg-opacity-20">
                            <div class="d-flex gap-3 mb-3">
                                <div class="bg-warning text-dark rounded-circle p-2 flex-shrink-0" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bullhorn fa-xs"></i>
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <p class="mb-1 fw-bold text-truncate" style="font-size: 0.9rem;" x-text="title || 'Your Campaign Title'"></p>
                                    <p class="mb-0 text-white text-opacity-60" style="font-size: 0.75rem; word-wrap: break-word;" x-text="message || 'Type your message in the form to see how it will appear to your customers...'"></p>
                                    
                                    <template x-if="url">
                                        <div class="mt-3">
                                            <span class="text-[9px] font-black text-warning uppercase tracking-widest border-b border-warning border-opacity-30 pb-0.5">
                                                Link Attached <i class="fas fa-external-link-alt ms-1" style="font-size: 7px;"></i>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-white text-opacity-30" style="font-size: 0.6rem;">Just now</small>
                            </div>
                        </div>
                        
                        <div class="mt-5 pt-5 border-top border-white border-opacity-10">
                            <p class="small text-white text-opacity-50">This is how your broadcast will appear in the customer notification portal.</p>
                            <ul class="text-[10px] text-white text-opacity-30 mt-3 space-y-1 ps-3">
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
</div>
@endsection
