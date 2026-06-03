@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-shopping-cart me-2"></i>Sales</h2>
    <a href="{{ route('sales.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Sale
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search buyer..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(request('search'))<a href="{{ route('sales.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>@endif
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Buyer</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td><strong>{{ $sale->buyer->name ?? 'N/A' }}</strong></td>
                            <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                            <td>
                                <span class="badge {{ $sale->payment_status == 'paid' ? 'bg-success' : ($sale->payment_status == 'partial' ? 'badge-partial' : 'badge-pending') }} text-dark">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @if($sale->delivery)
                                    <span class="badge {{ $sale->delivery->status == 'delivered' ? 'bg-success' : ($sale->delivery->status == 'in_transit' ? 'badge-transit' : 'badge-pending') }} text-dark">
                                        {{ str_replace('_', ' ', ucfirst($sale->delivery->status)) }}
                                    </span>
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this sale? This will restore product stock.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart"></i>
                                    <h5>No Sales Recorded</h5>
                                    <p>Record your first sale to start tracking transactions.</p>
                                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> New Sale
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
