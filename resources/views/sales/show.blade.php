@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-receipt me-2"></i>Sale #{{ $sale->id }}</h2>
    <div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-outline-primary me-1">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        @endif
        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Sale Information</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Sale Date</td>
                        <td><strong>{{ $sale->sale_date->format('F d, Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Amount</td>
                        <td><strong class="text-success">${{ number_format($sale->total_amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment Status</td>
                        <td>
                            <span class="badge {{ $sale->payment_status == 'paid' ? 'bg-success' : ($sale->payment_status == 'partial' ? 'badge-partial' : 'badge-pending') }} text-dark">
                                {{ ucfirst($sale->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="fas fa-user me-2"></i>Buyer Information</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td class="text-muted">Name</td>
                        <td><strong>{{ $sale->buyer->name ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td>{{ $sale->buyer->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $sale->buyer->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Address</td>
                        <td>{{ $sale->buyer->address ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header"><i class="fas fa-boxes me-2"></i>Products Sold</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-end">Unit Price</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleDetails as $detail)
                <tr>
                    <td><strong>{{ $detail->product->name ?? 'N/A' }}</strong></td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-end">${{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-end"><strong>${{ number_format($detail->subtotal, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Amount:</th>
                    <th class="text-end text-success">${{ number_format($sale->total_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@if($sale->delivery)
<div class="card">
    <div class="card-header"><i class="fas fa-truck me-2"></i>Delivery Information</div>
    <div class="card-body">
        <table class="table table-sm">
            <tr>
                <td class="text-muted">Delivery Date</td>
                <td>{{ $sale->delivery->delivery_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Destination</td>
                <td>{{ $sale->delivery->destination }}</td>
            </tr>
            <tr>
                <td class="text-muted">Status</td>
                <td>
                    <span class="badge {{ $sale->delivery->status == 'delivered' ? 'bg-success' : ($sale->delivery->status == 'in_transit' ? 'badge-transit' : 'badge-pending') }} text-dark">
                        {{ str_replace('_', ' ', ucfirst($sale->delivery->status)) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>
@endif

<div class="mt-4">
    <button class="btn btn-outline-primary" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Print Invoice
    </button>
</div>
@endsection
