@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('page-title', 'Proses Pengembalian')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Proses Pengembalian</h1>
        <p class="page-header-sub">Konfirmasi pengembalian barang yang dipinjam</p>
    </div>
    <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-12 col-lg-7">
        <!-- Ringkasan Peminjaman -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Ringkasan Peminjaman</div>
            <div class="card-body">
                <div class="d-flex gap-3 align-items-start">
                    <div class="stat-card-icon sky"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <h6 class="fw-semibold mb-1">{{ $peminjaman->barang->nama_barang }}</h6>
                        <div class="text-muted-sm">{{ $peminjaman->barang->kode_barang }}</div>
                        <div class="mt-2 d-flex gap-3">
                            <div>
                                <span class="text-muted-sm">Peminjam:</span>
                                <strong>{{ $peminjaman->nama_peminjam }}</strong>
                                @if($peminjaman->instansi_peminjam) — {{ $peminjaman->instansi_peminjam }}@endif
                            </div>
                        </div>
                        <div class="mt-1 d-flex gap-4">
                            <div><span class="text-muted-sm">Jumlah:</span> <strong>{{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->barang->satuan }}</strong></div>
                            <div><span class="text-muted-sm">Tgl Pinjam:</span> <strong>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</strong></div>
                            <div>
                                <span class="text-muted-sm">Rencana Kembali:</span>
                                <strong class="{{ $peminjaman->is_terlambat ? 'text-danger' : '' }}">
                                    {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                                </strong>
                                @if($peminjaman->is_terlambat)
                                <span class="badge bg-danger">Terlambat {{ abs($peminjaman->sisa_hari) }} hari</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pengembalian -->
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-return-left me-2 text-success"></i>Form Pengembalian</div>
            <form action="{{ route('peminjaman.proses-pengembalian', $peminjaman) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kondisi Barang Saat Dikembalikan <span class="text-danger">*</span></label>
                        <div class="row g-2 mt-1">
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="kondisi_kembali" id="kondisi_baik" value="baik" required
                                       {{ old('kondisi_kembali', 'baik') == 'baik' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success w-100 py-3" for="kondisi_baik">
                                    <i class="bi bi-check-circle d-block fs-4 mb-1"></i>
                                    <strong>Baik</strong>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="kondisi_kembali" id="kondisi_ringan" value="rusak_ringan"
                                       {{ old('kondisi_kembali') == 'rusak_ringan' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning w-100 py-3" for="kondisi_ringan">
                                    <i class="bi bi-exclamation-triangle d-block fs-4 mb-1"></i>
                                    <strong>Rusak Ringan</strong>
                                </label>
                            </div>
                            <div class="col-4">
                                <input type="radio" class="btn-check" name="kondisi_kembali" id="kondisi_berat" value="rusak_berat"
                                       {{ old('kondisi_kembali') == 'rusak_berat' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger w-100 py-3" for="kondisi_berat">
                                    <i class="bi bi-x-circle d-block fs-4 mb-1"></i>
                                    <strong>Rusak Berat</strong>
                                </label>
                            </div>
                        </div>
                        @error('kondisi_kembali')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="keterangan_kembali">Catatan Pengembalian</label>
                        <textarea id="keterangan_kembali" name="keterangan_kembali" rows="3" class="form-control"
                                  placeholder="Catatan kondisi, kerusakan yang ditemukan, dll...">{{ old('keterangan_kembali') }}</textarea>
                    </div>

                    <div class="alert alert-info d-flex gap-2">
                        <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                        <div>
                            <strong>Tanggal pengembalian akan dicatat hari ini:</strong>
                            {{ now()->format('d F Y') }}.
                            Stok barang akan <strong>bertambah {{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->barang->satuan }}</strong> setelah dikonfirmasi.
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-check-lg me-1"></i> Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
