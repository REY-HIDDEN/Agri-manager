@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card shadow-sm">
                <div class="stat-icon" style="background: #e8f5e9; color: #2d6a4f;">
                    <i class="fas fa-apple-alt"></i>
                </div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card shadow-sm">
                <div class="stat-icon" style="background: #e3f2fd; color: #1565c0;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">{{ $totalBuyers }}</div>
                <div class="stat-label">Total Buyers</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card shadow-sm">
                <div class="stat-icon" style="background: #fff3e0; color: #e65100;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $totalSales }}</div>
                <div class="stat-label">Total Sales</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card shadow-sm">
                <div class="stat-icon" style="background: #fce4ec; color: #c62828;">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-value">{{ $pendingDeliveries }}</div>
                <div class="stat-label">Pending Deliveries</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #2d6a4f, #40916c); color: #fff;">
                <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label" style="color: rgba(255,255,255,0.8);">Total Revenue</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #e65100, #ff8f00); color: #fff;">
                <div class="stat-value">${{ number_format($totalProfit, 2) }}</div>
                <div class="stat-label" style="color: rgba(255,255,255,0.8);">Net Profit</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #1565c0, #42a5f5); color: #fff;">
                <div class="stat-value">{{ $totalSales }}</div>
                <div class="stat-label" style="color: rgba(255,255,255,0.8);">Total Transactions</div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-line me-2"></i>Sales Trend (Last 7 Days)
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-2"></i>Revenue (Last 6 Months)
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-2"></i>Product Stock Levels
                </div>
                <div class="card-body">
                    <canvas id="stockChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle me-2" style="color: #e65100;"></i>Low Stock Products
                </div>
                <div class="card-body p-0">
                    @if($lowStockProducts->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockProducts as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-cube me-2" style="color: #888;"></i>
                                        {{ $product->name }}
                                    </span>
                                    <span class="badge bg-danger rounded-pill">{{ $product->quantity }} left</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center p-4 text-muted">
                            <i class="fas fa-check-circle fa-2x mb-2" style="color: #2d6a4f;"></i>
                            <p class="mb-0">All products are well stocked!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'Sales ($)',
                    data: {!! json_encode($salesChart->pluck('total')) !!},
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2d6a4f',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '$' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });

        // Revenue Chart
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueChart->pluck('month')) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueChart->pluck('total')) !!},
                    backgroundColor: 'rgba(45, 106, 79, 0.7)',
                    borderColor: '#2d6a4f',
                    borderWidth: 2,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '$' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });

        // Stock Chart
        const stockCtx = document.getElementById('stockChart').getContext('2d');
        new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($stockChart->pluck('name')) !!},
                datasets: [{
                    label: 'In Stock',
                    data: {!! json_encode($stockChart->pluck('quantity')) !!},
                    backgroundColor: {!! json_encode($stockChart->map(fn($p) => $p->quantity < 10 ? '#dc3545' : ($p->quantity < 50 ? '#ffc107' : '#28a745'))) !!},
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesChart->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))) !!},
                datasets: [{
                    label: 'Sales ($)',
                    data: {!! json_encode($salesChart->pluck('total')) !!},
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2d6a4f',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return '$' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
