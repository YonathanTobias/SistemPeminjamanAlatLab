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
    's1_kep_ners': {
        nama_sistem: 'Sistem Peminjaman Alat Lab Prodi S1 Keperawatan & Profesi Ners',
        nama_prodi: 'Prodi S1 Keperawatan & Profesi Ners',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Keperawatan Medikal Bedah & OSCE Center',
        kepala_lab: 'Ns. Wening Prabawati, M.Kep.',
        nip_kepala_lab: '198205142010122001',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    },
    's1_farmasi': {
        nama_sistem: 'Sistem Peminjaman Alat Laboratorium Farmasi',
        nama_prodi: 'Prodi S1 Farmasi',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Farmakologi & Teknologi Farmasi',
        kepala_lab: 'apt. Fransiska Dewi, M.Farm.',
        nip_kepala_lab: '199002152016032004',
        alamat: 'Jl. Yulius Usman No.62, Kasin, Kec. Klojen, Kota Malang, Jawa Timur 65117',
        kontak: '(0341) 369003'
    },
    'd4_mik': {
        nama_sistem: 'Sistem Laboratorium Komputer & Rekam Medis D4 MIK',
        nama_prodi: 'Prodi D4 Manajemen Informasi Kesehatan',
        nama_institusi: 'STIKES Panti Waluya Malang',
        unit_lab: 'Unit Laboratorium Rekam Medis Elektronik & SIMRS',
        kepala_lab: 'Bayu Prasetyo, S.Kom., M.MT.',
        nip_kepala_lab: '199208192017041005',
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
