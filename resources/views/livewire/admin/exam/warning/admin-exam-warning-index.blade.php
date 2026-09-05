<div class="min-h-screen bg-slate-100/70 flex flex-col items-center justify-center p-3 sm:p-6">
    <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-5 sm:p-8 bg-gradient-to-r from-blue-900 to-indigo-900 text-white text-center relative">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md mb-3">
                <i class="fa-solid fa-shield-halved text-2xl text-blue-300"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight">Peraturan & Tata Tertib Ujian</h1>
            <p class="mt-1 text-xs sm:text-sm text-blue-100/80">Harap membaca dan menyetujui ketentuan berikut sebelum memulai ujian</p>
        </div>

        <div class="p-4 sm:p-8 space-y-5 sm:space-y-6">
            <!-- Informasi Ujian -->
            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-100">
                <h2 class="text-xs font-bold uppercase tracking-wider text-blue-900 mb-2.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-info text-blue-600"></i> Informasi Ujian
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4 text-xs sm:text-sm text-slate-700">
                    <div class="space-y-1">
                        <p class="flex items-center justify-between sm:justify-start gap-2">
                            <span class="text-gray-500 font-medium">Modul:</span>
                            <span class="font-bold text-gray-900 text-right sm:text-left">{{ $userTimetable->timetable->module->name ?? '-' }}</span>
                        </p>
                        <p class="flex items-center justify-between sm:justify-start gap-2">
                            <span class="text-gray-500 font-medium">Durasi:</span>
                            <span class="font-bold text-blue-700">{{ $userTimetable->timetable->module->duration }} Menit</span>
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="flex items-center justify-between sm:justify-start gap-2">
                            <span class="text-gray-500 font-medium">Jumlah Soal:</span>
                            <span class="font-bold text-indigo-700 px-2 py-0.5 bg-indigo-100 rounded-md">{{ $userTimetable->userModuleQuestions->count() }} Soal</span>
                        </p>
                        <p class="flex items-center justify-between sm:justify-start gap-2">
                            <span class="text-gray-500 font-medium">Tipe Ujian:</span>
                            <span class="font-semibold text-gray-800">{{ $userTimetable->timetable->module->questionType->name ?? '-' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Peraturan -->
            <div class="space-y-3">
                <h2 class="text-xs sm:text-sm font-bold text-gray-800 uppercase tracking-wider">Peraturan yang wajib dipatuhi:</h2>
                <ul class="space-y-2.5 text-xs sm:text-sm text-gray-600">
                    @forelse ($regulations as $regulation)
                        <li class="flex items-start gap-2.5 p-2 rounded-lg bg-gray-50 border border-gray-100">
                            <i class="{{ $regulation['type'] == 'licensing' ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-triangle-exclamation text-amber-500' }} mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">{{ $regulation['description'] }}</span>
                        </li>
                    @empty
                        <li class="flex items-start gap-2.5 p-2 rounded-lg bg-red-50/50 border border-red-100">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">Dilarang keras membuka tab, jendela, atau aplikasi lain selama ujian berlangsung.</span>
                        </li>
                        <li class="flex items-start gap-2.5 p-2 rounded-lg bg-red-50/50 border border-red-100">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">Dilarang mengambil screenshot atau merekam tampilan layar.</span>
                        </li>
                        <li class="flex items-start gap-2.5 p-2 rounded-lg bg-green-50/50 border border-green-100">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">Kamera harus menyala dan wajah terlihat jelas selama sesi ujian.</span>
                        </li>
                        <li class="flex items-start gap-2.5 p-2 rounded-lg bg-green-50/50 border border-green-100">
                            <i class="fa-solid fa-circle-check text-green-500 mt-0.5 flex-shrink-0"></i>
                            <span class="leading-relaxed">Pastikan koneksi internet stabil sebelum menekan tombol Mulai Ujian.</span>
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Camera Check -->
            @if ($userTimetable->timetable->is_camera)
            <div class="p-3.5 sm:p-4 border border-gray-200 rounded-xl bg-gray-50/50 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs sm:text-sm font-bold text-gray-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-camera text-blue-600"></i> Periksa Kamera
                    </h3>
                </div>

                <div wire:ignore>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Perangkat Kamera:</label>
                    <select id="videoSource" wire:model="camera_device_id"
                        class="w-full text-xs sm:text-sm border-gray-300 rounded-lg shadow-2xs focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Mencari kamera...</option>
                    </select>
                </div>

                <div class="overflow-hidden bg-gray-900 rounded-xl aspect-video max-h-56 mx-auto relative shadow-inner">
                    <video id="cameraPreview" autoplay playsinline class="object-cover w-full h-full"></video>
                </div>
                <p id="cameraStatus" class="text-xs text-gray-500 text-center font-medium">Pastikan wajah Anda terlihat jelas pada layar kamera di atas.</p>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', async () => {
                    const videoElement = document.getElementById('cameraPreview');
                    const videoSelect = document.getElementById('videoSource');
                    const statusElement = document.getElementById('cameraStatus');
                    let currentStream = null;

                    if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        if (videoSelect) {
                            videoSelect.innerHTML = '<option>Tidak tersedia (perlu HTTPS)</option>';
                        }
                        if (statusElement) {
                            statusElement.textContent = '⚠️ Kamera tidak dapat diakses melalui koneksi HTTP. Ujian tetap dapat dilanjutkan.';
                            statusElement.className = 'text-xs text-yellow-600';
                        }
                        return;
                    }

                    async function getCameras() {
                        try {
                            await navigator.mediaDevices.getUserMedia({ video: true });
                            const devices = await navigator.mediaDevices.enumerateDevices();
                            const videoDevices = devices.filter(device => device.kind === 'videoinput');

                            videoSelect.innerHTML = '';

                            if (videoDevices.length === 0) {
                                const option = document.createElement('option');
                                option.text = 'Tidak ada kamera ditemukan';
                                videoSelect.appendChild(option);
                                return;
                            }

                            videoDevices.forEach((device, index) => {
                                const option = document.createElement('option');
                                option.value = device.deviceId;
                                option.text = device.label || `Camera ${index + 1}`;
                                videoSelect.appendChild(option);
                            });

                            if (videoDevices.length > 0) {
                                startStream(videoDevices[0].deviceId);
                                @this.set('camera_device_id', videoDevices[0].deviceId);
                            }
                        } catch (err) {
                            console.error('Error getting cameras:', err);
                            if (statusElement) {
                                statusElement.textContent = 'Gagal mendeteksi kamera: ' + err.message;
                                statusElement.className = 'text-xs text-red-500';
                            }
                        }
                    }

                    async function startStream(uDeviceId) {
                        if (currentStream) {
                            currentStream.getTracks().forEach(track => track.stop());
                        }

                        const constraints = {
                            video: { deviceId: uDeviceId ? { exact: uDeviceId } : undefined }
                        };

                        try {
                            const stream = await navigator.mediaDevices.getUserMedia(constraints);
                            currentStream = stream;
                            if (videoElement) videoElement.srcObject = stream;
                            if (statusElement) {
                                statusElement.textContent = '✓ Kamera aktif. Silakan lanjutkan ke ujian.';
                                statusElement.className = 'text-xs text-green-600 font-bold';
                            }
                        } catch (err) {
                            console.error('Error starting stream:', err);
                            if (statusElement) {
                                statusElement.textContent = 'Gagal memulai kamera: ' + err.message;
                                statusElement.className = 'text-xs text-red-500';
                            }
                        }
                    }

                    videoSelect.onchange = () => {
                        startStream(videoSelect.value);
                        @this.set('camera_device_id', videoSelect.value);
                    };

                    await getCameras();

                    window.addEventListener('beforeunload', () => {
                        if (currentStream) {
                            currentStream.getTracks().forEach(track => track.stop());
                            currentStream = null;
                        }
                    });
                });
            </script>
            @endif

            <!-- Consent Checkbox -->
            <label for="consent" class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 bg-gray-50/70 cursor-pointer hover:bg-gray-100/70 transition select-none">
                <input type="checkbox" id="consent" class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer flex-shrink-0">
                <span class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                    Saya telah membaca dan menyetujui semua peraturan ujian di atas. Saya memahami bahwa setiap pelanggaran akan dicatat otomatis oleh sistem pengawas.
                </span>
            </label>

            <!-- Action Button -->
            <div class="flex justify-center pt-2">
                <button id="startExam" disabled wire:click="confirmStartUjian()"
                    class="w-full sm:w-auto px-8 py-3.5 text-sm sm:text-base font-bold text-white transition-all bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md hover:shadow-lg disabled:bg-gray-300 disabled:shadow-none disabled:cursor-not-allowed cursor-pointer flex items-center justify-center gap-2">
                    <span>Mulai Ujian Sekarang</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </div>
</div>