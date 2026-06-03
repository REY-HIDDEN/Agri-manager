@extends('layouts.app')

@section('title', 'Buyer Purchase History')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-history me-2"></i>{{ $buyer->name }} - Purchase History</h2>
    <a href="{{ route('reports.buyers') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Total Purchases</div>
                <div class="fs-4 fw-bold">{{ $buyer->sales->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Total Spent</div>
                <div class="fs-4 fw-bold text-success">${{ number_format($buyer->sales->sum('total_amount'), 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Phone</div>
                <div class="fs-5">{{ $buyer->phone }}</div>
            </div>
        </div>
    </div>
</div>

@foreach($buyer->sales as $sale)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-receipt me-2"></i> Sale #{{ $sale->id }} - {{ $sale->sale_date->format('M d, Y') }}
        </span>
        <span class="badge {{ $sale->payment_status == 'paid' ? 'bg-success' : 'badge-pending' }} text-dark">
            {{ ucfirst($sale->payment_status) }}
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-end">${{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-end">${{ number_format($detail->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-active">
                    <th colspan="3" class="text-end">Total:</th>
                    <th class="text-end">${{ number_format($sale->total_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endforeach
@endsection
