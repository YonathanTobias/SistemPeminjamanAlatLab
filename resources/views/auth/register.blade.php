@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 rounded-4 shadow-lg overflow-hidden">
                <!-- Header Gradient & Logo -->
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #0d9488 100%);">
                    <div class="mx-auto mb-3 bg-white p-2 rounded-4 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 72px; height: 72px;">
                        <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="max-height: 56px; max-width: 56px; object-fit: contain;">
                    </div>
                    <h4 class="fw-bold mb-1">Registrasi Laboran Baru</h4>
                    <p class="text-white-50 small mb-0">Laboratorium Keperawatan STIKES Panti Waluya Malang</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person text-primary"></i> Nama Lengkap
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <input id="name" type="text" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nama Petugas Laboran" required autocomplete="name" autofocus>
                            </div>
                            @error('name')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope text-primary"></i> Alamat Email Kampus
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-at"></i>
                                </span>
                                <input id="email" type="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="laboran@stikespantiwaluya.ac.id" required autocomplete="email">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock text-primary"></i> Kata Sandi
                                </label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="new-password">
                                @error('password')
                                    <span class="text-danger small mt-1 d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label">
                                    <i class="bi bi-shield-check text-primary"></i> Konfirmasi Sandi
                                </label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm">
                                <i class="bi bi-check-circle-fill"></i> Daftarkan Akun Laboran
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left"></i> Sudah punya akun? Masuk di sini
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

