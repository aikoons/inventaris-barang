@extends('layouts.guest')
@section('title', 'Lupa Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                 style="width:64px;height:64px;background:linear-gradient(135deg,#4F46E5,#818CF8);border-radius:16px;box-shadow:0 8px 24px rgba(79,70,229,0.35);">
                <i class="bi bi-key-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-extrabold" style="font-size:1.5rem;color:#1E293B;">Lupa Password</h1>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Masukkan email untuk menerima link reset password.</p>
        </div>

        @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus placeholder="email@sekolah.id">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-send me-2"></i>Kirim Link Reset
            </button>
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none" style="font-size:0.875rem;color:#4F46E5;">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
