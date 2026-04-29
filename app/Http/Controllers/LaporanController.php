<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stokBarang(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        $barangs   = $query->orderBy('nama_barang')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $lokasis   = Barang::distinct()->pluck('lokasi')->filter()->sort()->values();

        return view('laporan.stok_barang', compact('barangs', 'kategoris', 'lokasis'));
    }

    public function riwayatPeminjaman(Request $request)
    {
        $query = Peminjaman::with(['barang.kategori', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjamans = $query->latest()->get();

        return view('laporan.riwayat_peminjaman', compact('peminjamans'));
    }

    public function exportPdfStok(Request $request)
    {
        $query = Barang::with('kategori');
        if ($request->filled('kategori')) $query->where('kategori_id', $request->kategori);
        if ($request->filled('kondisi'))  $query->where('kondisi', $request->kondisi);
        $barangs   = $query->orderBy('nama_barang')->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.stok_barang', compact('barangs', 'kategoris'))
                                           ->setPaper('a4', 'landscape');
        return $pdf->download('laporan-stok-barang-' . now()->format('Ymd') . '.pdf');
    }

    public function exportPdfPeminjaman(Request $request)
    {
        $query = Peminjaman::with(['barang', 'user']);
        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('tanggal_dari')) $query->where('tanggal_pinjam', '>=', $request->tanggal_dari);
        $peminjamans = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf.riwayat_peminjaman', compact('peminjamans'))
                                           ->setPaper('a4', 'landscape');
        return $pdf->download('laporan-peminjaman-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcelStok(Request $request)
    {
        $filters = $request->only(['kategori', 'kondisi', 'lokasi']);
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BarangExport($filters),
            'laporan-stok-barang-' . now()->format('Ymd') . '.xlsx'
        );
    }

    public function exportExcelPeminjaman(Request $request)
    {
        $filters = $request->only(['status', 'tanggal_dari', 'tanggal_sampai']);
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\PeminjamanExport($filters),
            'laporan-peminjaman-' . now()->format('Ymd') . '.xlsx'
        );
    }
}
