<div>
    <div class="flex flex-col items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-3xl p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Peraturan Ujian</h1>
                <p class="mt-2 text-gray-500">Harap membaca dan menyetujui peraturan berikut sebelum memulai ujian</p>
            </div>

            <div class="space-y-6">
                <div class="p-4 rounded-lg bg-blue-50">
                    <h2 class="mb-2 font-semibold text-blue-800">Informasi Ujian</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <p><span class="font-medium">Mata Kuliah:</span> Matematika Dasar</p>
                            <p><span class="font-medium">Durasi:</span> 90 Menit</p>
                            <p><span class="font-medium">Jumlah Soal:</span> 40 Soal</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Jenis Ujian:</span> UTS</p>
                            <p><span class="font-medium">Semester:</span> Ganjil 2024/2025</p>
                            <p><span class="font-medium">Dosen:</span> Dr. Jane Doe</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="font-semibold text-gray-900">Peraturan yang harus dipatuhi:</h2>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Dilarang keras membuka tab/aplikasi lain selama ujian berlangsung</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Dilarang mengambil screenshot atau merekam layar</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Kamera harus menyala dan wajah harus terlihat selama ujian</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="flex-shrink-0 w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Pastikan koneksi internet stabil sebelum memulai ujian</span>
                        </li>
                    </ul>
                </div>

                <!-- Camera Check -->
                <div class="p-4 border rounded-lg">
                    <h3 class="mb-3 font-medium text-gray-900">Periksa Kamera</h3>
                    <div class="mb-3 overflow-hidden bg-gray-900 rounded-lg aspect-video">
                        <video id="cameraPreview" autoplay class="object-cover w-full h-full"></video>
                    </div>
                    <p class="text-sm text-gray-500">Pastikan wajah Anda terlihat jelas pada kamera</p>
                </div>

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
                        class="px-6 py-3 font-medium text-white transition-colors bg-blue-500 rounded-lg hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Mulai Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
