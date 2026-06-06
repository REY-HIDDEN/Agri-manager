@extends('layouts.app')

@section('title', 'Buyers')

@section('content')
<x-page-header icon="users" title="Buyers" :actionRoute="route('buyers.create')" actionLabel="Add Buyer" />

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
                                <x-delete-button :route="route('buyers.destroy', $buyer)" confirm="Delete this buyer?" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="users"
                            title="No Buyers Yet"
                            message="Add your first buyer to start recording sales."
                            :actionRoute="route('buyers.create')"
                            actionLabel="Add Buyer"
                        />
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
