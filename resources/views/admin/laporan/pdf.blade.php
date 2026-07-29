<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Alat Lab</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        h3 { text-align: center; margin-bottom: 4px; text-transform: uppercase; }
        p { text-align: center; margin-top: 0; font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h3>Laporan Peminjaman Alat Laboratorium</h3>
    <p>Dicetak pada: {{ date('d-m-Y H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>NIM/NIP</th>
                <th>Prodi</th>
                <th>Kode Alat</th>
                <th>Nama Alat</th>
                <th>Jml</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->nim_nip }}</td>
                <td>{{ $item->prodi }}</td>
                <td class="text-center">{{ $item->alat->kode_alat ?? '-' }}</td>
                <td>{{ $item->alat->nama_alat ?? 'Alat Dihapus' }}</td>
                <td class="text-center">{{ $item->jumlah_pinjam }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_kembali_rencana)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>