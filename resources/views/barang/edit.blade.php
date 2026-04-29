@extends('layouts.app')
@section('title', 'Edit: '.$barang->nama_barang)
@section('page-title', 'Edit Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Edit Barang</h1>
        <p class="page-header-sub">{{ $barang->kode_barang }} · {{ $barang->nama_barang }}</p>
    </div>
    <a href="{{ route('barang.show', $barang) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="{{ route('barang.update', $barang) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Informasi Barang</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                            @error('nama_barang')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Barang</label>
                            <input type="text" class="form-control" value="{{ $barang->kode_barang }}" disabled>
                            <small class="text-muted">Kode barang tidak dapat diubah</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="kategori_id">Kategori <span class="text-danger">*</span></label>
                            <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="merk">Merk / Tipe</label>
                            <input type="text" id="merk" name="merk" class="form-control" value="{{ old('merk', $barang->merk) }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="jumlah">Jumlah Total <span class="text-danger">*</span></label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', $barang->jumlah) }}" min="0" required>
                            @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="jumlah_tersedia">Tersedia <span class="text-danger">*</span></label>
                            <input type="number" id="jumlah_tersedia" name="jumlah_tersedia" class="form-control @error('jumlah_tersedia') is-invalid @enderror"
                                   value="{{ old('jumlah_tersedia', $barang->jumlah_tersedia) }}" min="0" required>
                            @error('jumlah_tersedia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label" for="satuan">Satuan</label>
                            <input type="text" id="satuan" name="satuan" class="form-control" value="{{ old('satuan', $barang->satuan) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="lokasi">Lokasi</label>
                            <input type="text" id="lokasi" name="lokasi" class="form-control" value="{{ old('lokasi', $barang->lokasi) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="kondisi">Kondisi <span class="text-danger">*</span></label>
                            <select id="kondisi" name="kondisi" class="form-select" required>
                                <option value="baik" {{ old('kondisi', $barang->kondisi)=='baik' ? 'selected':'' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi', $barang->kondisi)=='rusak_ringan' ? 'selected':'' }}>Rusak Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi', $barang->kondisi)=='rusak_berat' ? 'selected':'' }}>Rusak Berat</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="nilai">Nilai / Harga (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="nilai" name="nilai" class="form-control" value="{{ old('nilai', $barang->nilai) }}" min="0">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="keterangan">Keterangan</label>
                            <textarea id="keterangan" name="keterangan" rows="3" class="form-control">{{ old('keterangan', $barang->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto -->
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-header"><i class="bi bi-image me-2 text-primary"></i>Foto Barang</div>
                <div class="card-body text-center">
                    @if($barang->foto)
                    <img src="{{ asset('storage/'.$barang->foto) }}" alt="{{ $barang->nama_barang }}" class="img-fluid rounded mb-3" style="max-height:180px; object-fit:cover;">
                    <form action="{{ route('barang.hapus-foto', $barang) }}" method="POST" class="mb-3" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100"><i class="bi bi-trash me-1"></i>Hapus Foto Lama</button>
                    </form>
                    @else
                    <div class="border rounded d-flex flex-column align-items-center justify-content-center mb-3" style="height:140px; background:#F8FAFC;">
                        <i class="bi bi-image fs-2 text-muted"></i>
                        <small class="text-muted">Belum ada foto</small>
                    </div>
                    @endif

                    <label class="form-label d-block text-start">Ganti / Upload Foto Baru</label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">JPG, PNG, WebP · Maks 2MB</small>
                    @error('foto')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('barang.show', $barang) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
