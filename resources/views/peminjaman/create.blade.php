@extends('layouts.app')
@section('title', 'Catat Peminjaman')
@section('page-title', 'Catat Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Catat Peminjaman</h1>
        <p class="page-header-sub">Daftarkan peminjaman barang baru</p>
    </div>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('peminjaman.store') }}" method="POST">
    @csrf
    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-header"><i class="bi bi-box-seam me-2 text-primary"></i>Data Peminjaman</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="barang_id">Barang yang Dipinjam <span class="text-danger">*</span></label>
                            <select id="barang_id" name="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required onchange="updateBarangInfo(this)">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangs as $b)
                                    <option value="{{ $b->id }}"
                                            data-tersedia="{{ $b->jumlah_tersedia }}"
                                            data-satuan="{{ $b->satuan }}"
                                            data-kondisi="{{ $b->kondisi_label }}"
                                            {{ old('barang_id', $selectedBarang?->id) == $b->id ? 'selected' : '' }}>
                                        {{ $b->nama_barang }} ({{ $b->kode_barang }}) — Tersedia: {{ $b->jumlah_tersedia }} {{ $b->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('barang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <!-- Info stok dinamis -->
                            <div id="stokInfo" class="mt-2 d-none">
                                <span class="badge bg-success" id="stokBadge"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="jumlah_pinjam">Jumlah Pinjam <span class="text-danger">*</span></label>
                            <input type="number" id="jumlah_pinjam" name="jumlah_pinjam" class="form-control @error('jumlah_pinjam') is-invalid @enderror"
                                   value="{{ old('jumlah_pinjam', 1) }}" min="1" required>
                            @error('jumlah_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="tanggal_pinjam">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                   value="{{ old('tanggal_pinjam', now()->toDateString()) }}" required>
                            @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="tanggal_kembali_rencana">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                   value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="keterangan">Keterangan / Keperluan</label>
                            <textarea id="keterangan" name="keterangan" rows="2" class="form-control" placeholder="Tujuan peminjaman, keterangan tambahan...">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Peminjam</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="nama_peminjam">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" id="nama_peminjam" name="nama_peminjam" class="form-control @error('nama_peminjam') is-invalid @enderror"
                               value="{{ old('nama_peminjam') }}" placeholder="Nama lengkap peminjam" required>
                        @error('nama_peminjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="instansi_peminjam">Kelas / Jabatan</label>
                        <input type="text" id="instansi_peminjam" name="instansi_peminjam" class="form-control"
                               value="{{ old('instansi_peminjam') }}" placeholder="Contoh: Kelas XI IPA 1, Guru Fisika">
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i> Catat Peminjaman
                    </button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function updateBarangInfo(select) {
    const opt = select.options[select.selectedIndex];
    const info = document.getElementById('stokInfo');
    const badge = document.getElementById('stokBadge');
    const jmlInput = document.getElementById('jumlah_pinjam');

    if (select.value) {
        const tersedia = parseInt(opt.dataset.tersedia);
        const satuan = opt.dataset.satuan;
        badge.textContent = `Tersedia: ${tersedia} ${satuan}`;
        badge.className = `badge bg-${tersedia <= 0 ? 'danger' : tersedia <= 2 ? 'warning' : 'success'}`;
        info.classList.remove('d-none');
        jmlInput.max = tersedia;
    } else {
        info.classList.add('d-none');
        jmlInput.removeAttribute('max');
    }
}

// Init on page load if value is pre-selected
document.addEventListener('DOMContentLoaded', () => {
    const sel = document.getElementById('barang_id');
    if (sel.value) updateBarangInfo(sel);

    // Set min date for tanggal_kembali
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        document.getElementById('tanggal_kembali_rencana').min = this.value;
    });
});
</script>
@endpush
@endsection
