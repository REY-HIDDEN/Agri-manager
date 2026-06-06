@extends('layouts.app')

@section('title', 'Edit Delivery')

@section('content')
<x-page-header icon="edit" title="Edit Delivery" :backRoute="route('deliveries.index')" />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('deliveries.update', $delivery) }}">
            @csrf @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Sale</label>
                    <input type="text" class="form-control" value="Sale #{{ $delivery->sale->id }} - {{ $delivery->sale->buyer->name ?? 'N/A' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                    <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror"
                           value="{{ old('delivery_date', $delivery->delivery_date->format('Y-m-d')) }}" required>
                    @error('delivery_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Destination <span class="text-danger">*</span></label>
                    <textarea name="destination" class="form-control @error('destination') is-invalid @enderror"
                              rows="2" required>{{ old('destination', $delivery->destination) }}</textarea>
                    @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="pending" {{ old('status', $delivery->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_transit" {{ old('status', $delivery->status) == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="delivered" {{ old('status', $delivery->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Delivery
                    </button>
                    <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
