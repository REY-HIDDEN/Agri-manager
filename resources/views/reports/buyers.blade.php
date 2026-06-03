@extends('layouts.app')

@section('title', 'Buyer Report')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-users me-2"></i>Buyer Report</h2>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Reports
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search buyers..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    @if(request('search'))<a href="{{ route('reports.buyers') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>@endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Buyer</th>
                        <th>Phone</th>
                        <th class="text-center">Total Purchases</th>
                        <th class="text-end">Total Spent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buyers as $buyer)
                    <tr>
                        <td><strong>{{ $buyer->name }}</strong></td>
                        <td>{{ $buyer->phone }}</td>
                        <td class="text-center">{{ $buyer->sales_count }}</td>
                        <td class="text-end">${{ number_format($buyer->sales_sum_total_amount ?? 0, 2) }}</td>
                        <td>
                            <a href="{{ route('reports.buyer-history', $buyer) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-eye"></i> View History
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h5>No Buyers Found</h5>
                                <p>No buyers match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $buyers->links() }}
        </div>
    </div>
</div>
@endsection
