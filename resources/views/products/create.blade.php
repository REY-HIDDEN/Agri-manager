@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<x-page-header icon="plus-circle" title="Add Product" :backRoute="route('products.index')" />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required placeholder="e.g., Organic Rice">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', 0) }}" min="0" required>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Buying Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="buying_price" class="form-control @error('buying_price') is-invalid @enderror"
                           value="{{ old('buying_price', 0) }}" min="0" required>
                    @error('buying_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Selling Price ($) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror"
                           value="{{ old('selling_price', 0) }}" min="0" required>
                    @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Product
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
