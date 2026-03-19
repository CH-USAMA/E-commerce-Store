@extends('layouts.admin')

@section('title', 'Dashboard Overview')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-warning">
                <div class="text-muted small text-uppercase fw-bold">Total Revenue</div>
                <div class="h2 fw-bold mb-0">R {{ number_format($stats['total_revenue'], 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-dark">
                <div class="text-muted small text-uppercase fw-bold">Total Orders</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_orders'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-secondary">
                <div class="text-muted small text-uppercase fw-bold">Products</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_products'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow-sm border-0 mb-4 p-3 border-start border-4 border-info">
                <div class="text-muted small text-uppercase fw-bold">Active Stores</div>
                <div class="h2 fw-bold mb-0">{{ $stats['total_stores'] }}</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mt-2">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark text-uppercase tracking-widest" style="font-size: 0.7rem;">
                        <i class="fas fa-chart-line text-warning me-2"></i> Revenue Analytics (Last 7 Days)
                    </h6>
                    <span class="badge bg-light text-dark border px-3" style="font-size: 0.6rem;">ZAR</span>
                </div>
                <div class="card-body p-4">
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-4">
                    <h6 class="mb-0 fw-bold text-dark text-uppercase tracking-widest" style="font-size: 0.7rem;">
                        <i class="fas fa-chart-pie text-warning me-2"></i> Top Selling Products
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div style="height: 300px;">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-link text-dark text-decoration-none fw-bold small">View All <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order #</th>
                                    <th>Customer</th>
                                    <th>Store</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_orders'] as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $order->customer_name }}</span>
                                                <small class="text-muted">{{ $order->customer_email }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $order->store->name ?? 'N/A' }}</td>
                                        <td class="fw-bold text-dark">R {{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill bg-{{ $order->status == 'completed' || $order->status == 'processing' ? 'success' : ($order->status == 'awaiting_payment' ? 'warning text-dark' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark px-3 mt-1">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 opacity-20"></i><br>
                                            No recent orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shared chart options
        const fontOptions = { size: 11, family: "'Inter', sans-serif" };

        // Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($stats['sales_chart']->pluck('date')) !!},
                    datasets: [{
                        label: 'Daily Revenue',
                        data: {!! json_encode($stats['sales_chart']->pluck('total')) !!},
                        borderColor: '#FF8C00',
                        backgroundColor: 'rgba(255, 140, 0, 0.08)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#FF8C00',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111',
                            padding: 12,
                            displayColors: false,
                            bodyFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return 'R ' + new Intl.NumberFormat().format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { 
                                font: fontOptions,
                                callback: val => 'R' + val
                            }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: fontOptions }
                        }
                    }
                }
            });
        }

        // Products Chart
        const productsCtx = document.getElementById('productsChart');
        if (productsCtx) {
            new Chart(productsCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($stats['top_products']->map(fn($tp) => $tp->product->name ?? 'Unknown')) !!},
                    datasets: [{
                        data: {!! json_encode($stats['top_products']->pluck('total_qty')) !!},
                        backgroundColor: ['#FF8C00', '#111111', '#888888', '#FFCC00', '#333333'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { 
                        legend: { 
                            position: 'bottom', 
                            labels: { 
                                boxWidth: 10, 
                                padding: 15,
                                font: { size: 10, weight: '500' },
                                usePointStyle: true
                            } 
                        } 
                    }
                }
            });
        }
    });
</script>
@endpush