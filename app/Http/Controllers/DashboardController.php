<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Barang
        $totalBarang        = Barang::count();
        $totalUnit          = Barang::sum('jumlah');
        $totalTersedia      = Barang::sum('jumlah_tersedia');
        $totalDipinjam      = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->sum('jumlah_pinjam');
        $barangRusakRingan  = Barang::where('kondisi', 'rusak_ringan')->count();
        $barangRusakBerat   = Barang::where('kondisi', 'rusak_berat')->count();
        $totalNilai         = Barang::get()->sum(fn($b) => $b->jumlah * $b->nilai);

        // Statistik Peminjaman
        $totalPeminjaman      = Peminjaman::count();
        $peminjamanAktif      = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $peminjamanTerlambat  = Peminjaman::where('status', 'terlambat')
                                          ->orWhere(function ($q) {
                                              $q->where('status', 'dipinjam')
                                                ->where('tanggal_kembali_rencana', '<', now()->toDateString());
                                          })->count();

        // Chart: Peminjaman 6 bulan terakhir
        $chartPeminjaman = collect(range(5, 0))->map(function ($i) {
            $month = now()->subMonths($i);
            return [
                'bulan'  => $month->format('M y'),
                'total'  => Peminjaman::whereYear('tanggal_pinjam', $month->year)
                                      ->whereMonth('tanggal_pinjam', $month->month)
                                      ->count(),
            ];
        });

        // Chart: Distribusi kondisi barang
        $kondisiData = [
            'baik'        => Barang::where('kondisi', 'baik')->count(),
            'rusak_ringan' => Barang::where('kondisi', 'rusak_ringan')->count(),
            'rusak_berat' => Barang::where('kondisi', 'rusak_berat')->count(),
        ];

        // Chart: Barang per kategori (top 6)
        $kategoriData = Kategori::withCount('barangs')
                                ->orderBy('barangs_count', 'desc')
                                ->take(6)
                                ->get()
                                ->map(fn($k) => [
                                    'label' => $k->nama_kategori,
                                    'total' => $k->barangs_count,
                                    'warna' => $k->warna,
                                ]);

        // Peminjaman terbaru
        $peminjamanTerbaru = Peminjaman::with(['barang', 'user'])
                                       ->latest()
                                       ->take(5)
                                       ->get();

        // Barang stok kritis (tersedia <= 2)
        $barangKritis = Barang::where('jumlah_tersedia', '<=', 2)
                               ->where('jumlah', '>', 0)
                               ->with('kategori')
                               ->take(5)
                               ->get();

        return view('dashboard', compact(
            'totalBarang', 'totalUnit', 'totalTersedia', 'totalDipinjam',
            'barangRusakRingan', 'barangRusakBerat', 'totalNilai',
            'totalPeminjaman', 'peminjamanAktif', 'peminjamanTerlambat',
            'chartPeminjaman', 'kondisiData', 'kategoriData',
            'peminjamanTerbaru', 'barangKritis'
        ));
    }
}
