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

    // Render Table Rows
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

document.addEventListener('DOMContentLoaded', () => {
    updateCartUI();
});
