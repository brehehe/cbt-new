<div>
    <div class="flex flex-col items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-3xl p-8 bg-white shadow-sm rounded-xl">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Peraturan Ujian</h1>
                <p class="mt-2 text-gray-500">Harap membaca dan menyetujui peraturan berikut sebelum memulai ujian</p>
            </div>

            <div class="space-y-6">
                <div class="p-4 rounded-lg bg-orange-50">
                    <h2
                        class="mb-2 font-semibold {{ config('app.name_slug') === 'ups_tegal' ? 'text-blue-800' : 'text-orange-800' }}">
                        Informasi Ujian</h2>
                    <div
                        class="grid grid-cols-2 gap-4 text-sm {{ config('app.name_slug') === 'ups_tegal' ? 'text-blue-700' : 'text-orange-700' }}">
                        <div>
                            <p><span class="font-medium">Modul:</span>
                                {{ $userTimetable->timetable->module->name ?? '-' }}</p>
                            <p><span class="font-medium">Durasi:</span>
                                {{ $userTimetable->timetable->module->duration }} Menit</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Jumlah Soal:</span> 40 Soal</p>
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
                    <h3 class="mb-3 font-medium text-gray-900">Periksa Kamera</h3>
                    <div class="mb-3 overflow-hidden bg-gray-900 rounded-lg aspect-video">
                        <video id="cameraPreview" autoplay class="object-cover w-full h-full"></video>
                    </div>
                    <p id="cameraStatus" class="text-sm text-gray-500">Pastikan wajah Anda terlihat jelas pada kamera
                    </p>
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
                        class="px-6 py-3 font-medium text-white transition-colors bg-[#f58634] rounded-lg hover:bg-[#f58634] disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Mulai Ujian
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
