@extends('layouts.app')

@section('title', 'Profit Report')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-chart-line me-2"></i>Profit Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Reports
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="text-muted small mb-1">Total Revenue</div>
                <div class="fs-2 fw-bold text-success">${{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4">
                <div class="text-muted small mb-1">Total Cost</div>
                <div class="fs-2 fw-bold text-danger">${{ number_format($totalCost, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center py-4" style="background: {{ $netProfit >= 0 ? 'linear-gradient(135deg, #2d6a4f, #40916c)' : 'linear-gradient(135deg, #c62828, #e53935)' }}; color: #fff; border-radius: 12px;">
                <div class="small mb-1" style="color: rgba(255,255,255,0.8);">Net Profit</div>
                <div class="fs-2 fw-bold">${{ number_format($netProfit, 2) }}</div>
                <div class="small" style="color: rgba(255,255,255,0.8);">Margin: {{ $profitMargin }}%</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-chart-bar me-2"></i>Monthly Profit Breakdown (Last 12 Months)</div>
    <div class="card-body">
        <canvas id="profitChart" height="250"></canvas>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body p-0">
        @if($monthlyData->count() > 0)
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-end">Revenue</th>
                    <th class="text-end">Cost</th>
                    <th class="text-end">Profit</th>
                    <th class="text-end">Margin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $data)
                <tr>
                    <td><strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->month)->format('M Y') }}</strong></td>
                    <td class="text-end text-success">${{ number_format($data->revenue, 2) }}</td>
                    <td class="text-end text-danger">${{ number_format($data->cost, 2) }}</td>
                    <td class="text-end {{ $data->profit >= 0 ? 'text-success' : 'text-danger' }}">
                        <strong>${{ number_format($data->profit, 2) }}</strong>
                    </td>
                    <td class="text-end">{{ $data->revenue > 0 ? round(($data->profit / $data->revenue) * 100, 2) : 0 }}%</td>
                </tr>
                @endforeach
                <tr class="table-active">
                    <th>Total</th>
                    <th class="text-end">${{ number_format($monthlyData->sum('revenue'), 2) }}</th>
                    <th class="text-end">${{ number_format($monthlyData->sum('cost'), 2) }}</th>
                    <th class="text-end">${{ number_format($monthlyData->sum('profit'), 2) }}</th>
                    <th class="text-end">{{ $monthlyData->sum('revenue') > 0 ? round(($monthlyData->sum('profit') / $monthlyData->sum('revenue')) * 100, 2) : 0 }}%</th>
                </tr>
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h5>No Data Available</h5>
            <p>Record some sales to see profit reports.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('profitChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyData->pluck('month')->map(fn($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M Y'))) !!},
                datasets: [
                    {
                        label: 'Revenue',
                        data: {!! json_encode($monthlyData->pluck('revenue')) !!},
                        backgroundColor: 'rgba(45, 106, 79, 0.7)',
                        borderColor: '#2d6a4f',
                        borderWidth: 1
                    },
                    {
                        label: 'Cost',
                        data: {!! json_encode($monthlyData->pluck('cost')) !!},
                        backgroundColor: 'rgba(198, 40, 40, 0.7)',
                        borderColor: '#c62828',
                        borderWidth: 1
                    },
                    {
                        label: 'Profit',
                        data: {!! json_encode($monthlyData->pluck('profit')) !!},
                        backgroundColor: 'rgba(46, 125, 50, 0.7)',
                        borderColor: '#2e7d32',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
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
