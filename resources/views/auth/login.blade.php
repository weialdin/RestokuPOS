@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100 position-relative" style="background-image: url('{{ asset('assets/bg.jpg') }}'); background-size: cover; background-position: center;">
    <!-- Overlay Gelap untuk Background -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.6);"></div>

    <div class="col-md-6 col-lg-5 position-relative" style="z-index: 1;">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
            <!-- Header dengan Tema Restoran -->
            <div class="card-header text-center" style="background-color: #abc32f; color: white; padding: 30px; position: relative;">
                <h2 class="mb-1" style="font-family: 'Georgia', serif;">{{ __('Welcome to Our Restaurant') }} </h2>
                <p class="mb-0" style="font-size: 1em; font-style: italic;">{{ __('Please log in to your account to continue') }}</p>
                <div style="position: absolute; bottom: -20px; left: 50%; transform: translateX(-50%); width: 50px; height: 50px; background-color: white; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-utensils" style="color: #abc32f; font-size: 1.5em;"></i>
                </div>
            </div>

            <!-- Body Formulir -->
                <div class="card-body p-3" style="padding-top: 30px;">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Field Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label" style="color: #444;">{{ __('Email Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror rounded-3" name="email" placeholder="e.g., user@restaurant.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Field Password -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label" style="color: #444;">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background-color: #abc32f; color: white;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-3" name="password" placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Checkbox Remember Me -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember" style="color: #444;">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <!-- Tombol Login -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg rounded-pill" style="background-color: #abc32f; border: none; color: white; transition: background-color 0.3s ease;">
                                {{ __('Login') }}
                            </button>
                        </div>

                        <!-- Link Reset Password -->
                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <p style="color: #444;">{{ __('Dont have an account?') }} <a href="{{ route('register') }}" class="text-decoration-none" style="color: #abc32f;">{{ __('Register here') }}</a></p>
                                <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}" style="color: #abc32f;">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection
