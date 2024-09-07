@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Customer</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Customer Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
        </div>

        <div class="mb-3">
            <label for="joining_date" class="form-label">Joining Date</label>
            <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $customer->joining_date) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
