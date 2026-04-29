@extends('layouts.guest')
@section('title', 'Verifikasi Email')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card text-center">
        <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center mb-3"
                 style="width:64px;height:64px;background:linear-gradient(135deg,#10B981,#34D399);border-radius:16px;box-shadow:0 8px 24px rgba(16,185,129,0.3);">
                <i class="bi bi-envelope-check-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-extrabold" style="font-size:1.3rem;color:#1E293B;">Verifikasi Email</h1>
            <p class="text-muted" style="font-size:0.875rem;">
                Terima kasih! Sebelum mulai, tolong verifikasi email Anda dengan mengklik link yang telah dikirim ke email Anda.
            </p>
        </div>

        @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success">Link verifikasi baru telah dikirim ke email Anda.</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-send me-2"></i>Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">
                <i class="bi bi-box-arrow-right me-2"></i>Keluar
            </button>
        </form>
    </div>
</div>
@endsection
