@extends('layouts.app') <!-- assuming you have a default layout -->

@section('content')
<div class="container mt-4">
    <h2>Customer List : </h2>
    <p>Query Execution Time: {{ number_format($executionTime, 2) }} ms</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('customers.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name or email">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Table to display customers -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><a href="{{ route('customers.index', ['sort' => 'name', 'order' => $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">
                    Name
                    @if($sort == 'name')
                        <i class="fa fa-sort-{{ $order === 'asc' ? 'asc' : 'desc' }}"></i>
                    @endif
                </a></th>
                <th><a href="{{ route('customers.index', ['sort' => 'email', 'order' => $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">
                    Email
                    @if($sort == 'email')
                        <i class="fa fa-sort-{{ $order === 'asc' ? 'asc' : 'desc' }}"></i>
                    @endif
                </a></th>
                <th>Phone</th>
                <th>Joining Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->joining_date }}</td>
                    <td>
                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No customers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-between">
        {{ $customers->links('pagination::bootstrap-5') }} <!-- This will generate Bootstrap-styled pagination links -->
    </div>
</div>
@endsection
