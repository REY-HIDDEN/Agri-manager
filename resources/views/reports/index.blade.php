@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-file-alt me-2"></i>Reports</h2>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <a href="{{ route('reports.daily-sales') }}" class="text-decoration-none">
            <div class="card h-100">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-day fa-3x mb-3" style="color: #2d6a4f;"></i>
                    <h5>Daily Sales Report</h5>
                    <p class="text-muted mb-0">View sales transactions for a specific day</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('reports.monthly-sales') }}" class="text-decoration-none">
            <div class="card h-100">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-alt fa-3x mb-3" style="color: #1565c0;"></i>
                    <h5>Monthly Sales Report</h5>
                    <p class="text-muted mb-0">View monthly revenue and transactions</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('reports.products') }}" class="text-decoration-none">
            <div class="card h-100">
                <div class="card-body text-center py-5">
                    <i class="fas fa-apple-alt fa-3x mb-3" style="color: #e65100;"></i>
                    <h5>Product Report</h5>
                    <p class="text-muted mb-0">View stock levels and sold quantities</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('reports.buyers') }}" class="text-decoration-none">
            <div class="card h-100">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-3x mb-3" style="color: #6a1b9a;"></i>
                    <h5>Buyer Report</h5>
                    <p class="text-muted mb-0">View buyer purchase history</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('reports.profit') }}" class="text-decoration-none">
            <div class="card h-100">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-line fa-3x mb-3" style="color: #2e7d32;"></i>
                    <h5>Profit Report</h5>
                    <p class="text-muted mb-0">View revenue, costs, and net profit</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
