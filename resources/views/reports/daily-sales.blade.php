@extends('layouts.app')

@section('title', 'Daily Sales Report')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-calendar-day me-2"></i>Daily Sales Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Reports
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>
            <div class="col-md-auto">
                <span class="text-muted">
                    <i class="fas fa-shopping-cart me-1"></i> {{ $transactionCount }} transactions
                </span>
            </div>
            <div class="col-md-auto">
                <span class="text-muted">
                    <i class="fas fa-dollar-sign me-1"></i> Total: <strong>${{ number_format($totalSales, 2) }}</strong>
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
                    <th>Sale #</th>
                    <th>Buyer</th>
                    <th>Products</th>
                    <th class="text-end">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->buyer->name ?? 'N/A' }}</td>
                    <td>
                        @foreach($sale->saleDetails as $detail)
                            <span class="badge bg-light text-dark me-1">
                                {{ $detail->product->name ?? 'N/A' }} x{{ $detail->quantity }}
                            </span>
                        @endforeach
                    </td>
                    <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                    <td><span class="badge {{ $sale->payment_status == 'paid' ? 'bg-success' : 'badge-pending' }} text-dark">{{ ucfirst($sale->payment_status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <th colspan="3">Total</th>
                    <th class="text-end">${{ number_format($totalSales, 2) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-day"></i>
            <h5>No Sales Found</h5>
            <p>No sales recorded for this date.</p>
        </div>
        @endif
    </div>
</div>
@endsection
