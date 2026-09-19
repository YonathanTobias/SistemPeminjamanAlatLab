@extends('layouts.app')

@section('content')
<div class="auth-split-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card auth-split-card border-0 bg-white">
                    <div class="row g-0">
                        <!-- Left Side: Institutional Branding & Features -->
                        <div class="col-lg-6 auth-brand-side">
                            <div>
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <div class="bg-white p-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
                                        <img src="{{ asset('images/logo-stikes.png') }}" alt="Logo STIKES" style="max-height: 40px; max-width: 40px; object-fit: contain;">
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-white mb-0">STIKES Panti Waluya</h5>
                                        <small class="text-white-50">Sistem Informasi Laboratorium Medis</small>
                                    </div>
                                </div>

                                <h3 class="fw-bold text-white mb-3">Portal Manajemen & Layanan Laboratorium</h3>
                                <p class="text-white-50 small mb-4">
                                    Sistem terintegrasi untuk pengelolaan katalog peralatan medis, peminjaman paket multi-alat, verifikasi QR Code instan, dan pelaporan berkala.
                                </p>

                                <div class="d-flex flex-column gap-3 mb-4">
                                    <div class="d-flex align-items-center gap-3 text-white-50 small">
                                        <div class="p-2 rounded-circle text-info d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255, 255, 255, 0.15);">
                                            <i class="bi bi-qr-code-scan"></i>
                                        </div>
                                        <span>Verifikasi Cepat dengan QR Code Scanner</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-white-50 small">
                                        <div class="p-2 rounded-circle text-success d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255, 255, 255, 0.15);">
                                            <i class="bi bi-boxes"></i>
                                        </div>
                                        <span>Manajemen Stok & Logistik Alat Medis</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 text-white-50 small">
                                        <div class="p-2 rounded-circle text-warning d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; background: rgba(255, 255, 255, 0.15);">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </div>
                                        <span>Ekspor Laporan PDF & Rekapitulasi CSV</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-top border-white border-opacity-10 text-white-50 small">
                                &copy; {{ date('Y') }} Laboratorium STIKES Panti Waluya Malang
                            </div>
                        </div>

                        <!-- Right Side: Login Form -->
                        <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                            <div class="mb-4">
                                <h4 class="fw-bold text-dark mb-1">Masuk ke Akun</h4>
                                <p class="text-muted small">Silakan masukkan kredensial akun laboran / admin Anda.</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-semibold text-muted">
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
                                        <label for="password" class="form-label small fw-semibold text-muted mb-0">
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
                                        <input id="password" type="password" class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror" name="password" placeholder="••••••••" required autocomplete="current-password">
                                        <button class="btn btn-light bg-white border border-start-0 text-muted" type="button" onclick="togglePasswordVisibility('password', this)" title="Lihat Sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="text-danger small mt-1 d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-4 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label small text-muted" for="remember">
                                            Ingat saya
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                        <span>Masuk ke Dashboard</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-2">
                                <a href="{{ url('/') }}" class="text-decoration-none small text-muted">
                                    <i class="bi bi-arrow-left"></i> Kembali ke Katalog Publik
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const inp = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
@endpush

