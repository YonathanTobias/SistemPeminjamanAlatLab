/**
 * Sistem Dashboard Admin & QR Scanner
 * File JS: admin-dashboard.js
 */

// 1. Helper Inisialisasi Grafik Chart.js
function initDashboardCharts(weeklyLabels, weeklyData) {
    // Grafik: Tren Mingguan
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
}

// 2. Helper Scanner QR Code & Audio Chime
let html5QrcodeScanner = null;

function playScanSuccessAudio() {
    try {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        const ctx = new AudioContext();
        
        // Tone 1
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(880, ctx.currentTime); // A5
        gain1.gain.setValueAtTime(0.15, ctx.currentTime);
        gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.12);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(ctx.currentTime);
        osc1.stop(ctx.currentTime + 0.12);

        // Tone 2 (Higher harmony)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(1320, ctx.currentTime + 0.08); // E6
        gain2.gain.setValueAtTime(0.2, ctx.currentTime + 0.08);
        gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(ctx.currentTime + 0.08);
        osc2.stop(ctx.currentTime + 0.25);
    } catch (e) {
        console.log("Audio not supported or blocked: ", e);
    }
}

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
                    playScanSuccessAudio();
                    const resultEl = document.getElementById('qr-reader-results');
                    if (resultEl) resultEl.innerText = `Ditemukan: ${decodedText}`;
                    stopQrScanner();
                    setTimeout(() => {
                        window.location.href = `${searchUrl}?search=${encodeURIComponent(decodedText)}`;
                    }, 400);
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

