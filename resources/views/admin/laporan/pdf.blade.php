<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Alat Laboratorium</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #222;
            line-height: 1.4;
            margin: 15px;
        }

        /* Kop Surat Resmi */
        .kop-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-title {
            text-align: center;
        }
        .kop-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-title h4 {
            margin: 2px 0 3px 0;
            font-size: 10.5pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
        }
        .kop-title p {
            margin: 0;
            font-size: 7.5pt;
            color: #555;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            margin: 10px 0 12px 0;
        }
        .doc-title h3 {
            margin: 0;
            font-size: 10.5pt;
            text-transform: uppercase;
            text-decoration: underline;
            color: #111;
        }
        .doc-title p {
            margin: 2px 0 0 0;
            font-size: 7.5pt;
            color: #666;
        }

        /* Main Data Table */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: bold;
            font-size: 7.5pt;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            text-align: center;
        }
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 5px;
            font-size: 7.5pt;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            margin-top: 25px;
            border: none;
        }
        .ttd-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            font-size: 8pt;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="kop-table">
        <tr>
            <td style="width: 70px; text-align: center; vertical-align: middle; padding-bottom: 5px;">
                @php
                    $logoFile = public_path($pengaturan->logo_path ?? 'images/logo-stikes.png');
                @endphp
                @if(file_exists($logoFile))
                    <img src="{{ $logoFile }}" height="65" style="object-fit: contain;">
                @endif
            </td>
            <td class="kop-title" style="text-align: center; vertical-align: middle;">
                <h2>{{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</h2>
                <h4>{{ $pengaturan->unit_laboratorium ?? 'Unit Laboratorium Keperawatan' }} - {{ $pengaturan->nama_prodi ?? 'Prodi S1 Keperawatan' }}</h4>
                <p>{{ $pengaturan->alamat_institusi ?? 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang' }} &bull; Telp: {{ $pengaturan->kontak_lab ?? '(0341) 369003' }}</p>
            </td>
        </tr>
    </table>

    <!-- Judul Laporan -->
    <div class="doc-title">
        <h3>Laporan Rekapitulasi Transaksi Peminjaman Alat Praktikum</h3>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }} WIB &bull; Total Transaksi: {{ $peminjaman->count() }} Data</p>
    </div>

    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th style="width: 75px;">Kode TRX</th>
                <th style="width: 110px;">Peminjam</th>
                <th style="width: 80px;">Program Studi</th>
                <th>Rincian Paket Alat yang Dipinjam</th>
                <th style="width: 30px;">Total Unit</th>
                <th style="width: 60px;">Tgl Pinjam</th>
                <th style="width: 60px;">Rencana Kembali</th>
                <th style="width: 55px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $item->kode_transaksi ?? ('TRX-' . $item->id) }}</td>
                <td>
                    <strong>{{ $item->nama_peminjam }}</strong><br>
                    <span style="color: #666;">NIM: {{ $item->nim_nip }}</span>
                </td>
                <td>{{ $item->prodi }}</td>
                <td>
                    @foreach($item->details as $d)
                        <div style="margin-bottom: 2px;">
                            &bull; {{ $d->alat->nama_alat ?? 'Alat Dihapus' }} 
                            <strong>({{ $d->jumlah_pinjam }} Unit)</strong>
                        </div>
                    @endforeach
                </td>
                <td class="text-center fw-bold">{{ $item->details->sum('jumlah_pinjam') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d/m/Y') }}</td>
                <td class="text-center fw-bold">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 15px; color: #777;">
                    Tidak ada data riwayat peminjaman pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Lembar Pengesahan / Tanda Tangan -->
    <table class="ttd-table">
        <tr>
            <td style="width: 60%;"></td>
            <td style="width: 40%; text-align: center;">
                <p style="margin-bottom: 50px;">
                    Malang, {{ date('d F Y') }}<br>
                    <strong>Kepala Unit Laboratorium,</strong>
                </p>
                <p style="margin: 0; text-decoration: underline; font-weight: bold;">
                    ( {{ $pengaturan->kepala_lab ?? 'Ns. Wening Prabawati, M.Kep.' }} )
                </p>
                <p style="margin: 2px 0 0 0; font-size: 7.5pt; color: #555;">NIP/NIK. {{ $pengaturan->nip_kepala_lab ?? '-' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>