@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Invoice Details</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Invoice #{{ $invoice->invoice_number }}</h5>
            <p class="card-text"><strong>Customer Name:</strong> <a href="{{ route('customers.show', $invoice->customer->id) }}">{{ $invoice->customer->name }}</a></p>
            <p class="card-text"><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</p>
            <p class="card-text"><strong>Amount:</strong> {{ $invoice->amount }}</p>
            <p class="card-text"><strong>Tax:</strong> {{ $invoice->tax ?? '-' }}</p>

            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
