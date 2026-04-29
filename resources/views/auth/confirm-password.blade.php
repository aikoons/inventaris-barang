@extends('layouts.guest')
@section('title', 'Konfirmasi Password')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                 style="width:64px;height:64px;background:linear-gradient(135deg,#4F46E5,#818CF8);border-radius:16px;box-shadow:0 8px 24px rgba(79,70,229,0.35);">
                <i class="bi bi-shield-check text-white fs-2"></i>
            </div>
            <h1 class="fw-extrabold" style="font-size:1.3rem;color:#1E293B;">Konfirmasi Password</h1>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Ini adalah area aman. Konfirmasi password untuk melanjutkan.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           required autocomplete="current-password" placeholder="••••••••">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-check-lg me-2"></i>Konfirmasi
            </button>
        </form>
    </div>
</div>
@endsection
