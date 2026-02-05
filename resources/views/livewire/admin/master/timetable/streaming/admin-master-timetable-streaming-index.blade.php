 <div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    User</h1>
            </div>
            <div>
                <button wire:click="refreshStreamData" class="btn btn-success">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>
    <div class="flex h-full">
        <div class="grid grid-cols-6 gap-6">
            @foreach ($sessions as $session)
                <div
                    class="bg-white rounded-lg shadow-sm overflow-hidden border {{ $selectedSessionId === $session->id ? 'ring-2 ring-blue-500' : '' }}">
                    <!-- Camera Stream Container -->
                    <div class="relative bg-black aspect-video">
                        <div id="streamContainer-{{ $session->id }}" class="w-full h-full">
                            <!-- Camera stream will be injected here -->
                            <div class="flex items-center justify-center h-full text-white">
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Connecting...</p>
                                </div>
                            </div>
                        </div>
                        <!-- Connection Status Indicator -->
                        <div id="statusIndicator-{{ $session->id }}"
                            class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium bg-yellow-500 text-white">
                            Connecting
                        </div>
                        <!-- Manual Retry Button -->
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
                        </div>

                        <!-- Progress Bar -->
                        <!-- <div class="mt-2">
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $session->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300"
                                    style="width: {{ $session->progress_percentage }}%"></div>
                            </div>
                        </div> -->

                        <!-- Actions -->
                        <div class="mt-3">
                            <button type="button"
                                wire:click="openSessionModal('{{ $session->id }}')"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 hover:border-blue-300 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail
                            </button>
                            <!-- <button class="btn btn-sm btn-outline-danger"
                                wire:click="suspendSession({{ $session->id }})">
                                Suspend & Logout
                            </button>
                            <button class="btn btn-sm btn-outline-warning"
                                wire:click="terminateSession({{ $session->id }})">
                                Putus Sesi
                            </button> -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div wire:ignore.self id="modal-streaming-session"
    class="modal fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-auto flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Detail Sesi Streaming</h2>
            </div>
            <button type="button" wire:click="closeModal()"
                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'modal-streaming-session' } }))"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-700">
            <div id="modalStreamContainer" class="w-full aspect-video bg-black rounded-lg overflow-hidden">
                <div class="flex items-center justify-center h-full text-white">
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p class="text-xs text-gray-400">Memuat kamera...</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="flex justify-end items-center gap-2 px-6 py-4 border-t">
            <button type="button" wire:click="closeModal()"
                onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'modal-streaming-session' } }))"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Include PeerJS for easier WebRTC connections -->
    <script src="https://unpkg.com/peerjs@1.5.0/dist/peerjs.min.js"></script>

    <script>
        // Global variables for PeerJS - use var to prevent "already declared" errors on re-renders
        var peer = window.peer || null;
        var connections = window.connections || {};
        var localStream = window.localStream || null;
        var activeSessions = window.activeSessions || [];
        var isConnecting = window.isConnecting || false;

        // Guard against duplicate initialization and intervals
        window.__supervisorInitialized = window.__supervisorInitialized || false;
        window.__supervisorIntervals = window.__supervisorIntervals || {
            durations: null,
            monitor: null,
            refresh: null
        };

        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Supervisor Page Loaded ===');
            console.log('Page URL:', window.location.href);
            console.log('PeerJS available:', typeof Peer !== 'undefined');

            if (!window.__supervisorInitialized) {
                window.__supervisorInitialized = true;
                initializeLiveStreaming();
                setupPeerJSConnections();
            } else {
                console.log('Skipping duplicate initialization');
            }

            // Add debugging functions to window for manual testing
            window.debugSupervisor = {
                peer: () => peer,
                connections: () => connections,
                activeSessions: () => activeSessions,
                reconnect: () => {
                    console.log('Manual reconnection triggered');
                    connectToStudentStreams();
                },
                retryConnection: (sessionId) => {
                    const session = activeSessions.find(s => s.session_id === sessionId);
                    if (session && session.peer_id) {
                        console.log(`Retrying connection to ${session.user_name}...`);
                        connectToPeerJSStudent(session).then(() => {
                            console.log(`✅ Retry successful for ${session.user_name}`);
                        }).catch(err => {
                            console.log(`❌ Retry failed for ${session.user_name}:`, err.message);
                        });
                    }
                },
                testPeerJSConnection: () => {
                    if (peer && peer.open) {
                        console.log('✅ PeerJS connection is open, ID:', peer.id);
                        console.log('Active connections:', Object.keys(connections).length);
                        console.log('Active sessions:', activeSessions.length);
                    } else {
                        console.log('❌ PeerJS connection is not open');
                    }
                },
                checkAllPeers: async () => {
                    console.log('Checking availability of all peer IDs...');
                    for (const session of activeSessions) {
                        if (session.peer_id) {
                            const available = await checkPeerAvailability(session.peer_id);
                            console.log(
                                `${session.user_name} (${session.peer_id}): ${available ? '✅ Available' : '❌ Not available'}`
                            );
                        }
                    }
                }
            };
            // Listen for stream refresh events from Livewire (e.g., after pagination)
            if (typeof Livewire !== 'undefined') {
                Livewire.on('streamDataRefreshed', () => {
                    console.log('⚡ Stream data refresh event received from Livewire');
                    // Wait for DOM to update
                    setTimeout(() => {
                        connectToStudentStreams();
                    }, 500);
                });
            }
        });

        // Initialize live streaming functionality
        function initializeLiveStreaming() {
            console.log('Live streaming initialized for timetable {{ $timetable->id }}');

            // Setup local camera (supervisor view - optional)
            setupSupervisorCamera();

            // Update session durations
            updateSessionDurations();
            if (window.__supervisorIntervals.durations) clearInterval(window.__supervisorIntervals.durations);
            window.__supervisorIntervals.durations = setInterval(updateSessionDurations, 1000);

            // Connect to existing student streams
            setTimeout(() => {
                connectToStudentStreams();
            }, 2000);

            // Set up periodic monitoring and reconnection
            window.__supervisorIntervals.monitor = setInterval(() => {
                monitorConnections();
            }, 5000); // Check every 5 seconds

            // Set up periodic refresh of stream data
            if (window.__supervisorIntervals.refresh) clearInterval(window.__supervisorIntervals.refresh);
            window.__supervisorIntervals.refresh = setInterval(() => {
                refreshStreamData();
            }, 120000); // Refresh every 2 minutes
        }

        // Setup PeerJS connections
        function setupPeerJSConnections() {
            try {
                // Get configuration based on environment - ensure it matches student config
                const isDevelopment = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

                console.log('Environment detection:', {
                    hostname: window.location.hostname,
                    isDevelopment: isDevelopment
                });

                const peerConfig = isDevelopment ? {
                    host: 'localhost',
                    port: 9000,
                    path: '/peerjs',
                    secure: false
                } : {
                    host: 'procbt.id',
                    path: '/peerjs',
                    secure: true
                };

                console.log('PeerJS config for supervisor:', peerConfig);

                // Initialize PeerJS with environment-specific config
                peer = new Peer({
                    ...peerConfig,
                    debug: 0, // Enable debug logs like student
                    sdpSemantics: 'unified-plan',
                    config: {
                        iceServers: [{
                                urls: 'stun:stun.cloudflare.com:3478'
                            },
                            {
                                urls: 'turn:procbt.id:3478?transport=udp',
                                username: 'admin',
                                credential: 'ProcbtSecure123!'
                            }
                        ],
                        iceTransportPolicy: 'relay'
                    }
                });

                peer.on('open', function(id) {
                    console.log('✅ Supervisor PeerJS connected with ID:', id);
                    console.log('Ready to receive student connections');
                    // Store supervisor peer ID for students to connect
                    localStorage.setItem('supervisorPeerID', id);

                    // Wait a bit more before connecting to students to ensure they're ready
                    setTimeout(() => {
                        console.log('🔄 Delayed connection attempt after supervisor PeerJS ready...');
                        connectToStudentStreams();
                    }, 500); // Reduced to 0.5s for faster connection
                });

                peer.on('call', function(call) {
                    console.log('📞 Incoming call from student:', call.peer);

                    // Answer the call - supervisor doesn't need to send video back
                    if (localStream) {
                        call.answer(localStream);
                        console.log('Answered call with supervisor camera');
                    } else {
                        call.answer(); // Answer without video
                        console.log('Answered call without supervisor camera');
                    }

                    // Listen for remote stream from student
                    call.on('stream', function(remoteStream) {
                        console.log('📺 Received stream from student:', call.peer);
                        console.log('Stream tracks:', remoteStream.getTracks().map(track => ({
                            kind: track.kind,
                            enabled: track.enabled,
                            readyState: track.readyState
                        })));

                        handleIncomingStream(call.peer, remoteStream);
                    });

                    // Store connection
                    connections[call.peer] = call;

                    call.on('close', function() {
                        console.log('Call closed by student:', call.peer);
                        delete connections[call.peer];
                    });
                });

                peer.on('disconnected', function() {
                    console.log('⚠️ Supervisor PeerJS disconnected from server. Attempting to reconnect...');
                    peer.reconnect();
                });

                peer.on('close', function() {
                    console.log('❌ Supervisor PeerJS connection closed permanently. Re-initializing...');
                    peer = null;
                    setTimeout(setupPeerJSConnections, 5000);
                });

                peer.on('error', function(err) {
                    console.error('❌ PeerJS error:', err);
                    console.error('Error type:', err.type);

                    if (err.type === 'peer-unavailable') {
                        // This is normal for students who closed the tab
                        console.log('Peer unavailable - student offline');
                    } else if (err.type === 'network' || err.type === 'server-error' || err.type === 'socket-error') {
                        console.log('🔥 Critical Network/Server Error - Reconnecting Supervisor...');
                        if (peer) {
                            peer.disconnect();
                            setTimeout(() => {
                                if (peer && !peer.destroyed) peer.reconnect();
                            }, 2000);
                        }
                    }
                });

                // === HEARTBEAT WATCHDOG ===
                // Periodically check if we are still connected to the signaling server
                if (window.__supervisorIntervals.heartbeat) clearInterval(window.__supervisorIntervals.heartbeat);
                window.__supervisorIntervals.heartbeat = setInterval(() => {
                    if (peer && peer.disconnected && !peer.destroyed) {
                        console.log('💓 Watchdog: Peer disconnected, forcing reconnect...');
                        peer.reconnect();
                    }
                }, 5000); // Check every 5 seconds

            } catch (error) {
                console.log('PeerJS initialization failed, using demo mode:', error);
            }
        }

        // Handle incoming stream from student
        function handleIncomingStream(studentPeerID, stream) {
            console.log('=== handleIncomingStream called ===');
            console.log('Student Peer ID:', studentPeerID);
            console.log('Stream details:', {
                id: stream.id,
                active: stream.active,
                tracks: stream.getTracks().length
            });
            console.log('Active sessions:', activeSessions);

            // Find session data for this peer
            const sessionData = activeSessions.find(s => s.peer_id === studentPeerID);

            if (sessionData) {
                console.log(`✅ Found matching session for peer ${studentPeerID}:`, sessionData);
                console.log(`Displaying real stream for ${sessionData.user_name}`);

                displayStudentStream(sessionData.session_id, stream, {
                    ...sessionData,
                    type: 'real',
                    connection_status: 'connected'
                });
            } else {
                console.log(`❌ Session data not found for peer: ${studentPeerID}`);
                console.log('Available peer IDs in activeSessions:',
                    activeSessions.map(s => ({
                        name: s.user_name,
                        peer_id: s.peer_id,
                        session_id: s.session_id
                    }))
                );

                // Try to find by other means or create placeholder
                console.log('Creating placeholder for unknown peer connection');
                const placeholderData = {
                    session_id: 'unknown-' + studentPeerID,
                    user_name: 'Unknown Student (' + studentPeerID.substring(0, 8) + ')',
                    module_name: 'Unknown Module',
                    peer_id: studentPeerID
                };

                displayStudentStream(placeholderData.session_id, stream, {
                    ...placeholderData,
                    type: 'real',
                    connection_status: 'connected'
                });
            }
        }

        // Setup supervisor camera (optional - for two-way communication)
        async function setupSupervisorCamera() {
            try {
                const constraints = {
                    video: {
                        width: {
                            ideal: 640,
                            max: 640
                        },
                        height: {
                            ideal: 360,
                            max: 360
                        },
                        frameRate: {
                            ideal: 10,
                            max: 12
                        } // ringan tapi jelas
                    },
                    audio: false // supervisor tidak perlu kirim suara
                };

                localStream = await navigator.mediaDevices.getUserMedia(constraints);
                console.log('🎥 Supervisor camera started with lightweight settings');

                // tampilkan preview kecil di halaman (opsional)
                const preview = document.getElementById('supervisorPreview');
                if (preview) {
                    preview.srcObject = localStream;
                    preview.play().catch(err => console.warn('Preview autoplay prevented:', err));
                }
            } catch (error) {
                console.error('❌ Error starting supervisor camera:', error);
                localStream = null;
            }
        }

        // Connect to student streams (filtered by visibility/pagination)
        async function connectToStudentStreams() {
            if (isConnecting) {
                console.log('Already connecting to streams, skipping...');
                return;
            }

            isConnecting = true;

            try {
                // 1. Identify valid session IDs currently in the DOM (current page)
                // We use Strings for safe comparison to avoid "19" vs 19 mismatch
                const visibleSessionIds = [];
                document.querySelectorAll('[id^="streamContainer-"]').forEach(el => {
                    const id = el.id.replace('streamContainer-', '');
                    if (id) visibleSessionIds.push(String(id));
                });

                console.log('Visible session IDs (String):', visibleSessionIds);

                // 2. Clean up sessions that are no longer visible (e.g., after pagination change)
                const sessionsToRemove = activeSessions.filter(s => !visibleSessionIds.includes(String(s.session_id)));
                sessionsToRemove.forEach(session => {
                    console.log(`Cleaning up invisible session: ${session.user_name}`);
                    if (session.cleanup) session.cleanup();
                    // Remove from activeSessions array
                    const index = activeSessions.indexOf(session);
                    if (index > -1) activeSessions.splice(index, 1);
                });

                // Try to get real camera streams for this timetable
                console.log('Checking for real student camera streams for timetable {{ $timetable->id }}...');
                try {
                    const realStreamsResponse = await fetch(
                        '/api/stream/real-streams?timetable_id={{ $timetable->id }}');

                    if (!realStreamsResponse.ok) {
                        throw new Error('Real streams API not available');
                    }

                    const realStreamsData = await realStreamsResponse.json();

                    if (realStreamsData.success && Array.isArray(realStreamsData.streams) && realStreamsData.streams.length > 0) {
                        console.log(`Found ${realStreamsData.streams.length} total streams`, realStreamsData.streams);

                        for (const streamInfo of realStreamsData.streams) {
                            // 🔥 DETAILED DEBUG LOGGING
                            console.log('📹 Processing stream for:', streamInfo.user_name);
                            console.log('  - has_real_camera:', streamInfo.has_real_camera);
                            console.log('  - peer_id:', streamInfo.peer_id || 'NULL');
                            console.log('  - camera_status:', streamInfo.camera_status);
                            console.log('  - connection_status:', streamInfo.connection_status);
                            // SCALABILITY CHECK: Only connect if this session is visible on the current page
                            // TYPE SAFE COMPARISON
                            if (!visibleSessionIds.includes(String(streamInfo.session_id))) {
                                console.log(`Skipping off-screen session: ${streamInfo.user_name} (ID: ${streamInfo.session_id})`);
                                continue;
                            }

                            // Check if already active/connected
                            // Check if already active/connected
                            const existingSession = activeSessions.find(s => String(s.session_id) === String(streamInfo.session_id));
                            if (existingSession && existingSession.type === 'real' && existingSession.connection_status === 'connected') {
                                // LIVEWIRE DOM RESET HANDLER:
                                // If we are "Connected" in JS, but the DOM was reset (no <video> tag), we must re-attach the stream!
                                const container = document.getElementById(`streamContainer-${streamInfo.session_id}`);
                                const hasVideo = container && container.querySelector('video');

                                if (container && !hasVideo && existingSession.stream && existingSession.stream.active) {
                                     console.log(`♻️ streamContainer reset by Livewire, re-attaching video for ${streamInfo.user_name}`);
                                     displayStudentStream(streamInfo.session_id, existingSession.stream, existingSession);
                                }
                                continue;
                            }

                            // Add to activeSessions if not present
                            if (!existingSession) {
                                activeSessions.push(streamInfo);
                            }

                            if (streamInfo.has_real_camera && streamInfo.peer_id) {
                                console.log(`Found visible student with camera: ${streamInfo.user_name}`);

                                // Check availability and connect
                                if (await checkPeerAvailability(streamInfo.peer_id)) {
                                    try {
                                        await connectToPeerJSStudent(streamInfo);
                                    } catch (error) {
                                        console.log(`❌ Connection failed for ${streamInfo.user_name}, falling back to demo`);
                                        createMockVideoStreamForSession(streamInfo, activeSessions.length);
                                    }
                                } else {
                                    console.log(`Peer ${streamInfo.peer_id} not available, using demo`);
                                    createMockVideoStreamForSession(streamInfo, activeSessions.length);
                                }
                            } else {
                                // No camera/peer, use demo
                                createMockVideoStreamForSession(streamInfo, activeSessions.length);
                            }
                        }
                        return;
                    }
                } catch (realStreamError) {
                    console.log('Real streams error:', realStreamError.message);
                }

                // If we get here and have no sessions, show empty state
                 if (activeSessions.length === 0) {
                    console.log('No active sessions to display');
                    showNoActiveSessions();
                 }

            } catch (error) {
                console.error('Error connecting to student streams:', error);
                showConnectionError();
            } finally {
                isConnecting = false;
            }
        }

        // Monitor existing connections and attempt reconnection for failed ones
        async function monitorConnections() {
            console.log('=== Monitoring Connections ===');
            console.log(`Active sessions: ${activeSessions.length}`);
            console.log(`PeerJS connections: ${Object.keys(connections).length}`);

            // Check each session
            for (const session of activeSessions) {
                if (session.type === 'real' && session.call) {
                    // Check if call is still open
                    if (!session.call.open) {
                        console.log(`⚠️ Connection lost for ${session.user_name}, attempting reconnection...`);

                        // Update UI to show reconnecting status
                        const indicator = document.getElementById(`statusIndicator-${session.session_id}`);
                        if (indicator) {
                            indicator.className = 'absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium bg-orange-500 text-white';
                            indicator.textContent = 'Reconnecting...';
                        }

                        try {
                            await connectToPeerJSStudent(session);
                            console.log(`✅ Reconnected to ${session.user_name}`);
                        } catch (error) {
                            console.log(`❌ Reconnection failed for ${session.user_name}:`, error.message);
                            // Switch to demo mode
                            session.type = 'demo';
                            createMockVideoStreamForSession(session, activeSessions.indexOf(session) + 1);
                        }
                    }
                } else if (session.type === 'demo' && session.peer_id) {
                    // Try to reconnect demo sessions that have peer IDs
                    const available = await checkPeerAvailability(session.peer_id);
                    if (available) {
                        console.log(`🔄 Peer ${session.peer_id} is now available, attempting connection...`);
                        try {
                            await connectToPeerJSStudent(session);
                            console.log(`✅ Connected to ${session.user_name} (was demo)`);
                        } catch (error) {
                            console.log(`❌ Connection failed for ${session.user_name}:`, error.message);
                        }
                    }
                }
            }
        }

        // Refresh stream data from server
        async function refreshStreamData() {
            console.log('🔄 Refreshing stream data...');
            try {
                const response = await fetch(`/api/stream/real-streams?timetable_id={{ $timetable->id }}`);
                if (response.ok) {
                    const data = await response.json();
                    console.log('Updated stream data:', data);

                    // Check for new sessions
                    if (data.success && data.streams) {
                        for (const newStream of data.streams) {
                            const existingSession = activeSessions.find(s => s.session_id === newStream.session_id);
                            if (!existingSession) {
                                console.log(`New session detected: ${newStream.user_name}`);
                                // Add new session
                                activeSessions.push(newStream);

                                if (newStream.has_real_camera && newStream.peer_id) {
                                    try {
                                        await connectToPeerJSStudent(newStream);
                                    } catch (error) {
                                        createMockVideoStreamForSession(newStream, activeSessions.length);
                                    }
                                } else {
                                    createMockVideoStreamForSession(newStream, activeSessions.length);
                                }
                            }
                        }
                    }
                }
            } catch (error) {
                console.log('Error refreshing stream data:', error.message);
            }
        }

        // Check if a peer is available before attempting connection
        async function checkPeerAvailability(peerId) {
            return new Promise((resolve) => {
                if (!peer || !peer.open) {
                    console.log('Supervisor peer not open, cannot check peer availability');
                    resolve(false);
                    return;
                }

                // Use a shorter timeout for availability check
                const timeoutId = setTimeout(() => {
                    console.log(`Peer ${peerId} availability check timeout`);
                    resolve(false);
                }, 3000); // 3 second timeout for availability check

                try {
                    // Try to create a data connection to check if peer exists
                    const dataConn = peer.connect(peerId);

                    if (!dataConn) {
                        clearTimeout(timeoutId);
                        resolve(false);
                        return;
                    }

                    dataConn.on('open', () => {
                        clearTimeout(timeoutId);
                        console.log(`✅ Peer ${peerId} is available`);
                        dataConn.close(); // Close the test connection
                        resolve(true);
                    });

                    dataConn.on('error', (err) => {
                        clearTimeout(timeoutId);
                        console.log(`❌ Peer ${peerId} not available:`, err.message);
                        resolve(false);
                    });

                } catch (error) {
                    clearTimeout(timeoutId);
                    console.log(`❌ Error checking peer ${peerId} availability:`, error.message);
                    resolve(false);
                }
            });
        }

        // Connect to student via PeerJS
        async function connectToPeerJSStudent(streamInfo) {
            return new Promise((resolve, reject) => {
                if (!peer || !peer.open) {
                    reject(new Error('Supervisor PeerJS not ready'));
                    return;
                }

                if (!streamInfo.peer_id) {
                    reject(new Error('No peer ID provided'));
                    return;
                }

                console.log(`Attempting to call student peer: ${streamInfo.peer_id}`);
                console.log('StreamInfo data:', streamInfo);

                let callAttempted = false;

                // Set a timeout for the entire connection process
                const timeoutId = setTimeout(() => {
                    if (!callAttempted) {
                        console.log(
                            `Connection timeout for ${streamInfo.user_name} - no call response`);
                        reject(new Error('Connection timeout - student may not be online'));
                    }
                }, 10000); // 10 seconds timeout

                try {
                    // Call the student - supervisor calls student to get their video
                    const call = peer.call(streamInfo.peer_id, localStream, {
                        constraints: {
                            video: {
                                width: 480,
                                height: 270,
                                frameRate: 10
                            }
                        }
                    });;

                    if (!call) {
                        clearTimeout(timeoutId);
                        reject(new Error('Failed to create call'));
                        return;
                    }

                    console.log(`Call created for ${streamInfo.user_name}, waiting for stream...`);
                    callAttempted = true;

                    // Track if we received a stream
                    let streamReceived = false;

                    // Listen for student's stream
                    call.on('stream', function(remoteStream) {
                        if (streamReceived) return; // Prevent duplicate handling
                        streamReceived = true;

                        clearTimeout(timeoutId);
                        console.log(`✅ Successfully received stream from ${streamInfo.user_name}`);
                        console.log('Stream details:', {
                            id: remoteStream.id,
                            tracks: remoteStream.getTracks().length,
                            active: remoteStream.active,
                            videoTracks: remoteStream.getVideoTracks().length,
                            audioTracks: remoteStream.getAudioTracks().length
                        });

                        displayStudentStream(streamInfo.session_id, remoteStream, {
                            ...streamInfo,
                            type: 'real',
                            connection_status: 'connected'
                        });

                        // Update the session in activeSessions
                        const sessionIndex = activeSessions.findIndex(s => s.session_id === streamInfo
                            .session_id);
                        if (sessionIndex !== -1) {
                            activeSessions[sessionIndex] = {
                                ...activeSessions[sessionIndex],
                                type: 'real',
                                call: call,
                                stream: remoteStream,
                                cleanup: () => {
                                    console.log(
                                        `Cleaning up connection for ${streamInfo.user_name}`
                                    );
                                    try {
                                        if (call && call.open) {
                                            call.close();
                                        }
                                        if (remoteStream && remoteStream.active) {
                                            remoteStream.getTracks().forEach(track => track
                                                .stop());
                                        }
                                    } catch (cleanupError) {
                                        console.log('Cleanup error:', cleanupError);
                                    }
                                }
                            };
                        }

                        resolve(remoteStream);
                    });

                    call.on('close', function() {
                        console.log(`Call closed with ${streamInfo.user_name}`);

                        // Force update session status
                        const sessionCtx = activeSessions.find(s => s.session_id === streamInfo.session_id);
                        if (sessionCtx) {
                            sessionCtx.connection_status = 'disconnected';
                            // If we didn't initiate the close (e.g. not during cleanup), try to recover
                            if (!streamInfo.isCleanup) {
                                console.log(`Unexpected close for ${streamInfo.user_name}, marking for immediate check`);
                            }
                        }

                        if (!streamReceived) {
                            clearTimeout(timeoutId);
                            // Don't reject if we are just closing normally, but here we assume error if no stream
                            console.log('Call closed before stream receipt');
                        }
                    });

                    call.on('error', function(err) {
                        console.error(`Call error with ${streamInfo.user_name}:`, err);
                        clearTimeout(timeoutId);

                        // Provide more specific error messages
                        let errorMessage = err.message || 'Unknown call error';
                        if (err.type === 'peer-unavailable') {
                            errorMessage = 'Student is not online or has disconnected';
                        } else if (err.type === 'network') {
                            errorMessage = 'Network error - check internet connection';
                        }

                        reject(new Error(errorMessage));
                    });

                    // Monitor ICE connection state for faster disconnect detection
                    if (call.peerConnection) {
                        call.peerConnection.oniceconnectionstatechange = () => {
                            const state = call.peerConnection.iceConnectionState;
                            console.log(`❄️ ICE state for ${streamInfo.user_name}: ${state}`);
                            if (state === 'disconnected' || state === 'failed' || state === 'closed') {
                                console.warn(`ICE connection critical for ${streamInfo.user_name}, restarting...`);
                                // Close the call to trigger cleanup and reconnection logic
                                if (call.open) call.close();
                            }
                        };
                    }

                    // Additional timeout specifically for stream reception
                    setTimeout(() => {
                        if (!streamReceived && call && call.open) {
                            console.log(
                                `No stream received from ${streamInfo.user_name} within timeout`);
                            call.close();
                            if (!streamReceived) {
                                reject(new Error(
                                    'No video stream received - student camera may not be active'
                                ));
                            }
                        }
                    }, 15000); // Increased to 15 seconds for stream reception

                } catch (error) {
                    clearTimeout(timeoutId);
                    console.error(`Exception in connectToPeerJSStudent for ${streamInfo.user_name}:`, error);
                    reject(error);
                }
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

                // Draw timetable info
                ctx.fillStyle = 'rgba(0, 255, 255, 0.7)';
                ctx.font = 'bold 16px Arial';
                ctx.fillText('Timetable: {{ $timetable->id }}', 50, 90);

                // Draw student info
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 28px Arial';
                ctx.fillText(session.user_name || session.name || 'Unknown Student', 50, canvas.height / 2 - 40);

                ctx.font = '18px Arial';
                ctx.fillStyle = '#cccccc';
                ctx.fillText(session.module_name || '{{ $timetable->module->name ?? 'Demo Module' }}', 50, canvas.height /
                    2 - 10);

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
            console.log(`Displaying stream for session ${sessionId}:`, {
                sessionData: sessionData,
                streamId: stream?.id,
                streamActive: stream?.active,
                trackCount: stream?.getTracks()?.length
            });

            const containers = [
                document.getElementById(`streamContainer-${sessionId}`),
                document.getElementById(`galleryContainer-${sessionId}`)
            ];

            containers.forEach((container, index) => {
                if (container) {
                    console.log(`Setting up video in container ${index} for session ${sessionId}`);
                    container.innerHTML = '';

                    const video = document.createElement('video');
                    video.srcObject = stream;
                    video.autoplay = true;
                    video.muted = true;
                    video.playsInline = true;
                    video.className = 'w-full h-full object-cover rounded-lg';

                    // Add error handling
                    video.addEventListener('error', (e) => {
                        console.error(`Video error for session ${sessionId}:`, e);
                        showVideoError(container, sessionData);
                    });

                    video.addEventListener('loadeddata', () => {
                        console.log(
                            `✅ Video loaded successfully for session ${sessionId}: ${sessionData.user_name || sessionData.name}`
                        );
                    });

                    video.addEventListener('loadedmetadata', () => {
                        console.log(`Video metadata loaded for session ${sessionId}:`, {
                            videoWidth: video.videoWidth,
                            videoHeight: video.videoHeight,
                            duration: video.duration
                        });
                    });

                    // Try to play the video
                    video.play().then(() => {
                        console.log(`Video playing for session ${sessionId}`);
                    }).catch(e => {
                        console.warn(`Video autoplay prevented for session ${sessionId}:`, e);
                    });

                    container.appendChild(video);

                    // Update connection status indicator
                    const statusIndicator = document.getElementById(`statusIndicator-${sessionId}`);
                    if (statusIndicator) {
                        if (sessionData.type === 'real') {
                            statusIndicator.className =
                                'absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium bg-green-500 text-white';
                            statusIndicator.textContent = 'LIVE';
                        } else if (sessionData.type === 'demo') {
                            statusIndicator.className =
                                'absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium bg-yellow-500 text-white';
                            statusIndicator.textContent = 'DEMO';
                        }
                    }

                    console.log(
                        `Stream displayed in container ${index} for session ${sessionId}: ${sessionData.user_name || sessionData.name}`
                    );
                } else {
                    // console.debug(`Container ${index} not found for session ${sessionId}`);
                }
            });

            // Handle single view if selected
            const singleContainer = document.getElementById('singleStreamContainer');
            if (singleContainer && String(window.selectedSessionId || '') === String(sessionId)) {
                singleContainer.innerHTML = '';

                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.muted = true;
                video.playsInline = true;
                video.className = 'w-full h-full object-cover rounded-lg';

                singleContainer.appendChild(video);
                console.log(`Single view updated for session ${sessionId}`);
            }

            // Handle modal focus view if selected
            const modalContainer = document.getElementById('modalStreamContainer');
            if (modalContainer && String(window.selectedSessionId || '') === String(sessionId)) {
                modalContainer.innerHTML = '';

                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.muted = true;
                video.playsInline = true;
                video.className = 'w-full h-full object-cover';

                video.addEventListener('error', (e) => {
                    console.error(`Video error for modal session ${sessionId}:`, e);
                    showVideoError(modalContainer, sessionData);
                });

                video.play().catch(e => {
                    console.warn(`Video autoplay prevented for modal session ${sessionId}:`, e);
                });

                modalContainer.appendChild(video);
                console.log(`Modal view updated for session ${sessionId}`);
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
                        ${sessionData.error ? `<p class="text-xs text-gray-500 mt-1">${sessionData.error}</p>` : ''}
                    </div>
                </div>
            `;

            // Update status indicator for error
            const sessionId = sessionData.session_id || sessionData.id;
            const statusIndicator = document.getElementById(`statusIndicator-${sessionId}`);
            if (statusIndicator) {
                statusIndicator.className =
                    'absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium bg-red-500 text-white';
                statusIndicator.textContent = 'ERROR';
            }
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
                            <p class="text-xs text-gray-500">Waiting for students to join this timetable...</p>
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
                window.selectedSessionId = String(sessionId);
                const session = activeSessions.find(s => String(s.session_id) === String(sessionId));
                if (session && session.stream && session.stream.active) {
                    displayStudentStream(sessionId, session.stream, session);
                } else {
                    // If stream not cached yet, try reconnecting to attach it to modal
                    connectToStudentStreams();
                }
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
        window.addEventListener('beforeunload', () => {
            activeSessions.forEach(s => s.cleanup?.());
            peer?.destroy();
            localStream?.getTracks().forEach(t => t.stop());
        });
    </script>
@endpush
