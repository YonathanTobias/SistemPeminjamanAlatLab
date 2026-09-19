/**
 * Sistem Pengaturan Aplikasi STIKES Panti Waluya
 * File JS: settings.js
 */

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logoPreview');
            if (preview) preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Quick Presets Helper
const presets = {
    's1_kep': {
        nama_sistem: 'Sistem Peminjaman Alat Lab Prodi S1 Keperawatan',
        nama_prodi: 'Prodi S1 Ilmu Keperawatan',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Keperawatan Medikal Bedah & Dasar',
        kepala_lab: 'Ns. Wening Prabawati, M.Kep.',
        nip_kepala_lab: '198205142010122001',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    },
    'd3_kep': {
        nama_sistem: 'Sistem Peminjaman Alat Lab Prodi D3 Keperawatan',
        nama_prodi: 'Prodi D3 Keperawatan',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Praktikum Keperawatan Vokasi',
        kepala_lab: 'Ns. Maria Magdalena, S.Kep., M.Kes.',
        nip_kepala_lab: '198506122011012003',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    },
    'ners': {
        nama_sistem: 'Sistem Peminjaman Alat Lab Program Profesi Ners',
        nama_prodi: 'Program Studi Profesi Ners',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium OSCE & Clinical Skills Center',
        kepala_lab: 'Ns. Yohanes Bagus, S.Kep., M.Kep.',
        nip_kepala_lab: '198804122014021002',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    },
    'farmasi': {
        nama_sistem: 'Sistem Peminjaman Alat Laboratorium Farmasi',
        nama_prodi: 'Prodi D3 Farmasi',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Farmakologi & Kimia Farmasi',
        kepala_lab: 'apt. Fransiska Dewi, M.Farm.',
        nip_kepala_lab: '199002152016032004',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    }
};

function applyPreset(key) {
    if (presets[key]) {
        const p = presets[key];
        const fields = {
            'inp_nama_sistem': p.nama_sistem,
            'inp_nama_prodi': p.nama_prodi,
            'inp_nama_institusi': p.nama_institusi,
            'inp_unit_lab': p.unit_lab,
            'inp_kepala_lab': p.kepala_lab,
            'inp_nip_kepala_lab': p.nip_kepala_lab,
            'inp_alamat': p.alamat,
            'inp_kontak': p.kontak
        };

        for (const [id, val] of Object.entries(fields)) {
            const el = document.getElementById(id);
            if (el) el.value = val;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Preset Diterapkan',
                text: `Preset untuk ${p.nama_prodi} telah dimuat ke formulir.`
            });
        } else {
            alert(`Preset ${p.nama_prodi} telah diterapkan ke formulir!`);
        }
    }
}
