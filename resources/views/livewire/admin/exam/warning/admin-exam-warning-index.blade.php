<div>
    <div class="flex flex-col items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-3xl p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Peraturan Ujian</h1>
                <p class="mt-2 text-gray-500">Harap membaca dan menyetujui peraturan berikut sebelum memulai ujian</p>
            </div>

            <div class="space-y-6">
                <div class="p-4 rounded-lg bg-orange-50">
                    <h2 class="mb-2 font-semibold text-[color:var(--primary)]">
                        Informasi Ujian</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm text-[color:var(--primary)]">
                        <div>
                            <p><span class="font-medium">Modul:</span>
                                {{ $userTimetable->timetable->module->name ?? '-' }}</p>
                            <p><span class="font-medium">Durasi:</span>
                                {{ $userTimetable->timetable->module->duration }} Menit</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Jumlah Soal:</span> {{$userTimetable->userModuleQuestions->count()}} Soal</p>
                            <p><span class="font-medium">Jenis Ujian:</span>
                                {{ $userTimetable->timetable->module->questionType->name ?? '-' }}
                            </p>
                            {{-- <p><span class="font-medium">Semester:</span> Ganjil 2024/2025</p>
                            <p><span class="font-medium">Dosen:</span> Dr. Jane Doe</p> --}}
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="font-semibold text-gray-900">Peraturan yang harus dipatuhi:</h2>
                    <ul class="space-y-3 text-gray-600">
                        @forelse ($regulations as $regulation)
                            <li class="flex items-start">
                                <i
                                    class="{{ $regulation['type'] == 'licensing' ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-triangle-exclamation text-red-500' }} w-5 h-5 mr-2 mt-1">
                                </i>
                                <span>{{ $regulation['description'] }}</span>
                            </li>
                        @empty
                            <li class="flex items-start">
                                <i class="fa-solid fa-triangle-exclamation text-red-500 w-5 h-5 mr-2 mt-1"></i>
                                <span>Dilarang keras membuka tab/aplikasi lain selama ujian berlangsung</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-triangle-exclamation text-red-500 w-5 h-5 mr-2 mt-1"></i>
                                <span>Dilarang mengambil screenshot atau merekam layar</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-circle-check text-green-500 w-5 h-5 mr-2 mt-1"></i>
                                <span>Kamera harus menyala dan wajah harus terlihat selama ujian</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-circle-check text-green-500 w-5 h-5 mr-2 mt-1"></i>
                                <span>Pastikan koneksi internet stabil sebelum memulai ujian</span>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Camera Check -->
                <div class="p-4 border rounded-lg">
                    <h3 class="font-medium text-gray-900 mb-2">Periksa Kamera</h3>

                    <div class="mb-3" wire:ignore>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
                        <select id="videoSource" wire:model="camera_device_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Mencari kamera...</option>
                        </select>
                    </div>

                    <div class="mb-3 overflow-hidden bg-gray-900 rounded-lg aspect-video">
                        <video id="cameraPreview" autoplay playsinline class="object-cover w-full h-full"></video>
                    </div>
                    <p id="cameraStatus" class="text-sm text-gray-500">Pastikan wajah Anda terlihat jelas pada kamera
                    </p>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', async () => {
                        const videoElement = document.getElementById('cameraPreview');
                        const videoSelect = document.getElementById('videoSource');
                        const statusElement = document.getElementById('cameraStatus');
                        let currentStream = null;

                        // Cek apakah browser mengizinkan akses kamera (hanya HTTPS atau localhost)
                        if (!window.isSecureContext || !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            if (videoSelect) {
                                videoSelect.innerHTML = '<option>Tidak tersedia (perlu HTTPS)</option>';
                            }
                            if (statusElement) {
                                statusElement.textContent = '⚠️ Kamera tidak dapat diakses melalui koneksi HTTP. Ujian tetap dapat dilanjutkan.';
                                statusElement.className = 'text-sm text-yellow-600';
                            }
                            return; // hentikan eksekusi, tidak perlu lanjut
                        }

                        async function getCameras() {
                            try {
                                await navigator.mediaDevices.getUserMedia({ video: true }); // Request permission first
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

                                // Select the first one by default if not set
                                if (videoDevices.length > 0) {
                                    // Trigger change to start stream
                                    startStream(videoDevices[0].deviceId);
                                    // Update Livewire if needed (though wire:model does it on change)
                                    @this.set('camera_device_id', videoDevices[0].deviceId);
                                }
                            } catch (err) {
                                console.error('Error getting cameras:', err);
                                if (statusElement) {
                                    statusElement.textContent = 'Gagal mendeteksi kamera: ' + err.message;
                                    statusElement.className = 'text-sm text-red-500';
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
                                    statusElement.textContent = 'Kamera aktif. Silakan lanjutkan.';
                                    statusElement.className = 'text-sm text-green-500';
                                }
                            } catch (err) {
                                console.error('Error starting stream:', err);
                                if (statusElement) {
                                    statusElement.textContent = 'Gagal memulai kamera: ' + err.message;
                                    statusElement.className = 'text-sm text-red-500';
                                }
                            }
                        }

                        videoSelect.onchange = () => {
                            startStream(videoSelect.value);
                            @this.set('camera_device_id', videoSelect.value);
                        };

                        await getCameras();
                    });
                </script>

                <!-- Consent Checkbox -->
                <div class="flex items-start mt-6 space-x-3">
                    <input type="checkbox" id="consent" class="mt-1">
                    <label for="consent" class="text-sm text-gray-600">
                        Saya telah membaca dan menyetujui semua peraturan ujian di atas. Saya mengerti bahwa pelanggaran
                        terhadap peraturan ini dapat mengakibatkan pembatalan nilai ujian.
                    </label>
                </div>

                <!-- Action Button -->
                <div class="flex justify-center mt-8">
                    <button id="startExam" disabled wire:click="confirmStartUjian()"
                        class="px-6 py-3 font-medium text-white transition-colors bg-primary hover:bg-primary rounded-lg hover:bg-primary disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Mulai Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>