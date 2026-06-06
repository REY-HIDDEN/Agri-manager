@extends('layouts.app')

@section('title', 'Record Delivery')

@section('content')
<x-page-header icon="truck-loading" title="Record Delivery" :backRoute="route('deliveries.index')" />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('deliveries.store') }}">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Sale <span class="text-danger">*</span></label>
                    <select name="sale_id" class="form-select @error('sale_id') is-invalid @enderror" required>
                        <option value="">Select Sale</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                                Sale #{{ $sale->id }} - {{ $sale->buyer->name ?? 'N/A' }} (${{ number_format($sale->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('sale_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                    <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror"
                           value="{{ old('delivery_date', date('Y-m-d')) }}" required>
                    @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destination <span class="text-danger">*</span></label>
                    <textarea name="destination" class="form-control @error('destination') is-invalid @enderror"
                              rows="2" required placeholder="Enter delivery destination">{{ old('destination') }}</textarea>
                    @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_transit" {{ old('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Delivery
                    </button>
                    <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
