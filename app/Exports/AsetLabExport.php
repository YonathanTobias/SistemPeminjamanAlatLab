<?php

namespace App\Exports;

use App\Models\AlatLab;
use App\Models\PengaturanLab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AsetLabExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $kategori;
    protected $kategoriNama;
    protected $pengaturan;
    protected $rowNumber = 0;

    public function __construct($kategori = null)
    {
        $this->kategori = $kategori;
        $this->kategoriNama = $kategori ? $kategori : 'Semua Kategori (Rekap Global)';
        $this->pengaturan = PengaturanLab::getPengaturan();
    }

    public function collection()
    {
        $query = AlatLab::query();

        if ($this->kategori) {
            $query->where('kategori', $this->kategori);
        }

        return $query->orderBy('nama_alat', 'asc')->get();
    }

    public function headings(): array
    {
        $namaInstitusi = strtoupper($this->pengaturan->nama_institusi ?? 'SEKOLAH TINGGI ILMU KESEHATAN PANTI WALUYA MALANG');
        $unitLab       = strtoupper($this->pengaturan->unit_laboratorium ?? 'UNIT LABORATORIUM KEPERAWATAN & KESEHATAN');

        return [
            ['KARTU INVENTARIS ALAT LABORATORIUM (KIR)'],
            [$namaInstitusi],
            [$unitLab],
            ['KATEGORI PRAKTIKUM : ' . strtoupper($this->kategoriNama)],
            ['TANGGAL EKSPOR     : ' . date('d-m-Y H:i') . ' WIB'],
            [], // Baris kosong
            [
                'No',
                'Kode Alat',
                'Nama Alat / Barang Lab',
                'Kategori Praktikum',
                'Stok Total',
                'Stok Tersedia',
                'Kondisi',
                'Keterangan'
            ]
        ];
    }

    public function map($alat): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $alat->kode_alat,
            $alat->nama_alat,
            $alat->kategori ?? '-',
            $alat->stok_total,
            $alat->stok_tersedia,
            $alat->kondisi,
            $alat->kondisi === 'Baik' ? 'Siap Pakai' : ($alat->kondisi === 'Rusak Ringan' ? 'Perlu Pemeliharaan' : 'Tidak Dapat Digunakan'),
        ];
    }

    public function title(): string
    {
        return 'Inventaris Alat Lab';
    }

    public function styles(Worksheet $sheet)
    {
        // Bold untuk Judul Header Laporan
        $sheet->getStyle('A1:A5')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        
        // Bold & Background untuk Heading Tabel (Baris ke-7)
        $sheet->getStyle('A7:H7')->getFont()->setBold(true);
        $sheet->getStyle('A7:H7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF2F4F7');

        // Border tipis untuk seluruh tabel
        $lastRow = $this->rowNumber > 0 ? ($this->rowNumber + 7) : 7;
        $sheet->getStyle("A7:H{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Alignment kolom tengah
        $sheet->getStyle("A7:A{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B7:B{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E7:G{$lastRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
