<div class="min-h-screen bg-gray-50">
    <!-- Header with Controls -->
    <div class="bg-white border-b border-gray-200 px-4 py-3 sm:px-6">
        <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Live Stream Monitor</h1>
                <p class="mt-1 text-sm text-gray-600">Monitor kamera siswa secara real-time</p>
            </div>

            <div class="flex items-center space-x-3">
                <!-- View Mode Selector -->
                <div class="flex rounded-lg overflow-hidden border border-gray-300">
                    <button wire:click="setViewMode('grid')"
                        class="px-3 py-2 text-sm font-medium {{ $viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        Grid
                    </button>
                    <button wire:click="setViewMode('single')"
                        class="px-3 py-2 text-sm font-medium border-l {{ $viewMode === 'single' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        Single
                    </button>
                    <button wire:click="setViewMode('gallery')"
                        class="px-3 py-2 text-sm font-medium border-l {{ $viewMode === 'gallery' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                        Gallery
                    </button>
                </div>

                <!-- Refresh Button -->
                <button wire:click="refreshStreamData"
                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>

                <!-- HTTPS Setup Help -->
                <div class="relative">
                    <button id="httpsHelpBtn" onclick="toggleHttpsHelp()"
                        class="flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 border border-blue-300 rounded-md hover:bg-blue-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        HTTPS Setup
                    </button>

                    <!-- HTTPS Help Popup -->
                    <div id="httpsHelp"
                        class="hidden absolute right-0 top-12 w-80 bg-white border border-gray-300 rounded-lg shadow-lg p-4 z-10">
                        <h3 class="font-semibold text-gray-900 mb-2">Setup HTTPS untuk Live Streaming</h3>
                        <div class="text-sm text-gray-700 space-y-2">
                            <p><strong>1. Jalankan command ini:</strong></p>
                            <code class="block bg-gray-100 p-2 rounded text-xs">herd secure cbt-test</code>

                            <p><strong>2. Akses via:</strong></p>
                            <code class="block bg-blue-100 p-2 rounded text-xs">https://cbt-test.test</code>

                            <p><strong>3. Izinkan camera permission di browser</strong></p>

                            <div class="mt-3 pt-2 border-t">
                                <button onclick="checkHttpsStatus()"
                                    class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                                    ✓ Test HTTPS
                                </button>
                                <button onclick="copyCommand()"
                                    class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded ml-2">
                                    📋 Copy Command
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-sm text-blue-600">Total Sessions</div>
            </div>
            <div class="bg-green-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
                <div class="text-sm text-green-600">Active</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['warning'] }}</div>
                <div class="text-sm text-yellow-600">Warning</div>
            </div>
            <div class="bg-red-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-red-600">{{ $stats['error'] }}</div>
                <div class="text-sm text-red-600">Error</div>
            </div>
        </div>
        live-stream:1397 live-stream:1397
        <!-- Filters -->
        <div class="mt-4 flex flex-wrap items-center gap-2">
            <span class="text-sm font-medium text-gray-700">Filter:</span>
            <button wire:click="setFilter('all')"
                class="px-3 py-1 text-xs font-medium rounded-full {{ $filterStatus === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Semua
            </button>
            <button wire:click="setFilter('active')"
                class="px-3 py-1 text-xs font-medium rounded-full {{ $filterStatus === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Aktif
            </button>
            <button wire:click="setFilter('warning')"
                class="px-3 py-1 text-xs font-medium rounded-full {{ $filterStatus === 'warning' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Warning
            </button>
            <button wire:click="setFilter('error')"
                class="px-3 py-1 text-xs font-medium rounded-full {{ $filterStatus === 'error' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Error
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex h-full">
        @if ($viewMode === 'single' && $selectedSession)
            <!-- Single View Mode -->
            <div class="flex-1 p-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Student Info Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">{{ $selectedSession->user->name }}</h2>
                                <p class="text-sm text-gray-600">
                                    {{ $selectedSession->timetable->module->name ?? 'Unknown Module' }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedSession->status_color === 'green' ? 'bg-green-100 text-green-800' : ($selectedSession->status_color === 'red' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $selectedSession->connection_status }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedSession->camera_status_color === 'green' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $selectedSession->camera_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Large Camera View -->
                    <div class="p-6">
                        <div class="relative bg-black rounded-lg aspect-video">
                            <div id="singleStreamContainer" class="w-full h-full rounded-lg overflow-hidden">
                                <!-- Camera stream will be injected here -->
                                <div class="flex items-center justify-center h-full text-white">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-400">Live Camera Stream</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $selectedSession->user->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Overlay Controls -->
                            <div class="absolute top-4 right-4 flex space-x-2">
                                <button wire:click="takeSnapshot('{{ $selectedSession->id }}')"
                                    class="px-3 py-1 bg-black bg-opacity-50 text-white text-sm rounded hover:bg-opacity-70">
                                    📷 Snapshot
                                </button>
                                <button onclick="toggleFullscreen('singleStreamContainer')"
                                    class="px-3 py-1 bg-black bg-opacity-50 text-white text-sm rounded hover:bg-opacity-70">
                                    ⛶ Fullscreen
                                </button>
                            </div>

                            <!-- Status Indicator -->
                            <div class="absolute top-4 left-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="text-white text-sm font-medium">LIVE</span>
                                </div>
                            </div>
                        </div>

                        <!-- Session Details -->
                        <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Progress</h3>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ $selectedSession->progress_percentage }}%</div>
                                <div class="text-sm text-gray-600">
                                    {{ $selectedSession->answered_questions }}/{{ $selectedSession->total_questions }}
                                    soal</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Alerts</h3>
                                <div class="text-2xl font-bold text-red-600">{{ $selectedSession->alert_count }}</div>
                                <div class="text-sm text-gray-600">peringatan</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Duration</h3>
                                <div class="text-2xl font-bold text-green-600"
                                    id="sessionDuration-{{ $selectedSession->id }}">
                                    --:--:--
                                </div>
                                <div class="text-sm text-gray-600">waktu ujian</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Panel for Session List -->
            <div class="w-80 bg-white border-l border-gray-200 overflow-y-auto">
                <div class="p-4 border-b">
                    <h3 class="text-sm font-medium text-gray-900">Active Sessions ({{ $sessions->total() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach ($sessions as $session)
                        <div wire:click="selectSession('{{ $session->id }}')"
                            class="p-4 cursor-pointer hover:bg-gray-50 {{ $selectedSessionId === $session->id ? 'bg-blue-50 border-r-2 border-blue-500' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $session->user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $session->timetable->module->name ?? 'Unknown' }}</p>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div
                                        class="w-2 h-2 rounded-full {{ $session->status_color === 'green' ? 'bg-green-500' : ($session->status_color === 'red' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                    </div>
                                    @if ($session->alert_count > 0)
                                        <span
                                            class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                            {{ $session->alert_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Grid/Gallery View Mode -->
            <div class="flex-1 p-6">
                @if ($viewMode === 'grid')
                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($sessions as $session)
                            <div
                                class="bg-white rounded-lg shadow-sm overflow-hidden border {{ $selectedSessionId === $session->id ? 'ring-2 ring-blue-500' : '' }}">
                                <!-- Camera Stream Container -->
                                <div class="relative bg-black aspect-video">
                                    <div id="streamContainer-{{ $session->id }}" class="w-full h-full">
                                        <!-- Camera stream will be injected here -->
                                        <div class="flex items-center justify-center h-full text-white">
                                            <div class="text-center">
                                                <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-xs text-gray-400">Live Stream</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Overlay Controls -->
                                    <div class="absolute top-2 right-2 flex space-x-1">
                                        <button wire:click="takeSnapshot('{{ $session->id }}')"
                                            class="p-1 bg-black bg-opacity-50 text-white text-xs rounded hover:bg-opacity-70">
                                            📷
                                        </button>
                                        <button wire:click="selectSession('{{ $session->id }}')"
                                            class="p-1 bg-black bg-opacity-50 text-white text-xs rounded hover:bg-opacity-70">
                                            🔍
                                        </button>
                                    </div>

                                    <!-- Status Indicators -->
                                    <div class="absolute top-2 left-2 flex items-center space-x-1">
                                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                                        <span class="text-white text-xs">LIVE</span>
                                    </div>

                                    <!-- Alert Badge -->
                                    @if ($session->alert_count > 0)
                                        <div class="absolute bottom-2 left-2">
                                            <span
                                                class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full">
                                                {{ $session->alert_count }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Student Info -->
                                <div class="p-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium text-gray-900 truncate">
                                                {{ $session->user->name }}</h3>
                                            <p class="text-xs text-gray-500 truncate">
                                                {{ $session->timetable->module->name ?? 'Unknown' }}</p>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span
                                                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium {{ $session->status_color === 'green' ? 'bg-green-100 text-green-800' : ($session->status_color === 'red' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($session->connection_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $session->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                                                style="width: {{ $session->progress_percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Gallery View -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($sessions as $session)
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <!-- Larger camera view for gallery -->
                                <div class="relative bg-black aspect-video">
                                    <div id="galleryContainer-{{ $session->id }}" class="w-full h-full">
                                        <!-- Camera stream will be injected here -->
                                        <div class="flex items-center justify-center h-full text-white">
                                            <div class="text-center">
                                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                <p class="text-sm text-gray-400">Live Camera Stream</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $session->user->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Controls and indicators... -->
                                    <div class="absolute top-3 right-3 flex space-x-2">
                                        <button wire:click="takeSnapshot('{{ $session->id }}')"
                                            class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded hover:bg-opacity-70">
                                            📷 Snapshot
                                        </button>
                                        <button wire:click="selectSession('{{ $session->id }}')"
                                            class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded hover:bg-opacity-70">
                                            🔍 Detail
                                        </button>
                                    </div>

                                    <div class="absolute top-3 left-3">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                            <span class="text-white text-sm font-medium">LIVE</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Extended info panel -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ $session->user->name }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                {{ $session->timetable->module->name ?? 'Unknown Module' }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $session->status_color === 'green' ? 'bg-green-100 text-green-800' : ($session->status_color === 'red' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($session->connection_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Stats grid -->
                                    <div class="grid grid-cols-3 gap-3 text-center">
                                        <div>
                                            <div class="text-lg font-bold text-blue-600">
                                                {{ $session->progress_percentage }}%</div>
                                            <div class="text-xs text-gray-600">Progress</div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-red-600">{{ $session->alert_count }}
                                            </div>
                                            <div class="text-xs text-gray-600">Alerts</div>
                                        </div>
                                        <div>
                                            <div class="text-lg font-bold text-green-600">
                                                {{ $session->answered_questions }}</div>
                                            <div class="text-xs text-gray-600">Answered</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Pagination -->
                @if ($sessions->hasPages())
                    <div class="mt-6">
                        {{ $sessions->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <!-- Include PeerJS for easier WebRTC connections -->
    <script src="https://unpkg.com/peerjs@1.5.0/dist/peerjs.min.js"></script>

    <script>
        // Global variables for PeerJS - declare only once
        let peer = null;
        let connections = {};
        let localStream = null;
        let activeSessions = [];
        let isConnecting = false;

        // Guard against duplicate initialization
        window.__examLiveStreamInitialized = window.__examLiveStreamInitialized || false;
        window.__examLiveStreamIntervals = window.__examLiveStreamIntervals || { durations: null };

        document.addEventListener('DOMContentLoaded', function() {
            if (!window.__examLiveStreamInitialized) {
                window.__examLiveStreamInitialized = true;
                initializeLiveStreaming();
                setupPeerJSConnections();
            } else {
                console.log('Skipping duplicate exam live-stream initialization');
            }
        });

        // Initialize live streaming functionality
        function initializeLiveStreaming() {
            console.log('Live streaming initialized with PeerJS');

            // Setup local camera (supervisor view - optional)
            setupSupervisorCamera();

            // Update session durations
            updateSessionDurations();
            if (window.__examLiveStreamIntervals.durations) {
                clearInterval(window.__examLiveStreamIntervals.durations);
            }
            window.__examLiveStreamIntervals.durations = setInterval(updateSessionDurations, 1000);

            // Connect to existing student streams
            setTimeout(() => {
                connectToStudentStreams();
            }, 2000);
        }

        // Setup PeerJS connections
        function setupPeerJSConnections() {
            try {
                // Initialize PeerJS with auto-generated ID
                peer = new Peer({
                    host: 'peer.toti.my.id',
                    // port: 9443,
                    path: '/peerjs',
                    secure: true, // Use HTTP instead of HTTPS
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

                peer.on('open', function(id) {
                    console.log('PeerJS connected with ID:', id);
                    // Store supervisor peer ID for students to connect
                    localStorage.setItem('supervisorPeerID', id);
                });

                peer.on('call', function(call) {
                    console.log('Incoming call from:', call.peer);

                    // Answer the call with our local stream (optional)
                    call.answer(localStream);

                    // Listen for remote stream
                    call.on('stream', function(remoteStream) {
                        console.log('Received stream from:', call.peer);
                        handleIncomingStream(call.peer, remoteStream);
                    });

                    // Store connection
                    connections[call.peer] = call;
                });

                peer.on('error', function(err) {
                    console.log('PeerJS error:', err);
                    // Fallback to demo mode if PeerJS fails
                    console.log('PeerJS not available, using demo mode');
                });

            } catch (error) {
                console.log('PeerJS initialization failed, using demo mode:', error);
            }
        }

        // Handle incoming stream from student
        function handleIncomingStream(studentPeerID, stream) {
            // Find session data for this peer
            const sessionData = activeSessions.find(s => s.peer_id === studentPeerID);

            if (sessionData) {
                console.log(`Displaying real stream for ${sessionData.user_name}`);
                displayStudentStream(sessionData.session_id, stream, {
                    ...sessionData,
                    type: 'real',
                    connection_status: 'connected'
                });
            } else {
                console.log('Session data not found for peer:', studentPeerID);
            }
        }

        // Setup supervisor camera (optional - for two-way communication)
        async function setupSupervisorCamera() {
            try {
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: false
                });
                console.log('Supervisor camera ready');
            } catch (error) {
                console.log('Supervisor camera not required:', error.message);
                localStream = null;
            }
        }

        // Connect to student streams
        async function connectToStudentStreams() {
            if (isConnecting) {
                console.log('Already connecting to streams, skipping...');
                return;
            }

            isConnecting = true;

            try {
                // Clear existing streams first
                activeSessions.forEach(session => {
                    if (session.cleanup) {
                        session.cleanup();
                    }
                });
                activeSessions = [];

                // Try to get real camera streams first
                console.log('Checking for real student camera streams...');
                try {
                    const realStreamsResponse = await fetch('/api/stream/real-streams');

                    if (!realStreamsResponse.ok) {
                        throw new Error('Real streams API not available');
                    }

                    const realStreamsData = await realStreamsResponse.json();

                    if (realStreamsData.success && Array.isArray(realStreamsData.streams) && realStreamsData.streams
                        .length > 0) {
                        console.log(`Found ${realStreamsData.streams.length} sessions`);

                        for (const streamInfo of realStreamsData.streams) {
                            if (streamInfo.has_real_camera && streamInfo.peer_id) {
                                try {
                                    // Try to connect to student via PeerJS
                                    await connectToPeerJSStudent(streamInfo);
                                } catch (error) {
                                    console.log(`PeerJS connection failed for ${streamInfo.user_name}:`, error.message);
                                    // Fall back to demo for this session
                                    createMockVideoStreamForSession(streamInfo, activeSessions.length + 1);
                                    activeSessions.push({
                                        ...streamInfo,
                                        type: 'demo'
                                    });
                                }
                            } else {
                                // No real camera or peer ID, use demo
                                createMockVideoStreamForSession(streamInfo, activeSessions.length + 1);
                                activeSessions.push({
                                    ...streamInfo,
                                    type: 'demo'
                                });
                            }
                        }
                        return;
                    }
                } catch (realStreamError) {
                    console.log('Real streams not available, using demo mode:', realStreamError.message);
                }

                // Load demo sessions if no real streams
                console.log('Loading demo sessions...');
                const response = await fetch('/api/stream/sessions');
                const sessionsData = await response.json();
                const sessions = Array.isArray(sessionsData) ? sessionsData : sessionsData.sessions || [];

                console.log('Demo sessions found:', sessions.length);
                activeSessions = sessions;

                if (sessions.length === 0) {
                    showNoActiveSessions();
                    return;
                }

                // Create mock video streams for demo
                sessions.forEach((session, index) => {
                    setTimeout(() => {
                        createMockVideoStreamForSession(session, index + 1);
                    }, index * 500);
                });

            } catch (error) {
                console.error('Error connecting to student streams:', error);
                showConnectionError();
            } finally {
                isConnecting = false;
            }
        }

        // Connect to student via PeerJS
        async function connectToPeerJSStudent(streamInfo) {
            return new Promise((resolve, reject) => {
                if (!peer || !streamInfo.peer_id) {
                    reject(new Error('PeerJS not ready or no peer ID'));
                    return;
                }

                console.log(`Calling student peer: ${streamInfo.peer_id}`);

                // Call the student
                const call = peer.call(streamInfo.peer_id, localStream);

                if (!call) {
                    reject(new Error('Failed to create call'));
                    return;
                }

                // Listen for student's stream
                call.on('stream', function(remoteStream) {
                    console.log(`✅ Received stream from ${streamInfo.user_name}`);
                    displayStudentStream(streamInfo.session_id, remoteStream, {
                        ...streamInfo,
                        type: 'real',
                        connection_status: 'connected'
                    });

                    activeSessions.push({
                        ...streamInfo,
                        type: 'real',
                        call: call,
                        cleanup: () => {
                            call.close();
                            remoteStream.getTracks().forEach(track => track.stop());
                        }
                    });

                    resolve(remoteStream);
                });

                call.on('close', function() {
                    console.log(`Call closed with ${streamInfo.user_name}`);
                });

                call.on('error', function(err) {
                    console.log(`Call error with ${streamInfo.user_name}:`, err);
                    reject(err);
                });

                // Timeout if no response
                setTimeout(() => {
                    if (!call.open) {
                        call.close();
                        reject(new Error('Connection timeout'));
                    }
                }, 8000);
            });
        }

        // Create mock video stream for a specific session (DEMO MODE)
        function createMockVideoStreamForSession(session, displayIndex) {
            console.log('Creating DEMO video stream for session:', session.user_name || session.name);

            // Create a canvas for mock video
            const canvas = document.createElement('canvas');
            canvas.width = 640;
            canvas.height = 480;
            const ctx = canvas.getContext('2d');

            let frameCount = 0;

            // Create mock video content
            function drawMockVideo() {
                frameCount++;

                // Create gradient background
                const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
                gradient.addColorStop(0, '#1a1a2e');
                gradient.addColorStop(1, '#16213e');
                ctx.fillStyle = gradient;
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Draw "DEMO MODE" watermark
                ctx.fillStyle = 'rgba(255, 255, 0, 0.8)';
                ctx.font = 'bold 24px Arial';
                ctx.fillText('DEMO MODE', 50, 60);

                // Draw PeerJS status
                ctx.fillStyle = 'rgba(0, 255, 255, 0.7)';
                ctx.font = 'bold 16px Arial';
                ctx.fillText('PeerJS Fallback', 50, 90);

                // Draw student info
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 28px Arial';
                ctx.fillText(session.user_name || session.name || 'Unknown Student', 50, canvas.height / 2 - 40);

                ctx.font = '18px Arial';
                ctx.fillStyle = '#cccccc';
                ctx.fillText(session.module_name || 'Demo Module', 50, canvas.height / 2 - 10);

                // Draw mock camera simulation
                ctx.strokeStyle = '#00ff00';
                ctx.lineWidth = 3;
                ctx.strokeRect(canvas.width - 200, 120, 150, 200);

                // Draw "face"
                ctx.fillStyle = '#ffdbac';
                ctx.fillRect(canvas.width - 190, 130, 130, 180);

                // Draw eyes with blinking
                const blinkFrame = Math.floor(frameCount / 30) % 4;
                if (blinkFrame !== 3) {
                    ctx.fillStyle = '#000';
                    ctx.fillRect(canvas.width - 170, 160, 15, 10);
                    ctx.fillRect(canvas.width - 140, 160, 15, 10);
                }

                // Draw mouth
                ctx.fillRect(canvas.width - 155, 220, 30, 8);

                // Draw real-time clock
                ctx.fillStyle = '#00ff00';
                ctx.font = '14px Arial';
                const timestamp = new Date().toLocaleTimeString();
                ctx.fillText(timestamp, canvas.width - 120, canvas.height - 20);

                // Draw "waiting for PeerJS connection"
                ctx.fillStyle = '#ffaa00';
                ctx.font = '12px Arial';
                ctx.fillText('Waiting for PeerJS connection...', 50, canvas.height - 40);
            }

            // Update at 25 FPS
            const videoInterval = setInterval(drawMockVideo, 1000 / 25);
            drawMockVideo();

            // Get stream from canvas
            const stream = canvas.captureStream(25);

            // Display the stream
            displayStudentStream(session.id || session.session_id, stream, {
                ...session,
                type: 'demo',
                connection_status: 'demo'
            });

            // Store cleanup function
            session.cleanup = () => {
                clearInterval(videoInterval);
                canvas.remove();
            };
        }

        // Display student stream in the appropriate containers
        function displayStudentStream(sessionId, stream, sessionData) {
            const containers = [
                document.getElementById(`streamContainer-${sessionId}`),
                document.getElementById(`galleryContainer-${sessionId}`)
            ];

            containers.forEach(container => {
                if (container) {
                    container.innerHTML = '';

                    const video = document.createElement('video');
                    video.srcObject = stream;
                    video.autoplay = true;
                    video.muted = true;
                    video.playsInline = true;
                    video.className = 'w-full h-full object-cover rounded-lg';

                    video.addEventListener('error', (e) => {
                        console.error('Video error for session', sessionId, e);
                        showVideoError(container, sessionData);
                    });

                    video.addEventListener('loadeddata', () => {
                        console.log(
                            `Video loaded for session ${sessionId}: ${sessionData.user_name || sessionData.name}`
                        );
                    });

                    container.appendChild(video);

                    // Add status indicator
                    const statusDiv = document.createElement('div');
                    statusDiv.className =
                        'absolute bottom-2 left-2 px-2 py-1 bg-black bg-opacity-50 rounded text-white text-xs';

                    if (sessionData.type === 'real') {
                        statusDiv.innerHTML = '🔴 LIVE (PeerJS)';
                        statusDiv.classList.add('bg-green-600');
                    } else {
                        statusDiv.innerHTML = '🟡 DEMO';
                        statusDiv.classList.add('bg-yellow-600');
                    }

                    container.appendChild(statusDiv);

                    console.log(
                        `Stream displayed for session ${sessionId}: ${sessionData.user_name || sessionData.name}`
                    );
                }
            });

            // Handle single view if selected
            const singleContainer = document.getElementById('singleStreamContainer');
            if (singleContainer && @js($selectedSessionId) == sessionId) {
                singleContainer.innerHTML = '';

                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.muted = true;
                video.playsInline = true;
                video.className = 'w-full h-full object-cover rounded-lg';

                singleContainer.appendChild(video);
            }
        }

        // Show video error
        function showVideoError(container, sessionData) {
            container.innerHTML = `
                <div class="flex items-center justify-center h-full text-white">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.316 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <p class="text-xs text-red-400">Stream Error</p>
                        <p class="text-xs text-gray-400">${sessionData.user_name || sessionData.name}</p>
                    </div>
                </div>
            `;
        }

        // Show no active sessions
        function showNoActiveSessions() {
            const containers = document.querySelectorAll('[id^="streamContainer-"], [id^="galleryContainer-"]');
            containers.forEach(container => {
                container.innerHTML = `
                    <div class="flex items-center justify-center h-full text-white">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-400">No Active Sessions</p>
                            <p class="text-xs text-gray-500">Waiting for students to join exam...</p>
                        </div>
                    </div>
                `;
            });
        }

        // Show connection error
        function showConnectionError() {
            const containers = document.querySelectorAll('[id^="streamContainer-"], [id^="galleryContainer-"]');
            containers.forEach(container => {
                container.innerHTML = `
                    <div class="flex items-center justify-center h-full text-white">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.316 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p class="text-xs text-red-400">Connection Error</p>
                            <p class="text-xs text-gray-400">PeerJS connection failed</p>
                        </div>
                    </div>
                `;
            });
        }

        // Update session durations
        function updateSessionDurations() {
            document.querySelectorAll('[id^="sessionDuration-"]').forEach(element => {
                const sessionId = element.id.split('-')[1];
                const startTime = new Date().getTime() - (Math.random() * 3600000);
                const duration = Math.floor((new Date().getTime() - startTime) / 1000);
                element.textContent = formatDuration(duration);
            });
        }

        // Format duration to HH:MM:SS
        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // Toggle fullscreen for video container
        function toggleFullscreen(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!document.fullscreenElement) {
                container.requestFullscreen().catch(err => {
                    console.error('Error attempting to enable fullscreen:', err);
                });
            } else {
                document.exitFullscreen();
            }
        }

        // Capture snapshot from video stream
        function captureSnapshot(sessionId) {
            const videoElement = document.querySelector(
                `#streamContainer-${sessionId} video, #galleryContainer-${sessionId} video`);

            if (videoElement) {
                const canvas = document.createElement('canvas');
                canvas.width = videoElement.videoWidth || 640;
                canvas.height = videoElement.videoHeight || 480;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(videoElement, 0, 0);

                canvas.toBlob(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `snapshot-session-${sessionId}-${Date.now()}.jpg`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }, 'image/jpeg', 0.9);

                console.log('Snapshot captured for session:', sessionId);
            }
        }

        // Livewire event listeners
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('sessionSelected', function(sessionId) {
                console.log('Session selected:', sessionId);
            });

            Livewire.on('viewModeChanged', function(mode) {
                console.log('View mode changed to:', mode);
                setTimeout(() => {
                    connectToStudentStreams();
                }, 1000);
            });

            Livewire.on('captureSnapshot', function(sessionId) {
                captureSnapshot(sessionId);
            });
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            activeSessions.forEach(session => {
                if (session.cleanup) {
                    session.cleanup();
                }
            });

            if (peer) {
                peer.destroy();
            }

            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
        });

        // HTTPS Setup Helper Functions
        function toggleHttpsHelp() {
            const helpDiv = document.getElementById('httpsHelp');
            helpDiv.classList.toggle('hidden');
        }

        function checkHttpsStatus() {
            if (location.protocol === 'https:') {
                alert('✅ HTTPS aktif! PeerJS live streaming siap digunakan.');
            } else {
                alert('❌ PeerJS memerlukan HTTPS. Jalankan: herd secure cbt-test');
            }
        }

        function copyCommand() {
            navigator.clipboard.writeText('herd secure cbt-test').then(() => {
                alert('✅ Command copied! Setup HTTPS untuk PeerJS.');
            }).catch(() => {
                const textArea = document.createElement('textarea');
                textArea.value = 'herd secure cbt-test';
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('✅ Command copied!');
            });
        }

        // Click outside to close help popup
        document.addEventListener('click', function(event) {
            const helpBtn = document.getElementById('httpsHelpBtn');
            const helpDiv = document.getElementById('httpsHelp');

            if (helpBtn && helpDiv && !helpBtn.contains(event.target) && !helpDiv.contains(event.target)) {
                helpDiv.classList.add('hidden');
            }
        });

        // Auto-check HTTPS status on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                const warningBanner = document.createElement('div');
                warningBanner.className = 'fixed top-0 left-0 right-0 bg-red-600 text-white p-3 text-center z-50';
                warningBanner.innerHTML = `
                    ⚠️ PeerJS memerlukan HTTPS untuk live streaming.
                    <button onclick="copyCommand()" class="underline ml-2">Setup HTTPS</button>
                    <button onclick="this.parentElement.style.display='none'" class="float-right">✕</button>
                `;
                document.body.insertBefore(warningBanner, document.body.firstChild);
            }
        });
    </script>
@endpush
