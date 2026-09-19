/**
 * Sistem Dashboard Admin & QR Scanner
 * File JS: admin-dashboard.js
 */

// 1. Helper Inisialisasi Grafik Chart.js
function initDashboardCharts(weeklyLabels, weeklyData, prodiLabels, prodiData) {
    // Grafik 1: Tren Mingguan
    const ctxTren = document.getElementById('chartTrenMingguan');
    if (ctxTren && typeof Chart !== 'undefined') {
        new Chart(ctxTren, {
            type: 'bar',
            data: {
                labels: weeklyLabels && weeklyLabels.length > 0 ? weeklyLabels : ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: weeklyData && weeklyData.length > 0 ? weeklyData : [0, 0, 0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(2, 132, 199, 0.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Grafik 2: Distribusi Prodi
    const ctxProdi = document.getElementById('chartProdi');
    if (ctxProdi && typeof Chart !== 'undefined') {
        new Chart(ctxProdi, {
            type: 'doughnut',
            data: {
                labels: prodiLabels && prodiLabels.length > 0 ? prodiLabels : ['S1 Keperawatan', 'D3 Keperawatan', 'Profesi Ners'],
                datasets: [{
                    data: prodiData && prodiData.length > 0 ? prodiData : [1, 1, 1],
                    backgroundColor: [
                        '#0284c7',
                        '#0d9488',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });
    }
}

// 2. Helper Scanner QR Code
let html5QrcodeScanner = null;

function initQrScanner(searchUrl) {
    const modalScan = document.getElementById('modalScanQr');
    if (modalScan && typeof Html5QrcodeScanner !== 'undefined') {
        modalScan.addEventListener('shown.bs.modal', function () {
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", 
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    false
                );
                html5QrcodeScanner.render((decodedText) => {
                    const resultEl = document.getElementById('qr-reader-results');
                    if (resultEl) resultEl.innerText = `Ditemukan: ${decodedText}`;
                    stopQrScanner();
                    window.location.href = `${searchUrl}?search=${encodeURIComponent(decodedText)}`;
                }, (err) => {
                    // silent on frame scanning
                });
            }
        });

        modalScan.addEventListener('hidden.bs.modal', function () {
            stopQrScanner();
        });
    }
}

function stopQrScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().catch(error => {
            console.error("Failed to clear html5QrcodeScanner: ", error);
        });
        html5QrcodeScanner = null;
    }
}

function submitManualTrx(searchUrl) {
    const input = document.getElementById('manualTrxInput');
    if (input && input.value.trim()) {
        window.location.href = `${searchUrl}?search=${encodeURIComponent(input.value.trim())}`;
    }
}
