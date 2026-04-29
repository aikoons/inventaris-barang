<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Riwayat Peminjaman</title>
<style>
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 8.5px; color: #1E293B; margin: 0; padding: 20px; }
  .header { text-align: center; border-bottom: 2px solid #4F46E5; padding-bottom: 12px; margin-bottom: 16px; }
  .header h1 { font-size: 13px; color: #4F46E5; margin: 0 0 4px; }
  .header p  { margin: 2px 0; color: #64748B; font-size: 8.5px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #4F46E5; color: white; padding: 5px 6px; font-size: 7.5px; text-align: left; text-transform: uppercase; letter-spacing: 0.03em; }
  td { padding: 4px 6px; border-bottom: 1px solid #E2E8F0; vertical-align: top; }
  tr:nth-child(even) td { background: #F8FAFC; }
  .badge { padding: 2px 5px; border-radius: 3px; font-size: 7.5px; font-weight: 600; }
  .badge-primary   { background: #DBEAFE; color: #1E40AF; }
  .badge-success   { background: #D1FAE5; color: #065F46; }
  .badge-danger    { background: #FEE2E2; color: #991B1B; }
  .badge-warning   { background: #FEF3C7; color: #92400E; }
  .footer { margin-top: 16px; font-size: 7.5px; color: #94A3B8; text-align: right; }
</style>
</head>
<body>
<div class="header">
  <h1>LAPORAN RIWAYAT PEMINJAMAN BARANG</h1>
  <p>Sistem Informasi Inventaris Barang Sekolah</p>
  <p>Dicetak: {{ now()->format('d F Y, H:i') }} WIB &nbsp;·&nbsp; Oleh: {{ auth()->user()->display_name }}</p>
</div>

<table>
  <thead>
    <tr>
      <th width="22">No</th>
      <th width="80">Kode PMJ</th>
      <th>Nama Barang</th>
      <th>Nama Peminjam</th>
      <th width="40" style="text-align:center">Jml</th>
      <th width="60">Tgl Pinjam</th>
      <th width="60">Tgl Kembali</th>
      <th width="65">Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($peminjamans as $i => $p)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $p->kode_peminjaman }}</td>
      <td>{{ $p->barang->nama_barang }}</td>
      <td>
        {{ $p->nama_peminjam }}
        @if($p->instansi_peminjam)<br><small style="color:#94A3B8">{{ $p->instansi_peminjam }}</small>@endif
      </td>
      <td style="text-align:center">{{ $p->jumlah_pinjam }}</td>
      <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
      <td>
        {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
        @if($p->tanggal_kembali_aktual)
        <br><small style="color:#10B981">↩ {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}</small>
        @endif
      </td>
      <td>
        <span class="badge badge-{{ $p->status_badge }}">{{ $p->status_label }}</span>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="footer">
  Total {{ $peminjamans->count() }} transaksi
</div>
</body>
</html>
