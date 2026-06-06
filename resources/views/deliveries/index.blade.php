@extends('layouts.app')

@section('title', 'Deliveries')

@section('content')
@if(auth()->user()->isAdmin())
    <x-page-header icon="truck" title="Deliveries" :actionRoute="route('deliveries.create')" actionLabel="Record Delivery" />
@else
    <x-page-header icon="truck" title="Deliveries" />
@endif

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
                                <x-status-badge :status="$delivery->status" type="delivery" />
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                <a href="{{ route('deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <x-delete-button :route="route('deliveries.destroy', $delivery)" confirm="Delete this delivery record?" />
                            </td>
                            @endif
                        </tr>
                    @empty
                        <x-empty-state
                            icon="truck"
                            title="No Deliveries Recorded"
                            message="Record deliveries for completed sales."
                            :actionRoute="auth()->user()->isAdmin() ? route('deliveries.create') : null"
                            :actionLabel="auth()->user()->isAdmin() ? 'Record Delivery' : null"
                            :colspan="auth()->user()->isAdmin() ? 7 : 6"
                        />
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
