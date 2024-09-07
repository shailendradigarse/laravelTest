@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Customer Details</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $customer->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $customer->email }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $customer->phone ?? 'N/A' }}</p>
            <p class="card-text"><strong>Joining Date:</strong> {{ $customer->joining_date }}</p>

            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
