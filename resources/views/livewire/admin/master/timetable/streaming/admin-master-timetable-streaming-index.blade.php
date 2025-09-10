<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#f58634]">User</h1>
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
            console.log('=== Supervisor Page Loaded ===');
            console.log('Page URL:', window.location.href);
            console.log('PeerJS available:', typeof Peer !== 'undefined');

            initializeLiveStreaming();
            setupPeerJSConnections();

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

            // Set up periodic monitoring and reconnection
            setInterval(() => {
                monitorConnections();
            }, 30000); // Check every 30 seconds

            // Set up periodic refresh of stream data
            setInterval(() => {
                refreshStreamData();
            }, 60000); // Refresh every minute
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
                    host: 'peer.toti.my.id',
                    path: '/peerjs',
                    secure: true
                };

                console.log('PeerJS config for supervisor:', peerConfig);

                // Initialize PeerJS with environment-specific config
                peer = new Peer({
                    ...peerConfig,
                    debug: 2, // Enable debug logs like student
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
                    console.log('✅ Supervisor PeerJS connected with ID:', id);
                    console.log('Ready to receive student connections');
                    // Store supervisor peer ID for students to connect
                    localStorage.setItem('supervisorPeerID', id);

                    // Wait a bit more before connecting to students to ensure they're ready
                    setTimeout(() => {
                        console.log('🔄 Delayed connection attempt after supervisor PeerJS ready...');
                        connectToStudentStreams();
                    }, 3000); // Additional 3 seconds delay
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

                peer.on('error', function(err) {
                    console.error('❌ PeerJS error:', err);
                    console.error('Error type:', err.type);
                    console.error('Error message:', err.message);

                    // Handle specific error types
                    switch (err.type) {
                        case 'network':
                            console.log('Network error - check PeerJS server connection');
                            break;
                        case 'peer-unavailable':
                            console.log('Peer unavailable - student may have disconnected');
                            break;
                        case 'server-error':
                            console.log('Server error - PeerJS server may be down');
                            break;
                        default:
                            console.log('Unknown PeerJS error type');
                    }

                    // Fallback to demo mode if PeerJS fails
                    console.log('PeerJS connection failed, using demo mode for all sessions');
                });

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

                    console.log('=== API Response ===');
                    console.log('Full API response:', realStreamsData);
                    console.log('Success:', realStreamsData.success);
                    console.log('Total streams:', realStreamsData.streams?.length || 0);

                    if (realStreamsData.streams && realStreamsData.streams.length > 0) {
                        console.log('Stream details:', realStreamsData.streams.map(s => ({
                            user_name: s.user_name,
                            peer_id: s.peer_id,
                            has_real_camera: s.has_real_camera,
                            camera_status: s.camera_status,
                            session_id: s.session_id,
                            last_seen: s.last_seen
                        })));
                    }

                    if (realStreamsData.success && Array.isArray(realStreamsData.streams) && realStreamsData.streams
                        .length > 0) {
                        console.log(
                            `Found ${realStreamsData.streams.length} sessions for timetable {{ $timetable->id }}`);

                        for (const streamInfo of realStreamsData.streams) {
                            // Add to activeSessions first for handleIncomingStream
                            activeSessions.push(streamInfo);

                            if (streamInfo.has_real_camera && streamInfo.peer_id) {
                                console.log(
                                    `Found student with camera: ${streamInfo.user_name} (Peer ID: ${streamInfo.peer_id})`
                                );

                                // Check if peer is actually available before attempting connection
                                if (await checkPeerAvailability(streamInfo.peer_id)) {
                                    try {
                                        // Try to connect to student via PeerJS
                                        await connectToPeerJSStudent(streamInfo);
                                        console.log(`✅ Successfully connected to ${streamInfo.user_name}`);

                                        // Update session type to 'real'
                                        const sessionIndex = activeSessions.findIndex(s => s.session_id === streamInfo
                                            .session_id);
                                        if (sessionIndex !== -1) {
                                            activeSessions[sessionIndex].type = 'real';
                                        }
                                    } catch (error) {
                                        console.log(`❌ PeerJS connection failed for ${streamInfo.user_name}:`, error
                                            .message);
                                        // Fall back to demo for this session
                                        console.log(`Using demo mode for ${streamInfo.user_name}`);
                                        createMockVideoStreamForSession(streamInfo, activeSessions.length);

                                        // Update session type to 'demo'
                                        const sessionIndex = activeSessions.findIndex(s => s.session_id === streamInfo
                                            .session_id);
                                        if (sessionIndex !== -1) {
                                            activeSessions[sessionIndex].type = 'demo';
                                            activeSessions[sessionIndex].error = error.message;
                                        }
                                    }
                                } else {
                                    console.log(
                                        `Peer ${streamInfo.peer_id} is not available, using demo mode for ${streamInfo.user_name}`
                                    );
                                    createMockVideoStreamForSession(streamInfo, activeSessions.length);

                                    // Update session type to 'demo'
                                    const sessionIndex = activeSessions.findIndex(s => s.session_id === streamInfo
                                        .session_id);
                                    if (sessionIndex !== -1) {
                                        activeSessions[sessionIndex].type = 'demo';
                                        activeSessions[sessionIndex].error = 'Peer not available';
                                    }
                                }
                            } else {
                                console.log(
                                    `${streamInfo.user_name} - No camera or peer ID (camera: ${streamInfo.has_real_camera}, peer: ${streamInfo.peer_id})`
                                );
                                // No real camera or peer ID, use demo
                                createMockVideoStreamForSession(streamInfo, activeSessions.length);

                                // Update session type to 'demo'
                                const sessionIndex = activeSessions.findIndex(s => s.session_id === streamInfo
                                    .session_id);
                                if (sessionIndex !== -1) {
                                    activeSessions[sessionIndex].type = 'demo';
                                }
                            }
                        }
                        return;
                    }
                } catch (realStreamError) {
                    console.log('Real streams not available, using demo mode:', realStreamError.message);
                    console.log('Error details:', realStreamError);
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
                    const call = peer.call(streamInfo.peer_id, localStream || new MediaStream());

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
                        if (!streamReceived) {
                            clearTimeout(timeoutId);
                            reject(new Error('Call closed before receiving stream'));
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
                    }, 8000); // 8 seconds for stream reception

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
                    console.warn(`Container ${index} not found for session ${sessionId}`);
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
                console.log(`Single view updated for session ${sessionId}`);
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
