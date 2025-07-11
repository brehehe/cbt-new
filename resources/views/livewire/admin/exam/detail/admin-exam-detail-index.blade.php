<div>
    <header class="p-2 text-white bg-blue-800 shadow-lg sm:p-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                <h1 class="text-lg font-bold sm:text-xl">Computer Based Test</h1>
                <div class="px-2 py-1 bg-blue-700 rounded sm:px-3">
                    <span class="text-xs sm:text-sm">Modul: {{ $userTimetable->timetable->module->name ?? '-' }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                <div class="text-center sm:text-right">
                    <div class="text-xs sm:text-sm opacity-90">Waktu Tersisa</div>
                    <div class="font-mono text-base font-bold text-yellow-300 sm:text-lg" id="countdown">01:59:45
                    </div>
                </div>
                <button wire:click='confirmFinishExam'
                    class="px-3 py-2 text-xs font-medium transition-colors bg-red-600 rounded sm:px-4 sm:text-sm hover:bg-red-700">
                    Selesai Ujian
                </button>
            </div>
        </div>
    </header>
    <!-- Mobile Menu Toggle Button -->
    <div class="p-4 bg-white border-b border-gray-200 lg:hidden">
        <div class="flex items-center justify-between">
            <button id="toggleLeftSidebar" class="flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Navigasi Soal
            </button>
            <button id="toggleRightSidebar" class="flex items-center text-blue-600 hover:text-blue-800">
                Profil & Camera
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </button>
        </div>
    </div>

    <div class="relative flex">
        <!-- Sidebar Kiri - Navigasi Soal -->
        <div id="leftSidebar"
            class="fixed z-30 h-full overflow-y-auto transition-transform duration-300 ease-in-out transform -translate-x-full bg-white border-r border-gray-200 shadow-sm lg:relative w-80 lg:w-80 lg:h-auto lg:translate-x-0">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden bg-blue-50">
                <h3 class="font-semibold text-blue-800">Navigasi Soal</h3>
                <button id="closeLeftSidebar" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Info Ujian -->
            <div class="p-4 border-b border-gray-200 bg-blue-50">
                <h3 class="hidden mb-2 font-semibold text-blue-800 lg:block">Navigasi Soal</h3>
                <div class="text-sm text-gray-600">
                    <div>Total: 50 soal</div>
                    <div class="flex flex-wrap gap-2 mt-2 lg:space-x-4 lg:flex-nowrap">
                        <span class="text-xs text-green-600 lg:text-sm">Dijawab: 23</span>
                        <span class="text-xs text-yellow-600 lg:text-sm">Ditandai: 5</span>
                        <span class="text-xs text-red-600 lg:text-sm">Belum: 22</span>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="p-4 border-b border-gray-200">
                <div class="mb-2 text-xs text-gray-500">Keterangan:</div>
                <div class="grid grid-cols-4 gap-2 text-xs">
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-green-500 rounded"></div>
                        <span>Dijawab</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-yellow-500 rounded"></div>
                        <span>Ditandai</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-blue-500 rounded"></div>
                        <span>Aktif</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-gray-300 rounded"></div>
                        <span>Belum</span>
                    </div>
                </div>
            </div>

            <!-- Grid Nomor Soal -->
            <div class="p-4 overflow-y-auto" style="height: calc(100vh - 350px); min-height: 300px;">
                <div class="grid grid-cols-7 gap-2">
                    <!-- Soal yang sudah dijawab -->
                    <button
                        class="w-8 h-8 text-xs font-medium text-white transition-colors bg-green-500 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-green-600">1</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-white transition-colors bg-green-500 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-green-600">2</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-white transition-colors bg-yellow-500 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-yellow-600">3</button>

                    <!-- Soal aktif -->
                    <button
                        class="w-8 h-8 text-xs font-medium text-white bg-blue-600 rounded lg:w-8 lg:h-8 lg:text-sm ring-2 ring-blue-300">4</button>

                    <!-- Soal belum dijawab -->
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">5</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">6</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">7</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">8</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">9</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">10</button>

                    <!-- Lanjutkan untuk soal 11-50 -->
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">11</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">12</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-white transition-colors bg-green-500 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-green-600">13</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-white transition-colors bg-yellow-500 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-yellow-600">14</button>
                    <button
                        class="w-8 h-8 text-xs font-medium text-gray-700 transition-colors bg-gray-300 rounded lg:w-8 lg:h-8 lg:text-sm hover:bg-gray-400">15</button>
                    <!-- Tambahkan nomor 16-50 jika diperlukan -->
                </div>
            </div>
        </div>

        <!-- Overlay untuk mobile -->
        <div id="overlay" class="fixed inset-0 z-20 hidden bg-black bg-opacity-50 lg:hidden"></div>

        <!-- Konten Tengah - Area Soal -->
        <div class="flex flex-col flex-1 min-h-screen bg-white">
            <!-- Header Soal -->
            <div class="p-4 border-b border-gray-200 lg:p-6 bg-gray-50">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Soal No. 4</h2>
                    <button
                        class="flex items-center justify-center text-yellow-600 transition-colors sm:justify-start hover:text-yellow-700">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        Tandai Soal
                    </button>
                </div>
            </div>

            <!-- Konten Soal -->
            <div class="flex-1 p-4 overflow-y-auto lg:p-6">
                <div class="">
                    <!-- Pertanyaan -->
                    <div class="mb-6">
                        <p class="text-base leading-relaxed text-gray-800 lg:text-lg">
                            Dalam konteks pemrograman web, apa yang dimaksud dengan <strong>responsive
                                design</strong> dan mengapa hal ini penting dalam pengembangan website modern?
                        </p>
                    </div>

                    <!-- Pilihan Jawaban -->
                    <div class="space-y-4">
                        <label
                            class="flex items-start p-3 transition-all border border-gray-200 rounded-lg cursor-pointer lg:p-4 hover:bg-blue-50 hover:border-blue-300">
                            <input type="radio" name="answer" value="a"
                                class="flex-shrink-0 mt-1 mr-3 text-blue-600 lg:mr-4">
                            <div>
                                <span class="font-medium text-blue-800">A.</span>
                                <span class="ml-2 text-sm text-gray-700 lg:text-base">Responsive design adalah
                                    teknik untuk membuat website yang hanya dapat diakses melalui desktop</span>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 transition-all border border-gray-200 rounded-lg cursor-pointer lg:p-4 hover:bg-blue-50 hover:border-blue-300">
                            <input type="radio" name="answer" value="b"
                                class="flex-shrink-0 mt-1 mr-3 text-blue-600 lg:mr-4">
                            <div>
                                <span class="font-medium text-blue-800">B.</span>
                                <span class="ml-2 text-sm text-gray-700 lg:text-base">Responsive design adalah
                                    pendekatan desain web yang membuat halaman web dapat menyesuaikan diri dengan
                                    berbagai ukuran layar dan perangkat</span>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 transition-all border border-gray-200 rounded-lg cursor-pointer lg:p-4 hover:bg-blue-50 hover:border-blue-300">
                            <input type="radio" name="answer" value="c"
                                class="flex-shrink-0 mt-1 mr-3 text-blue-600 lg:mr-4">
                            <div>
                                <span class="font-medium text-blue-800">C.</span>
                                <span class="ml-2 text-sm text-gray-700 lg:text-base">Responsive design adalah
                                    teknik untuk membuat website loading lebih cepat</span>
                            </div>
                        </label>

                        <label
                            class="flex items-start p-3 transition-all border border-gray-200 rounded-lg cursor-pointer lg:p-4 hover:bg-blue-50 hover:border-blue-300">
                            <input type="radio" name="answer" value="d"
                                class="flex-shrink-0 mt-1 mr-3 text-blue-600 lg:mr-4">
                            <div>
                                <span class="font-medium text-blue-800">D.</span>
                                <span class="ml-2 text-sm text-gray-700 lg:text-base">Responsive design adalah
                                    metode untuk mengamankan website dari serangan hacker</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer Navigasi Soal -->
            <div class="p-4 border-t border-gray-200 lg:p-6 bg-gray-50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <button
                        class="flex items-center justify-center order-2 px-4 py-2 text-blue-600 transition-colors lg:justify-start hover:text-blue-700 lg:order-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                        Soal Sebelumnya
                    </button>

                    <div class="flex flex-col order-1 gap-3 sm:flex-row lg:order-2">
                        <button
                            class="px-4 py-2 text-white transition-colors bg-blue-600 rounded lg:px-6 hover:bg-blue-700">
                            Simpan Jawaban
                        </button>
                        <button
                            class="px-4 py-2 text-gray-700 transition-colors bg-gray-200 rounded lg:px-6 hover:bg-gray-300">
                            Lewati
                        </button>
                    </div>

                    <button
                        class="flex items-center justify-center order-3 px-4 py-2 text-blue-600 transition-colors lg:justify-start hover:text-blue-700">
                        Soal Selanjutnya
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan - Camera dan Profile -->
        <div id="rightSidebar"
            class="fixed right-0 z-30 h-full overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-full bg-white border-l border-gray-200 shadow-sm lg:relative w-80 lg:w-80 lg:h-auto lg:translate-x-0">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden bg-blue-50">
                <h3 class="font-semibold text-blue-800">Profil & Camera</h3>
                <button id="closeRightSidebar" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Profile Mahasiswa -->
            <div class="p-4 border-b border-gray-200 bg-blue-50">
                <div class="text-center">
                    <div
                        class="flex items-center justify-center w-16 h-16 mx-auto mb-3 bg-blue-600 rounded-full lg:w-20 lg:h-20">
                        <span class="text-lg font-bold text-white lg:text-xl">JD</span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-600">NIM:
                        {{ Auth::user()->nim ?? (Auth::user()->username ?? 'Tidak Diketahui') }}</p>
                </div>
            </div>

            <!-- Camera Monitor -->
            <div class="p-4 border-b border-gray-200">
                <h4 class="mb-3 font-medium text-gray-800">Monitor Camera</h4>
                <div class="flex items-center justify-center mb-3 bg-black rounded-lg aspect-video">
                    <div class="text-center text-white">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-50 lg:w-12 lg:h-12" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                        </svg>
                        <p class="text-xs opacity-75 lg:text-sm">Camera Feed</p>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center text-green-600">
                        <div class="w-2 h-2 mr-2 bg-green-500 rounded-full"></div>
                        Camera Aktif
                    </span>
                    <span class="text-gray-500">Recording</span>
                </div>
            </div>

            <!-- Status Ujian -->
            <div class="p-4 border-b border-gray-200">
                <h4 class="mb-3 font-medium text-gray-800">Status Ujian</h4>
                <div class="space-y-3">
                    {{-- <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Mulai Ujian:</span>
                        <span class="font-medium">14:00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Berakhir:</span>
                        <span class="font-medium">16:00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Durasi:</span>
                        <span class="font-medium">120 menit</span>
                    </div> --}}
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progres:</span>
                        <span class="font-medium text-blue-600">46%</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mt-3">
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: 46%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function startCountdown(totalSeconds) {
            const countdownElement = document.getElementById("countdown");
            let remainingTime = totalSeconds;
            let interval; // Deklarasikan interval di sini

            function updateCountdown() {
                if (remainingTime <= 0) {
                    countdownElement.innerHTML = "Waktu Habis";
                    clearInterval(interval); // interval sekarang dapat diakses

                    // Cek apakah Livewire tersedia
                    if (window.Livewire) {
                        setTimeout(() => {
                            Livewire.dispatch('timeExpired'); // Pastikan Livewire tersedia
                        }, 100); // Tambahkan delay kecil
                    } else {
                        console.error('Livewire tidak terdeteksi!');
                    }
                    return;
                }

                const hours = Math.floor(remainingTime / 3600);
                const minutes = Math.floor((remainingTime % 3600) / 60);
                const seconds = remainingTime % 60;

                countdownElement.innerHTML =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                remainingTime--;
            }

            interval = setInterval(updateCountdown, 1000); // Inisialisasi interval setelah fungsi dideklarasikan
            updateCountdown(); // Panggil update pertama kali agar tidak delay 1 detik
        }

        document.addEventListener("DOMContentLoaded", function() {
            const totalSeconds = {{ $remainingTime }};
            // const totalSeconds = 10;
            startCountdown(totalSeconds);
        });
    </script>
@endpush
