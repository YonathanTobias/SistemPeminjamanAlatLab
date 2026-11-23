<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Inventaris Alat Lab (KIR) - {{ $pengaturan->nama_institusi ?? 'STIKES Panti Waluya Malang' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Resmi STIKES Panti Waluya */
        .kop-table {
            width: 100%;
            border-bottom: 2.5px double #000000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .kop-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .kop-logo {
            width: 70px;
            text-align: center;
        }
        .kop-text {
            text-align: center;
            padding-left: 5px;
            padding-right: 5px;
        }
        .kop-text h4 {
            margin: 0;
            font-size: 11.5pt;
            letter-spacing: 0.03em;
            line-height: 1.15;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text h3 {
            margin: 1px 0 2px 0;
            font-size: 13.5pt;
            letter-spacing: 0.04em;
            line-height: 1.15;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 0;
            font-size: 7.8pt;
            line-height: 1.2;
        }

        /* Dokumen Title */
        .doc-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .doc-title {
            margin: 0 0 2px 0;
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.02em;
        }
        .doc-subtitle {
            margin: 0;
            font-size: 8.2pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Table KIR */
        table.table-kir {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.table-kir th {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            background-color: #f2f2f2;
        }
        table.table-kir td {
            border: 1px solid #000;
            padding: 4px 5px;
            font-size: 8pt;
            vertical-align: top;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .font-mono { font-family: monospace, 'Courier New', Courier; }

        /* Tanda Tangan */
        .ttd-wrapper {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .ttd-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            text-align: center;
            font-size: 8.5pt;
        }
        .ttd-space {
            height: 55px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI STIKES PANTI WALUYA MALANG -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @php
                    $logoPath = public_path($pengaturan->logo_path ?? 'images/logo-stikes.png');
                @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" style="width: 65px; height: auto;">
                @endif
            </td>
            <td class="kop-text">
                <h4>SEKOLAH TINGGI ILMU KESEHATAN</h4>
                <h3>PANTI WALUYA MALANG</h3>
                <p>Jalan Yulius Usman No. 62 Malang &ndash; 65117 Telp (0341) 369003 Fax. 368737</p>
                <p>Email : <u>stikes.pantiwaluyamlg@gmail.com</u>, website : <u>www.stikespantiwaluya.ac.id</u></p>
            </td>
            <td style="width: 70px;"></td>
        </tr>
    </table>

    <!-- JUDUL DOKUMEN -->
    <div class="doc-header">
        <h4 class="doc-title">KARTU INVENTARIS ALAT LABORATORIUM (KIR)</h4>
        <div class="doc-subtitle">
            UNIT : {{ strtoupper($pengaturan->unit_laboratorium ?? 'UNIT LABORATORIUM KEPERAWATAN & KESEHATAN') }}
            @if(!empty($kategoriSelected))
                <br>KATEGORI : {{ strtoupper($kategoriSelected) }}
            @else
                <br>REKAPITULASI ASET SELURUH KATEGORI PRAKTIKUM (GLOBAL)
            @endif
        </div>
    </div>

    <!-- TABEL KIR -->
    <table class="table-kir">
        <thead>
            <tr>
                <th style="width: 25px;">NO</th>
                <th style="width: 90px;">KODE ALAT</th>
                <th>NAMA ALAT / BARANG LAB</th>
                <th style="width: 120px;">KATEGORI</th>
                <th style="width: 40px;">TOTAL</th>
                <th style="width: 45px;">READY</th>
                <th style="width: 75px;">KONDISI</th>
                <th style="width: 90px;">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alats as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center font-mono" style="font-size: 7.5pt;">{{ $item->kode_alat }}</td>
                <td class="fw-bold">{{ $item->nama_alat }}</td>
                <td>{{ $item->kategori ?? '-' }}</td>
                <td class="text-center fw-bold">{{ $item->stok_total }}</td>
                <td class="text-center">{{ $item->stok_tersedia }}</td>
                <td class="text-center">{{ $item->kondisi }}</td>
                <td class="text-center" style="font-size: 7.5pt;">
                    {{ $item->kondisi === 'Baik' ? 'Siap Pakai' : ($item->kondisi === 'Rusak Ringan' ? 'Perlu Servis' : 'Perbaikan') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px;">Tidak ada data alat laboratorium yang tercatat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN (3 PIHAK RESMI) -->
    <div class="ttd-wrapper">
        <!-- Baris 1: PJ Unit Lab (Kiri) & Kepala Bagian Sarpras (Kanan) -->
        <table class="ttd-table">
            <tr>
                <td style="width: 45%;">
                    <p style="margin: 0;">&nbsp;</p>
                    <p style="margin: 0; font-weight: bold;">Kepala Unit Laboratorium,</p>
                    <div class="ttd-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $pengaturan->kepala_lab ?? 'Ns. Wening Prabawati, M.Kep.' }}</p>
                    <p style="margin: 0; font-size: 7.8pt;">NIP. {{ $pengaturan->nip_kepala_lab ?? '198205142010122001' }}</p>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    <p style="margin: 0;">{{ $pengaturan->kota_dokumen ?? 'Malang' }}, {{ \Carbon\Carbon::parse($pengaturan->tanggal_dokumen ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0; font-weight: bold;">Kepala Program Studi S1 Keperawatan &amp; Profesi Ners,</p>
                    <div class="ttd-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $pengaturan->nama_kabag_sarpras ?? 'Petrus Tobias, S.Kom.' }}</p>
                    <p style="margin: 0; font-size: 7.8pt;">{{ $pengaturan->nip_kabag_sarpras ?? 'NIP. 2021.08.045' }}</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 15px;">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0; font-weight: bold;">Ketua STIKES Panti Waluya Malang</p>
                    <div class="ttd-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $pengaturan->nama_ketua ?? 'Wibowo, S.Kep., Ns. M.Biomed' }}</p>
                    <p style="margin: 0; font-size: 7.8pt;">NIP. {{ $pengaturan->nip_ketua ?? '1283/A' }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
