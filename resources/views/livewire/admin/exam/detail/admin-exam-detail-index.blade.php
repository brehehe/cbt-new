<div id="exam-container">
    @php
        use App\Models\User\UserModuleQuestion;


        $first = UserModuleQuestion::where('id', '<', $questionNavigationId)->exists();
        $last = UserModuleQuestion::where('id', '>', $questionNavigationId)->exists();
    @endphp

    <!-- Hidden elements untuk video recording -->
    <video id="hiddenVideo" style="display: none;" autoplay muted></video>
    <canvas id="hiddenCanvas" style="display: none;"></canvas>

    <header class="p-2 text-white bg-orange-600 shadow-lg sm:p-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                <h1 class="text-lg font-bold sm:text-xl">Computer Based Test</h1>
                <div class="px-2 py-1 bg-orange-600 rounded sm:px-3">
                    <span class="text-xs sm:text-sm">Modul: {{ $userTimetable->timetable->module->name ?? '-' }}</span>
                </div>
                <!-- Alert Counter -->
                @if ($alertCount > 0)
                    <div class="px-2 py-1 bg-red-600 rounded sm:px-3">
                        <span class="text-xs sm:text-sm">⚠️ Peringatan: {{ $alertCount }}</span>
                    </div>
                @endif
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                <div class="text-center sm:text-right">
                    <div class="text-xs sm:text-sm opacity-90">Waktu Tersisa</div>
                    <div class="font-mono text-base font-bold text-yellow-300 sm:text-lg" id="countdown"> 00:00:00
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
            <button id="toggleLeftSidebar" class="flex items-center text-orange-600 hover:text-orange-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Navigasi Soal
            </button>
            <button id="toggleRightSidebar" class="flex items-center text-orange-600 hover:text-orange-600">
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
            <div class="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden bg-orange-50">
                <h3 class="font-semibold text-orange-600">Navigasi Soal</h3>
                <button id="closeLeftSidebar" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Info Ujian -->
            <div class="p-4 border-b border-gray-200 bg-orange-50">
                <h3 class="hidden mb-2 font-semibold text-orange-600 lg:block">Navigasi Soal</h3>
                <div class="text-sm text-gray-600">
                    <div>Total: {{ $questionNavigations['total'] }} soal</div>
                    <div class="flex flex-wrap gap-2 mt-2 lg:space-x-4 lg:flex-nowrap">
                        <span class="text-xs text-orange-600 lg:text-sm">Dijawab:
                            {{ $questionNavigations['answered'] }}</span>
                        <span class="text-xs text-yellow-600 lg:text-sm">Ditandai:
                            {{ $questionNavigations['marked'] }}</span>
                        <span class="text-xs text-red-600 lg:text-sm">Belum:
                            {{ $questionNavigations['unanswered'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="p-4 border-b border-gray-200">
                <div class="mb-2 text-xs text-gray-500">Keterangan:</div>
                <div class="grid grid-cols-4 gap-2 text-xs">
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-green-500 rounded"></div>
                        <span>Aktif</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-green-500 rounded"></div>
                        <span>Dijawab</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 mr-2 bg-yellow-500 rounded"></div>
                        <span>Ditandai</span>
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
                    @foreach ($questionNavigations['numbers'] as $key => $detail)
                        @php
                            $isCurrent = $questionNavigationId === $detail['id'];
                            $isAnswered = $detail['timetable_answer_id'];
                            $isMarked = $detail['is_mark'];

                            $buttonClass = 'w-8 h-8 text-xs font-medium rounded lg:w-8 lg:h-8 lg:text-sm ';

                            if ($isCurrent) {
                                $buttonClass .= 'text-white bg-green-600 ring-2 ring-green-300';
                            } elseif ($isAnswered) {
                                $buttonClass .= 'text-white bg-green-500';
                            } elseif ($isMarked) {
                                $buttonClass .= 'text-white bg-yellow-500 transition-colors hover:bg-yellow-600';
                            } else {
                                $buttonClass .= 'text-gray-700 bg-gray-300 transition-colors hover:bg-gray-400';
                            }
                        @endphp

                        <button wire:click="changeQuestionNavigation('{{ $detail['id'] }}')"
                            class="{{ $buttonClass }}">
                            {{ $key + 1 }}
                        </button>
                    @endforeach
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
                    <h2 class="text-lg font-semibold text-gray-800">Soal No. {{ $number }}</h2>
                    <button wire:click='updateMark()' @class([
                        'flex items-center justify-center sm:justify-start transition-colors',
                        'text-yellow-600 hover:text-yellow-700' => $isMark,
                        'text-gray-600 hover:text-gray-700' => !$isMark,
                    ])>
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
                <div>
                    <!-- Pertanyaan -->
                    <div class="mb-6">
                        <p class="text-base leading-relaxed text-gray-800 lg:text-lg">
                            {{ $question }}
                        </p>
                        <div class="mt-2 text-sm text-gray-600">
                            {{ $description }}
                        </div>

                        @if (!empty($images) && $images->isNotEmpty())
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($images as $image)
                                    <div
                                        class="overflow-hidden rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Gambar soal"
                                            class="w-full h-auto object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Pilihan Jawaban -->
                    <div class="space-y-4" wire:key="question-{{ $questionNavigationId }}">
                        @foreach ($question_answers as $question_answer)
                            <label
                                class="flex items-start p-3 transition-all border border-gray-200 rounded-lg cursor-pointer lg:p-4 hover:bg-orange-50 hover:border-orange-300">
                                {{-- Radio --}}
                                <input type="radio" name="timetable_answer_id" wire:model.live="timetable_answer_id"
                                    value="{{ $question_answer['id'] }}"
                                    class="flex-shrink-0 mt-1 mr-3 text-orange-600 lg:mr-4">

                                {{-- Isi jawaban --}}
                                <div class="flex-1">
                                    {{-- Teks jawaban --}}
                                    <p class="text-sm text-gray-700 lg:text-base">
                                        <span
                                            class="font-medium text-orange-800">{{ $question_answer['alphabet'] }}.</span>
                                        <span class="ml-2">{{ $question_answer['context'] }}</span>
                                    </p>

                                    {{-- Gambar (jika ada) --}}
                                    @if (!empty($question_answer['images']))
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                            @foreach ($question_answer['images'] as $img)
                                                <img src="{{ asset('storage/' . $img->path) }}"
                                                    alt="Gambar jawaban {{ $question_answer['alphabet'] }}"
                                                    class="w-full h-auto rounded-md object-cover">
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Footer Navigasi Soal -->
            <div class="p-4 border-t border-gray-200 lg:p-6 bg-gray-50">
                <div class="flex items-center justify-between">
                    <!-- Tombol Soal Sebelumnya - Sebelah Kiri -->
                    <div class="flex">
                        @if ($first)
                            <button wire:click='previousQuestion()' type="button"
                                class="flex items-center px-4 py-2 text-orange-600 transition-colors hover:text-orange-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                                Soal Sebelumnya
                            </button>
                        @endif
                    </div>

                    <!-- Tombol Soal Selanjutnya/Selesai Ujian - Sebelah Kanan -->
                    <div class="flex">
                        @if ($last)
                            <button type="button" wire:click='nextQuestion()'
                                class="flex items-center px-4 py-2 text-orange-600 transition-colors hover:text-orange-700">
                                Soal Selanjutnya
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <button type="button"
                                class="flex items-center px-4 py-2 text-orange-600 transition-colors hover:text-orange-700"
                                wire:click="confirmFinishExam">
                                Selesai Ujian
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Kanan - Camera dan Profile -->
        <div id="rightSidebar"
            class="fixed right-0 z-30 h-full overflow-y-auto transition-transform duration-300 ease-in-out transform translate-x-full bg-white border-l border-gray-200 shadow-sm lg:relative w-80 lg:w-80 lg:h-auto lg:translate-x-0">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden bg-orange-50">
                <h3 class="font-semibold text-orange-800">Profil & Camera</h3>
                <button id="closeRightSidebar" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Profile Mahasiswa -->
            <div class="p-4 border-b border-gray-200 bg-orange-50">
                <div class="text-center">
                    <div
                        class="flex items-center justify-center w-16 h-16 mx-auto mb-3 bg-orange-600 rounded-full lg:w-20 lg:h-20">
                        <span
                            class="text-lg font-bold text-white lg:text-xl">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-600">NIM:
                        {{ Auth::user()->nim ?? (Auth::user()->username ?? 'Tidak Diketahui') }}</p>
                </div>
            </div>

            <!-- Monitor Camera -->
           <div x-data="{ showCamera: true }" class="p-4 border-b border-gray-200">
    <div class="flex items-center justify-between mb-3">
        <h4 class="font-medium text-gray-800">Monitor Camera</h4>
        <button
            @click="showCamera = !showCamera"
            class="px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600"
        >
            <span x-text="showCamera ? 'Hide' : 'Show'"></span>
        </button>
    </div>

    <div x-show="showCamera" x-transition>
        <div class="relative mb-3 bg-black rounded-lg aspect-video">
            <video id="cameraPreview" class="w-full h-full object-cover rounded-lg" autoplay muted></video>
            <div id="cameraStatus" class="absolute top-2 right-2 flex items-center">
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse mr-1"></div>
                <span class="text-xs text-white bg-black bg-opacity-50 px-1 rounded">REC</span>
            </div>
        </div>
        <div class="flex items-center justify-between text-sm">
            <span class="flex items-center text-green-600">
                <div class="w-2 h-2 mr-2 bg-green-500 rounded-full"></div>
                <span id="cameraStatusText">Camera Aktif</span>
            </span>
            <span class="text-gray-500">Recording</span>
        </div>
    </div>
</div>


            <!-- Status Ujian -->
            <div class="p-4 border-b border-gray-200">
                <h4 class="mb-3 font-medium text-gray-800">Status Ujian</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Progres:</span>
                        <span class="font-medium text-green-600">{{ number_format($percentage, 0) }}%</span>
                    </div>
                    {{-- <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Peringatan:</span>
                        <span class="font-medium {{ $alertCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ $alertCount }}/5
                        </span>
                    </div> --}}
                </div>

                <div class="mt-3">
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full transition-all duration-300"
                            style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Recording Status -->
            <div class="p-4">
                <h4 class="mb-3 font-medium text-gray-800">Recording & Streaming</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Recording:</span>
                        <span class="text-green-600" id="recordingStatus">Recording</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Live Stream:</span>
                        <span class="text-yellow-600" id="streamingStatus">Connecting</span>
                    </div>
                    <!-- <div class="flex justify-between">
                        <span class="text-gray-600">Chunk:</span>
                        <span class="text-green-600" id="chunkNumber">1</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="text-green-600" id="recordingDuration">00:00</span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Warning Modal -->
    <div id="warningModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4">
            <div class="flex items-center mb-4">
                <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.316 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="text-lg font-semibold text-red-600">Peringatan!</h3>
            </div>
            <p class="text-gray-700 mb-4">Anda terdeteksi mencoba meninggalkan halaman ujian. Hal ini akan dicatat
                sebagai pelanggaran.</p>
            <div class="flex justify-end space-x-2">
                <button id="stayButton" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Tetap di Halaman
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Include PeerJS for student side -->
    <script src="https://unpkg.com/peerjs@1.5.0/dist/peerjs.min.js"></script>

    <!-- Test PeerJS loading -->
    <script>
        console.log('Script loaded, testing PeerJS availability...');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, PeerJS available:', typeof Peer !== 'undefined');
            if (typeof Peer !== 'undefined') {
                console.log('PeerJS loaded successfully!');
            } else {
                console.error('PeerJS failed to load!');
            }
        });
    </script>

    <script>
        // Global variables
        let mediaRecorder;
        let recordedChunks = [];
        let currentChunk = 1;
        let recordingStartTime;
        let recordingDurationInterval;
        let isRecording = false;
        let stream;
        let warningShown = false;
        let pageLoaded = false;
        let peerConnection;
        let streamId = '{{ $liveSession->session_token ?? '' }}';
        let isStreaming = false;

        // PeerJS variables
        let peer = null;
        let supervisorPeerID = null;
        let currentCall = null;
        let cameraStream = null;

        // Initialize everything when page loads
        document.addEventListener("DOMContentLoaded", function() {
            console.log('=== DOMContentLoaded fired ===');
            const totalSeconds = {{ $remainingTime }};
            startCountdown(totalSeconds);
            initializeExamEnvironment();
            initializeCamera();
            console.log('About to call initializePeerJS...');
            initializePeerJS(); // Initialize PeerJS
            console.log('initializePeerJS called');
            setupEventListeners();
            initializeLiveSessionMonitoring();

            // Mark page as loaded
            setTimeout(() => {
                pageLoaded = true;
            }, 2000);
        });

        // Countdown function
        function startCountdown(totalSeconds) {
            const countdownElement = document.getElementById("countdown");
            let remainingTime = totalSeconds;
            let interval;

            function updateCountdown() {
                if (remainingTime <= 0) {
                    countdownElement.innerHTML = "Waktu Habis";
                    clearInterval(interval);
                    stopRecording();

                    if (window.Livewire) {
                        setTimeout(() => {
                            Livewire.dispatch('timeExpired');
                        }, 100);
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

            interval = setInterval(updateCountdown, 1000);
            updateCountdown();
        }

        // Initialize exam environment
        function initializeExamEnvironment() {
            // Force fullscreen
            // requestFullscreen();

            // // Disable right click
            // document.addEventListener('contextmenu', function(e) {
            //     e.preventDefault();
            //     logAlert('right_click', 'Mencoba membuka menu konteks');
            // });

            // // Disable F12, Ctrl+Shift+I, etc.
            // document.addEventListener('keydown', function(e) {
            //     // F12
            //     if (e.key === 'F12') {
            //         e.preventDefault();
            //         logAlert('dev_tools', 'Mencoba membuka developer tools dengan F12');
            //     }
            //     // Ctrl+Shift+I
            //     if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            //         e.preventDefault();
            //         logAlert('dev_tools', 'Mencoba membuka developer tools dengan Ctrl+Shift+I');
            //     }
            //     // Ctrl+Shift+J
            //     if (e.ctrlKey && e.shiftKey && e.key === 'J') {
            //         e.preventDefault();
            //         logAlert('dev_tools', 'Mencoba membuka developer tools dengan Ctrl+Shift+J');
            //     }
            //     // Ctrl+U
            //     if (e.ctrlKey && e.key === 'u') {
            //         e.preventDefault();
            //         logAlert('view_source', 'Mencoba melihat source code');
            //     }
            //     // Alt+Tab
            //     if (e.altKey && e.key === 'Tab') {
            //         e.preventDefault();
            //         logAlert('alt_tab', 'Mencoba beralih aplikasi dengan Alt+Tab');
            //     }
            //     // Ctrl+Tab
            //     if (e.ctrlKey && e.key === 'Tab') {
            //         e.preventDefault();
            //         logAlert('ctrl_tab', 'Mencoba beralih tab dengan Ctrl+Tab');
            //     }
            // });

            // // Disable copy, paste, cut
            // document.addEventListener('keydown', function(e) {
            //     if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
            //         e.preventDefault();
            //         logAlert('copy_paste', 'Mencoba copy/paste/cut');
            //     }
            // });

            // // Disable drag and drop
            // document.addEventListener('dragstart', function(e) {
            //     e.preventDefault();
            // });
        }

        // Setup event listeners
        function setupEventListeners() {
            // Tab visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    logAlert('tab_switch', 'Beralih ke tab/aplikasi lain');
                    showWarning('Anda terdeteksi beralih ke tab atau aplikasi lain!');
                }
            });

            // Window blur (lost focus)
            window.addEventListener('blur', function() {
                if (pageLoaded) {
                    logAlert('window_blur', 'Jendela kehilangan fokus');
                    showWarning('Jendela browser kehilangan fokus!');
                }
            });

            // Fullscreen change
            document.addEventListener('fullscreenchange', function() {
                if (!document.fullscreenElement) {
                    logAlert('fullscreen_exit', 'Keluar dari mode fullscreen');
                    showWarning('Anda keluar dari mode fullscreen!');
                    // Force back to fullscreen
                    setTimeout(() => {
                        requestFullscreen();
                    }, 1000);
                }
            });

            // Page reload/refresh
            window.addEventListener('beforeunload', function(e) {
                if (pageLoaded) {
                    // Save current video chunk before reload
                    if (isRecording && mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.requestData();
                    }

                    // Log the reload
                    navigator.sendBeacon('/api/log-alert', JSON.stringify({
                        alert_type: 'page_reload',
                        description: 'Halaman di-refresh atau ditutup'
                    }));
                }
            });

            // Page load (after refresh)
            window.addEventListener('load', function() {
                if (performance.navigation.type === 1) { // Page was refreshed
                    if (window.Livewire) {
                        Livewire.dispatch('pageReloaded');
                    }
                }
            });

            // Sidebar toggles
            setupSidebarToggles();
        }

        // Setup sidebar toggles
        function setupSidebarToggles() {
            const toggleLeftSidebar = document.getElementById('toggleLeftSidebar');
            const toggleRightSidebar = document.getElementById('toggleRightSidebar');
            const closeLeftSidebar = document.getElementById('closeLeftSidebar');
            const closeRightSidebar = document.getElementById('closeRightSidebar');
            const leftSidebar = document.getElementById('leftSidebar');
            const rightSidebar = document.getElementById('rightSidebar');
            const overlay = document.getElementById('overlay');

            function showLeftSidebar() {
                leftSidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideLeftSidebar() {
                leftSidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function showRightSidebar() {
                rightSidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function hideRightSidebar() {
                rightSidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (toggleLeftSidebar) toggleLeftSidebar.addEventListener('click', showLeftSidebar);
            if (toggleRightSidebar) toggleRightSidebar.addEventListener('click', showRightSidebar);
            if (closeLeftSidebar) closeLeftSidebar.addEventListener('click', hideLeftSidebar);
            if (closeRightSidebar) closeRightSidebar.addEventListener('click', hideRightSidebar);
            if (overlay) overlay.addEventListener('click', function() {
                hideLeftSidebar();
                hideRightSidebar();
            });
        }

        // Initialize camera
        async function initializeCamera() {
            console.log('Starting camera initialization...');
            console.log('Protocol:', window.location.protocol);
            console.log('Host:', window.location.host);

            try {
                // Check if HTTPS is being used (required for camera access)
                if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !==
                    '127.0.0.1') {
                    throw new Error(
                        'Camera requires HTTPS connection. Please use https://cbt-test.test instead of http://');
                }

                // Check if getUserMedia is available
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('getUserMedia is not supported in this browser');
                }

                // Check if MediaRecorder is available
                if (!window.MediaRecorder) {
                    throw new Error('MediaRecorder is not supported in this browser');
                }

                const constraints = {
                    video: {
                        width: {
                            ideal: 1280,
                            min: 640
                        },
                        height: {
                            ideal: 720,
                            min: 480
                        },
                        facingMode: 'user',
                        frameRate: {
                            ideal: 30,
                            min: 15
                        }
                    },
                    audio: false
                };

                console.log('Requesting camera access...');
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                console.log('Camera access granted');

                const cameraPreview = document.getElementById('cameraPreview');
                const hiddenVideo = document.getElementById('hiddenVideo');

                if (cameraPreview) {
                    cameraPreview.srcObject = stream;
                    // Ensure video plays
                    cameraPreview.onloadedmetadata = () => {
                        cameraPreview.play().catch(e => {
                            console.warn('Video autoplay prevented:', e);
                        });
                    };
                    console.log('Camera preview set');
                }

                if (hiddenVideo) {
                    hiddenVideo.srcObject = stream;
                    console.log('Hidden video set');
                }

                // Start recording
                startRecording();

                // Start live streaming for supervisor monitoring
                initializeLiveStreaming();

                updateCameraStatus('Camera Aktif', 'text-green-600');

                // Notify Livewire that camera is active
                if (window.Livewire) {
                    Livewire.dispatch('cameraStatusUpdated', {
                        status: 'active',
                        timestamp: new Date().toISOString()
                    });
                }

            } catch (error) {
                console.error('Error accessing camera:', error);
                console.error('Error name:', error.name);
                console.error('Error message:', error.message);

                // More user-friendly error messages
                let errorMessage = 'Camera tidak dapat diakses';
                let suggestions = [];

                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Akses camera ditolak';
                    suggestions = [
                        'Klik ikon camera/gembok di address bar',
                        'Pilih "Allow" untuk camera access',
                        'Refresh halaman setelah memberikan izin'
                    ];
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'Camera tidak ditemukan';
                    suggestions = [
                        'Pastikan camera terhubung ke komputer',
                        'Cek apakah camera digunakan aplikasi lain',
                        'Restart browser dan coba lagi'
                    ];
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'Browser tidak mendukung camera';
                    suggestions = [
                        'Gunakan Chrome, Firefox, atau Edge terbaru',
                        'Update browser ke versi terbaru'
                    ];
                } else if (error.message.includes('HTTPS')) {
                    errorMessage = 'Camera memerlukan HTTPS';
                    suggestions = [
                        'Akses melalui https://cbt-test.test',
                        'Jangan gunakan http:// untuk ujian',
                        'Setup SSL certificate jika perlu'
                    ];
                }

                updateCameraStatus('Camera Error: ' + errorMessage, 'text-red-600');
                logAlert('camera_error', 'Gagal mengakses kamera: ' + errorMessage + ' (Protocol: ' + window.location
                    .protocol + ')');

                // Show detailed error with solutions
                const suggestionText = suggestions.length > 0 ? '\n\nSolusi:\n' + suggestions.join('\n') : '';
                alert('⚠️ ' + errorMessage + suggestionText);

                // Notify Livewire about camera error
                if (window.Livewire) {
                    Livewire.dispatch('cameraStatusUpdated', {
                        status: 'error',
                        error: errorMessage,
                        timestamp: new Date().toISOString()
                    });
                }
            }
        }

        // Initialize PeerJS for live streaming to supervisor
        async function initializePeerJS() {
            try {
                console.log('=== Starting PeerJS initialization ===');
                console.log('PeerJS available:', typeof Peer !== 'undefined');
                console.log('Livewire available:', typeof window.Livewire !== 'undefined');

                // Check if PeerJS is loaded
                if (typeof Peer === 'undefined') {
                    console.error('PeerJS library not loaded!');
                    return;
                }

                console.log('Initializing PeerJS for student...');

                // Initialize PeerJS with auto-generated ID
                peer = new Peer({
                    host: 'peer.toti.my.id',
                    path: '/peerjs',
                    secure: true, // Use HTTP instead of HTTPS
                    debug: 2, // Enable debug logs
                    config: {
                        'iceServers': [{
                                urls: 'stun:stun.l.google.com:19302'
                            },
                            {
                                urls: 'stun:stun1.l.google.com:19302'
                            }
                        ]
                    }
                });

                console.log('PeerJS instance created, waiting for connection...');

                peer.on('open', function(id) {
                    console.log('PeerJS connected with ID:', id);
                    console.log('Livewire available:', !!window.Livewire);

                    // Send PeerJS ID to server for supervisor to connect
                    if (window.Livewire) {
                        console.log('Dispatching updatePeerJSId with ID:', id);
                        try {
                            Livewire.dispatch('updatePeerJSId', [id]);
                            console.log('updatePeerJSId dispatched successfully');
                        } catch (error) {
                            console.error('Error dispatching updatePeerJSId:', error);
                        }
                    } else {
                        console.error('Livewire is not available!');
                    }

                    // Update live session status
                    updateLiveSessionData({
                        camera_status: 'active',
                        connection_status: 'connected'
                    });
                });

                peer.on('call', function(call) {
                    console.log('Incoming call from supervisor');

                    // Answer the call with our camera stream
                    if (cameraStream) {
                        call.answer(cameraStream);
                        currentCall = call;

                        // Update connection status
                        updateLiveSessionData({
                            connection_status: 'streaming'
                        });

                        console.log('Answered call with camera stream');
                    } else {
                        console.log('No camera stream available to answer call');
                        // Try to get camera stream first
                        getCameraStreamForPeerJS().then(stream => {
                            if (stream) {
                                call.answer(stream);
                                currentCall = call;
                                cameraStream = stream;
                            }
                        });
                    }

                    call.on('close', function() {
                        console.log('Call closed by supervisor');
                        currentCall = null;
                        updateLiveSessionData({
                            connection_status: 'connected'
                        });
                    });
                });

                peer.on('disconnected', function() {
                    console.log('PeerJS disconnected');
                    updateLiveSessionData({
                        connection_status: 'disconnected'
                    });
                });

                peer.on('error', function(err) {
                    console.log('PeerJS error:', err);
                    updateLiveSessionData({
                        connection_status: 'unstable'
                    });
                });

                // Try to get camera stream for PeerJS
                getCameraStreamForPeerJS();

            } catch (error) {
                console.log('Failed to initialize PeerJS:', error);
                updateLiveSessionData({
                    camera_status: 'error',
                    connection_status: 'unstable'
                });
            }
        }

        // Get camera stream specifically for PeerJS
        async function getCameraStreamForPeerJS() {
            try {
                // Use the same stream as recording or create new one
                if (stream) {
                    cameraStream = stream;
                    console.log('Using existing camera stream for PeerJS');
                    return stream;
                }

                // Create new stream for PeerJS
                const constraints = {
                    video: {
                        width: {
                            ideal: 1280,
                            min: 640
                        },
                        height: {
                            ideal: 720,
                            min: 480
                        },
                        frameRate: {
                            ideal: 30,
                            min: 15
                        }
                    },
                    audio: false
                };

                cameraStream = await navigator.mediaDevices.getUserMedia(constraints);
                console.log('Created new camera stream for PeerJS');

                return cameraStream;

            } catch (error) {
                console.error('Failed to get camera stream for PeerJS:', error);
                updateLiveSessionData({
                    camera_status: 'error'
                });
                return null;
            }
        }

        // Update live session data
        function updateLiveSessionData(data) {
            if (window.Livewire) {
                Livewire.dispatch('updateLiveSessionData', [data]);
            }
        }

        // Start recording
        function startRecording() {
            if (!stream) return;

            try {
                const options = {
                    mimeType: 'video/webm;codecs=vp9',
                    videoBitsPerSecond: 500000 // 500 kbps
                };

                mediaRecorder = new MediaRecorder(stream, options);
                recordedChunks = [];

                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = function() {
                    saveVideoChunk();
                };

                mediaRecorder.start();
                isRecording = true;
                recordingStartTime = Date.now();

                updateRecordingStatus('Recording', currentChunk);
                startRecordingTimer();

                // Auto-save every 2 minutes
                setInterval(() => {
                    if (isRecording && mediaRecorder && mediaRecorder.state === 'recording') {
                        mediaRecorder.requestData();
                    }
                }, 120000); // 2 minutes

            } catch (error) {
                console.error('Error starting recording:', error);
                updateRecordingStatus('Error', currentChunk);
            }
        }

        // Stop recording
        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                isRecording = false;
                clearInterval(recordingDurationInterval);
                updateRecordingStatus('Stopped', currentChunk);
            }
        }

        // Save video chunk
        function saveVideoChunk() {
            console.log('saveVideoChunk called, chunks:', recordedChunks.length);

            if (recordedChunks.length === 0) {
                console.log('No chunks to save');
                return;
            }

            const blob = new Blob(recordedChunks, {
                type: 'video/webm'
            });
            console.log('Created blob, size:', blob.size);

            const reader = new FileReader();

            reader.onload = function(e) {
                const base64Data = e.target.result;
                console.log('Base64 data length:', base64Data.length);
                console.log('Base64 header:', base64Data.substring(0, 50));

                // Send to server via Livewire
                if (window.Livewire) {
                    console.log('Sending to Livewire, chunk:', currentChunk);

                    Livewire.dispatch('saveVideoChunk', {
                        videoBlob: base64Data,
                        chunkNumber: currentChunk
                    });
                } else {
                    console.error('Livewire not available');
                }

                // Prepare for next chunk
                currentChunk++;
                recordedChunks = [];

                // Restart recording
                setTimeout(() => {
                    if (stream && stream.active) {
                        console.log('Restarting recording...');
                        startRecording();
                    } else {
                        console.log('Stream not active, cannot restart recording');
                    }
                }, 1000);
            };

            reader.onerror = function() {
                console.error('FileReader error');
            };

            reader.readAsDataURL(blob);
        }

        // Update camera status
        function updateCameraStatus(status, className) {
            const statusElement = document.getElementById('cameraStatusText');
            if (statusElement) {
                statusElement.textContent = status;
                statusElement.className = `flex items-center ${className}`;
            }
        }

        // Update recording status
        function updateRecordingStatus(status, chunk) {
            const statusElement = document.getElementById('recordingStatus');
            const chunkElement = document.getElementById('chunkNumber');

            if (statusElement) {
                statusElement.textContent = status;
                statusElement.className = status === 'Recording' ? 'text-green-600' : 'text-red-600';
            }

            if (chunkElement) {
                chunkElement.textContent = chunk;
            }
        }

        // Start recording timer
        function startRecordingTimer() {
            recordingDurationInterval = setInterval(() => {
                const now = Date.now();
                const duration = Math.floor((now - recordingStartTime) / 1000);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;

                const durationElement = document.getElementById('recordingDuration');
                if (durationElement) {
                    durationElement.textContent =
                        `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }, 1000);
        }

        // Request fullscreen
        function requestFullscreen() {
            const elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }

        // Log alert
        function logAlert(type, description) {
            if (window.Livewire) {
                Livewire.dispatch('logAlert', {
                    alertType: type,
                    description: description,
                    metadata: {
                        timestamp: new Date().toISOString(),
                        userAgent: navigator.userAgent,
                        url: window.location.href
                    }
                });
            }
        }

        // Show warning modal
        function showWarning(message) {
            if (warningShown) return;

            warningShown = true;
            const modal = document.getElementById('warningModal');
            const stayButton = document.getElementById('stayButton');

            if (modal) {
                modal.querySelector('p').textContent = message;
                modal.classList.remove('hidden');

                stayButton.addEventListener('click', function() {
                    modal.classList.add('hidden');
                    warningShown = false;

                    // Force focus back to exam
                    window.focus();
                    requestFullscreen();
                });
            }
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }

            // Notify server that session is ending
            if (window.Livewire) {
                navigator.sendBeacon('/api/end-live-session', JSON.stringify({
                    session_token: '{{ $liveSession->session_token ?? '' }}'
                }));
            }
        });

        // Initialize live session monitoring
        function initializeLiveSessionMonitoring() {
            // Update session activity every 30 seconds
            setInterval(() => {
                updateLiveSessionActivity();
            }, 30000);

            // Monitor screen capture for screenshot
            setInterval(() => {
                captureScreenshot();
            }, 60000); // Every minute

            // Send initial session data
            updateLiveSessionActivity();
        }

        // Update live session activity
        function updateLiveSessionActivity() {
            if (window.Livewire) {
                const sessionData = {
                    connection_status: navigator.onLine ? 'connected' : 'disconnected',
                    camera_status: isRecording ? 'active' : 'inactive',
                    last_activity: new Date().toISOString(),
                    browser_info: {
                        user_agent: navigator.userAgent,
                        platform: navigator.platform,
                        language: navigator.language,
                        screen_resolution: `${screen.width}x${screen.height}`,
                        window_size: `${window.innerWidth}x${window.innerHeight}`
                    }
                };

                Livewire.dispatch('updateLiveSessionData', sessionData);
            }
        }

        // Capture screenshot for monitoring
        function captureScreenshot() {
            if (!stream) return;

            try {
                const canvas = document.getElementById('hiddenCanvas');
                const video = document.getElementById('hiddenVideo');

                if (canvas && video) {
                    const ctx = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    ctx.drawImage(video, 0, 0);

                    canvas.toBlob((blob) => {
                        if (blob) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                if (window.Livewire) {
                                    Livewire.dispatch('saveScreenshot', {
                                        screenshot: e.target.result,
                                        timestamp: new Date().toISOString()
                                    });
                                }
                            };
                            reader.readAsDataURL(blob);
                        }
                    }, 'image/jpeg', 0.7);
                }
            } catch (error) {
                console.error('Error capturing screenshot:', error);
            }
        }

        // Monitor connection status
        window.addEventListener('online', function() {
            updateLiveSessionActivity();
            logAlert('connection_restored', 'Koneksi internet pulih');
        });

        window.addEventListener('offline', function() {
            logAlert('connection_lost', 'Koneksi internet terputus');
        });

        // Livewire hooks
        document.addEventListener('livewire:initialized', function() {
            // Handle new recording start
            Livewire.on('startNewRecording', function() {
                if (stream && stream.active) {
                    setTimeout(() => {
                        startRecording();
                    }, 1000);
                }
            });
        });

        // Live Streaming Functions for Supervisor Monitoring
        async function initializeLiveStreaming() {
            if (!stream || !streamId) {
                console.log('Stream or streamId not available for live streaming');
                return;
            }

            try {
                console.log('Initializing live streaming with streamId:', streamId);

                // Create peer connection for WebRTC
                peerConnection = new RTCPeerConnection({
                    iceServers: [{
                            urls: 'stun:stun.l.google.com:19302'
                        },
                        {
                            urls: 'stun:stun1.l.google.com:19302'
                        }
                    ]
                });

                // Add stream to peer connection
                stream.getTracks().forEach(track => {
                    peerConnection.addTrack(track, stream);
                });

                // Handle ice candidates
                peerConnection.onicecandidate = (event) => {
                    if (event.candidate) {
                        // Send ICE candidate to signaling server
                        sendSignalingMessage('ice-candidate', {
                            candidate: event.candidate,
                            streamId: streamId
                        });
                    }
                };

                // Handle connection state changes
                peerConnection.onconnectionstatechange = () => {
                    console.log('Connection state:', peerConnection.connectionState);
                    updateStreamingStatus(peerConnection.connectionState);
                };

                // Connect to signaling server (WebSocket)
                initializeSignalingConnection();

                isStreaming = true;
                console.log('Live streaming initialized successfully');

            } catch (error) {
                console.error('Error initializing live streaming:', error);
            }
        }

        // Initialize WebSocket connection for signaling
        function initializeSignalingConnection() {
            // In a real implementation, this would connect to your WebSocket server
            // For now, we'll simulate the connection
            console.log('Signaling connection initialized for stream:', streamId);

            // Simulate supervisor connection request
            setTimeout(() => {
                handleSupervisorConnectionRequest();
            }, 2000);
        }

        // Handle supervisor connection request
        async function handleSupervisorConnectionRequest() {
            if (!peerConnection) return;

            try {
                // Create offer for supervisor
                const offer = await peerConnection.createOffer();
                await peerConnection.setLocalDescription(offer);

                // Send offer to supervisor through signaling server
                sendSignalingMessage('offer', {
                    offer: offer,
                    streamId: streamId
                });

                console.log('Offer sent to supervisor');

            } catch (error) {
                console.error('Error creating offer:', error);
            }
        }

        // Send message through signaling server
        function sendSignalingMessage(type, data) {
            // In real implementation, this would send through WebSocket
            console.log('Sending signaling message:', type, data);

            // For now, we'll just log the message
            // In production, you would implement:
            // webSocket.send(JSON.stringify({ type, data }));
        }

        // Handle incoming signaling messages
        function handleSignalingMessage(message) {
            const {
                type,
                data
            } = message;

            switch (type) {
                case 'answer':
                    if (peerConnection && data.answer) {
                        peerConnection.setRemoteDescription(new RTCSessionDescription(data.answer));
                    }
                    break;

                case 'ice-candidate':
                    if (peerConnection && data.candidate) {
                        peerConnection.addIceCandidate(new RTCIceCandidate(data.candidate));
                    }
                    break;

                case 'supervisor-disconnect':
                    console.log('Supervisor disconnected from stream');
                    break;
            }
        }

        // Update streaming status
        function updateStreamingStatus(status) {
            const streamingElement = document.getElementById('streamingStatus');
            if (streamingElement) {
                streamingElement.textContent = status;
                streamingElement.className = `text-${status === 'connected' ? 'green' : 'yellow'}-600`;
            }

            // Update live session data
            if (window.Livewire) {
                Livewire.dispatch('updateLiveSessionData', {
                    streaming_status: status,
                    last_activity: new Date().toISOString()
                });
            }
        }

        // Enhanced live session monitoring with streaming
        function initializeLiveSessionMonitoring() {
            // Update session activity every 30 seconds
            setInterval(() => {
                updateLiveSessionActivity();
            }, 30000);

            // Monitor screen capture for screenshot
            setInterval(() => {
                captureScreenshot();
            }, 60000); // Every minute

            // Monitor streaming health
            setInterval(() => {
                checkStreamingHealth();
            }, 10000); // Every 10 seconds

            // Send initial session data
            updateLiveSessionActivity();
        }

        // Check streaming health
        function checkStreamingHealth() {
            if (peerConnection && isStreaming) {
                peerConnection.getStats().then(stats => {
                    stats.forEach(report => {
                        if (report.type === 'outbound-rtp' && report.mediaType === 'video') {
                            console.log('Video streaming stats:', {
                                bytesSent: report.bytesSent,
                                packetsSent: report.packetsSent,
                                timestamp: report.timestamp
                            });
                        }
                    });
                });
            }
        }

        // Enhanced cleanup on page unload
        window.addEventListener('beforeunload', function() {
            // Stop video stream
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            // Stop camera stream for PeerJS
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            // Close PeerJS connection
            if (currentCall) {
                currentCall.close();
            }

            if (peer) {
                peer.destroy();
            }

            // Stop recording
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
            }

            // Close peer connection
            if (peerConnection) {
                peerConnection.close();
            }

            // Notify server that session is ending
            if (window.Livewire) {
                navigator.sendBeacon('/api/end-live-session', JSON.stringify({
                    session_token: streamId,
                    streaming_ended: true
                }));
            }
        });
    </script>
@endpush
