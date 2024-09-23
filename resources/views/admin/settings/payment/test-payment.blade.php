
@extends('layouts.backend')
@section('title')
    main settings
@endsection

@section('content')

<h2>Payment Details</h2>

<form id="paymentForm" action="{{ route('admin.payment.initiate') }}" method="POST">
    @csrf
    <label for="amount">Amount:</label>
    <input type="text" id="amount" name="amount" required><br><br>
    
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required><br><br>
    
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required><br><br>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    
    <label for="phone">Phone:</label>
    <input type="text" id="phone" name="phone" required><br><br>
    
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" required><br><br>
    
    <label for="card_exp_month">Expiry Month:</label>
    <input type="text" id="card_exp_month" name="card_exp_month" required><br><br>
    
    <label for="card_exp_year">Expiry Year:</label>
    <input type="text" id="card_exp_year" name="card_exp_year" required><br><br>
    
    <label for="card_cvv">CVV:</label>
    <input type="text" id="card_cvv" name="card_cvv" required><br><br>
    
    <button type="submit">Pay Now</button>
</form>

@endsection

@push('script')

@endpush
