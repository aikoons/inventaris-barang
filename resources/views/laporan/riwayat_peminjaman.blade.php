@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Laporan Riwayat Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Riwayat Peminjaman</h1>
        <p class="page-header-sub">Rekap seluruh transaksi peminjaman barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.export.excel.peminjaman', request()->query()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
        <a href="{{ route('laporan.export.pdf.peminjaman', request()->query()) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                    <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('laporan.riwayat-peminjaman') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Total: <strong>{{ $peminjamans->count() }}</strong> transaksi</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th class="text-center">Jml</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Kondisi Kembali</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $i => $p)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td><code><a href="{{ route('peminjaman.show', $p) }}" class="text-decoration-none">{{ $p->kode_peminjaman }}</a></code></td>
                    <td>{{ Str::limit($p->barang->nama_barang, 25) }}</td>
                    <td>
                        <div>{{ $p->nama_peminjam }}</div>
                        @if($p->instansi_peminjam)<small class="text-muted">{{ $p->instansi_peminjam }}</small>@endif
                    </td>
                    <td class="text-center">{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>
                        {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                        @if($p->tanggal_kembali_aktual)
                        <br><small class="text-success">↩ {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</small>
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
                    <td class="text-muted-sm">{{ $p->user->display_name }}</td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">Tidak ada data peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
