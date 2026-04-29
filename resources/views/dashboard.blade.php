@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- ─── Stat Cards ──────────────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('barang.index') }}" class="stat-card">
            <div class="stat-card-icon indigo"><i class="bi bi-boxes"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Total Jenis</div>
                <div class="stat-card-value">{{ number_format($totalBarang) }}</div>
                <div class="stat-card-sub">{{ number_format($totalUnit) }} unit</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-card-icon emerald"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Unit Tersedia</div>
                <div class="stat-card-value">{{ number_format($totalTersedia) }}</div>
                <div class="stat-card-sub">siap dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('peminjaman.index', ['status'=>'dipinjam']) }}" class="stat-card">
            <div class="stat-card-icon sky"><i class="bi bi-arrow-left-right"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Dipinjam</div>
                <div class="stat-card-value">{{ number_format($peminjamanAktif) }}</div>
                <div class="stat-card-sub">transaksi aktif</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <a href="{{ route('peminjaman.index', ['status'=>'terlambat']) }}" class="stat-card">
            <div class="stat-card-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Terlambat</div>
                <div class="stat-card-value">{{ number_format($peminjamanTerlambat) }}</div>
                <div class="stat-card-sub">perlu tindakan</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-card-icon amber"><i class="bi bi-tools"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Rusak</div>
                <div class="stat-card-value">{{ $barangRusakRingan + $barangRusakBerat }}</div>
                <div class="stat-card-sub">{{ $barangRusakRingan }} ringan · {{ $barangRusakBerat }} berat</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-card-icon purple"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Nilai Aset</div>
                <div class="stat-card-value" style="font-size:1.1rem;">Rp {{ number_format($totalNilai / 1000000, 1) }}Jt</div>
                <div class="stat-card-sub">total inventaris</div>
            </div>
        </div>
    </div>
</div>

<!-- ─── Charts ─────────────────────────────────────────────────────────────── -->
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2 text-primary"></i>Tren Peminjaman (6 Bulan)</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Kondisi Barang
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="chart-container" style="height:200px;">
                    <canvas id="chartKondisi"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ─── Tables ─────────────────────────────────────────────────────────────── -->
<div class="row g-3">
    <div class="col-12 col-xl-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Peminjaman Terbaru</span>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanTerbaru as $p)
                        <tr>
                            <td>
                                <a href="{{ route('peminjaman.show', $p) }}" class="fw-semibold text-decoration-none font-monospace small">
                                    {{ $p->kode_peminjaman }}
                                </a>
                            </td>
                            <td>{{ Str::limit($p->barang->nama_barang, 22) }}</td>
                            <td>{{ $p->nama_peminjam }}</td>
                            <td class="{{ $p->is_terlambat ? 'text-danger fw-semibold' : '' }}">
                                {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                            </td>
                            <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Stok Kritis</span>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-warning">Lihat Barang</a>
            </div>
            @if($barangKritis->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($barangKritis as $b)
                <div class="list-group-item d-flex align-items-center gap-3 py-3">
                    <div class="item-photo-placeholder">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="fw-semibold small text-truncate" style="max-width:160px;">{{ $b->nama_barang }}</div>
                        <div class="text-muted-sm">{{ $b->kategori->nama_kategori }}</div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <span class="badge bg-{{ $b->jumlah_tersedia == 0 ? 'danger' : 'warning' }} fs-6">
                            {{ $b->jumlah_tersedia }}/{{ $b->jumlah }}
                        </span>
                        <div class="text-muted-sm">{{ $b->satuan }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state py-4">
                <i class="bi bi-check-circle text-success d-block fs-1"></i>
                <p class="mb-0 text-success fw-semibold mt-2">Semua stok aman!</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Chart: Tren Peminjaman
const chartPmjData = @json($chartPeminjaman->values());
new Chart(document.getElementById('chartPeminjaman'), {
    type: 'bar',
    data: {
        labels: chartPmjData.map(d => d.bulan),
        datasets: [{
            label: 'Peminjaman',
            data: chartPmjData.map(d => d.total),
            backgroundColor: 'rgba(79,70,229,0.15)',
            borderColor: '#4F46E5',
            borderWidth: 2,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

// Chart: Kondisi Barang
const kondisiData = @json($kondisiData);
new Chart(document.getElementById('chartKondisi'), {
    type: 'doughnut',
    data: {
        labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
        datasets: [{
            data: [kondisiData.baik, kondisiData.rusak_ringan, kondisiData.rusak_berat],
            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true } }
        },
        cutout: '65%',
    }
});
</script>
@endpush
