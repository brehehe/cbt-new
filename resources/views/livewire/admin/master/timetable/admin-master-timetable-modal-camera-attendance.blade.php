<!-- Modal Scanner Kamera Absensi Responsif (Mobile & Desktop) -->
<div wire:ignore.self id="modal-camera-attendance"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-3 sm:p-5 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[92vh] flex flex-col border border-slate-200 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600">
                    <i class="fas fa-camera text-xl"></i>
                </div>
                <div>
                    <h5 class="modal-title font-bold text-lg text-slate-800" id="modalCameraAttendanceLabel">
                        Scan Kehadiran QRCODE
                    </h5>
                    <p class="text-xs text-slate-500">
                        Arahkan kamera ke QRCODE kartu peserta atau layar HP {{ student_label() }}
                    </p>
                </div>
            </div>
            <button type="button" wire:click="closeAttendanceScanner" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

            <!-- Modal Body -->
            <div class="modal-body p-6 space-y-5">
                <!-- Info Jadwal & Pilihan Detail -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <div class="text-xs text-slate-400 uppercase font-bold tracking-wider">Jadwal Terpilih</div>
                        <div class="font-bold text-slate-800 text-base mt-0.5">
                            {{ $selected_scanner_timetable?->name ?? 'Semua Jadwal' }}
                        </div>
                        <div class="text-xs text-indigo-600 font-semibold mt-0.5">
                            Kelas: {{ $selected_scanner_timetable?->classmate?->name ?? '-' }} ({{ $selected_scanner_timetable?->classmate?->classmateStudents?->count() ?? 0 }} {{ student_label() }})
                        </div>
                    </div>

                    @if($selected_scanner_timetable && $selected_scanner_timetable->timetableDetails->isNotEmpty())
                        <div class="w-full sm:w-auto">
                            <label class="block text-[11px] text-slate-500 mb-1 font-semibold">Filter Detail Kegiatan:</label>
                            <select class="form-select text-xs bg-white border-slate-300 text-slate-800 rounded-xl focus:border-amber-500" wire:model.live="scan_detail_id" id="scannerDetailSelect">
                                <option value="">Semua Detail Jadwal</option>
                                @foreach($selected_scanner_timetable->timetableDetails as $d)
                                    <option value="{{ $d->id }}">
                                        [{{ strtoupper($d->type) }}] {{ $d->examRoom->name ?? 'Ruang' }} - {{ $d->examSession->name ?? 'Sesi' }} (QR: {{ $d->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Camera Viewport Section -->
                <div class="relative bg-slate-950 rounded-2xl overflow-hidden border-2 border-slate-200 flex flex-col items-center justify-center min-h-[320px] max-h-[420px]">
                    <div id="qr-reader" class="w-full h-full min-h-[300px]"></div>

                    <!-- Target Scan Overlay -->
                    <div id="scan-overlay-target" class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                        <div class="w-56 h-56 sm:w-64 sm:h-64 border-2 border-amber-400/90 rounded-2xl relative animate-pulse shadow-[0_0_20px_rgba(251,191,36,0.3)]">
                            <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-amber-400 rounded-tl-lg"></div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-amber-400 rounded-tr-lg"></div>
                            <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-amber-400 rounded-bl-lg"></div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-amber-400 rounded-br-lg"></div>
                            <div class="w-full h-0.5 bg-red-500/90 absolute top-1/2 left-0 shadow-[0_0_10px_red]"></div>
                        </div>
                        <span class="text-[11px] text-amber-900 font-bold mt-3 bg-amber-100/95 border border-amber-300 px-3.5 py-1 rounded-full shadow-sm">
                            Posisikan QRCODE di dalam kotak
                        </span>
                    </div>

                    <!-- Camera Control Bar -->
                    <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2 z-10 px-4">
                        <button type="button" id="btn-toggle-camera" class="px-3.5 py-1.5 bg-white/95 hover:bg-white text-slate-800 rounded-xl text-xs font-bold shadow-md border border-slate-200 transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-camera-rotate text-slate-600"></i> Ganti Kamera
                        </button>
                        <button type="button" id="btn-restart-camera" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl text-xs font-bold shadow-md transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-play"></i> Mulai Kamera
                        </button>
                    </div>
                </div>

                <!-- Toast Notifikasi Scan Realtime -->
                <div id="scan-feedback-box" class="hidden p-4 rounded-2xl transition-all duration-300 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div id="scan-feedback-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold"></div>
                        <div class="flex-1">
                            <div id="scan-feedback-title" class="font-bold text-sm"></div>
                            <div id="scan-feedback-desc" class="text-xs"></div>
                        </div>
                    </div>
                </div>

                <!-- Log Hasil Scan Sesi Ini -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-clock-rotate-left text-amber-500"></i> Riwayat Scan Terbaru
                        </span>
                        <span class="text-xs text-slate-500 font-medium">
                            {{ count($recentScanned) }} Terdata
                        </span>
                    </div>

                    <div class="space-y-2 max-h-36 overflow-y-auto pr-1" id="recent-scanned-container">
                        @forelse($recentScanned as $scan)
                            <div class="p-2.5 bg-white rounded-xl border border-slate-200 flex items-center justify-between text-xs animate-fade-in shadow-xs">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 font-bold flex items-center justify-center border border-emerald-200">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $scan['name'] }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $scan['username'] }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-0.5 rounded-full font-bold text-[10px] bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        {{ $scan['status'] }}
                                    </span>
                                    <div class="text-[10px] text-slate-400 mt-0.5 font-mono">{{ $scan['time'] }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-xs text-slate-400 italic">
                                Belum ada peserta yang discan pada sesi ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-t border-slate-100 px-6 py-3.5 bg-slate-50/80 flex justify-between items-center">
                <div class="text-xs text-slate-600 flex items-center gap-1.5 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    Scanner aktif & siap memindai
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition shadow-xs cursor-pointer" wire:click="openAttendanceRecap('{{ $scan_timetable_id }}', '{{ $scan_detail_id }}')">
                        <i class="fas fa-list-check mr-1 text-indigo-600"></i> Rekap Kehadiran
                    </button>
                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition cursor-pointer" wire:click="closeAttendanceScanner">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
</div>

<!-- Script HTML5-QRCode Scanner dengan Web Audio Beep -->
<script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let html5QrCode = null;
        let isScannerRunning = false;
        let currentFacingMode = "environment"; // Kamera belakang by default untuk smartphone
        let lastScannedText = "";
        let scanThrottleTimer = null;

        // Audio Context Synthesizer untuk Beep Sukses (Bekerja di iOS & Android tanpa perlu file audio mp3)
        function playBeep(success = true) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                if (success) {
                    // Beep nada tinggi ceria
                    osc.type = "sine";
                    osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.18);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.18);
                } else {
                    // Beep nada rendah peringatan
                    osc.type = "sawtooth";
                    osc.frequency.setValueAtTime(300, ctx.currentTime);
                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.3);
                }
            } catch (e) {
                console.warn('Audio feedback failed:', e);
            }
        }

        function showFeedback(type, title, desc) {
            const box = document.getElementById('scan-feedback-box');
            const icon = document.getElementById('scan-feedback-icon');
            const titleEl = document.getElementById('scan-feedback-title');
            const descEl = document.getElementById('scan-feedback-desc');
            if (!box) return;

            box.classList.remove('hidden', 'bg-emerald-50', 'border-emerald-300', 'text-emerald-900', 'bg-rose-50', 'border-rose-300', 'text-rose-900', 'bg-emerald-950/80', 'border-emerald-500', 'text-emerald-200', 'bg-red-950/80', 'border-red-500', 'text-red-200');

            if (type === 'success') {
                box.classList.add('bg-emerald-50', 'border', 'border-emerald-300', 'text-emerald-900');
                icon.className = 'w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold';
                icon.innerHTML = '<i class="fas fa-check"></i>';
                playBeep(true);
            } else {
                box.classList.add('bg-rose-50', 'border', 'border-rose-300', 'text-rose-900');
                icon.className = 'w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold';
                icon.innerHTML = '<i class="fas fa-triangle-exclamation"></i>';
                playBeep(false);
            }

            titleEl.textContent = title;
            descEl.textContent = desc;

            setTimeout(() => {
                box.classList.add('hidden');
            }, 4500);
        }

        // Helper untuk memastikan Html5Qrcode ter-load
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

        function startScanner() {
            ensureHtml5Qrcode(() => {
                const qrElement = document.getElementById('qr-reader');
                if (!qrElement) return;

                if (html5QrCode && isScannerRunning) {
                    return;
                }

                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("qr-reader");
                }

                const config = {
                    fps: 15,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };

                const tryStartWithCamera = (camMode) => {
                    return html5QrCode.start(
                        camMode,
                        config,
                        onScanSuccess,
                        onScanFailure
                    ).then(() => {
                        isScannerRunning = true;
                        const restartBtn = document.getElementById('btn-restart-camera');
                        if (restartBtn) restartBtn.innerHTML = '<i class="fas fa-stop"></i> Jeda Kamera';
                    });
                };

                tryStartWithCamera({ facingMode: currentFacingMode })
                    .catch(err => {
                        console.warn("Kamera facingMode " + currentFacingMode + " tidak aktif, mencoba front/webcam...", err);
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
                        isScannerRunning = false;
                        showFeedback('error', 'KAMERA TIDAK DAPAT DIBUKA', finalErr.message || 'Izin kamera ditolak atau kamera sedang digunakan aplikasi lain.');
                    });
            });
        }

        function stopScanner() {
            if (html5QrCode && isScannerRunning) {
                html5QrCode.stop().then(() => {
                    isScannerRunning = false;
                    const restartBtn = document.getElementById('btn-restart-camera');
                    if (restartBtn) restartBtn.innerHTML = '<i class="fas fa-play"></i> Mulai Kamera';
                }).catch(err => {
                    console.error("Gagal menghentikan kamera:", err);
                });
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Hindari spam scanning jika QR yang sama terdeteksi berulang dalam 3 detik
            if (decodedText === lastScannedText && scanThrottleTimer) {
                return;
            }

            lastScannedText = decodedText;
            clearTimeout(scanThrottleTimer);
            scanThrottleTimer = setTimeout(() => {
                lastScannedText = "";
            }, 3000);

            // Kirim data scan ke Livewire
            const detailSelect = document.getElementById('scannerDetailSelect');
            const selectedDetailId = detailSelect ? detailSelect.value : null;

            @this.processAttendanceScan(decodedText, selectedDetailId);
        }

        function onScanFailure(error) {
            // Silent error during frame scans
        }

        // Event listener saat modal kamera dibuka
        window.addEventListener('open-modal', event => {
            const id = event.detail.id || (event.detail[0] && event.detail[0].id);
            if (id === 'modal-camera-attendance') {
                setTimeout(() => {
                    startScanner();
                }, 400);
            }
        });

        // Event listener saat modal ditutup
        window.addEventListener('close-modal', event => {
            const id = event.detail.id || (event.detail[0] && event.detail[0].id);
            if (id === 'modal-camera-attendance') {
                stopScanner();
            }
        });

        // Toggle Switch Kamera Depan/Belakang
        const toggleBtn = document.getElementById('btn-toggle-camera');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
                stopScanner();
                setTimeout(() => {
                    startScanner();
                }, 300);
            });
        }

        // Toggle Start/Stop
        const restartBtn = document.getElementById('btn-restart-camera');
        if (restartBtn) {
            restartBtn.addEventListener('click', function () {
                if (isScannerRunning) {
                    stopScanner();
                } else {
                    startScanner();
                }
            });
        }

        // Livewire Dispatch Listeners
        window.addEventListener('scan-success', event => {
            const data = event.detail.student || (event.detail[0] && event.detail[0].student);
            const msg = event.detail.message || (event.detail[0] && event.detail[0].message);
            showFeedback('success', 'PRESENSI BERHASIL!', `${data?.name} (${data?.username}) telah tercatat hadir.`);
        });

        window.addEventListener('scan-error', event => {
            const msg = event.detail.message || (event.detail[0] && event.detail[0].message);
            showFeedback('error', 'ABSENSI DITOLAK / GAGAL', msg);
        });
    });
</script>
