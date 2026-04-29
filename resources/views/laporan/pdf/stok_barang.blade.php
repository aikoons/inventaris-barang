<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Stok Barang</title>
<style>
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #1E293B; margin: 0; padding: 20px; }
  .header { text-align: center; border-bottom: 2px solid #4F46E5; padding-bottom: 12px; margin-bottom: 16px; }
  .header h1 { font-size: 14px; color: #4F46E5; margin: 0 0 4px; }
  .header p { margin: 2px 0; color: #64748B; font-size: 9px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #4F46E5; color: white; padding: 6px 8px; text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.03em; }
  td { padding: 5px 8px; border-bottom: 1px solid #E2E8F0; vertical-align: top; }
  tr:nth-child(even) td { background: #F8FAFC; }
  .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: 600; }
  .badge-success { background: #D1FAE5; color: #065F46; }
  .badge-warning { background: #FEF3C7; color: #92400E; }
  .badge-danger  { background: #FEE2E2; color: #991B1B; }
  tfoot td { font-weight: bold; background: #EEF2FF; border-top: 2px solid #4F46E5; }
  .footer { margin-top: 20px; font-size: 8px; color: #94A3B8; text-align: right; }
</style>
</head>
<body>
<div class="header">
  <h1>LAPORAN STOK BARANG INVENTARIS</h1>
  <p>Sistem Informasi Inventaris Barang Sekolah</p>
  <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB &nbsp;·&nbsp; Oleh: {{ auth()->user()->display_name }}</p>
</div>

<table>
  <thead>
    <tr>
      <th width="25">No</th>
      <th width="90">Kode Barang</th>
      <th>Nama Barang</th>
      <th width="80">Kategori</th>
      <th width="80">Lokasi</th>
      <th width="35" style="text-align:center">Jml</th>
      <th width="45" style="text-align:center">Tersedia</th>
      <th width="60">Kondisi</th>
      <th width="80" style="text-align:right">Nilai (Rp)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($barangs as $i => $b)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $b->kode_barang }}</td>
      <td>{{ $b->nama_barang }}</td>
      <td>{{ $b->kategori->nama_kategori }}</td>
      <td>{{ $b->lokasi ?? '-' }}</td>
      <td style="text-align:center">{{ $b->jumlah }}</td>
      <td style="text-align:center">{{ $b->jumlah_tersedia }}</td>
      <td>
        <span class="badge badge-{{ $b->kondisi_badge }}">{{ $b->kondisi_label }}</span>
      </td>
      <td style="text-align:right">{{ $b->nilai ? number_format($b->nilai,0,',','.') : '-' }}</td>
    </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" style="text-align:right">Total:</td>
      <td style="text-align:center">{{ $barangs->sum('jumlah') }}</td>
      <td style="text-align:center">{{ $barangs->sum('jumlah_tersedia') }}</td>
      <td></td>
      <td style="text-align:right">{{ number_format($barangs->sum(fn($b)=>$b->jumlah*$b->nilai),0,',','.') }}</td>
    </tr>
  </tfoot>
</table>

<div class="footer">
  Total {{ $barangs->count() }} jenis barang &nbsp;|&nbsp; Halaman <span style="color:#4F46E5">1</span>
</div>
</body>
</html>
