@extends('layouts.app')
@section('title', 'Lapor Kerusakan — '.$barang->nama_barang)
@section('page-title', 'Lapor Kerusakan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Lapor Kerusakan</h1>
        <p class="page-header-sub">{{ $barang->kode_barang }} — {{ $barang->nama_barang }}</p>
    </div>
    <a href="{{ route('barang.show', $barang) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    {{-- Form --}}
    <div class="col-12 col-lg-7">
        <form action="{{ route('barang.proses-lapor-kerusakan', $barang) }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-tools me-2 text-warning"></i>Form Laporan Kerusakan / Perbaikan
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        {{-- Jenis --}}
                        <div class="col-12">
                            <label class="form-label">Jenis Laporan <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="jenis_kerusakan" id="jenis_ringan"
                                           value="rusak_ringan" {{ old('jenis_kerusakan')=='rusak_ringan'?'checked':'' }} required>
                                    <label class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3" for="jenis_ringan">
                                        <i class="bi bi-exclamation-triangle fs-4 mb-1"></i>
                                        <span class="fw-semibold">Rusak Ringan</span>
                                        <small class="text-muted mt-1">Masih bisa diperbaiki</small>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="jenis_kerusakan" id="jenis_berat"
                                           value="rusak_berat" {{ old('jenis_kerusakan')=='rusak_berat'?'checked':'' }}>
                                    <label class="btn btn-outline-danger w-100 d-flex flex-column align-items-center py-3" for="jenis_berat">
                                        <i class="bi bi-x-octagon fs-4 mb-1"></i>
                                        <span class="fw-semibold">Rusak Berat</span>
                                        <small class="text-muted mt-1">Tidak bisa dipakai</small>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="jenis_kerusakan" id="jenis_perbaikan"
                                           value="perbaikan" {{ old('jenis_kerusakan')=='perbaikan'?'checked':'' }}>
                                    <label class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3" for="jenis_perbaikan">
                                        <i class="bi bi-wrench fs-4 mb-1"></i>
                                        <span class="fw-semibold">Selesai Diperbaiki</span>
                                        <small class="text-muted mt-1">Kembalikan ke stok</small>
                                    </label>
                                </div>
                            </div>
                            @error('jenis_kerusakan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jumlah --}}
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Unit <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="jumlah_laporan"
                                       class="form-control @error('jumlah_laporan') is-invalid @enderror"
                                       value="{{ old('jumlah_laporan', 1) }}"
                                       min="1" max="{{ $barang->jumlah }}" required>
                                <span class="input-group-text">{{ $barang->satuan }}</span>
                                @error('jumlah_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">
                                Maks. tersedia saat ini: <strong>{{ $barang->jumlah_tersedia }}</strong> {{ $barang->satuan }}
                                &nbsp;|&nbsp; Rusak ringan: <strong>{{ $barang->jumlah_rusak_ringan }}</strong> {{ $barang->satuan }}
                            </small>
                        </div>

                        {{-- Keterangan --}}
                        <div class="col-12">
                            <label class="form-label">Keterangan Kerusakan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                      placeholder="Contoh: APAR bocor karena katup kendur, ditemukan saat inspeksi bulanan...">{{ old('keterangan') }}</textarea>
                        </div>

                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <a href="{{ route('barang.show', $barang) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="bi bi-send me-1"></i>Kirim Laporan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Info Kondisi Saat Ini --}}
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Kondisi Saat Ini</div>
            <div class="card-body">

                {{-- Progress bar komposisi --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="fw-semibold">Komposisi {{ $barang->jumlah }} {{ $barang->satuan }}</small>
                        <small class="text-muted">total</small>
                    </div>
                    @php
                        $pctBaik    = $barang->jumlah > 0 ? ($barang->jumlah_baik / $barang->jumlah) * 100 : 0;
                        $pctRingan  = $barang->jumlah > 0 ? ($barang->jumlah_rusak_ringan / $barang->jumlah) * 100 : 0;
                        $pctBerat   = $barang->jumlah > 0 ? ($barang->jumlah_rusak_berat / $barang->jumlah) * 100 : 0;
                    @endphp
                    <div class="progress" style="height:20px;border-radius:8px;">
                        <div class="progress-bar bg-success" style="width:{{ $pctBaik }}%" title="Baik">
                            {{ $barang->jumlah_baik > 0 ? $barang->jumlah_baik : '' }}
                        </div>
                        <div class="progress-bar bg-warning" style="width:{{ $pctRingan }}%" title="Rusak Ringan">
                            {{ $barang->jumlah_rusak_ringan > 0 ? $barang->jumlah_rusak_ringan : '' }}
                        </div>
                        <div class="progress-bar bg-danger" style="width:{{ $pctBerat }}%" title="Rusak Berat">
                            {{ $barang->jumlah_rusak_berat > 0 ? $barang->jumlah_rusak_berat : '' }}
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-2">
                        <span class="d-flex align-items-center gap-1 small"><span class="status-dot bg-success"></span>Baik ({{ $barang->jumlah_baik }})</span>
                        <span class="d-flex align-items-center gap-1 small"><span class="status-dot bg-warning"></span>Rusak Ringan ({{ $barang->jumlah_rusak_ringan }})</span>
                        <span class="d-flex align-items-center gap-1 small"><span class="status-dot bg-danger"></span>Rusak Berat ({{ $barang->jumlah_rusak_berat }})</span>
                    </div>
                </div>

                <hr>

                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Total Unit</td>
                        <td class="fw-semibold text-end">{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tersedia (bisa dipinjam)</td>
                        <td class="fw-semibold text-end text-success">{{ $barang->jumlah_tersedia }} {{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rusak Ringan</td>
                        <td class="fw-semibold text-end text-warning">{{ $barang->jumlah_rusak_ringan }} {{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rusak Berat</td>
                        <td class="fw-semibold text-end text-danger">{{ $barang->jumlah_rusak_berat }} {{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kondisi Keseluruhan</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $barang->kondisi_badge }}">{{ $barang->kondisi_label }}</span>
                        </td>
                    </tr>
                </table>

                @if($barang->keterangan)
                <hr>
                <p class="text-muted small mb-1 fw-semibold">Riwayat Keterangan:</p>
                <div class="small" style="white-space:pre-line;max-height:150px;overflow-y:auto;">{{ $barang->keterangan }}</div>
                @endif
            </div>
        </div>

        {{-- Panduan --}}
        <div class="card mt-3">
            <div class="card-body p-3">
                <p class="fw-semibold mb-2 small"><i class="bi bi-lightbulb-fill text-warning me-1"></i>Panduan Penggunaan</p>
                <ul class="small text-muted mb-0 ps-3">
                    <li><strong>Rusak Ringan</strong> — Unit masih ada tapi tidak bisa dipinjam (misal: bocor, retak, perlu servis)</li>
                    <li><strong>Rusak Berat</strong> — Unit tidak bisa digunakan/diperbaiki lagi (afkir)</li>
                    <li><strong>Selesai Diperbaiki</strong> — Unit rusak ringan yang sudah diperbaiki, akan dikembalikan ke stok tersedia</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
