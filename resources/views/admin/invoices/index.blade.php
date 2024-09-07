@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Invoice List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <!-- Search Form -->
    <form method="GET" action="{{ route('invoices.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by invoice number or customer name">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Invoice Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th><a href="{{ route('invoices.index', ['sort' => 'invoice_number', 'order' => $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Invoice Number</a></th>
                <th><a href="{{ route('invoices.index', ['sort' => 'customer_name', 'order' => $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Customer Name</a></th>
                <th><a href="{{ route('invoices.index', ['sort' => 'invoice_date', 'order' => $order === 'asc' ? 'desc' : 'asc', 'search' => $search]) }}">Invoice Date</a></th>
                <th>Amount</th>
                <th>Tax</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td><a href="{{ route('invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a></td>
                    <td><a href="{{ route('customers.show', $invoice->customer->id) }}">{{ $invoice->customer->name }}</a></td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td>{{ $invoice->tax ?? '-' }}</td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No invoices found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $invoices->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
