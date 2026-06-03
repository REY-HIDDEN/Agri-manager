@extends('layouts.app')

@section('title', 'Monthly Sales Report')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-calendar-alt me-2"></i>Monthly Sales Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Reports
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-3">
                <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-auto">
                <span class="text-muted">
                    <i class="fas fa-shopping-cart me-1"></i> {{ $transactionCount }} transactions
                </span>
            </div>
            <div class="col-md-auto">
                <span class="text-muted">
                    <i class="fas fa-dollar-sign me-1"></i> Revenue: <strong>${{ number_format($totalRevenue, 2) }}</strong>
                </span>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($sales->count() > 0)
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                    <td>{{ $sale->buyer->name ?? 'N/A' }}</td>
                    <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                    <td><span class="badge {{ $sale->payment_status == 'paid' ? 'bg-success' : 'badge-pending' }} text-dark">{{ ucfirst($sale->payment_status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <th colspan="3">Total Revenue</th>
                    <th class="text-end">${{ number_format($totalRevenue, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-alt"></i>
            <h5>No Sales Found</h5>
            <p>No sales recorded for this month.</p>
        </div>
        @endif
    </div>
</div>
@endsection
