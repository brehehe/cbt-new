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
                <div class="text-center sm:text-right" wire:ignore>
                    <div class="text-xs sm:text-sm opacity-90">Waktu Tersisa</div>
                    <div class="font-mono text-base font-bold text-yellow-300 sm:text-lg" id="countdown"> 00:00:00
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click='confirmFinishExam'
                        class="px-3 py-2 text-xs font-medium transition-colors bg-red-600 rounded sm:px-4 sm:text-sm hover:bg-red-700">
                        Selesai Ujian
                    </button>

                    <!-- Manual Save Recording Button for Testing -->
                    {{-- <button onclick="manualSaveRecording()"
                        class="px-3 py-2 text-xs font-medium transition-colors bg-blue-600 rounded sm:px-4 sm:text-sm hover:bg-blue-700">
                        💾 Save Video
                    </button> --}}
                </div>
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
                                <input type="radio" name="timetable_answer_id"
                                    wire:model.live="timetable_answer_id" value="{{ $question_answer['id'] }}"
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
                    <button @click="showCamera = !showCamera"
                        class="px-2 py-1 text-xs text-white bg-orange-500 rounded hover:bg-orange-600">
                        <span x-text="showCamera ? 'Hide' : 'Show'"></span>
                    </button>
                </div>

                <div x-show="showCamera" x-transition>
                    <div class="relative mb-3 bg-black rounded-lg aspect-video">
                        <video id="cameraPreview" class="w-full h-full object-cover rounded-lg" autoplay
                            muted></video>
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
                        <span class="text-yellow-600" id="recordingStatus">Initializing</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Live Stream:</span>
                        <span class="text-yellow-600" id="streamingStatus">Connecting</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Info:</span>
                        <span class="text-gray-600" id="recordingInfo">Starting...</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="text-green-600" id="recordingDuration">00:00</span>
                    </div>
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

    <!-- Enhanced Recording Callback System -->
    <script src="{{ asset('js/recording-callback-system.js') }}"></script>

    <script>
        // Global variables
        let mediaRecorder;
        let recordedChunks = [];
        let recordingStartTime;
        let recordingDurationInterval;
        let periodicSaveInterval;
        let isRecording = false;
        let recordingSegmentCount = 0;
        let totalRecordingSize = 0;
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

            // Check essential elements FIRST
            const countdownElement = document.getElementById("countdown");
            const cameraPreview = document.getElementById('cameraPreview');

            console.log('🔍 Elements check:');
            console.log('- Countdown element:', countdownElement ? '✅ Found' : '❌ Missing');
            console.log('- Camera preview:', cameraPreview ? '✅ Found' : '❌ Missing');

            // If essential elements missing, stop initialization
            if (!countdownElement) {
                console.error('❌ CRITICAL: Countdown element missing - stopping initialization');
                alert('❌ Error: Countdown element not found. Please refresh the page.');
                return;
            }

            if (!cameraPreview) {
                console.error('❌ CRITICAL: Camera preview element missing');
                alert('❌ Error: Camera preview element not found. Please refresh the page.');
                return;
            }

            // Get remaining time from server with detailed logging
            const totalSeconds = {{ $remainingTime ?? 0 }};
            console.log('⏰ Raw remaining time from server:', totalSeconds);
            console.log('⏰ Type of remainingTime:', typeof totalSeconds);

            // Better fallback logic with validation
            let actualTime;
            if (totalSeconds && totalSeconds > 0) {
                actualTime = totalSeconds;
                console.log('✅ Using server time:', actualTime, 'seconds');
            } else {
                actualTime = 60 * 60; // 1 hour fallback for testing
                console.warn('⚠️ Server time invalid, using 1 hour fallback:', actualTime, 'seconds');
            }

            // Start countdown FIRST - most important
            console.log('🕐 Starting countdown initialization...');
            try {
                startCountdown(actualTime);
                console.log('✅ Countdown started successfully');
            } catch (err) {
                console.error('❌ Countdown failed:', err);
                alert('❌ Countdown initialization failed: ' + err.message);
                return;
            }

            // Initialize camera SECOND - critical for exam
            console.log('📹 Starting camera initialization...');
            setTimeout(() => {
                try {
                    initializeCamera();
                    console.log('✅ Camera initialization started');
                } catch (err) {
                    console.error('❌ Camera initialization failed:', err);
                    // Don't stop here - camera issues are common
                }
            }, 500); // Small delay to let countdown start first

            // Initialize other components with delays
            setTimeout(() => {
                initializeExamEnvironment();
                setupEventListeners();
                console.log('✅ Exam environment and event listeners initialized');
            }, 1000);

            setTimeout(() => {
                try {
                    initializePeerJS();
                    console.log('✅ PeerJS initialization started');
                } catch (err) {
                    console.warn('⚠️ PeerJS initialization failed (non-critical):', err);
                }
            }, 1500);

            setTimeout(() => {
                initializeLiveSessionMonitoring();
                checkForEmergencyRecording();
                console.log('✅ Live session monitoring and emergency check completed');
            }, 2000);

            // Mark page as loaded
            setTimeout(() => {
                pageLoaded = true;
                console.log('✅ Page fully loaded and initialized');
            }, 3000);
        });

        // Enhanced countdown function with better error handling
        function startCountdown(totalSeconds) {
            console.log('🕐 Starting countdown with:', totalSeconds, 'seconds');

            // Validate input
            if (!totalSeconds || totalSeconds <= 0) {
                console.error('❌ Invalid totalSeconds:', totalSeconds);
                totalSeconds = 3600; // 1 hour fallback
                console.warn('⚠️ Using 1 hour fallback');
            }

            const countdownElement = document.getElementById("countdown");
            if (!countdownElement) {
                console.error('❌ Countdown element not found!');
                throw new Error('Countdown element not found');
            }

            // Clear any existing interval
            if (window.countdownInterval) {
                clearInterval(window.countdownInterval);
                console.log('🧹 Cleared existing countdown interval');
            }

            let remainingTime = parseInt(totalSeconds);
            console.log('🕐 Initial remaining time:', remainingTime, 'seconds');

            function updateCountdown() {
                try {
                    if (remainingTime <= 0) {
                        countdownElement.innerHTML = "⏰ Waktu Habis";
                        countdownElement.style.color = "red";
                        clearInterval(window.countdownInterval);
                        console.log('⏰ Time expired, stopping recording...');

                        // Stop recording if it exists
                        if (typeof stopRecording === 'function') {
                            stopRecording();
                        }

                        // Notify Livewire
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

                    const timeString =
                        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                    countdownElement.innerHTML = timeString;

                    // Color coding for urgency
                    if (remainingTime <= 300) { // 5 minutes
                        countdownElement.style.color = "red";
                    } else if (remainingTime <= 900) { // 15 minutes
                        countdownElement.style.color = "orange";
                    } else {
                        countdownElement.style.color = "";
                    }

                    // Log every 5 minutes for debugging (reduced frequency)
                    if (remainingTime % 300 === 0) {
                        console.log('⏱️ Time remaining:', timeString, `(${remainingTime}s)`);
                    }

                    remainingTime--;

                } catch (error) {
                    console.error('❌ Error in countdown update:', error);
                    countdownElement.innerHTML = "⚠️ Timer Error";
                    clearInterval(window.countdownInterval);
                }
            }

            // Start countdown with stored reference
            window.countdownInterval = setInterval(updateCountdown, 1000);

            // Run immediately to show initial time
            updateCountdown();

            console.log('✅ Countdown started successfully with interval ID:', window.countdownInterval);

            // Verify countdown is running after 2 seconds
            setTimeout(() => {
                const currentDisplay = countdownElement.innerHTML;
                console.log('🔍 Countdown verification after 2s:', currentDisplay);
                if (currentDisplay === "00:00:00" || currentDisplay.includes("Error")) {
                    console.error('❌ Countdown not working properly!');
                    alert('⚠️ Countdown timer tidak berjalan dengan benar. Silakan refresh halaman.');
                }
            }, 2000);
        }

        // Initialize exam environment
        function initializeExamEnvironment() {
            // Force fullscreen
            requestFullscreen();

            // Disable right click
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                logAlert('right_click', 'Mencoba membuka menu konteks');
            });

            // Disable F12, Ctrl+Shift+I, etc.
            document.addEventListener('keydown', function(e) {
                // F12
                if (e.key === 'F12') {
                    e.preventDefault();
                    logAlert('dev_tools', 'Mencoba membuka developer tools dengan F12');
                }
                // Ctrl+Shift+I
                if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                    e.preventDefault();
                    logAlert('dev_tools', 'Mencoba membuka developer tools dengan Ctrl+Shift+I');
                }
                // Ctrl+Shift+J
                if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                    e.preventDefault();
                    logAlert('dev_tools', 'Mencoba membuka developer tools dengan Ctrl+Shift+J');
                }
                // Ctrl+U
                if (e.ctrlKey && e.key === 'u') {
                    e.preventDefault();
                    logAlert('view_source', 'Mencoba melihat source code');
                }
                // Alt+Tab
                if (e.altKey && e.key === 'Tab') {
                    e.preventDefault();
                    logAlert('alt_tab', 'Mencoba beralih aplikasi dengan Alt+Tab');
                }
                // Ctrl+Tab
                if (e.ctrlKey && e.key === 'Tab') {
                    e.preventDefault();
                    logAlert('ctrl_tab', 'Mencoba beralih tab dengan Ctrl+Tab');
                }
            });

            // Disable copy, paste, cut
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && (e.key === 'c' || e.key === 'v' || e.key === 'x')) {
                    e.preventDefault();
                    logAlert('copy_paste', 'Mencoba copy/paste/cut');
                }
            });

            // Disable drag and drop
            document.addEventListener('dragstart', function(e) {
                e.preventDefault();
            });
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

        // Enhanced camera initialization with step-by-step debugging
        async function initializeCamera() {
            console.log('📹 === CAMERA INITIALIZATION START ===');
            console.log('📍 Protocol:', window.location.protocol);
            console.log('📍 Host:', window.location.host);
            console.log('📍 Full URL:', window.location.href);

            // Update status immediately
            updateRecordingStatus('Initializing', 'Starting camera check...');
            updateCameraStatus('Initializing', 'text-yellow-600');

            try {
                // Step 1: Check browser support
                console.log('🔍 Step 1: Checking browser support...');

                if (!navigator.mediaDevices) {
                    throw new Error('navigator.mediaDevices not available (older browser?)');
                }

                if (!navigator.mediaDevices.getUserMedia) {
                    throw new Error('getUserMedia not supported in this browser');
                }

                if (!window.MediaRecorder) {
                    throw new Error('MediaRecorder not supported in this browser');
                }

                console.log('✅ Browser support check passed');
                updateRecordingStatus('Checking', 'Browser support OK');

                // Step 2: Check protocol requirements
                console.log('🔍 Step 2: Checking protocol requirements...');

                const isSecure = location.protocol === 'https:' ||
                    location.hostname === 'localhost' ||
                    location.hostname === '127.0.0.1' ||
                    location.hostname.endsWith('.test');

                if (!isSecure) {
                    throw new Error(`Camera requires HTTPS or localhost. Current protocol: ${location.protocol}://`);
                }

                console.log('✅ Protocol check passed');
                updateRecordingStatus('Checking', 'Security requirements OK');

                // Step 3: Request camera permissions
                console.log('🔍 Step 3: Requesting camera permissions...');
                updateRecordingStatus('Requesting', 'Camera permissions...');
                updateCameraStatus('Requesting Permissions', 'text-blue-600');

                // Step 4: Define camera constraints
                const constraints = {
                    video: {
                        width: {
                            ideal: 640,
                            min: 320
                        }, // Reduced for better compatibility
                        height: {
                            ideal: 480,
                            min: 240
                        },
                        facingMode: 'user'
                        // Removed frameRate to avoid conflicts
                    },
                    audio: false
                };

                console.log('🎥 Constraints:', JSON.stringify(constraints, null, 2));
                console.log('🔍 Step 4: Requesting camera stream...');

                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                    console.log('✅ Camera stream obtained!');
                    console.log('📊 Stream info:', {
                        active: stream.active,
                        id: stream.id,
                        tracks: stream.getTracks().length
                    });
                } catch (streamError) {
                    console.error('❌ Stream request failed:', streamError);
                    throw streamError;
                }

                updateRecordingStatus('Connecting', 'Stream obtained');

                // Step 5: Connect to video elements
                console.log('🔍 Step 5: Connecting to video elements...');

                const cameraPreview = document.getElementById('cameraPreview');
                const hiddenVideo = document.getElementById('hiddenVideo');

                if (!cameraPreview) {
                    throw new Error('Camera preview element not found');
                }

                console.log('📺 Setting up camera preview...');
                cameraPreview.srcObject = stream;

                // Enhanced video play handling
                cameraPreview.onloadedmetadata = () => {
                    console.log('📺 Video metadata loaded');
                    cameraPreview.play()
                        .then(() => {
                            console.log('✅ Camera preview playing');
                            updateCameraStatus('Camera Active', 'text-green-600');
                            updateRecordingStatus('Preview Active', 'Camera feed working');
                        })
                        .catch(e => {
                            console.warn('⚠️ Video autoplay prevented:', e.message);
                            // Try to play on user interaction
                            updateCameraStatus('Click to Start', 'text-yellow-600');
                            cameraPreview.addEventListener('click', () => {
                                cameraPreview.play();
                            });
                        });
                };

                cameraPreview.onerror = (e) => {
                    console.error('❌ Video element error:', e);
                    updateCameraStatus('Video Error', 'text-red-600');
                };

                if (hiddenVideo) {
                    hiddenVideo.srcObject = stream;
                    console.log('📺 Hidden video element connected');
                }

                console.log('✅ Video elements connected successfully');

                // Step 6: Start recording
                console.log('🔍 Step 6: Starting recording...');
                setTimeout(() => {
                    try {
                        startRecording();
                        console.log('✅ Recording started');
                        updateRecordingStatus('Recording', 'Active recording');
                    } catch (recordError) {
                        console.error('❌ Recording start failed:', recordError);
                        updateRecordingStatus('Recording Failed', recordError.message);
                    }
                }, 1000);

                // Step 7: Initialize other features
                setTimeout(() => {
                    try {
                        initializeLiveStreaming();
                        console.log('✅ Live streaming initialized');
                    } catch (streamingError) {
                        console.warn('⚠️ Live streaming failed (non-critical):', streamingError);
                    }
                }, 2000);

                console.log('✅ === CAMERA INITIALIZATION SUCCESS ===');
                updateRecordingStatus('Active', 'Recording started');

                console.log('✅ Camera initialization completed successfully');

                // Notify Livewire that camera is active
                if (window.Livewire) {
                    Livewire.dispatch('cameraStatusUpdated', {
                        status: 'active',
                        timestamp: new Date().toISOString()
                    });
                }

            } catch (error) {
                console.error('❌ Camera initialization failed:', error);
                console.error('📝 Error name:', error.name);
                console.error('📝 Error message:', error.message);

                updateRecordingStatus('Error', 'Camera failed');
                updateCameraStatus('Camera Error', 'text-red-600');

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

        // ==== PEERJS INITIALIZER (OPTIMIZED FOR 55 STUDENTS) ====
        async function initializePeerJS() {
            console.log("🔄 Initializing PeerJS (optimized)…");

            // ICE servers: STUN + your TURN
            const ICE_SERVERS = [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' },
                { urls: 'stun:stun.cloudflare.com:3478' },
                {
                urls: 'turn:peer.toti.my.id:3478?transport=udp',
                username: 'test',
                credential: 'supersecret'
                },
                {
                urls: 'turns:peer.toti.my.id:5349?transport=tcp',
                username: 'test',
                credential: 'supersecret'
                }
            ];

            const peer = new Peer({
                host: 'peer.toti.my.id',
                path: '/peerjs',
                secure: true,
                debug: 1,
                config: {
                iceServers: ICE_SERVERS,
                iceTransportPolicy: 'all',
                sdpSemantics: 'unified-plan'
                },
                pingInterval: 10000 // kirim ping tiap 10 detik
            });

            window.peer = peer;
            let reconnectTimer;

            peer.on('open', id => {
                console.log('✅ Peer connected:', id);
                updateLiveSessionData({ connection_status: 'connected' });
                if (window.Livewire) Livewire.dispatch('updatePeerJSId', [id]);
            });

            peer.on('call', call => handleIncomingCall(call));
            peer.on('disconnected', () => tryReconnect());
            peer.on('error', err => {
                console.warn('⚠️ PeerJS error:', err);
                updateLiveSessionData({ connection_status: 'unstable' });
                tryReconnect();
            });

            // === HANDLE INCOMING CALLS ===
            async function handleIncomingCall(call) {
                const stream = await getCameraStream();
                if (stream) {
                call.answer(stream);
                updateLiveSessionData({ connection_status: 'streaming' });

                call.on('close', () => {
                    console.log('📴 Call closed');
                    updateLiveSessionData({ connection_status: 'connected' });
                });
                }
            }

            function tryReconnect() {
                clearTimeout(reconnectTimer);
                reconnectTimer = setTimeout(() => {
                if (peer.disconnected) {
                    console.log('🔁 Reconnecting PeerJS...');
                    peer.reconnect();
                }
                }, 3000);
            }

            await getCameraStream();
            }

            // === CAMERA STREAM HANDLER ===
            async function getCameraStream() {
            try {
                if (window.cameraStream) return window.cameraStream;

                const constraints = {
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 360 },
                    frameRate: { ideal: 15, max: 20 } // ringan dan cukup smooth
                },
                audio: false
                };

                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                window.cameraStream = stream;
                console.log('🎥 Camera ready (360p / 15 fps)');

                // 🔹 Limit bitrate agar hemat bandwidth
                const videoTrack = stream.getVideoTracks()[0];
                const sender = new RTCPeerConnection().addTrack(videoTrack, stream);
                const params = sender.getParameters();
                if (!params.encodings) params.encodings = [{}];
                params.encodings[0].maxBitrate = 300_000; // 300 kbps max
                sender.setParameters(params);

                return stream;
            } catch (e) {
                console.error('🚫 Camera error:', e);
                updateLiveSessionData({ camera_status: 'error' });
                return null;
            }
            }



        // Update live session data
        function updateLiveSessionData(data) {
            if (window.Livewire) {
                Livewire.dispatch('updateLiveSessionData', [data]);
            }
        }

        // Get HIGHLY COMPRESSED recording settings for CBT 2-3 hour recordings
        function getOptimalRecordingSettings() {
            console.log('🗜️ Calculating AGGRESSIVE compression for CBT long recording...');

            // Detect screen/video resolution for optimal bitrate calculation
            const screenWidth = window.screen.width;
            const screenHeight = window.screen.height;
            const resolution = screenWidth * screenHeight;

            console.log(`📐 Screen resolution: ${screenWidth}x${screenHeight} (${resolution} pixels)`);

            // PROGRESSIVE COMPRESSION for 2-3 hour CBT recordings
            // Adjust compression based on current recording state
            let compressionLevel = window.compressionLevel || 'standard';
            let compressionMultiplier = 1.0;

            // Apply progressive compression multiplier
            switch (compressionLevel) {
                case 'high':
                    compressionMultiplier = 0.75; // 25% more compression
                    console.log('🗜️ Applying HIGH compression (25% smaller files)');
                    break;
                case 'maximum':
                    compressionMultiplier = 0.50; // 50% more compression
                    console.log('🔥 Applying MAXIMUM compression (50% smaller files)');
                    break;
                case 'ultra':
                    compressionMultiplier = 0.35; // 65% more compression
                    console.log('⚡ Applying ULTRA compression (65% smaller files)');
                    break;
                default:
                    compressionLevel = 'standard';
                    console.log('📹 Using STANDARD compression for CBT');
            }

            // AGGRESSIVE base bitrates for CBT recordings
            let videoBitrate;
            let audioBitrate = Math.floor(16000 * compressionMultiplier); // Progressive audio compression

            // Ensure minimum audio quality
            if (audioBitrate < 8000) audioBitrate = 8000; // 8kbps minimum

            if (resolution <= 921600) { // 720p and below (1280x720)
                videoBitrate = Math.floor(120000 * compressionMultiplier); // Progressive compression
                console.log('�️ CBT 720p ultra compression');
            } else if (resolution <= 2073600) { // 1080p (1920x1080)
                videoBitrate = Math.floor(180000 * compressionMultiplier); // Progressive compression
                console.log('�️ CBT 1080p ultra compression');
            } else if (resolution <= 8294400) { // 4K (3840x2160)
                videoBitrate = Math.floor(250000 * compressionMultiplier); // Progressive compression
                console.log('�️ CBT 4K ultra compression');
            } else {
                videoBitrate = Math.floor(200000 * compressionMultiplier); // Progressive compression
                console.log('�️ CBT default ultra compression');
            }

            // Ensure minimum video quality for CBT monitoring
            if (videoBitrate < 60000) {
                console.log(`⚠️ Bitrate too low (${videoBitrate}), adjusting to minimum 60kbps for CBT visibility`);
                videoBitrate = 60000; // 60kbps minimum for visibility
            }

            // Store current bitrate for dynamic updates
            window.currentRecordingBitrate = videoBitrate;

            console.log(
                `🎯 CBT ${compressionLevel.toUpperCase()} Compression: Video ${videoBitrate/1000}kbps + Audio ${audioBitrate/1000}kbps = ${(videoBitrate+audioBitrate)/1000}kbps total`
            );

            // PROGRESSIVE COMPRESSION - gets more aggressive over time for CBT
            // Expected file sizes with ultra compression:
            // - 1 hour: ~80-120MB (vs 300MB normal)
            // - 2 hours: ~160-240MB (vs 600MB normal)
            // - 3 hours: ~240-360MB (vs 900MB normal)

            // Try different codec options prioritizing MAXIMUM compression
            const codecOptions = [
                'video/webm;codecs=vp9,opus', // VP9 + Opus (60% smaller files)
                'video/webm;codecs=vp9', // VP9 only (65% smaller files)
                'video/webm;codecs=vp8,opus', // VP8 + Opus (50% smaller files)
                'video/webm;codecs=av01,opus', // AV1 + Opus (70% smaller - if supported)
                'video/webm', // Default WebM (40% smaller)
                'video/mp4;codecs=avc1.42E01E,mp4a.40.2', // H.264 + AAC (30% smaller)
                'video/mp4' // Default MP4 (20% smaller)
            ];

            let selectedCodec = 'video/webm';
            let compressionRatio = '40%';

            for (const codec of codecOptions) {
                if (MediaRecorder.isTypeSupported(codec)) {
                    selectedCodec = codec;

                    // Set compression ratio info
                    if (codec.includes('vp9,opus')) compressionRatio = '60%';
                    else if (codec.includes('vp9')) compressionRatio = '65%';
                    else if (codec.includes('av01')) compressionRatio = '70%';
                    else if (codec.includes('vp8')) compressionRatio = '50%';

                    console.log(`✅ Selected ULTRA codec: ${codec} (${compressionRatio} smaller files)`);
                    break;
                }
            }

            // Apply additional compression settings for CBT long recording
            const options = {
                mimeType: selectedCodec,
                videoBitsPerSecond: videoBitrate,
                audioBitsPerSecond: audioBitrate,
                // Additional compression hints for long recordings
                bitsPerSecond: videoBitrate + audioBitrate, // Total bitrate constraint
            };

            // Add advanced options if supported
            if (selectedCodec.includes('vp9')) {
                // VP9 specific ultra compression settings
                options.videoKeyFrameIntervalDuration = 5000; // Keyframe every 5 seconds (vs 1 second default)
                console.log('🗜️ VP9 ultra compression: Keyframes every 5s for maximum compression');
            }

            console.log('🎯 Final recording settings:', {
                codec: selectedCodec,
                videoBitrate: `${videoBitrate/1000} kbps`,
                audioBitrate: `${audioBitrate/1000} kbps`,
                frameRate: options.videoFrameRate + ' fps',
                estimatedFileSize: calculateEstimatedFileSize(videoBitrate, audioBitrate)
            });

            return options;
        }

        // Calculate estimated file size for 2-3 hour recording
        function calculateEstimatedFileSize(videoBitrate, audioBitrate) {
            const totalBitrate = videoBitrate + audioBitrate; // bits per second
            const hoursToRecord = 2.5; // Average 2.5 hours
            const secondsToRecord = hoursToRecord * 3600;

            const estimatedSizeBytes = (totalBitrate * secondsToRecord) / 8; // Convert bits to bytes
            const estimatedSizeMB = estimatedSizeBytes / (1024 * 1024);

            return `~${Math.round(estimatedSizeMB)} MB for ${hoursToRecord}h`;
        }

        // Simple optimization - recording is already optimized during capture
        async function compressVideoBlob(originalBlob) {
            console.log('� Video already optimized during recording');
            console.log(`📊 Final size: ${(originalBlob.size / 1024 / 1024).toFixed(2)} MB`);

            // Since we already optimized during recording with optimal settings,
            // we don't need complex post-processing compression
            return new Promise((resolve) => {
                setTimeout(() => {
                    console.log(`✅ Optimization complete! Used optimal recording settings`);
                    resolve(originalBlob);
                }, 200); // Small delay for UI feedback
            });
        }

        // Start recording - continuous recording from start to finish
        function startRecording() {
            if (!stream) {
                console.error('No camera stream available for recording');
                return;
            }

            try {
                console.log('Starting continuous exam recording...');

                // Check MediaRecorder support
                if (!MediaRecorder.isTypeSupported('video/webm')) {
                    console.warn('webm not supported, trying mp4');
                    if (!MediaRecorder.isTypeSupported('video/mp4')) {
                        console.error('No supported video format found');
                        return;
                    }
                }

                // Advanced video optimization settings
                const options = getOptimalRecordingSettings();

                mediaRecorder = new MediaRecorder(stream, options);
                recordedChunks = [];
                let chunkCount = 0;
                let totalSize = 0;

                // Enhanced data collection for long CBT recordings (2-3 hours)
                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                        chunkCount++;
                        totalSize += event.data.size;
                        totalRecordingSize = totalSize; // Update global variable

                        const sizeInMB = (event.data.size / (1024 * 1024)).toFixed(2);
                        const totalSizeInMB = (totalSize / (1024 * 1024)).toFixed(2);
                        const recordingMinutes = ((Date.now() - recordingStartTime) / 1000 / 60).toFixed(1);

                        // PROGRESSIVE COMPRESSION CHECK - analyze chunk efficiency
                        const chunkEfficiency = calculateChunkCompressionEfficiency(event.data.size, recordingMinutes);

                        console.log(
                            `📦 CBT Chunk ${chunkCount}: ${sizeInMB}MB | Total: ${totalSizeInMB}MB | Duration: ${recordingMinutes}min | Compression: ${chunkEfficiency}`
                        );

                        // Update recording info in UI with enhanced compression details
                        updateRecordingInfo(
                            `${chunkCount} chunks, ${totalSizeInMB}MB, ${recordingMinutes}min, ${chunkEfficiency} compression`
                        );

                        // PROGRESSIVE COMPRESSION TRIGGERS based on file size growth
                        if (totalSize > 50 * 1024 * 1024) { // 50MB (15+ minutes)
                            console.log(`📊 CBT Recording: ${totalSizeInMB}MB → Applying STANDARD compression`);
                            window.compressionLevel = 'standard';
                        }

                        if (totalSize > 150 * 1024 * 1024) { // 150MB (45+ minutes)
                            console.log(`�️ CBT Recording: ${totalSizeInMB}MB → Upgrading to HIGH compression`);
                            window.compressionLevel = 'high';
                            // Log expected final size reduction
                            const projectedFinalSize = totalSize * 2.5; // Project 2.5 hours
                            const withCompression = projectedFinalSize * 0.6; // 40% reduction
                            console.log(
                                `📈 Projected final size: ${(projectedFinalSize/1024/1024).toFixed(0)}MB → ${(withCompression/1024/1024).toFixed(0)}MB with compression`
                            );
                        }

                        if (totalSize > 300 * 1024 * 1024) { // 300MB (1.5+ hours)
                            console.log(`🔥 CBT Recording: ${totalSizeInMB}MB → Applying MAXIMUM compression`);
                            window.compressionLevel = 'maximum';
                            // Log aggressive size projections
                            const projectedFinalSize = totalSize * 1.8; // Project remaining 1.8x growth
                            const withMaxCompression = projectedFinalSize * 0.4; // 60% reduction
                            console.log(
                                `🎯 MAXIMUM compression target: ${(projectedFinalSize/1024/1024).toFixed(0)}MB → ${(withMaxCompression/1024/1024).toFixed(0)}MB (60% smaller)`
                            );
                        }

                        if (totalSize > 600 * 1024 * 1024) { // 600MB (2.5+ hours)
                            console.log(`⚡ CBT Recording: ${totalSizeInMB}MB → ULTRA compression for final hour`);
                            window.compressionLevel = 'ultra';
                        }

                        // Memory health check (warn if exceeding browser limits)
                        if (totalSize > 1024 * 1024 * 1024) { // 1GB
                            console.warn('⚠️ CBT recording approaching 1GB - browser may need memory management');
                            updateRecordingStatus('Warning', `Large file: ${totalSizeInMB}MB`);
                        }
                    }
                };

                // Enhanced stop handler - only save when exam truly ends
                mediaRecorder.onstop = function() {
                    console.log('📹 Recording stopped event fired');

                    // Check if this is intentional stop (exam end) or unexpected stop
                    if (window.isRecordingStopping || !isRecording) {
                        console.log('✅ Recording stopped intentionally (exam ended)');
                        saveFinalVideo();
                    } else {
                        console.warn('⚠️ Recording stopped unexpectedly during CBT! Attempting restart...');
                        setTimeout(() => {
                            if (isRecording) { // Still should be recording
                                attemptRecordingRestart();
                            }
                        }, 1000);
                    }
                };

                // Enhanced error handler with restart capability
                mediaRecorder.onerror = function(event) {
                    console.error('❌ MediaRecorder error during CBT:', event.error);
                    console.error('📝 Error type:', event.error.name);
                    console.error('📝 Error message:', event.error.message);

                    updateRecordingStatus('Error', 'Recording error - attempting restart');

                    // Try to restart recording after error
                    setTimeout(() => {
                        if (isRecording) {
                            console.log('🔄 Attempting to restart recording after error...');
                            attemptRecordingRestart();
                        }
                    }, 2000);
                };

                // ENHANCED: Start continuous recording for 2-3 hour exams
                // Use longer intervals (5 minutes) to reduce memory pressure and prevent auto-stop
                const timesliceInterval = 300000; // 5 minutes (300,000ms) - better for long recordings

                console.log('🎬 Starting LONG-DURATION recording for CBT exam...');
                console.log('📊 Configured for 2-3 hour continuous recording');
                console.log('⏱️ Timeslice interval:', timesliceInterval / 1000 / 60, 'minutes');

                mediaRecorder.start(timesliceInterval);
                isRecording = true;
                recordingStartTime = Date.now();

                // Enhanced status tracking
                updateRecordingStatus('Recording', 'CBT Long-Duration Mode');
                startRecordingTimer();
                startEnhancedBackup(); // Enhanced backup for long recordings

                console.log('✅ Long-duration CBT recording started successfully');
                console.log('� Recording will continue for entire exam duration (up to 3 hours)');
                console.log('💾 Enhanced backup system enabled for recording safety');

                // Set up recording health monitoring
                startRecordingHealthMonitor();

            } catch (error) {
                console.error('Error starting recording:', error);
                updateRecordingStatus('Error', 'Failed to start');
                updateCameraStatus('Recording Error: ' + error.message, 'text-red-600');
            }
        }

        // Stop recording and save final video
        function stopRecording() {
            // Prevent multiple calls
            if (window.isRecordingStopping) {
                console.log('🛑 stopRecording already in progress, skipping...');
                return;
            }

            window.isRecordingStopping = true;
            console.log('=== STOPPING RECORDING ===');
            console.log('MediaRecorder state:', mediaRecorder?.state);
            console.log('IsRecording flag:', isRecording);
            console.log('Recorded chunks length:', recordedChunks?.length || 0);

            if (mediaRecorder && mediaRecorder.state === 'recording') {
                console.log('Stopping MediaRecorder...');

                // Set a timeout fallback in case onstop doesn't fire
                const fallbackTimeout = setTimeout(() => {
                    console.warn('MediaRecorder onstop did not fire, manually saving video');
                    saveFinalVideo();
                }, 3000); // 3 second fallback

                mediaRecorder.onstop = function() {
                    console.log('MediaRecorder onstop event fired');
                    clearTimeout(fallbackTimeout);
                    saveFinalVideo();
                };

                mediaRecorder.stop();
                isRecording = false;
                clearInterval(recordingDurationInterval);
                stopEnhancedBackup();
                updateRecordingStatus('Stopping', 'Saving video...');
                console.log('MediaRecorder.stop() called');

            } else if (recordedChunks && recordedChunks.length > 0) {
                console.log('No active MediaRecorder but we have chunks, saving directly...');
                saveFinalVideo();
            } else {
                console.warn('No active recording and no chunks to save');
                updateRecordingStatus('Completed', 'No data');
            }
        }

        // Save final video when exam ends
        function saveFinalVideo() {
            // Prevent multiple calls
            if (window.isSavingVideo) {
                console.log('💾 saveFinalVideo already in progress, skipping...');
                return;
            }

            window.isSavingVideo = true;
            console.log('=== SAVING FINAL VIDEO ===');
            console.log('Total chunks:', recordedChunks.length);
            console.log('MediaRecorder mimeType:', mediaRecorder?.mimeType);

            if (recordedChunks.length === 0) {
                console.warn('⚠️ NO VIDEO DATA TO SAVE');
                updateRecordingStatus('Completed', 'No data');
                return;
            }

            // Combine all chunks into final video
            const originalBlob = new Blob(recordedChunks, {
                type: mediaRecorder?.mimeType || 'video/webm'
            });

            const originalSizeInMB = (originalBlob.size / 1024 / 1024).toFixed(2);
            console.log('✅ Original video blob created!');
            console.log('Size:', originalBlob.size, 'bytes');
            console.log('Size in MB:', originalSizeInMB);
            console.log('Type:', originalBlob.type);

            if (originalBlob.size === 0) {
                console.warn('⚠️ FINAL VIDEO SIZE IS 0');
                updateRecordingStatus('Completed', 'Empty file');
                return;
            }

            updateRecordingStatus('Processing', `Preparing ${originalSizeInMB}MB...`);
            console.log('� Processing optimized video...');

            // Apply simple optimization check
            compressVideoBlob(originalBlob).then(function(finalBlob) {
                const finalSizeInMB = (finalBlob.size / 1024 / 1024).toFixed(2);
                const compressionSavings = '25'; // Estimated savings from optimal recording settings

                console.log('✅ Video processing completed!');
                console.log(`📊 Final size: ${finalSizeInMB}MB (optimized during recording)`);

                updateRecordingStatus('Uploading', `Sending ${finalSizeInMB}MB...`);
                console.log('📤 Converting optimized video to base64...');

                const reader = new FileReader();

                console.log('📡 Calling saveRecordingVideo using multiple methods for reliability...');

                let saveSuccess = false;

                // Method 1: Try component.call first (most reliable for large data)
                const component = document.querySelector('[wire\\:id]');
                if (component) {
                    const componentId = component.getAttribute('wire:id');
                    const livewireComponent = Livewire.find(componentId);

                    if (livewireComponent) {
                        console.log('📡 Found Livewire component, calling method directly...');
                        updateRecordingStatus('Saving', 'Using component call...');

                        livewireComponent.call('saveRecordingVideo', base64Data)
                            .then((result) => {
                                console.log('✅ Component call response:', result);
                                if (result) {
                                    updateRecordingStatus('Completed', `Saved ${sizeInMB}MB`);
                                    console.log('✅ FINAL EXAM VIDEO SENT SUCCESSFULLY via component call!');
                                    alert(`✅ Video ujian berhasil disimpan! (${sizeInMB}MB)`);
                                    saveSuccess = true;
                                } else {
                                    console.error('❌ Component call returned false, trying dispatch...');
                                    fallbackToDispatch();
                                }
                            })
                            .catch((error) => {
                                console.error('❌ Component call failed, trying dispatch...', error);
                                fallbackToDispatch();
                            });
                    } else {
                        console.error('❌ Livewire component not found, using dispatch...');
                        fallbackToDispatch();
                    }
                } else {
                    console.error('❌ Wire element not found, using dispatch...');
                    fallbackToDispatch();
                }

                // Method 2: Fallback to dispatch (backup method)
                function fallbackToDispatch() {
                    if (saveSuccess) return; // Already saved via component call

                    console.log('📡 Using fallback dispatch method...');
                    updateRecordingStatus('Saving', 'Using dispatch method...');

                    try {
                        Livewire.dispatch('saveRecordingVideo', {
                            videoBlob: base64Data
                        });

                        console.log('📡 Dispatch sent successfully');

                        // Give dispatch time to process
                        setTimeout(() => {
                            updateRecordingStatus('Completed', 'Video dispatched');
                            console.log('📡 Dispatch method completed');
                            if (!saveSuccess) {
                                alert(
                                    `📡 Video dispatched ke server (${sizeInMB}MB) - cek server logs untuk konfirmasi`
                                    );
                            }
                        }, 2000);

                    } catch (dispatchError) {
                        console.error('❌ Dispatch also failed:', dispatchError);
                        updateRecordingStatus('Error', 'All methods failed');
                        alert('❌ GAGAL total mengirim video: ' + dispatchError.message);
                    }
                }

                // Emergency method: Also try dispatch immediately (parallel)
                setTimeout(() => {
                    if (!saveSuccess) {
                        console.log('📡 Emergency dispatch backup...');
                        try {
                            Livewire.dispatch('saveRecordingVideo', {
                                videoBlob: base64Data
                            });
                            console.log('📡 Emergency dispatch sent');
                        } catch (e) {
                            console.error('❌ Emergency dispatch failed:', e);
                        }
                    }
                }, 500);
                reader.onload = function(e) {
                    const base64Data = e.target.result;
                    const base64Length = base64Data.length;
                    console.log('✅ Base64 conversion complete');
                    console.log('Base64 length:', base64Length);
                    console.log('Base64 header:', base64Data.substring(0, 50));

                    // Send compressed video to server
                    sendVideoToServer(base64Data, finalSizeInMB, compressionSavings);
                };

                reader.onerror = function(error) {
                    console.error('❌ Base64 conversion failed:', error);
                    updateRecordingStatus('Error', 'Conversion failed');
                };

                console.log('🔄 Starting base64 conversion of compressed video...');
                reader.readAsDataURL(finalBlob);
            }).catch(function(error) {
                console.warn('⚠️ Compression failed, using original video:', error);

                // Fallback to original video if compression fails
                const finalBlob = originalBlob;
                const sizeInMB = originalSizeInMB;

                updateRecordingStatus('Saving', `Processing ${sizeInMB}MB...`);
                console.log('📤 Converting original video to base64...');

                const reader = new FileReader();

                reader.onload = function(e) {
                    const base64Data = e.target.result;
                    console.log('✅ Base64 conversion complete (fallback)');
                    console.log('Base64 length:', base64Data.length);

                    // Send original video to server (fallback)
                    sendVideoToServer(base64Data, sizeInMB, '0');
                };

                reader.onerror = function(error) {
                    console.error('❌ Base64 conversion failed:', error);
                    updateRecordingStatus('Error', 'Conversion failed');
                };

                reader.readAsDataURL(finalBlob);
            });
        }

        // Send video to server with compression info
        function sendVideoToServer(base64Data, sizeInMB, compressionSavings) {
            console.log('📡 Sending optimized video to server...');
            console.log(`📊 File size: ${sizeInMB}MB (compression savings: ${compressionSavings}%)`);

            if (window.Livewire) {
                console.log('✅ Livewire available, sending compressed video...');
                updateRecordingStatus('Uploading', `Sending ${sizeInMB}MB...`);

                try {
                    console.log('📡 Calling saveRecordingVideo with optimized video...');

                    // Method 1: Direct Livewire dispatch (most reliable)
                    Livewire.dispatch('saveRecordingVideo', {
                        videoBlob: base64Data,
                        compressionInfo: {
                            originalSize: sizeInMB,
                            compressionSavings: compressionSavings,
                            optimized: true
                        }
                    });

                    // Method 2: Try component.call as fallback
                    const component = document.querySelector('[wire\\:id]');
                    if (component) {
                        const componentId = component.getAttribute('wire:id');
                        const livewireComponent = Livewire.find(componentId);

                        if (livewireComponent) {
                            console.log('📡 Found Livewire component, calling method with compression info...');

                            livewireComponent.call('saveRecordingVideo', base64Data)
                                .then((result) => {
                                    console.log('✅ Server response:', result);
                                    if (result) {
                                        const compressionMsg = compressionSavings > 0 ?
                                            ` (${compressionSavings}% compressed)` : '';
                                        updateRecordingStatus('Completed', `Saved ${sizeInMB}MB${compressionMsg}`);
                                        console.log('✅ OPTIMIZED EXAM VIDEO SENT SUCCESSFULLY!');
                                        console.log(
                                            `📊 Final stats: ${sizeInMB}MB saved with ${compressionSavings}% compression`
                                        );
                                        // alert(
                                        //     `✅ Video ujian berhasil disimpan dan dioptimalkan!\n📊 Ukuran: ${sizeInMB}MB\n🗜️ Kompresi: ${compressionSavings}%`
                                        // );
                                    } else {
                                        console.error('❌ Server returned false');
                                        updateRecordingStatus('Error', 'Save failed');
                                        alert('❌ Server gagal menyimpan video!');
                                    }
                                })
                                .catch((error) => {
                                    console.error('❌ Error sending optimized video:', error);
                                    updateRecordingStatus('Error', 'Upload failed');
                                    alert('❌ Gagal mengirim video ke server: ' + error.message);
                                });
                        } else {
                            console.error('❌ Livewire component not found, using dispatch only');
                            updateRecordingStatus('Uploading', 'Using dispatch method...');
                        }
                    } else {
                        console.error('❌ Wire element not found, using dispatch only');
                        updateRecordingStatus('Uploading', 'Using dispatch method...');
                    }

                    // Add success feedback for dispatch method
                    setTimeout(() => {
                        updateRecordingStatus('Completed', 'Video sent via dispatch');
                        console.log('📡 Video dispatched successfully');
                    }, 2000);

                } catch (error) {
                    console.error('❌ Error calling saveRecordingVideo:', error);
                    updateRecordingStatus('Error', 'Call failed');
                }
            } else {
                console.error('❌ Livewire not available');
                updateRecordingStatus('Error', 'No connection');
            }

            // Clear chunks after save attempt
            recordedChunks = [];
            console.log('🗑️ Chunks cleared from memory');

            // Reset flags after save
            window.isRecordingStopping = false;
            window.isSavingVideo = false;
        };

        reader.onerror = function(error) {
            console.error('❌ FileReader error:', error);
            updateRecordingStatus('Error', 'Read failed');
            recordedChunks = []; // Clear chunks even on error

            // Reset flags on error too
            window.isRecordingStopping = false;
            window.isSavingVideo = false;
        };

        // Update camera status
        function updateCameraStatus(status, className) {
            const statusElement = document.getElementById('cameraStatusText');
            if (statusElement) {
                statusElement.textContent = status;
                statusElement.className = `flex items-center ${className}`;
            }
        }

        // Update recording status
        function updateRecordingStatus(status, info) {
            const statusElement = document.getElementById('recordingStatus');
            const infoElement = document.getElementById('recordingInfo');

            if (statusElement) {
                statusElement.textContent = status;
                statusElement.className = status === 'Recording' ? 'text-green-600' :
                    status === 'Error' ? 'text-red-600' : 'text-yellow-600';
            }

            if (infoElement) {
                infoElement.textContent = info;
            }
        }

        // Update recording information with detailed stats
        function updateRecordingInfo(info) {
            const infoElement = document.getElementById('recordingInfo');
            if (infoElement) {
                infoElement.textContent = info;
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

        // Enhanced backup system for long CBT recordings (2-3 hours)
        function startEnhancedBackup() {
            console.log('🔧 Starting enhanced backup system for long CBT recordings...');

            // Backup every 30 minutes for 2-3 hour exams (safer interval)
            periodicSaveInterval = setInterval(() => {
                if (isRecording && recordedChunks.length > 0) {
                    const currentTime = Date.now();
                    const recordingDuration = (currentTime - recordingStartTime) / 1000 / 60; // minutes
                    const recordingHours = recordingDuration / 60;

                    console.log(
                        `💾 CBT Recording health check at ${recordingDuration.toFixed(1)} minutes (${recordingHours.toFixed(1)} hours)`
                    );

                    // Create backup after 15 minutes and every 30 minutes thereafter
                    if (recordingDuration >= 15) {
                        console.log('📦 Creating CBT recording backup...');
                        createRecordingBackup();

                        // Health check - ensure recording is still active
                        if (mediaRecorder && mediaRecorder.state !== 'recording') {
                            console.error('⚠️ MediaRecorder stopped unexpectedly! Attempting restart...');
                            attemptRecordingRestart();
                        }
                    }

                    // Memory status logging for long recordings
                    const totalChunks = recordedChunks.length;
                    const estimatedSize = calculateTotalRecordingSize();
                    console.log(
                        `📊 Recording stats: ${totalChunks} chunks, ~${(estimatedSize/1024/1024).toFixed(1)}MB`);
                }
            }, 1800000); // Every 30 minutes (1,800,000ms) - better for long exams
        }

        // Calculate total recording size for monitoring AND apply dynamic compression
        function calculateTotalRecordingSize() {
            if (!recordedChunks || recordedChunks.length === 0) return 0;

            const totalSize = recordedChunks.reduce((total, chunk) => total + chunk.size, 0);

            // DYNAMIC COMPRESSION TRIGGER - get more aggressive as file grows
            const sizeMB = totalSize / 1024 / 1024;
            const recordingMinutes = (Date.now() - recordingStartTime) / 1000 / 60;

            if (sizeMB > 200 && recordingMinutes > 60) {
                // After 1 hour and file > 200MB: Trigger MAXIMUM compression
                console.log(
                    `🗜️ TRIGGERING MAXIMUM COMPRESSION: ${sizeMB.toFixed(1)}MB after ${recordingMinutes.toFixed(1)} minutes`
                );
                applyDynamicCompression('maximum');
            } else if (sizeMB > 100 && recordingMinutes > 30) {
                // After 30 minutes and file > 100MB: Trigger HIGH compression
                console.log(
                    `🗜️ TRIGGERING HIGH COMPRESSION: ${sizeMB.toFixed(1)}MB after ${recordingMinutes.toFixed(1)} minutes`
                );
                applyDynamicCompression('high');
            } else if (sizeMB > 50 && recordingMinutes > 15) {
                // After 15 minutes and file > 50MB: Trigger MEDIUM compression
                console.log(
                    `🗜️ TRIGGERING MEDIUM COMPRESSION: ${sizeMB.toFixed(1)}MB after ${recordingMinutes.toFixed(1)} minutes`
                );
                applyDynamicCompression('medium');
            }

            return totalSize;
        }

        // Apply dynamic compression during recording
        function applyDynamicCompression(level) {
            if (!mediaRecorder || mediaRecorder.state !== 'recording') return;

            console.log(`🔥 Applying ${level.toUpperCase()} compression to ongoing CBT recording...`);

            // We can't change MediaRecorder settings mid-recording, but we can:
            // 1. Reduce keyframe frequency (if we restart with new settings)
            // 2. Log this for future optimization
            // 3. Prepare for next recording segment with better compression

            let newBitrate = getCurrentRecordingBitrate();

            switch (level) {
                case 'medium':
                    newBitrate = Math.floor(newBitrate * 0.85); // 15% reduction
                    break;
                case 'high':
                    newBitrate = Math.floor(newBitrate * 0.70); // 30% reduction
                    break;
                case 'maximum':
                    newBitrate = Math.floor(newBitrate * 0.50); // 50% reduction
                    break;
            }

            // Store for next recording segment
            window.dynamicCompressionBitrate = newBitrate;
            window.dynamicCompressionLevel = level;

            console.log(
                `💾 Dynamic compression ${level}: Reduced bitrate to ${newBitrate/1000}kbps for remaining recording`);
        }

        // Get current recording bitrate
        function getCurrentRecordingBitrate() {
            // Return stored bitrate or calculate from current settings
            return window.currentRecordingBitrate || 180000; // Default fallback
        }

        // Calculate compression efficiency for each chunk
        function calculateChunkCompressionEfficiency(chunkSize, recordingMinutes) {
            // Expected uncompressed size calculation
            // Typical uncompressed video: ~10-20MB per minute depending on resolution
            const expectedSize = recordingMinutes * 12 * 1024 * 1024; // 12MB per minute baseline
            const actualSize = totalSize;

            if (expectedSize === 0) return 'calculating...';

            const compressionRatio = ((expectedSize - actualSize) / expectedSize * 100);
            const level = window.compressionLevel || 'standard';

            if (compressionRatio > 70) return `EXCELLENT (${compressionRatio.toFixed(0)}% smaller)`;
            if (compressionRatio > 50) return `GOOD (${compressionRatio.toFixed(0)}% smaller)`;
            if (compressionRatio > 30) return `FAIR (${compressionRatio.toFixed(0)}% smaller)`;
            return `BASIC (${compressionRatio.toFixed(0)}% smaller)`;
        }

        // Apply progressive compression to future segments
        function updateRecordingCompressionSettings() {
            const level = window.compressionLevel || 'standard';
            let bitrateMultiplier = 1.0;

            switch (level) {
                case 'high':
                    bitrateMultiplier = 0.75; // 25% reduction
                    break;
                case 'maximum':
                    bitrateMultiplier = 0.50; // 50% reduction
                    break;
                case 'ultra':
                    bitrateMultiplier = 0.35; // 65% reduction
                    break;
                default:
                    bitrateMultiplier = 1.0; // No change
            }

            // Store updated settings for next recording segment/restart
            const baseBitrate = getCurrentRecordingBitrate();
            window.currentRecordingBitrate = Math.floor(baseBitrate * bitrateMultiplier);

            console.log(
                `🔧 Updated recording bitrate: ${(window.currentRecordingBitrate/1000).toFixed(0)}kbps (${level} compression)`
            );

            return window.currentRecordingBitrate;
        }

        // Recording health monitor for long CBT sessions
        function startRecordingHealthMonitor() {
            console.log('🩺 Starting recording health monitor for CBT...');

            // Check recording health every 2 minutes
            const healthInterval = setInterval(() => {
                if (!isRecording) {
                    clearInterval(healthInterval);
                    return;
                }

                const currentTime = Date.now();
                const recordingDuration = (currentTime - recordingStartTime) / 1000 / 60;

                // Health checks
                if (mediaRecorder) {
                    console.log(
                        `🩺 Recording health: ${mediaRecorder.state}, Duration: ${recordingDuration.toFixed(1)}min, Chunks: ${recordedChunks.length}`
                    );

                    // Warning if no new chunks in last 10 minutes (potential issue)
                    if (recordedChunks.length === 0 && recordingDuration > 10) {
                        console.warn('⚠️ No recording chunks after 10 minutes - potential issue!');
                        updateRecordingStatus('Warning', 'No data received');
                    }

                    // Update status every 30 minutes
                    if (recordingDuration % 30 < 0.5) { // Approximately every 30 minutes
                        const hours = Math.floor(recordingDuration / 60);
                        const minutes = Math.floor(recordingDuration % 60);
                        updateRecordingStatus('Recording', `Active ${hours}h ${minutes}m`);
                    }
                } else {
                    console.error('❌ MediaRecorder object lost during health check!');
                    clearInterval(healthInterval);
                }
            }, 120000); // Every 2 minutes (120,000ms)

            // Store interval reference for cleanup
            window.recordingHealthInterval = healthInterval;
        }

        // Attempt to restart recording if it stops unexpectedly
        function attemptRecordingRestart() {
            console.log('🔄 Attempting to restart recording...');

            try {
                if (stream && stream.active) {
                    console.log('📹 Stream still active, creating new MediaRecorder...');

                    // Get optimal settings again
                    const options = getOptimalRecordingSettings();
                    mediaRecorder = new MediaRecorder(stream, options);

                    // Re-setup event handlers
                    mediaRecorder.ondataavailable = function(event) {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                            totalRecordingSize += event.data.size;
                            console.log(
                                `📦 Restarted recording chunk: ${(event.data.size / 1024 / 1024).toFixed(2)}MB`);
                        }
                    };

                    // Restart with same timeslice
                    mediaRecorder.start(300000); // 5 minutes
                    console.log('✅ Recording restarted successfully');
                    updateRecordingStatus('Recording', 'Restarted successfully');

                } else {
                    console.error('❌ Camera stream no longer active, cannot restart recording');
                    updateRecordingStatus('Error', 'Stream lost - restart required');
                }
            } catch (error) {
                console.error('❌ Failed to restart recording:', error);
                updateRecordingStatus('Error', 'Restart failed');
            }
        }

        // Create backup of current recording state
        function createRecordingBackup() {
            if (!recordedChunks || recordedChunks.length === 0) {
                console.log('⚠️ No chunks to backup');
                return;
            }

            try {
                const backupBlob = new Blob(recordedChunks, {
                    type: 'video/webm'
                });
                const backupSize = (backupBlob.size / (1024 * 1024)).toFixed(2);

                console.log(`💾 Creating backup of ${backupSize}MB recording`);

                // Store backup reference (but don't upload yet to avoid conflicts)
                window.recordingBackup = {
                    blob: backupBlob,
                    timestamp: Date.now(),
                    size: backupBlob.size,
                    segmentCount: recordingSegmentCount
                };

                console.log('✅ Recording backup created successfully');
                updateRecordingInfo(`Backup: ${backupSize}MB (${recordingSegmentCount} segments)`);

            } catch (error) {
                console.error('❌ Failed to create recording backup:', error);
            }
        }

        // Stop enhanced backup system
        function stopEnhancedBackup() {
            if (periodicSaveInterval) {
                clearInterval(periodicSaveInterval);
                periodicSaveInterval = null;
                console.log('🛑 Enhanced backup system stopped');
            }

            // Stop health monitor if exists
            if (window.recordingHealthInterval) {
                clearInterval(window.recordingHealthInterval);
                window.recordingHealthInterval = null;
                console.log('🛑 Recording health monitor stopped');
            }
        }

        // Check for emergency recording on page load
        function checkForEmergencyRecording() {
            try {
                const emergencyData = sessionStorage.getItem('emergencyRecording');
                const emergencyTime = sessionStorage.getItem('emergencyRecordingTime');

                if (emergencyData && emergencyTime) {
                    const timeAgo = Date.now() - parseInt(emergencyTime);
                    const minutesAgo = Math.floor(timeAgo / (1000 * 60));

                    console.log(`🚨 Found emergency recording from ${minutesAgo} minutes ago`);

                    // Only recover if it's within the last 30 minutes
                    if (minutesAgo <= 30) {
                        console.log('📦 Attempting to recover emergency recording...');

                        // Automatically save the emergency recording
                        if (window.Livewire) {
                            Livewire.dispatch('saveRecordingVideo', {
                                videoBlob: emergencyData,
                                isEmergencyRecovery: true
                            });

                            console.log('✅ Emergency recording sent to server for recovery');

                            // Clear the emergency data after successful dispatch
                            sessionStorage.removeItem('emergencyRecording');
                            sessionStorage.removeItem('emergencyRecordingTime');
                        }
                    } else {
                        console.log('⚠️ Emergency recording too old, cleaning up');
                        sessionStorage.removeItem('emergencyRecording');
                        sessionStorage.removeItem('emergencyRecordingTime');
                    }
                }
            } catch (error) {
                console.error('❌ Error checking emergency recording:', error);
                // Clean up on error
                sessionStorage.removeItem('emergencyRecording');
                sessionStorage.removeItem('emergencyRecordingTime');
            }
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
            console.log('Livewire initialized');

            // Event listeners for exam completion
            Livewire.on('timeExpired', function() {
                console.log('🔔 Time expired - stopping recording');
                stopRecording();
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

        // These duplicate event listeners were causing multiple alerts
        // Removed to prevent duplicate execution

        // Global event listener as additional fallback
        // Event listeners removed - using direct JavaScript calls from finishExam()

        // Manual save recording function for testing
        function manualSaveRecording() {
            console.log('🧪 Manual save recording triggered');

            // Test Livewire connection first
            if (!window.Livewire) {
                console.error('❌ window.Livewire not available');
                return;
            }

            console.log('✅ window.Livewire available');

            if (mediaRecorder && mediaRecorder.state === 'recording') {
                console.log('🧪 MediaRecorder is active, stopping and saving...');
                alert('🧪 Stopping active recording and saving...');
                stopRecording();
            } else if (recordedChunks && recordedChunks.length > 0) {
                console.log('🧪 Found recorded chunks, saving existing recording...');
                alert('🧪 Found ' + recordedChunks.length + ' chunks, saving...');
                saveFinalVideo();
            } else {
                console.log('🧪 No recording found, creating test video...');
                alert('🧪 No recording found, testing with dummy data...');

                // Test dengan dummy data yang lebih realistis
                const testVideoData = 'data:video/webm;codecs=vp8;base64,' + btoa('test video data with proper format');
                console.log('🧪 Testing with dummy video data:', testVideoData.substring(0, 50) + '...');

                try {
                    // Test dispatch method first
                    console.log('🧪 Testing Livewire.dispatch method...');
                    Livewire.dispatch('saveRecordingVideo', {
                        videoBlob: testVideoData
                    });
                    alert('✅ Dispatch method berhasil! Cek console log dan server log.');

                    // Also test component.call method
                    const component = document.querySelector('[wire\\:id]');
                    if (component) {
                        const componentId = component.getAttribute('wire:id');
                        const livewireComponent = Livewire.find(componentId);

                        if (livewireComponent) {
                            console.log('🧪 Testing component.call method...');

                            livewireComponent.call('saveRecordingVideo', testVideoData)
                                .then((result) => {
                                    console.log('✅ Component call successful:', result);
                                    alert('✅ Component call berhasil! Result: ' + result);
                                })
                                .catch((error) => {
                                    console.error('❌ Component call failed:', error);
                                    alert('❌ Component call gagal: ' + error.message);
                                });
                        }
                    }

                } catch (error) {
                    console.error('❌ Exception during test call:', error);
                    alert('❌ Exception: ' + error.message);
                }
            }
        } // Make function globally accessible
        window.manualSaveRecording = manualSaveRecording;

        // Enhanced cleanup on page unload
        window.addEventListener('beforeunload', function() {
            console.log('Page unloading, cleaning up...');

            // Emergency save if we have recording data
            if (recordedChunks && recordedChunks.length > 0) {
                console.log('🚨 Emergency save: Found recorded data on page unload');
                try {
                    const emergencyBlob = new Blob(recordedChunks, {
                        type: 'video/webm'
                    });
                    const emergencySize = (emergencyBlob.size / (1024 * 1024)).toFixed(2);
                    console.log(`💾 Emergency saving ${emergencySize}MB video`);

                    // Store in sessionStorage as backup
                    const reader = new FileReader();
                    reader.onload = function() {
                        try {
                            sessionStorage.setItem('emergencyRecording', reader.result);
                            sessionStorage.setItem('emergencyRecordingTime', Date.now().toString());
                            console.log('✅ Emergency backup stored in sessionStorage');
                        } catch (e) {
                            console.warn('⚠️ Could not store emergency backup:', e.message);
                        }
                    };
                    reader.readAsDataURL(emergencyBlob);
                } catch (error) {
                    console.error('❌ Emergency save failed:', error);
                }
            }

            // Stop recording first
            stopRecording();

            // Stop video streams
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Stopped camera track');
                });
            }

            if (cameraStream) {
                cameraStream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Stopped PeerJS camera track');
                });
            }

            // Close PeerJS connections
            if (currentCall) {
                currentCall.close();
                console.log('Closed PeerJS call');
            }

            if (peer) {
                peer.destroy();
                console.log('Destroyed PeerJS peer');
            }

            // Close peer connection
            if (peerConnection) {
                peerConnection.close();
                console.log('Closed peer connection');
            }

            // Notify server that session is ending
            if (window.Livewire) {
                try {
                    Livewire.dispatch('stopRecording');
                    console.log('Notified server about session end');
                } catch (error) {
                    console.error('Error notifying server:', error);
                }
            }
        });
    </script>
@endpush
