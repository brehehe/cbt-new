<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">User</h1>
            </div>
            <div>
                <button wire:click="refreshStreamData" class="btn btn-primary">
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
                    <div class="relative bg-black">
                        <div id="streamContainer-{{ $session->id }}" class="w-full h-full">
                            <!-- Camera stream will be injected here -->
                            <div class="flex items-center justify-center h-full text-white">
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-xs text-gray-400">Live Stream</p>
                                </div>
                            </div>
                        </div>
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
                    </div>
                </div>
            @endforeach
        </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            initializeLiveStreaming();
            setupPeerJSConnections();
        });

        // Initialize live streaming functionality
        function initializeLiveStreaming() {
            console.log('Live streaming initialized for timetable {{ $timetable->id }}');

            // Setup local camera (supervisor view - optional)
            setupSupervisorCamera();

            // Update session durations
            updateSessionDurations();
            setInterval(updateSessionDurations, 1000);

            // Connect to existing student streams
            setTimeout(() => {
                connectToStudentStreams();
            }, 2000);
        }

        // Setup PeerJS connections
        function setupPeerJSConnections() {
            try {
                // Get configuration based on environment
                const isDevelopment = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
                const peerConfig = isDevelopment ? {
                    host: 'localhost',
                    port: 9000,
                    path: '/peerjs',
                    secure: false
                } : {
                    host: 'peer.toti.my.id',
                    // port: 9443,
                    path: '/peerjs',
                    secure: true
                };

                console.log('Connecting to PeerJS with config:', peerConfig);

                // Initialize PeerJS with environment-specific config
                peer = new Peer({
                    ...peerConfig,
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

                console.log('PeerJS initialized:', peer);

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

        // Connect to student streams (filtered by timetable)
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

                // Try to get real camera streams for this timetable
                console.log('Checking for real student camera streams for timetable {{ $timetable->id }}...');
                try {
                    const realStreamsResponse = await fetch(
                        '/api/stream/real-streams?timetable_id={{ $timetable->id }}');

                    if (!realStreamsResponse.ok) {
                        throw new Error('Real streams API not available');
                    }

                    const realStreamsData = await realStreamsResponse.json();

                    if (realStreamsData.success && Array.isArray(realStreamsData.streams) && realStreamsData.streams
                        .length > 0) {
                        console.log(
                            `Found ${realStreamsData.streams.length} sessions for timetable {{ $timetable->id }}`);

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

                // No real streams found, show empty state
                console.log('No sessions found for timetable {{ $timetable->id }}');
                showNoActiveSessions();

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
    </script>
@endpush
