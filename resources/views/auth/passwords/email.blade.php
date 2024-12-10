@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center position-relative" style="background-image: url('{{ asset('assets/background.jpg') }}'); background-size: cover; background-position: center;">
    <!-- Overlay Gelap untuk Background -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.6);"></div>

    <div class="col-md-6 col-lg-5 position-relative" style="z-index: 1;">
        <div class="card shadow-sm border-0 rounded-3 overflow-hidden" style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
            <!-- Header dengan Latar Belakang Hijau Lemon -->
            <div class="card-header text-center p-4" style="background-color: #abc32f; color: #fff;">
                <h4 class="mb-0 fw-bold">{{ __('Reset Password') }}</h4>
                <p class="mb-0 text-white-50 mt-2">Dapatkan kembali akses ke akun Anda!</p>
            </div>

            <!-- Formulir Reset Password -->
            <div class="card-body px-4 py-4">
                @if (session('status'))
                    <div class="alert alert-success text-center mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label text-muted">{{ __('Alamat Email') }}</label>
                        <input id="email" type="email" 
                               class="form-control form-control-lg bg-light border-0 rounded-3 @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="Masukkan email Anda">
                        @error('email')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg rounded-pill fw-bold" style="background-color: #abc32f; color: #fff;">
                            {{ __('Kirim Tautan Reset') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Minimalis dengan Link Bantuan -->
            <div class="card-footer text-center py-3" style="background-color: #f0f6e8;">
                <small class="text-muted">Butuh bantuan? <a href="#" class="text-success fw-bold" style="color: #abc32f;">Hubungi kami</a></small>
            </div>
        </div>
    </div>
</div>
@endsection
