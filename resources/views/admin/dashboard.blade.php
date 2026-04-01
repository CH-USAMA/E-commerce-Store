@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')

    {{-- Stat Cards Row --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="ap-stat-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: rgba(255,140,0,0.12); color: var(--orange-400);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <span class="badge badge-orange">Revenue</span>
                </div>
                <div class="stat-value mb-1">R {{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ap-stat-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: rgba(13,202,240,0.10); color: hsl(195,80%,60%);">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <span class="badge" style="background: rgba(13,202,240,0.12); color: hsl(195,80%,65%);">Orders</span>
                </div>
                <div class="stat-value mb-1">{{ $stats['total_orders'] }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ap-stat-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: rgba(25,135,84,0.12); color: hsl(145,63%,55%);">
                        <i class="fas fa-tools"></i>
                    </div>
                    <span class="badge" style="background: rgba(25,135,84,0.12); color: hsl(145,63%,60%);">Products</span>
                </div>
                <div class="stat-value mb-1">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Products</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="ap-stat-card">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="stat-icon" style="background: rgba(111,66,193,0.12); color: hsl(270,60%,70%);">
                        <i class="fas fa-store"></i>
                    </div>
                    <span class="badge" style="background: rgba(111,66,193,0.12); color: hsl(270,60%,72%);">Stores</span>
                </div>
                <div class="stat-value mb-1">{{ $stats['total_stores'] }}</div>
                <div class="stat-label">Active Stores</div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size: 0.83rem;">Revenue Analytics</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">Last 7 days — ZAR</div>
                    </div>
                    <span class="badge badge-orange">Weekly</span>
                </div>
                <div class="card-body" style="padding: 1.25rem !important;">
                    <div style="height: 260px; position: relative;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="fw-bold" style="font-size: 0.83rem;">Top Selling Products</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 2px;">By quantity sold</div>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div style="height: 220px; width: 100%; position: relative;">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders Table --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold" style="font-size: 0.83rem;">Recent Orders</div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-link btn-sm p-0">
                View All <i class="fas fa-arrow-right ms-1" style="font-size: 0.7rem;"></i>
            </a>
        </div>
        <div class="card-body" style="padding: 0 !important;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Customer</th>
                            <th>Store</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_orders'] as $order)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold" style="color: var(--orange-400); font-family: var(--font-code); font-size: 0.78rem;">#{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="font-size: 0.82rem;">{{ $order->customer_name }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">{{ $order->customer_email }}</div>
                                </td>
                                <td style="color: var(--text-secondary); font-size: 0.82rem;">{{ $order->store->name ?? 'N/A' }}</td>
                                <td class="fw-semibold" style="font-size: 0.83rem;">R {{ number_format($order->total, 2) }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending'         => 'bg-warning',
                                            'processing'      => 'bg-info',
                                            'shipped'         => 'bg-primary',
                                            'delivered'       => 'bg-success',
                                            'completed'       => 'bg-success',
                                            'cancelled'       => 'bg-danger',
                                            'awaiting_payment'=> 'bg-warning',
                                        ];
                                        $sc = $statusMap[$order->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $sc }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-dark btn-sm">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="color: var(--text-muted);">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-20"></i>
                                    No recent orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const orange = '#FF8C00';
    const orangeLight = 'rgba(255,140,0,0.12)';
    const gridColor = 'rgba(255,255,255,0.04)';
    const textColor = 'rgba(255,255,255,0.35)';
    const font = { family: "'Inter', sans-serif", size: 11 };

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    // --- Sales Chart ---
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        const ctx2d = salesCtx.getContext('2d');
        const grad = ctx2d.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0, 'rgba(255,140,0,0.18)');
        grad.addColorStop(1, 'rgba(255,140,0,0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($stats['sales_chart']->pluck('date')) !!},
                datasets: [{
                    label: 'Revenue (R)',
                    data: {!! json_encode($stats['sales_chart']->pluck('total')) !!},
                    borderColor: orange,
                    backgroundColor: grad,
                    borderWidth: 2.5,
                    tension: 0.45,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: orange,
                    pointBorderColor: 'rgba(9,10,14,0.8)',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,16,20,0.95)',
                        titleFont: { ...font, weight: '700' },
                        bodyFont: font,
                        padding: 12,
                        borderColor: 'rgba(255,140,0,0.3)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: ctx => ' R ' + new Intl.NumberFormat().format(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font, color: textColor }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            font,
                            color: textColor,
                            callback: v => 'R' + v
                        }
                    }
                }
            }
        });
    }

    // --- Products Doughnut ---
    const prodCtx = document.getElementById('productsChart');
    if (prodCtx) {
        new Chart(prodCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($stats['top_products']->map(fn($tp) => $tp->product->name ?? 'Unknown')) !!},
                datasets: [{
                    data: {!! json_encode($stats['top_products']->pluck('total_qty')) !!},
                    backgroundColor: [
                        '#FF8C00',
                        'rgba(255,140,0,0.7)',
                        'rgba(255,140,0,0.5)',
                        'rgba(255,140,0,0.35)',
                        'rgba(255,140,0,0.2)',
                    ],
                    borderWidth: 0,
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '78%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { ...font, size: 10 },
                            color: textColor,
                            boxWidth: 8,
                            usePointStyle: true,
                            padding: 14,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15,16,20,0.95)',
                        padding: 10,
                        borderColor: 'rgba(255,140,0,0.3)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        titleFont: { ...font, weight: '700' },
                        bodyFont: font,
                    }
                }
            }
        });
    }
});
</script>
@endpush