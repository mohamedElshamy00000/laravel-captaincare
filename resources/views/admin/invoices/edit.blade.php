@extends('layouts.backend')

@section('content')
<div class="container">
    <!-- Display success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display error message -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subscription.invoice.update') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $invoice->id }}">

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $invoice->amount) }}" required>
            @error('amount')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mt-4">
            <label for="plan_id">Plan</label>
            <select class="form-control @error('plan_id') is-invalid @enderror" id="plan_id" name="plan_id" required>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id', $invoice->plan_id) == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }}
                    </option>
                @endforeach
            </select>
            @error('plan_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mt-4">
            <label for="user_id">Father</label>
            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                @foreach($fathers as $father)
                    <option value="{{ $father->id }}" {{ old('user_id', $invoice->user_id) == $father->id ? 'selected' : '' }}>
                        {{ $father->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mt-4">
            <label for="child_id">Children</label>
            <select class="form-control @error('child_id') is-invalid @enderror" id="child_id" name="child_id" required>
                {{$children}}
                @foreach($children as $child)
                    <option value="{{ $child->id }}" {{ $child->id, old('child_id', $invoice->child_id) ? 'selected' : '' }}>
                        {{ $child->name }}
                    </option>
                @endforeach
            </select>
            @error('child_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-4">Update Invoice</button>
    </form>
</div>
@endsection
