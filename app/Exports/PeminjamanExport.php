<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PeminjamanExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(private array $filters = []) {}

    public function title(): string
    {
        return 'Riwayat Peminjaman';
    }

    public function query()
    {
        $query = Peminjaman::with(['barang', 'user']);
        if (!empty($this->filters['status']))       $query->where('status', $this->filters['status']);
        if (!empty($this->filters['tanggal_dari'])) $query->where('tanggal_pinjam', '>=', $this->filters['tanggal_dari']);
        if (!empty($this->filters['tanggal_sampai'])) $query->where('tanggal_pinjam', '<=', $this->filters['tanggal_sampai']);
        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Peminjaman', 'Nama Barang', 'Nama Peminjam', 'Kelas/Jabatan',
            'Jml Pinjam', 'Tgl Pinjam', 'Tgl Kembali Rencana', 'Tgl Kembali Aktual',
            'Status', 'Kondisi Kembali', 'Dicatat Oleh', 'Keterangan',
        ];
    }

    public function map($p): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $p->kode_peminjaman,
            $p->barang?->nama_barang ?? '-',
            $p->nama_peminjam,
            $p->instansi_peminjam ?? '-',
            $p->jumlah_pinjam,
            $p->tanggal_pinjam?->format('d/m/Y') ?? '-',
            $p->tanggal_kembali_rencana?->format('d/m/Y') ?? '-',
            $p->tanggal_kembali_aktual?->format('d/m/Y') ?? '-',
            $p->status_label,
            $p->kondisi_kembali ?? '-',
            $p->user?->display_name ?? '-',
            $p->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']]],
        ];
    }
}
