@extends('layouts.app')

@section('title', 'Edit Sale')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-edit me-2"></i>Edit Sale #{{ $sale->id }}</h2>
    <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('sales.update', $sale) }}">
            @csrf @method('PUT')

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Buyer</label>
                    <input type="text" class="form-control" value="{{ $sale->buyer->name ?? 'N/A' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Sale Date</label>
                    <input type="text" class="form-control" value="{{ $sale->sale_date->format('M d, Y') }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                    <select name="payment_status" class="form-select @error('payment_status') is-invalid @enderror" required>
                        <option value="pending" {{ $sale->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $sale->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ $sale->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                    </select>
                    @error('payment_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered">
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
                            <td>{{ $detail->product->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $detail->quantity }}</td>
                            <td class="text-end">${{ number_format($detail->unit_price, 2) }}</td>
                            <td class="text-end">${{ number_format($detail->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-end">${{ number_format($sale->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Update Sale Status
            </button>
            <a href="{{ route('sales.show', $sale) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
