@extends('layouts.app')
@section('title', $barang->nama_barang)
@section('page-title', 'Detail Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">{{ $barang->nama_barang }}</h1>
        <p class="page-header-sub">
            <span class="font-monospace">{{ $barang->kode_barang }}</span>
            <span class="badge ms-2" style="background-color:{{ $barang->kategori->warna ?? '#6B7280' }}20; color:{{ $barang->kategori->warna ?? '#6B7280' }};">
                {{ $barang->kategori->nama_kategori }}
            </span>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('barang.lapor-kerusakan', $barang) }}" class="btn btn-outline-warning">
            <i class="bi bi-tools me-1"></i>Lapor Kerusakan
        </a>
        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        @if($barang->jumlah_tersedia > 0)
        <a href="{{ route('peminjaman.create', ['barang_id'=>$barang->id]) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left-right me-1"></i>Pinjamkan
        </a>
        @endif
    </div>
</div>

<div class="row g-3">
    <!-- Foto & QR -->
    <div class="col-12 col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center p-4">
                @if($barang->foto)
                    <img src="{{ asset('storage/'.$barang->foto) }}" alt="{{ $barang->nama_barang }}"
                         class="img-fluid rounded" style="max-height:250px; object-fit:cover;">
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center" style="height:200px; background:#F8FAFC; border-radius:8px;">
                        <i class="bi bi-box-seam display-4 text-muted opacity-50"></i>
                        <p class="text-muted-sm mt-2">Belum ada foto</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stock Info Card -->
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Informasi Stok & Kondisi</h6>

                {{-- Progress bar komposisi --}}
                @php
                    $pctBaik   = $barang->jumlah > 0 ? ($barang->jumlah_baik / $barang->jumlah) * 100 : 0;
                    $pctRingan = $barang->jumlah > 0 ? ($barang->jumlah_rusak_ringan / $barang->jumlah) * 100 : 0;
                    $pctBerat  = $barang->jumlah > 0 ? ($barang->jumlah_rusak_berat / $barang->jumlah) * 100 : 0;
                @endphp
                <div class="progress mb-2" style="height:16px;border-radius:8px;" title="Komposisi kondisi">
                    <div class="progress-bar bg-success" style="width:{{ $pctBaik }}%">
                        @if($barang->jumlah_baik > 0) {{ $barang->jumlah_baik }} @endif
                    </div>
                    <div class="progress-bar bg-warning" style="width:{{ $pctRingan }}%">
                        @if($barang->jumlah_rusak_ringan > 0) {{ $barang->jumlah_rusak_ringan }} @endif
                    </div>
                    <div class="progress-bar bg-danger" style="width:{{ $pctBerat }}%">
                        @if($barang->jumlah_rusak_berat > 0) {{ $barang->jumlah_rusak_berat }} @endif
                    </div>
                </div>
                <div class="d-flex gap-2 mb-3 flex-wrap">
                    <small class="d-flex align-items-center gap-1"><span class="status-dot bg-success"></span>Baik ({{ $barang->jumlah_baik }})</small>
                    <small class="d-flex align-items-center gap-1"><span class="status-dot bg-warning"></span>Rusak Ringan ({{ $barang->jumlah_rusak_ringan }})</small>
                    <small class="d-flex align-items-center gap-1"><span class="status-dot bg-danger"></span>Rusak Berat ({{ $barang->jumlah_rusak_berat }})</small>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Total Unit</span>
                    <span class="fw-semibold">{{ $barang->jumlah }} {{ $barang->satuan }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Tersedia (bisa dipinjam)</span>
                    <span class="badge bg-{{ $barang->status_stok_badge }} fs-6">{{ $barang->jumlah_tersedia }} {{ $barang->satuan }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Sedang Dipinjam</span>
                    <span class="fw-semibold text-primary">{{ max(0, $barang->jumlah - $barang->jumlah_tersedia - $barang->jumlah_rusak) }} {{ $barang->satuan }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted-sm">Total Rusak</span>
                    <span class="fw-semibold text-danger">{{ $barang->jumlah_rusak }} {{ $barang->satuan }}</span>
                </div>

                <hr class="my-3">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('barang.lapor-kerusakan', $barang) }}" class="btn btn-sm btn-outline-warning w-100">
                        <i class="bi bi-tools me-1"></i>Lapor Kerusakan
                    </a>
                </div>
            </div>
        </div>

        <!-- Status & Kondisi -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Kondisi</span>
                    <span class="badge bg-{{ $barang->kondisi_badge }}">{{ $barang->kondisi_label }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Nilai</span>
                    <span class="fw-semibold">{{ $barang->nilai ? $barang->nilai_formatted : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted-sm">Lokasi</span>
                    <span>{{ $barang->lokasi ?? '-' }}</span>
                </div>
                @if($barang->merk)
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted-sm">Merk</span>
                    <span>{{ $barang->merk }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Detail & Riwayat -->
    <div class="col-12 col-lg-8">
        @if($barang->keterangan)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-card-text me-2"></i>Keterangan / Spesifikasi</div>
            <div class="card-body">
                <p class="mb-0" style="white-space: pre-line;">{{ $barang->keterangan }}</p>
            </div>
        </div>
        @endif

        <!-- Riwayat Peminjaman -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Peminjaman</span>
                <a href="{{ route('peminjaman.create', ['barang_id'=>$barang->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Pinjamkan
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Peminjam</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang->peminjamans as $p)
                        <tr>
                            <td><a href="{{ route('peminjaman.show', $p) }}" class="fw-semibold text-decoration-none">{{ $p->kode_peminjaman }}</a></td>
                            <td>
                                <div>{{ $p->nama_peminjam }}</div>
                                @if($p->instansi_peminjam)<small class="text-muted">{{ $p->instansi_peminjam }}</small>@endif
                            </td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="{{ $p->is_terlambat ? 'text-danger fw-semibold' : '' }}">
                                {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                                @if($p->is_terlambat)<br><small class="badge bg-danger">Terlambat</small>@endif
                            </td>
                            <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat peminjaman</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
