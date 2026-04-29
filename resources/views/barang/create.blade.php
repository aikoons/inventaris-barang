@extends('layouts.app')
@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Tambah Barang</h1>
        <p class="page-header-sub">Daftarkan barang baru ke inventaris</p>
    </div>
    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <!-- Informasi Dasar -->
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Barang</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang') }}" placeholder="Masukkan nama barang..." required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="kategori_id">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="merk">Merk / Tipe</label>
                            <input type="text" id="merk" name="merk" class="form-control @error('merk') is-invalid @enderror"
                                   value="{{ old('merk') }}" placeholder="Merk atau tipe barang">
                            @error('merk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="jumlah">Jumlah Total <span class="text-danger">*</span></label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', 1) }}" min="0" required>
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="jumlah_tersedia">Jumlah Tersedia <span class="text-danger">*</span></label>
                            <input type="number" id="jumlah_tersedia" name="jumlah_tersedia" class="form-control @error('jumlah_tersedia') is-invalid @enderror"
                                   value="{{ old('jumlah_tersedia', 1) }}" min="0" required>
                            @error('jumlah_tersedia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="satuan">Satuan <span class="text-danger">*</span></label>
                            <input type="text" id="satuan" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
                                   value="{{ old('satuan', 'unit') }}" placeholder="unit, set, buah..." required>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="lokasi">Lokasi / Ruangan</label>
                            <input type="text" id="lokasi" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                   value="{{ old('lokasi') }}" placeholder="Contoh: Lab Komputer, Gudang AV">
                            @error('lokasi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="kondisi">Kondisi <span class="text-danger">*</span></label>
                            <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                                <option value="baik" {{ old('kondisi','baik')=='baik' ? 'selected':'' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi')=='rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi')=='rusak_berat' ? 'selected':'' }}>Rusak Berat</option>
                            </select>
                            @error('kondisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="nilai">Nilai / Harga Perolehan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="nilai" name="nilai" class="form-control @error('nilai') is-invalid @enderror"
                                       value="{{ old('nilai') }}" placeholder="0" min="0" step="1000">
                            </div>
                            @error('nilai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="keterangan">Keterangan / Spesifikasi</label>
                            <textarea id="keterangan" name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                                      placeholder="Spesifikasi teknis, catatan, atau informasi tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Foto -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-image me-2 text-primary"></i>Foto Barang</div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div id="fotoPreviewWrapper" class="d-none">
                            <img id="fotoPreview" src="" alt="Preview" class="img-fluid rounded" style="max-height:200px; object-fit:cover;">
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2 d-block w-100" onclick="clearFoto()">
                                <i class="bi bi-trash me-1"></i>Hapus Foto
                            </button>
                        </div>
                        <div id="fotoPlaceholder" class="border rounded d-flex flex-column align-items-center justify-content-center" style="height:160px; background:#F8FAFC; cursor:pointer;" onclick="document.getElementById('foto').click()">
                            <i class="bi bi-cloud-upload fs-2 text-muted"></i>
                            <p class="text-muted-sm mt-2 mb-0">Klik untuk pilih foto</p>
                            <p class="text-muted-sm">JPG, PNG, WebP · Maks 2MB</p>
                        </div>
                    </div>
                    <input type="file" id="foto" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*" style="display:none">
                    <button type="button" class="btn btn-outline-primary w-100" onclick="document.getElementById('foto').click()">
                        <i class="bi bi-upload me-1"></i> Pilih Foto
                    </button>
                    @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="card mt-3">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i> Simpan Barang
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        document.getElementById('fotoPreview').src = ev.target.result;
        document.getElementById('fotoPreviewWrapper').classList.remove('d-none');
        document.getElementById('fotoPlaceholder').classList.add('d-none');
    };
    reader.readAsDataURL(file);
});

function clearFoto() {
    document.getElementById('foto').value = '';
    document.getElementById('fotoPreviewWrapper').classList.add('d-none');
    document.getElementById('fotoPlaceholder').classList.remove('d-none');
}

// Auto-sync jumlah_tersedia when jumlah changes (only if tersedia >= jumlah)
document.getElementById('jumlah').addEventListener('input', function() {
    const tersedia = document.getElementById('jumlah_tersedia');
    if (parseInt(tersedia.value) > parseInt(this.value)) {
        tersedia.value = this.value;
    }
});
</script>
@endpush
@endsection
