/**
 * JavaScript Controller untuk Halaman Laporan & KIR Alat Lab
 * STIKES Panti Waluya Malang
 */

let currentPrintTarget = 'kir';
let originalPageTitle = document.title;

function setLaporanTab(tabName) {
    currentPrintTarget = tabName;
    let url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);
}

function triggerPrintTab(target) {
    currentPrintTarget = target;
    document.body.classList.remove('printing-kir', 'printing-pinjam');
    
    if (target === 'kir') {
        document.body.classList.add('printing-kir');
        document.title = 'KIR_Alat_Laboratorium_STIKES_Panti_Waluya';
    } else if (target === 'pinjam') {
        document.body.classList.add('printing-pinjam');
        document.title = 'Laporan_Peminjaman_Alat_Lab';
    }

    setTimeout(function() {
        window.print();
    }, 150);
}

window.addEventListener('afterprint', function() {
    document.body.classList.remove('printing-kir', 'printing-pinjam');
    document.title = originalPageTitle;
});

// Real-time Search Filter pada Tabel KIR
function onKIRTableFilter() {
    let input = document.getElementById('tableSearchKIR');
    let filter = input ? input.value.toLowerCase().trim() : '';
    let rows = document.querySelectorAll('#tbodyKIR .kir-row-item');
    let visibleCount = 0;

    rows.forEach(function(row) {
        let kode = row.getAttribute('data-kode') || '';
        let nama = row.getAttribute('data-nama') || '';
        let kategori = row.getAttribute('data-kategori') || '';

        if (kode.includes(filter) || nama.includes(filter) || kategori.includes(filter)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    let emptyRow = document.getElementById('kirSearchEmptyRow');
    if (emptyRow) {
        emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    let badgeCount = document.getElementById('badgeTotalItemsKIR');
    if (badgeCount) {
        badgeCount.innerText = visibleCount + ' Ditampilkan';
    }

    let btnClear = document.getElementById('btnClearSearchKIR');
    if (btnClear) {
        btnClear.classList.toggle('d-none', filter === '');
    }
}

function clearKIRSearch() {
    let input = document.getElementById('tableSearchKIR');
    if (input) {
        input.value = '';
        onKIRTableFilter();
        input.focus();
    }
}

// Quick Date Changer untuk Tanggal Dokumen
function handleQuickDateKIR(input) {
    let val = input.value;
    if (!val) return;
    
    let parts = val.split('-');
    if (parts.length === 3) {
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        let day = parseInt(parts[2], 10);
        let month = monthNames[parseInt(parts[1], 10) - 1];
        let year = parts[0];
        let formatted = day + ' ' + month + ' ' + year;

        let printTglElements = document.querySelectorAll('.print-tgl-dokumen');
        printTglElements.forEach(function(el) {
            let kota = document.getElementById('inputKotaDokumen') ? document.getElementById('inputKotaDokumen').value : 'Malang';
            el.innerText = kota + ', ' + formatted;
        });
    }
}

// Simpan Pejabat Penandatangan via AJAX
document.addEventListener('DOMContentLoaded', function() {
    let formTtd = document.getElementById('formPengaturanTTD');
    if (formTtd) {
        formTtd.addEventListener('submit', function(e) {
            e.preventDefault();
            let submitBtn = formTtd.querySelector('button[type="submit"]');
            let origBtnHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
            }

            let formData = new FormData(formTtd);

            fetch(formTtd.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origBtnHtml;
                }

                if (data.success) {
                    // Update Text Langsung di Dokumen Cetak
                    let namaKetua = formTtd.querySelector('[name="nama_ketua"]').value;
                    let nipKetua = formTtd.querySelector('[name="nip_ketua"]').value;
                    let namaKabag = formTtd.querySelector('[name="nama_kabag_sarpras"]').value;
                    let nipKabag = formTtd.querySelector('[name="nip_kabag_sarpras"]').value;
                    let namaKepalaLab = formTtd.querySelector('[name="kepala_lab"]').value;
                    let nipKepalaLab = formTtd.querySelector('[name="nip_kepala_lab"]').value;
                    let kota = formTtd.querySelector('[name="kota_dokumen"]').value;

                    document.querySelectorAll('.print-nama-ketua').forEach(el => el.innerText = namaKetua);
                    document.querySelectorAll('.print-nip-ketua').forEach(el => el.innerText = (nipKetua.startsWith('NIP') || nipKetua.startsWith('NIDN')) ? nipKetua : 'NIP. ' + nipKetua);
                    document.querySelectorAll('.print-nama-kabag').forEach(el => el.innerText = namaKabag);
                    document.querySelectorAll('.print-nip-kabag').forEach(el => el.innerText = (nipKabag.startsWith('NIP') || nipKabag.startsWith('NIDN')) ? nipKabag : 'NIP. ' + nipKabag);
                    document.querySelectorAll('.print-nama-lab').forEach(el => el.innerText = namaKepalaLab);
                    document.querySelectorAll('.print-nip-lab').forEach(el => el.innerText = (nipKepalaLab.startsWith('NIP') || nipKepalaLab.startsWith('NIDN')) ? nipKepalaLab : 'NIP. ' + nipKepalaLab);

                    // Tutup modal
                    let modalEl = document.getElementById('modalPengaturanTTD');
                    if (modalEl) {
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Disimpan!',
                            text: data.message || 'Pejabat penandatangan KIR berhasil diperbarui.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        alert(data.message || 'Pejabat penandatangan KIR berhasil diperbarui.');
                    }
                }
            })
            .catch(err => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origBtnHtml;
                }
                console.error(err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: 'Terjadi kesalahan sistem saat menyimpan pejabat penandatangan.'
                    });
                }
            });
        });
    }
});
