@extends('layouts.app')

@section('title', 'Deliveries')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-truck me-2"></i>Deliveries</h2>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Record Delivery
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sale</th>
                        <th>Buyer</th>
                        <th>Delivery Date</th>
                        <th>Destination</th>
                        <th>Status</th>
                        @if(auth()->user()->isAdmin())
                        <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                        <tr>
                            <td>#{{ $delivery->id }}</td>
                            <td>
                                <a href="{{ route('sales.show', $delivery->sale_id) }}" class="text-decoration-none">
                                    Sale #{{ $delivery->sale_id }}
                                </a>
                            </td>
                            <td>{{ $delivery->sale->buyer->name ?? 'N/A' }}</td>
                            <td>{{ $delivery->delivery_date->format('M d, Y') }}</td>
                            <td>{{ Str::limit($delivery->destination, 30) }}</td>
                            <td>
                                <span class="badge {{ $delivery->status == 'delivered' ? 'bg-success' : ($delivery->status == 'in_transit' ? 'badge-transit' : 'badge-pending') }} text-dark">
                                    {{ str_replace('_', ' ', ucfirst($delivery->status)) }}
                                </span>
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('deliveries.destroy', $delivery) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this delivery record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? '7' : '6' }}">
                                <div class="empty-state">
                                    <i class="fas fa-truck"></i>
                                    <h5>No Deliveries Recorded</h5>
                                    <p>Record deliveries for completed sales.</p>
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('deliveries.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Record Delivery
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $deliveries->links() }}
        </div>
    </div>
</div>
@endsection
