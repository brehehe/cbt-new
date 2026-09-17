<!-- Modal Scanner Kamera Presensi Mahasiswa / Kenshi (Responsif Mobile & Desktop) -->
<div wire:ignore.self id="modal-student-camera-scan"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[1050] p-3 sm:p-5 transition-all duration-300">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-lg max-h-[92vh] flex flex-col border border-slate-200 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-4 py-3 sm:px-6 sm:py-4 border-b border-slate-100 bg-slate-50/80">
            <div class="flex items-center gap-2.5 sm:gap-3">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 shrink-0">
                    <i class="fas fa-camera text-base sm:text-lg"></i>
                </div>
                <div>
                    <h5 class="font-extrabold text-sm sm:text-base text-slate-800 leading-tight">
                        Scan QRCODE Presensi
                    </h5>
                    <p class="text-[11px] sm:text-xs text-slate-500 line-clamp-1">
                        Arahkan kamera ke lembar QRCODE sesi jadwal
                    </p>
                </div>
            </div>
            <button type="button" wire:click="closeStudentScanner" class="text-slate-400 hover:text-slate-700 p-1.5 sm:p-2 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fas fa-times text-base sm:text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-3.5 sm:p-5 overflow-y-auto space-y-3 flex-1">
            <!-- Camera Viewport Container -->
            <div class="relative bg-slate-950 rounded-xl sm:rounded-2xl overflow-hidden border border-slate-800 flex flex-col items-center justify-center w-full h-64 sm:h-72 shadow-inner">
                <div id="student-scan-reader" class="w-full h-full"></div>

                <!-- Target Scanner Overlay -->
                <div id="student-scan-overlay" class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                    <div class="w-40 h-40 sm:w-52 sm:h-52 border-2 border-amber-400/90 rounded-2xl relative animate-pulse shadow-[0_0_20px_rgba(251,191,36,0.3)]">
                        <div class="absolute -top-1 -left-1 w-5 h-5 border-t-4 border-l-4 border-amber-400 rounded-tl-lg"></div>
                        <div class="absolute -top-1 -right-1 w-5 h-5 border-t-4 border-r-4 border-amber-400 rounded-tr-lg"></div>
                        <div class="absolute -bottom-1 -left-1 w-5 h-5 border-b-4 border-l-4 border-amber-400 rounded-bl-lg"></div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 border-b-4 border-r-4 border-amber-400 rounded-br-lg"></div>
                        <div class="w-full h-0.5 bg-amber-400/90 absolute top-1/2 left-0 shadow-[0_0_10px_#fbbf24]"></div>
                    </div>
                    <span class="text-[10px] sm:text-[11px] text-amber-900 font-bold mt-2.5 bg-amber-100/95 border border-amber-300 px-3 py-0.5 rounded-full shadow-xs">
                        Arahkan ke QRCODE Sesi Jadwal
                    </span>
                </div>

                <!-- Camera Switch & Pause Bar -->
                <div class="absolute bottom-2.5 left-0 right-0 flex justify-center gap-2 z-10 px-3">
                    <button type="button" id="btn-student-switch-cam" class="px-3 py-1 bg-white/95 hover:bg-white text-slate-800 rounded-lg text-[11px] font-bold shadow border border-slate-200 transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-camera-rotate text-slate-600 text-xs"></i> Ganti Kamera
                    </button>
                    <button type="button" id="btn-student-restart-cam" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-lg text-[11px] font-bold shadow transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-stop text-xs"></i> Jeda Kamera
                    </button>
                </div>
            </div>

            <!-- Feedback Toast Notification -->
            <div id="student-scan-feedback" class="hidden p-3 sm:p-4 rounded-xl transition-all duration-300 shadow-sm">
                <div class="flex items-center gap-3">
                    <div id="student-feedback-icon" class="w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-base sm:text-lg font-bold shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div id="student-feedback-title" class="font-bold text-xs sm:text-sm truncate"></div>
                        <div id="student-feedback-desc" class="text-[11px] sm:text-xs"></div>
                    </div>
                </div>
            </div>

            <!-- Fallback Input Manual (Accordion Collapsible - Hemat Ruang Mobile) -->
            <details class="group bg-slate-50 rounded-xl border border-slate-200 overflow-hidden transition-all">
                <summary class="p-3 cursor-pointer list-none select-none flex items-center justify-between text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs">
                            <i class="fas fa-keyboard"></i>
                        </span>
                        <span>Atau Masukkan ID QRCODE Manual</span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-amber-700 font-semibold">
                        <span class="group-open:hidden">Ketik ID</span>
                        <span class="hidden group-open:inline">Tutup</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 group-open:rotate-180"></i>
                    </div>
                </summary>
                <div class="p-3 pt-0 space-y-2 border-t border-slate-200/60 mt-2">
                    <div class="flex gap-2 pt-2">
                        <input type="text" wire:model="manual_qr_code"
                            placeholder="Contoh: TTD-20260917-ABCDEF"
                            class="flex-1 px-3 py-2 text-xs rounded-xl bg-white border border-slate-300 text-slate-900 font-mono uppercase tracking-wider focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                        <button type="button" wire:click="submitManualQrCode"
                            class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow transition cursor-pointer shrink-0">
                            Kirim
                        </button>
                    </div>
                    <span class="text-[10px] text-slate-500 block leading-tight">
                        Gunakan opsi manual ini jika kamera Anda tidak dapat memindai atau tidak memiliki izin akses kamera.
                    </span>
                </div>
            </details>
        </div>

        <!-- Footer -->
        <div class="px-4 py-2.5 sm:px-6 sm:py-3 border-t border-slate-100 bg-slate-50/80 flex justify-between items-center text-xs">
            <div class="text-[11px] sm:text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Status: Pemindai Kamera Siap</span>
            </div>
            <button type="button" wire:click="closeStudentScanner"
                class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
    #student-scan-reader {
        width: 100% !important;
        height: 100% !important;
        position: relative;
        background-color: #020617;
        overflow: hidden;
    }
    #student-scan-reader video {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 0.75rem;
    }
    #student-scan-reader__scan_region {
        background: transparent !important;
        width: 100% !important;
        height: 100% !important;
    }
    #student-scan-reader__dashboard_section_csr button {
        background-color: #f59e0b !important;
        color: #0f172a !important;
        font-weight: bold !important;
        border-radius: 0.75rem !important;
        padding: 6px 12px !important;
    }
