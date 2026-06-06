@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<x-page-header icon="user-cog" title="User Management" :actionRoute="route('users.create')" actionLabel="Add User" />

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                            @if($user->role == 'admin')
                                <i class="fas fa-shield-alt me-1"></i> Admin
                            @else
                                <i class="fas fa-user me-1"></i> Sales Officer
                            @endif
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <x-delete-button :route="route('users.destroy', $user)" confirm="Delete this user?" />
                        @endif
                    </td>
                </tr>
                @empty
                <x-empty-state
                    icon="user-cog"
                    title="No Users Found"
                    message="Create users to manage system access."
                    :actionRoute="route('users.create')"
                    actionLabel="Add User"
                />
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $users->links() }}
</div>
@endsection
