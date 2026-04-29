<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeminjamanRequest;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['barang.kategori', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_peminjam', 'like', "%{$keyword}%")
                  ->orWhere('kode_peminjaman', 'like', "%{$keyword}%")
                  ->orWhereHas('barang', fn($b) => $b->where('nama_barang', 'like', "%{$keyword}%"));
            });
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjamans    = $query->latest()->paginate(15)->withQueryString();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')
                                    ->orWhere(fn($q) => $q->where('status','dipinjam')->where('tanggal_kembali_rencana','<',now()))
                                    ->count();

        return view('peminjaman.index', compact('peminjamans', 'totalTerlambat'));
    }

    public function create(Request $request)
    {
        $barangs = Barang::where('jumlah_tersedia', '>', 0)
                         ->with('kategori')
                         ->orderBy('nama_barang')
                         ->get();
        $selectedBarang = $request->filled('barang_id') ? Barang::find($request->barang_id) : null;
        return view('peminjaman.create', compact('barangs', 'selectedBarang'));
    }

    public function store(PeminjamanRequest $request)
    {
        $validated = $request->validated();
        $barang    = Barang::findOrFail($validated['barang_id']);

        // Validasi stok mencukupi
        if ($barang->jumlah_tersedia < $validated['jumlah_pinjam']) {
            return back()->withInput()
                         ->with('error', "Stok tersedia hanya {$barang->jumlah_tersedia} {$barang->satuan}.");
        }

        DB::transaction(function () use ($validated, $barang) {
            $validated['kode_peminjaman'] = $this->generateKodePeminjaman();
            $validated['user_id']         = auth()->id();
            $validated['status']          = 'dipinjam';

            Peminjaman::create($validated);

            // Kurangi stok
            $barang->decrement('jumlah_tersedia', $validated['jumlah_pinjam']);
        });

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['barang.kategori', 'user']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function formPengembalian(Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.show', $peminjaman)
                             ->with('info', 'Barang ini sudah dikembalikan.');
        }
        $peminjaman->load(['barang', 'user']);
        return view('peminjaman.pengembalian', compact('peminjaman'));
    }

    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'kondisi_kembali'    => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'keterangan_kembali' => ['nullable', 'string', 'max:500'],
        ]);

        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('peminjaman.index')->with('info', 'Sudah dikembalikan.');
        }

        DB::transaction(function () use ($validated, $peminjaman) {
            $peminjaman->update([
                'status'                 => 'dikembalikan',
                'tanggal_kembali_aktual' => now()->toDateString(),
                'kondisi_kembali'        => $validated['kondisi_kembali'],
                'keterangan_kembali'     => $validated['keterangan_kembali'],
            ]);

            $barang  = $peminjaman->barang;
            $jumlah  = $peminjaman->jumlah_pinjam;
            $kondisi = $validated['kondisi_kembali'];

            if ($kondisi === 'baik') {
                // Semua unit kembali normal → tambah tersedia
                $barang->increment('jumlah_tersedia', $jumlah);

            } else {
                // Unit kembali rusak:
                // - jumlah_tersedia TIDAK bertambah (unit rusak tidak bisa dipinjam lagi)
                // - catat ke jumlah_rusak_ringan / jumlah_rusak_berat
                if ($kondisi === 'rusak_ringan') {
                    $barang->increment('jumlah_rusak_ringan', $jumlah);
                } else {
                    $barang->increment('jumlah_rusak_berat', $jumlah);
                }

                // Otomatis update kondisi keseluruhan barang
                $barang->refresh();
                $totalRusak = $barang->jumlah_rusak_ringan + $barang->jumlah_rusak_berat;
                if ($totalRusak === 0) {
                    $newKondisi = 'baik';
                } elseif ($barang->jumlah_rusak_berat > 0) {
                    $newKondisi = 'rusak_berat';
                } else {
                    $newKondisi = 'rusak_ringan';
                }
                $barang->update(['kondisi' => $newKondisi]);
            }
        });


        return redirect()->route('peminjaman.show', $peminjaman)
                         ->with('success', 'Pengembalian barang berhasil dicatat.');
    }

    private function generateKodePeminjaman(): string
    {
        $tahun = now()->year;
        $last  = Peminjaman::where('kode_peminjaman', 'like', "PMJ-{$tahun}-%")
                            ->orderBy('kode_peminjaman', 'desc')
                            ->first();
        $urutan = $last ? (int) substr($last->kode_peminjaman, -4) + 1 : 1;
        return "PMJ-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
