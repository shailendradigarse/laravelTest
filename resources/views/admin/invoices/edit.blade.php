@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Invoice</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="invoice_number" class="form-label">Invoice Number</label>
            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}" required>
        </div>

        <div class="mb-3">
            <label for="tax" class="form-label">Tax</label>
            <input type="number" name="tax" class="form-control" value="{{ old('tax', $invoice->tax) }}">
        </div>

        <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $invoice->invoice_date) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
