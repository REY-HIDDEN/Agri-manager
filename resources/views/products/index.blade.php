@extends('layouts.app')

@section('title', 'Products')

@section('content')
@if(auth()->user()->isAdmin())
    <x-page-header icon="apple-alt" title="Products" :actionRoute="route('products.create')" actionLabel="Add Product" />
@else
    <x-page-header icon="apple-alt" title="Products" />
@endif

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request()->anyFilled(['search', 'quantity_min', 'quantity_max', 'price_min', 'price_max', 'low_stock']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity_min" class="form-control" placeholder="Qty min" value="{{ request('quantity_min') }}" min="0">
            </div>
            <div class="col-md-2">
                <input type="number" name="quantity_max" class="form-control" placeholder="Qty max" value="{{ request('quantity_max') }}" min="0">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="price_min" class="form-control" placeholder="Price min ($)" value="{{ request('price_min') }}" min="0">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="price_max" class="form-control" placeholder="Price max ($)" value="{{ request('price_max') }}" min="0">
            </div>
            <div class="col-12">
                <div class="form-check form-check-inline">
                    <input type="checkbox" name="low_stock" class="form-check-input" id="lowStock" value="1" {{ request()->boolean('low_stock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="lowStock">Low Stock Only (&lt; 10)</label>
                </div>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Expected Profit</th>
                        <th>Created</th>
                        @if(auth()->user()->isAdmin())
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>
                                <span class="badge {{ $product->quantity < 10 ? 'bg-danger' : ($product->quantity < 50 ? 'bg-warning' : 'bg-success') }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td>${{ number_format($product->buying_price, 2) }}</td>
                            <td>${{ number_format($product->selling_price, 2) }}</td>
                            <td>
                                <span class="text-success">
                                    ${{ number_format(($product->selling_price - $product->buying_price) * $product->quantity, 2) }}
                                </span>
                            </td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <x-delete-button :route="route('products.destroy', $product)" confirm="Delete this product?" />
                            </td>
                            @endif
                        </tr>
                    @empty
                        <x-empty-state
                            icon="apple-alt"
                            title="No Products Yet"
                            message="Start by adding your first agricultural product."
                            :actionRoute="auth()->user()->isAdmin() ? route('products.create') : null"
                            :actionLabel="auth()->user()->isAdmin() ? 'Add Product' : null"
                            :colspan="auth()->user()->isAdmin() ? 8 : 7"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
