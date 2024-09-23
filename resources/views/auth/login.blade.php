@extends('layouts.auth')

@section('content')

<div>
    <h5 class="text-dark fw-bold">Welcome Back !</h5>
    <p class="text-muted">Sign in to continue.</p>
</div>


<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="username" class="form-label">{{ __('Email Address') }}</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" class=" @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    
    <div class="mb-3">
        @if (Route::has('password.request'))
            <div class="float-end">
                <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
            </div>
        @endif
        
        <label class="form-label">{{ __('Password') }}</label>
        <div class="input-group auth-pass-inputgroup">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
            <button class="btn btn-light" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>


    <div class="row mb-3">
        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
    </div>

            
    <div class="mt-3 d-grid">
        <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
    </div>

</form>
@endsection

