<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #F1F5F9; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-card { text-align: center; max-width: 420px; padding: 40px; background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .error-icon { font-size: 4rem; color: #EF4444; margin-bottom: 16px; }
        h1 { font-weight: 800; font-size: 1.5rem; color: #1E293B; }
        p { color: #64748B; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon"><i class="bi bi-shield-x"></i></div>
        <h1>Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini. Hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="bi bi-house me-1"></i>Dashboard</a>
    </div>
</body>
</html>
