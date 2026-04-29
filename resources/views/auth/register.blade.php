@extends('layouts.guest')
@section('title', 'Daftar Akun')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                 style="width:64px;height:64px;background:linear-gradient(135deg,#4F46E5,#818CF8);border-radius:16px;box-shadow:0 8px 24px rgba(79,70,229,0.35);">
                <i class="bi bi-person-plus-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-extrabold" style="font-size:1.5rem;color:#1E293B;">Buat Akun</h1>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Inventaris Barang Sekolah</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus placeholder="username">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required placeholder="email@sekolah.id">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="new-password" placeholder="Minimal 8 karakter">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password_confirmation" class="form-control"
                           required autocomplete="new-password" placeholder="Ulangi password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-person-check me-2"></i>Daftar
            </button>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none" style="font-size:0.875rem;color:#4F46E5;">
                    Sudah punya akun? Masuk
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
