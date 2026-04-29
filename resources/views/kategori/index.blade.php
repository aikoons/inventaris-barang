@extends('layouts.app')
@section('title', 'Kategori Barang')
@section('page-title', 'Kategori Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Kategori Barang</h1>
        <p class="page-header-sub">Kelola kategori untuk pengelompokan barang</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </button>
</div>

<div class="row g-3">
    @forelse($kategoris as $k)
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card hover-shadow h-100">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <div class="stat-card-icon" style="background-color:{{ $k->warna }}22; color:{{ $k->warna }}; width:46px; height:46px; font-size:1.2rem;">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <div class="flex-1">
                        <h6 class="fw-semibold mb-0">{{ $k->nama_kategori }}</h6>
                        @if($k->kode_kategori)<code class="text-muted small">{{ $k->kode_kategori }}</code>@endif
                        @if($k->deskripsi)<p class="text-muted-sm mt-1 mb-2">{{ $k->deskripsi }}</p>@endif
                        <span class="badge bg-primary">{{ $k->barangs_count }} jenis barang</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary flex-fill"
                        onclick="editKategori({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ $k->kode_kategori }}', '{{ addslashes($k->deskripsi ?? '') }}', '{{ $k->warna }}')">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
                <form action="{{ route('kategori.destroy', $k) }}" method="POST"
                      onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama_kategori) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ $k->barangs_count > 0 ? 'disabled title=Masih ada barang' : '' }}>
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="bi bi-tags d-block"></i>
            <h6>Belum ada kategori</h6>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Kategori Pertama</button>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Elektronik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori</label>
                        <input type="text" name="kode_kategori" class="form-control" maxlength="10" placeholder="ELK, FRN, ...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="2" placeholder="Deskripsi singkat kategori..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" name="warna" class="form-control form-control-color" value="#4F46E5" style="width:60px;">
                            <small class="text-muted">Pilih warna untuk tampilan badge kategori</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold">Edit Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori</label>
                        <input type="text" name="kode_kategori" id="editKode" class="form-control" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna Badge</label>
                        <input type="color" name="warna" id="editWarna" class="form-control form-control-color" style="width:60px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editKategori(id, nama, kode, deskripsi, warna) {
    document.getElementById('formEdit').action = `/kategori/${id}`;
    document.getElementById('editNama').value = nama;
    document.getElementById('editKode').value = kode || '';
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editWarna').value = warna;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>
@endpush
@endsection
