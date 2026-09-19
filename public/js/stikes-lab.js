/**
 * Sistem Informasi Laboratorium STIKES Panti Waluya Malang
 * File JS Utama (stikes-lab.js)
 */

// 1. Dark Mode Toggle & Persistence
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('stikes_theme', newTheme);
    updateThemeIcon(newTheme);
}

function updateThemeIcon(theme) {
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.className = theme === 'dark' ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill text-white';
    }
}

// Inisialisasi tema saat halaman dimuat
(function() {
    const savedTheme = localStorage.getItem('stikes_theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    document.addEventListener('DOMContentLoaded', () => updateThemeIcon(savedTheme));
})();

// 2. SweetAlert2 Toast Instance Helper
const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
}) : {
    fire: (opts) => alert(opts.title || opts.text)
};

// 3. Auto-Initialize QR Code Elements with data-qr-text
function initQrCodeElements() {
    if (typeof QRCode === 'undefined') return;
    document.querySelectorAll('.qr-code-canvas[data-qr-text]').forEach(el => {
        const text = el.getAttribute('data-qr-text');
        if (text && !el.hasChildNodes()) {
            new QRCode(el, {
                text: text,
                width: 120,
                height: 120,
                colorDark: '#0f172a',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initQrCodeElements);
