@extends('layouts.app')

@section('title', 'Product Report')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-apple-alt me-2"></i>Product Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Reports
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($products->count() > 0)
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Available Stock</th>
                    <th class="text-center">Sold Quantity</th>
                    <th class="text-end">Buying Price</th>
                    <th class="text-end">Selling Price</th>
                    <th class="text-end">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td class="text-center">
                        <span class="badge {{ $product->quantity == 0 ? 'bg-danger' : ($product->quantity < 10 ? 'bg-warning' : 'bg-success') }} text-dark">
                            {{ $product->quantity }}
                        </span>
                    </td>
                    <td class="text-center">{{ $product->sold_quantity }}</td>
                    <td class="text-end">${{ number_format($product->buying_price, 2) }}</td>
                    <td class="text-end">${{ number_format($product->selling_price, 2) }}</td>
                    <td class="text-end">${{ number_format($product->selling_price * $product->sold_quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-apple-alt"></i>
            <h5>No Products</h5>
            <p>No products have been added to the system.</p>
        </div>
        @endif
    </div>
</div>
@endsection
