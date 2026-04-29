@extends('layouts.app')
@section('title', 'Peminjaman Barang')
@section('page-title', 'Peminjaman Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Peminjaman Barang</h1>
        <p class="page-header-sub">Kelola transaksi peminjaman dan pengembalian barang</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Catat Peminjaman
    </a>
</div>

@if($totalTerlambat > 0)
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong>{{ $totalTerlambat }} peminjaman terlambat!</strong>
        Segera hubungi peminjam untuk pengembalian barang.
        <a href="?status=terlambat" class="alert-link ms-2">Lihat sekarang →</a>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Kode, nama peminjam, barang..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="dipinjam" {{ request('status')=='dipinjam' ? 'selected':'' }}>Dipinjam</option>
                    <option value="terlambat" {{ request('status')=='terlambat' ? 'selected':'' }}>Terlambat</option>
                    <option value="dikembalikan" {{ request('status')=='dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                    <option value="rusak" {{ request('status')=='rusak' ? 'selected':'' }}>Rusak</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Daftar Peminjaman <span class="badge bg-primary ms-1">{{ $peminjamans->total() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th class="text-center">Jml</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Kondisi Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr class="{{ $p->is_terlambat ? 'table-danger' : '' }}">
                    <td><a href="{{ route('peminjaman.show', $p) }}" class="fw-semibold text-decoration-none font-monospace">{{ $p->kode_peminjaman }}</a></td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($p->barang->nama_barang, 30) }}</div>
                        <small class="text-muted">{{ $p->barang->kode_barang }}</small>
                    </td>
                    <td>
                        <div>{{ $p->nama_peminjam }}</div>
                        @if($p->instansi_peminjam)<small class="text-muted">{{ $p->instansi_peminjam }}</small>@endif
                    </td>
                    <td class="text-center fw-semibold">{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="{{ $p->is_terlambat ? 'text-danger fw-bold' : '' }}">
                        {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                        @if($p->is_terlambat)
                        <br><small class="badge bg-danger">{{ abs($p->sisa_hari) }} hari terlambat</small>
                        @elseif(in_array($p->status, ['dipinjam','terlambat']) && $p->sisa_hari <= 2 && $p->sisa_hari >= 0)
                        <br><small class="badge bg-warning text-dark">{{ $p->sisa_hari }} hari lagi</small>
                        @endif
                    </td>
                    <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                    <td>
                        @if($p->kondisi_kembali)
                            @php
                                $kb = $p->kondisi_kembali;
                                $kbColor = match($kb) {
                                    'baik'        => 'success',
                                    'rusak_ringan' => 'warning',
                                    'rusak_berat'  => 'danger',
                                    default        => 'secondary',
                                };
                                $kbLabel = match($kb) {
                                    'baik'        => 'Baik',
                                    'rusak_ringan' => 'Rusak Ringan',
                                    'rusak_berat'  => 'Rusak Berat',
                                    default        => $kb,
                                };
                            @endphp
                            <span class="badge bg-{{ $kbColor }}">{{ $kbLabel }}</span>
                        @else
                            <span class="text-muted-sm">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            @if(in_array($p->status, ['dipinjam','terlambat']))
                            <a href="{{ route('peminjaman.form-pengembalian', $p) }}" class="btn btn-sm btn-outline-success" title="Proses Pengembalian">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9">
                    <div class="empty-state">
                        <i class="bi bi-inbox d-block"></i>
                        <h6>Tidak ada data peminjaman</h6>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjamans->hasPages())
    <div class="card-body border-top py-3">{{ $peminjamans->links() }}</div>
    @endif
</div>
@endsection
