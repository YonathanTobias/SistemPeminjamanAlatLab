/**
 * Sistem Keranjang Praktikum Multi-Alat Laboratorium
 * File JS: cart.js
 */

const CART_STORAGE_KEY = 'stikes_lab_cart_items';

function getCart() {
    const data = localStorage.getItem(CART_STORAGE_KEY);
    return data ? JSON.parse(data) : [];
}

function saveCart(cart) {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
    updateCartUI();
}

function addToCart(btn) {
    const id = parseInt(btn.getAttribute('data-id'));
    const kode = btn.getAttribute('data-kode');
    const nama = btn.getAttribute('data-nama');
    const stok = parseInt(btn.getAttribute('data-stok'));

    let cart = getCart();
    const existingIndex = cart.findIndex(item => item.id === id);

    if (existingIndex > -1) {
        if (cart[existingIndex].jumlah < stok) {
            cart[existingIndex].jumlah += 1;
        } else {
            Toast.fire({
                icon: 'warning',
                title: `Maksimal peminjaman ${nama} adalah ${stok} unit (stok habis).`
            });
            return;
        }
    } else {
        cart.push({
            id: id,
            kode: kode,
            nama: nama,
            stok: stok,
            jumlah: 1
        });
    }

    saveCart(cart);

    // SweetAlert2 Toast Feedback
    Toast.fire({
        icon: 'success',
        title: `+ ${nama} dimasukkan ke keranjang!`
    });

    // Feedback visual tombol
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check-lg"></i> Ditambahkan!';
    btn.classList.replace('btn-primary', 'btn-success');
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.classList.replace('btn-success', 'btn-primary');
    }, 800);
}

function updateQty(id, delta) {
    let cart = getCart();
    const item = cart.find(i => i.id === id);
    if (item) {
        const newQty = item.jumlah + delta;
        if (newQty >= 1 && newQty <= item.stok) {
            item.jumlah = newQty;
            saveCart(cart);
        } else if (newQty > item.stok) {
            Toast.fire({
                icon: 'warning',
                title: `Maksimal stok tersedia adalah ${item.stok} unit.`
            });
        }
    }
}

function setQtyDirect(id, val) {
    let cart = getCart();
    const item = cart.find(i => i.id === id);
    if (item) {
        let num = parseInt(val);
        if (isNaN(num) || num < 1) num = 1;
        if (num > item.stok) num = item.stok;
        item.jumlah = num;
        saveCart(cart);
    }
}

function removeFromCart(id) {
    let cart = getCart();
    cart = cart.filter(i => i.id !== id);
    saveCart(cart);
}

function clearCart() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Kosongkan Keranjang?',
            text: 'Seluruh daftar alat praktikum yang dipilih akan dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kosongkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem(CART_STORAGE_KEY);
                updateCartUI();
                Toast.fire({
                    icon: 'info',
                    title: 'Keranjang telah dikosongkan.'
                });
            }
        });
    } else {
        if (confirm('Yakin ingin mengosongkan keranjang praktikum?')) {
            localStorage.removeItem(CART_STORAGE_KEY);
            updateCartUI();
        }
    }
}