</style>

<!-- Script HTML5-QRCode Scanner Siswa dengan Web Audio API Beep & Multi-Camera Fallback -->
<script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script>
    (function () {
        let studentQrScanner = null;
        let isStudentScannerRunning = false;
        let studentFacingMode = "environment"; // Kamera belakang by default
        let lastStudentScannedText = "";
        let studentScanTimer = null;

        // Helper untuk memastikan pustaka Html5Qrcode terpasang
        function ensureHtml5Qrcode(callback) {
            if (typeof Html5Qrcode !== 'undefined') {
                callback();
                return;
            }

            let scriptTag = document.getElementById('html5-qrcode-library-script');
            if (!scriptTag) {
                scriptTag = document.createElement('script');
                scriptTag.id = 'html5-qrcode-library-script';
                scriptTag.src = "{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}";
                document.head.appendChild(scriptTag);
            }

            scriptTag.onload = () => {
                if (typeof Html5Qrcode !== 'undefined') {
                    callback();
                }
            };

            scriptTag.onerror = () => {
                console.warn('Gagal memuat html5-qrcode lokal, mencoba memuat dari unpkg...');
                const cdnTag = document.createElement('script');
                cdnTag.src = "https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js";
                cdnTag.onload = () => {
                    if (typeof Html5Qrcode !== 'undefined') {
                        callback();
                    }
                };
                document.head.appendChild(cdnTag);
            };
        }

        // Web Audio Synthesizer Beep (Bekerja di iOS & Android tanpa file audio eksternal)
        function playStudentBeep(success = true) {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                if (success) {
                    osc.type = "sine";
                    osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                    osc.frequency.exponentialRampToValueAtTime(1046.5, ctx.currentTime + 0.12); // C6
                    gain.gain.setValueAtTime(0.18, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.25);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                } else {
                    osc.type = "sawtooth";
                    osc.frequency.setValueAtTime(280, ctx.currentTime);
                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.35);
                }
            } catch (e) {
                console.warn('Audio feedback failed:', e);
            }
        }

        function showStudentFeedback(type, title, desc) {
            const box = document.getElementById('student-scan-feedback');
            const icon = document.getElementById('student-feedback-icon');
            const titleEl = document.getElementById('student-feedback-title');
            const descEl = document.getElementById('student-feedback-desc');
            if (!box) return;

            box.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-300', 'text-emerald-900', 'bg-rose-50', 'border-rose-300', 'text-rose-900');

            if (type === 'success') {
                box.classList.add('bg-emerald-50', 'border', 'border-emerald-300', 'text-emerald-900');
                icon.className = 'w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold shrink-0';
                icon.innerHTML = '<i class="fas fa-check"></i>';
                playStudentBeep(true);
            } else {
                box.classList.add('bg-rose-50', 'border', 'border-rose-300', 'text-rose-900');
                icon.className = 'w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold shrink-0';
                icon.innerHTML = '<i class="fas fa-triangle-exclamation"></i>';
                playStudentBeep(false);
            }

            titleEl.textContent = title;
            descEl.textContent = desc;

            setTimeout(() => {
                box.classList.add('hidden');
            }, 4500);
        }

        function startStudentScanner() {
            ensureHtml5Qrcode(() => {
                const qrElement = document.getElementById('student-scan-reader');
                if (!qrElement) return;

                if (studentQrScanner && isStudentScannerRunning) {
                    return;
                }

                if (!studentQrScanner) {
                    studentQrScanner = new Html5Qrcode("student-scan-reader");
                }

                const config = {
                    fps: 15,
                    qrbox: { width: 240, height: 240 },
                    aspectRatio: 1.0
                };

                const tryStartWithCamera = (camMode) => {
                    return studentQrScanner.start(
                        camMode,
                        config,
                        onStudentScanSuccess,
                        onStudentScanFailure
                    ).then(() => {
                        isStudentScannerRunning = true;
                        const restartBtn = document.getElementById('btn-student-restart-cam');
                        if (restartBtn) restartBtn.innerHTML = '<i class="fas fa-stop"></i> Jeda Kamera';
                    });
                };

                // Coba environment (kamera belakang) -> fallback user (kamera depan/webcam) -> fallback getCameras()
                tryStartWithCamera({ facingMode: studentFacingMode })
                    .catch(err => {
                        console.warn("Kamera facingMode " + studentFacingMode + " tidak aktif, mencoba kamera depan/webcam...", err);
                        return tryStartWithCamera({ facingMode: "user" });
                    })
                    .catch(err2 => {
                        console.warn("Kamera facingMode user gagal, mencari daftar perangkat kamera...", err2);
                        return Html5Qrcode.getCameras().then(cameras => {
                            if (cameras && cameras.length > 0) {
                                return tryStartWithCamera(cameras[0].id);
                            }
                            throw new Error("Tidak ditemukan kamera aktif pada perangkat ini.");
                        });
                    })
                    .catch(finalErr => {
                        console.error("Gagal memulai kamera:", finalErr);
                        isStudentScannerRunning = false;
                        showStudentFeedback('error', 'KAMERA TIDAK DAPAT DIBUKA', finalErr.message || 'Izin kamera ditolak atau kamera sedang digunakan aplikasi lain.');
                    });
            });
        }

        function stopStudentScanner() {
            if (studentQrScanner && isStudentScannerRunning) {
                studentQrScanner.stop().then(() => {
                    isStudentScannerRunning = false;
                    const restartBtn = document.getElementById('btn-student-restart-cam');
                    if (restartBtn) restartBtn.innerHTML = '<i class="fas fa-play"></i> Mulai Kamera';
                }).catch(err => {
                    console.warn("Gagal menghentikan scanner siswa:", err);
                    isStudentScannerRunning = false;
                });
            }
        }

        function onStudentScanSuccess(decodedText, decodedResult) {
            if (decodedText === lastStudentScannedText && studentScanTimer) {
                return;
            }

            lastStudentScannedText = decodedText;
            clearTimeout(studentScanTimer);
            studentScanTimer = setTimeout(() => {
                lastStudentScannedText = "";
            }, 3500);

            // Kirim payload QR yang discan ke backend Livewire
            if (window.Livewire) {
                @this.processStudentAttendanceScan(decodedText);
            }
        }

        function onStudentScanFailure(error) {
            // Silent during scanning video frames
        }

        // Listener saat modal dibuka
        window.addEventListener('open-modal', event => {
            const id = event.detail?.id || event.detail?.[0]?.id;
            if (id === 'modal-student-camera-scan') {
                setTimeout(() => {
                    startStudentScanner();
                }, 300);
            }
        });

        // Listener saat modal ditutup
        window.addEventListener('close-modal', event => {
            const id = event.detail?.id || event.detail?.[0]?.id;
            if (id === 'modal-student-camera-scan') {
                stopStudentScanner();
            }
        });

        // Tombol Ganti Kamera Depan / Belakang
        const toggleBtn = document.getElementById('btn-student-switch-cam');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                studentFacingMode = (studentFacingMode === "environment") ? "user" : "environment";
                if (studentQrScanner && isStudentScannerRunning) {
                    studentQrScanner.stop().then(() => {
                        isStudentScannerRunning = false;
                        setTimeout(() => {
                            startStudentScanner();
                        }, 250);
                    }).catch(e => {
                        isStudentScannerRunning = false;
                        startStudentScanner();
                    });
                } else {
                    startStudentScanner();
                }
            });
        }

        // Tombol Start / Stop Kamera
        const restartBtn = document.getElementById('btn-student-restart-cam');
        if (restartBtn) {
            restartBtn.addEventListener('click', function () {
                if (isStudentScannerRunning) {
                    stopStudentScanner();
                } else {
                    startStudentScanner();
                }
            });
        }

        // Tutup modal jika tombol ESC ditekan
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('modal-student-camera-scan');
                if (modal && !modal.classList.contains('hidden')) {
                    stopStudentScanner();
                    @this.closeStudentScanner();
                }
            }
        });

        // Livewire Dispatch Listeners
        window.addEventListener('student-scan-success', event => {
            const msg = event.detail?.message || event.detail?.[0]?.message || 'Presensi berhasil dicatat!';
            showStudentFeedback('success', 'PRESENSI BERHASIL!', msg);

            // Tutup modal secara mulus setelah 1.8 detik
            setTimeout(() => {
                stopStudentScanner();
                @this.closeStudentScanner();
            }, 1800);
        });

        window.addEventListener('student-scan-error', event => {
            const msg = event.detail?.message || event.detail?.[0]?.message || 'QRCODE tidak valid atau tidak sesuai!';
            showStudentFeedback('error', 'PRESENSI GAGAL', msg);
        });
    })();
</script>
