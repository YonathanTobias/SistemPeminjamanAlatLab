@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                <!-- Card Header with Accent Gradient & Logo -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0369a1 100%);">
                    <div class="mx-auto mb-3 bg-white p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 72px; height: 72px;">
                        <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="max-height: 56px; max-width: 56px; object-fit: contain;">
                    </div>
                    <h4 class="fw-bold mb-1">Login Laboran</h4>
                    <p class="text-white-50 small mb-0">Laboratorium Keperawatan STIKES Panti Waluya</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope text-primary"></i> Alamat Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="nama@stikespantiwaluya.ac.id" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label mb-0">
                                    <i class="bi bi-key text-primary"></i> Kata Sandi
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small text-muted" href="{{ route('password.request') }}">
                                        Lupa Sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="input-group mt-1">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat saya di perangkat ini
                                </label>
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold shadow-sm">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left"></i> Kembali ke Katalog Publik
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

