<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Ruangan Laboratorium</title>
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
            border-bottom: 2.5px double #000;
            padding-bottom: 5px;
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
            font-size: 11pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .kop-title h4 {
            margin: 2px 0 3px 0;
            font-size: 9.5pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.2;
        }
        .kop-title p {
            margin: 1px 0;
            font-size: 7.2pt;
            color: #222;
            line-height: 1.25;
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
            padding: 6px 5px;
            text-align: center;
        }
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            font-size: 7.5pt;
            vertical-align: middle;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* Badge Status */
        .badge-status {
            display: inline-block;
            padding: 2px 6px;
            font-size: 6.8pt;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .status-disetujui { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; }
        .status-selesai { background-color: #e0f2fe; color: #0369a1; border: 1px solid #7dd3fc; }
        .status-menunggu { background-color: #fef9c3; color: #a16207; border: 1px solid #fde047; }
        .status-ditolak { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

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

    <!-- Kop Surat Resmi -->
    <table class="kop-table">
        <tr>
            <td style="width: 75px; text-align: center; vertical-align: middle; padding-bottom: 5px;">
                @php
                    $logoFile = public_path($pengaturan->logo_path ?? 'images/logo-stikes.png');
                @endphp
                @if(file_exists($logoFile))
                    <img src="{{ $logoFile }}" height="65" style="object-fit: contain;">
                @endif
            </td>
            <td class="kop-title" style="text-align: center; vertical-align: middle;">
                <h2>SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG</h2>
                <h4>PROGRAM STUDI {{ strtoupper(trim(preg_replace('/^(program\s+studi|prodi)\s+/i', '', $pengaturan->nama_prodi ?? 'S1 Keperawatan & Profesi Ners')) ?: 'S1 KEPERAWATAN & PROFESI NERS') }}</h4>
                <p>Jl. Yulius Usman No. 62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117</p>
                <p>Telp./Fax. (0341) 369003 / 369004 | Email: info@stikespantiwaluya.ac.id | Web: www.stikespantiwaluya.ac.id</p>
            </td>
            <td style="width: 75px;"></td>
        </tr>
    </table>

    <!-- Judul Laporan -->
    <div class="doc-title">
        <h3>
            Laporan Rekapitulasi Peminjaman Ruangan Laboratorium
            @if(isset($tahunDipilih) && $tahunDipilih !== 'semua')
                — Tahun {{ $tahunDipilih }}
            @else
                — Rekapitulasi Seluruh Arsip Transaksi
            @endif
        </h3>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }} WIB &bull; Total Transaksi: {{ $peminjamanRuangans->count() }} Data</p>
    </div>

    <!-- Tabel Data Peminjaman Ruangan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 80px;">Kode TRX</th>
                <th style="width: 140px;">Ruang Laboratorium</th>
                <th style="width: 130px;">Peminjam</th>
                <th style="width: 105px;">Jadwal & Waktu</th>
                <th>Keperluan / Agenda Praktikum</th>
                <th style="width: 65px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanRuangans as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold text-center">{{ $item->kode_transaksi }}</td>
                <td>
                    <strong>{{ $item->ruangan->nama_ruangan ?? '-' }}</strong><br>
                    <span style="color: #666; font-size: 7pt;">Kode: {{ $item->ruangan->kode_ruangan ?? '-' }} &bull; {{ $item->ruangan->lokasi ?? '-' }}</span>
                </td>
                <td>
                    <strong>{{ $item->nama_peminjam }}</strong><br>
                    <span style="color: #555; font-size: 7pt;">{{ $item->nim_nip }} ({{ $item->prodi }})</span>
                </td>
                <td>
                    <span class="fw-bold">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</span><br>
                    <span style="color: #666; font-size: 7pt;">{{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }} WIB</span>
                </td>
                <td>
                    {{ $item->keperluan }}
                    @if($item->catatan)
                        <div style="font-size: 6.8pt; color: #777; font-style: italic; margin-top: 2px;">
                            Catatan: {{ $item->catatan }}
                        </div>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->status === 'Disetujui')
                        <span class="badge-status status-disetujui">Disetujui</span>
                    @elseif($item->status === 'Selesai')
                        <span class="badge-status status-selesai">Selesai</span>
                    @elseif($item->status === 'Ditolak')
                        <span class="badge-status status-ditolak">Ditolak</span>
                    @else
                        <span class="badge-status status-menunggu">Menunggu</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 18px; color: #777;">
                    Tidak ada data riwayat peminjaman ruangan pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Lembar Pengesahan / Tanda Tangan Resmi -->
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
