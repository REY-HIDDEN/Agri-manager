@extends('layouts.app')

@section('title', 'Buyers')

@section('content')
<div class="page-header">
    <h2><i class="fas fa-users me-2"></i>Buyers</h2>
    <a href="{{ route('buyers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add Buyer
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, phone or email..." value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('buyers.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buyers as $buyer)
                        <tr>
                            <td>{{ $buyer->id }}</td>
                            <td><strong>{{ $buyer->name }}</strong></td>
                            <td>{{ $buyer->phone }}</td>
                            <td>{{ $buyer->email ?? '-' }}</td>
                            <td>{{ Str::limit($buyer->address, 40) ?? '-' }}</td>
                            <td>{{ $buyer->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('buyers.edit', $buyer) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('buyers.destroy', $buyer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this buyer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <h5>No Buyers Yet</h5>
                                    <p>Add your first buyer to start recording sales.</p>
                                    <a href="{{ route('buyers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Buyer
                                    </a>
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