function updateCartUI() {
    const cart = getCart();
    const fab = document.getElementById('floatingCartBtn');
    const badge = document.getElementById('cartCountBadge');
    const listCount = document.getElementById('cartListCount');
    const tbody = document.getElementById('cartTableBody');
    const emptyWarning = document.getElementById('cartEmptyWarning');
    const btnSubmit = document.getElementById('btnSubmitCheckout');

    if (!fab || !badge) return;

    const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);

    if (cart.length > 0) {
        fab.style.display = 'block';
        badge.innerText = `${totalItems} Unit (${cart.length} Alat)`;
        if (listCount) listCount.innerText = cart.length;
        if (emptyWarning) emptyWarning.style.display = 'none';
        if (btnSubmit) btnSubmit.removeAttribute('disabled');
    } else {
        fab.style.display = 'none';
        badge.innerText = '0';
        if (listCount) listCount.innerText = '0';
        if (emptyWarning) emptyWarning.style.display = 'block';
        if (btnSubmit) btnSubmit.setAttribute('disabled', 'disabled');
    }

    // 1. Render Table Rows (jika ada tampilan table)
    if (tbody) {
        tbody.innerHTML = '';
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="fw-bold text-dark">${item.nama}</div>
                    <div class="small text-muted d-flex align-items-center gap-2">
                        <span class="badge badge-soft-primary">${item.kode}</span>
                        <span>Tersedia: <strong>${item.stok} Unit</strong></span>
                    </div>
                    <input type="hidden" name="items[${index}][alat_lab_id]" value="${item.id}">
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: auto;">
                        <button type="button" class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                        <input type="number" name="items[${index}][jumlah]" class="form-control text-center fw-bold px-1" value="${item.jumlah}" min="1" max="${item.stok}" onchange="setQtyDirect(${item.id}, this.value)">
                        <button type="button" class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-1" style="width: 28px; height: 28px;" onclick="removeFromCart(${item.id})" title="Hapus">
                        <i class="bi bi-x"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // 2. Render Offcanvas Drawer Cards (jika ada offcanvas cart)
    const offcanvasList = document.getElementById('cartOffcanvasList');
    if (offcanvasList) {
        offcanvasList.innerHTML = '';
        if (cart.length === 0) {
            offcanvasList.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-cart-x fs-1 opacity-50 d-block mb-2"></i>
                    <p class="mb-0">Keranjang praktikum masih kosong.</p>
                    <small>Silakan pilih peralatan medis di katalog.</small>
                </div>
            `;
        } else {
            cart.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'cart-item-card shadow-xs';
                div.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="fw-bold text-dark fs-6">${item.nama}</div>
                            <span class="badge badge-soft-primary" style="font-size: 0.72rem;">${item.kode}</span>
                            <span class="small text-muted ms-1">Tersedia: ${item.stok}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="removeFromCart(${item.id})" title="Hapus dari keranjang">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-1 border-top">
                        <small class="text-muted">Jumlah unit:</small>
                        <div class="input-group input-group-sm" style="max-width: 110px;">
                            <button type="button" class="btn btn-outline-secondary py-0" onclick="updateQty(${item.id}, -1)">-</button>
                            <input type="number" class="form-control text-center fw-bold p-0" value="${item.jumlah}" min="1" max="${item.stok}" onchange="setQtyDirect(${item.id}, this.value)">
                            <button type="button" class="btn btn-outline-secondary py-0" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                    </div>
                    <input type="hidden" name="items[${index}][alat_lab_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][jumlah]" value="${item.jumlah}">
                `;
                offcanvasList.appendChild(div);
            });
        }
    }
}

function validateCartSubmission() {
    const cart = getCart();
    if (cart.length === 0) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Silakan pilih peralatan praktikum terlebih dahulu sebelum mengirim formulir.'
            });
        } else {
            alert('Keranjang praktikum masih kosong! Silakan pilih alat terlebih dahulu.');
        }
        return false;
    }
    // Bersihkan keranjang saat formulir sukses dikirim
    setTimeout(() => {
        localStorage.removeItem(CART_STORAGE_KEY);
        updateCartUI();
    }, 500);
    return true;
}

// Live Instant Search & Filter Katalog
function filterKatalogRealtime() {
    const query = (document.getElementById('searchInputKatalog')?.value || '').toLowerCase().trim();
    const kategori = (document.getElementById('filterKategoriKatalog')?.value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.alat-grid-item');
    let foundCount = 0;

    cards.forEach(card => {
        const nama = (card.getAttribute('data-nama') || '').toLowerCase();
        const kode = (card.getAttribute('data-kode') || '').toLowerCase();
        const kat = (card.getAttribute('data-kategori') || '').toLowerCase();

        const matchQuery = !query || nama.includes(query) || kode.includes(query);
        const matchKategori = !kategori || kat === kategori;

        if (matchQuery && matchKategori) {
            card.style.display = '';
            foundCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const noResultEl = document.getElementById('noKatalogResults');
    if (noResultEl) {
        noResultEl.style.display = (foundCount === 0 && cards.length > 0) ? 'block' : 'none';
    }
}

// Quick Detail Modal Helper
function openQuickDetail(alat) {
    document.getElementById('detailAlatNama').innerText = alat.nama_alat || '-';
    document.getElementById('detailAlatKode').innerText = alat.kode_alat || '-';
    document.getElementById('detailAlatKategori').innerText = alat.kategori || 'Umum';
    document.getElementById('detailAlatStok').innerText = `${alat.stok || 0} Unit Tersedia`;
    document.getElementById('detailAlatKondisi').innerText = alat.kondisi || 'Baik';
    document.getElementById('detailAlatLokasi').innerText = alat.lokasi || 'Lemari Alat Medis Lt. 2';
    document.getElementById('detailAlatDeskripsi').innerText = alat.deskripsi || 'Tidak ada deskripsi tambahan.';
    
    const imgEl = document.getElementById('detailAlatFoto');
    if (imgEl) {
        imgEl.src = alat.foto ? `/storage/${alat.foto}` : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&auto=format&fit=crop&q=80';
    }

    const detailModal = new bootstrap.Modal(document.getElementById('modalDetailAlat'));
    detailModal.show();
}

document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();
    
    // Bind search input jika ada
    const searchInput = document.getElementById('searchInputKatalog');
    if (searchInput) {
        searchInput.addEventListener('input', filterKatalogRealtime);
    }
    const filterKat = document.getElementById('filterKategoriKatalog');
    if (filterKat) {
        filterKat.addEventListener('change', filterKatalogRealtime);
    }
});
