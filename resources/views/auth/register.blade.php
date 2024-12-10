@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center min-vh-100 position-relative" style="background-image: url('{{ asset('assets/bg.jpg') }}'); background-size: cover; background-position: center;">
    <!-- Overlay Gelap untuk Background -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.6);"></div>

    <div class="col-md-5 col-lg-4 position-relative" style="z-index: 1;"> <!-- Mengurangi ukuran kolom -->
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="background-color: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
            <!-- Header dengan Tema Restoran -->
            <div class="card-header text-center" style="background-color: #abc32f; color: white; padding: 20px; position: relative;"> <!-- Mengurangi padding header -->
                <h2 class="mb-1" style="font-family: 'Georgia', serif; font-size: 1.5rem;">{{ __('Create an Account') }}</h2> <!-- Memperkecil ukuran teks -->
                <p class="mb-0" style="font-size: 0.9rem; font-style: italic;">{{ __('Join us and explore our amazing menu') }}</p>
                <div style="position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 40px; height: 40px; background-color: white; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user-plus" style="color: #abc32f; font-size: 1.3em;"></i>
                </div>
            </div>

            <!-- Body Formulir -->
            <div class="card-body p-3" style="padding-top: 20px;"> <!-- Mengurangi padding body -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Field Name -->
                    <div class="form-group mb-3">
                        <label for="name" class="form-label" style="color: #444; font-size: 0.9rem;">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror rounded-3" name="name" placeholder="e.g., John Doe" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Field Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="form-label" style="color: #444; font-size: 0.9rem;">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror rounded-3" name="email" placeholder="e.g., user@restaurant.com" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Field Password -->
                    <div class="form-group mb-3">
                        <label for="password" class="form-label" style="color: #444; font-size: 0.9rem;">{{ __('Password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background-color: #abc32f; color: white;">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-3" name="password" placeholder="Enter your password" required autocomplete="new-password">
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Field Confirm Password -->
                    <div class="form-group mb-3">
                        <label for="password-confirm" class="form-label" style="color: #444; font-size: 0.9rem;">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control rounded-3" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password">
                    </div>

                    <!-- Tombol Register -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-lg rounded-pill" style="background-color: #abc32f; border: none; color: white; transition: background-color 0.3s ease;">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <!-- Link Login -->
                    <div class="text-center mt-3">
                        <p style="color: #444; font-size: 0.85rem;">{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="text-decoration-none" style="color: #abc32f;">{{ __('Login here') }}</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
