<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(private array $filters = []) {}

    public function title(): string
    {
        return 'Stok Barang';
    }

    public function query()
    {
        $query = Barang::with('kategori');
        if (!empty($this->filters['kategori'])) $query->where('kategori_id', $this->filters['kategori']);
        if (!empty($this->filters['kondisi']))  $query->where('kondisi', $this->filters['kondisi']);
        if (!empty($this->filters['lokasi']))   $query->where('lokasi', 'like', '%' . $this->filters['lokasi'] . '%');
        return $query->orderBy('nama_barang');
    }

    public function headings(): array
    {
        return [
            'No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Merk',
            'Jumlah', 'Tersedia', 'Satuan', 'Lokasi', 'Kondisi',
            'Nilai (Rp)', 'Keterangan',
        ];
    }

    public function map($barang): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $barang->kode_barang,
            $barang->nama_barang,
            $barang->kategori?->nama_kategori ?? '-',
            $barang->merk ?? '-',
            $barang->jumlah,
            $barang->jumlah_tersedia,
            $barang->satuan,
            $barang->lokasi ?? '-',
            $barang->kondisi_label,
            $barang->nilai ? number_format($barang->nilai, 0, ',', '.') : '-',
            $barang->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']], 'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']]],
        ];
    }
}
