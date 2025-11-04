<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Video Recording Debug Test</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { border: 1px solid #ddd; margin: 10px 0; padding: 15px; border-radius: 5px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        button { padding: 10px 15px; margin: 5px; background: #007cba; color: white; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background: #005a8b; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; white-space: pre-wrap; }
        #log { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body>
    <h1>🎥 Video Recording Debug Test</h1>
    
    <div class="test-section">
        <h3>1. Camera Access Test</h3>
        <button onclick="testCameraAccess()">Test Camera</button>
        <div id="cameraStatus"></div>
        <video id="testVideo" width="320" height="240" autoplay muted style="display:none;"></video>
    </div>

    <div class="test-section">
        <h3>2. MediaRecorder Test</h3>
        <button onclick="testMediaRecorder()">Test Recording</button>
        <button onclick="stopTestRecording()">Stop Recording</button>
        <div id="recordingStatus"></div>
    </div>

    <div class="test-section">
        <h3>3. Backend Connection Test</h3>
        <button onclick="testBackendConnection()">Test Backend</button>
        <div id="backendStatus"></div>
    </div>

    <div class="test-section">
        <h3>4. Simulated Video Save Test</h3>
        <button onclick="testVideoSave()">Test Save Video</button>
        <div id="saveStatus"></div>
    </div>

    <div class="test-section">
        <h3>5. Debug Log</h3>
        <button onclick="clearLog()">Clear Log</button>
        <pre id="log"></pre>
    </div>

    <script>
        let testStream = null;
        let testMediaRecorder = null;
        let testChunks = [];

        function log(message, type = 'info') {
            const timestamp = new Date().toISOString().substring(11, 23);
            const logElement = document.getElementById('log');
            const colorClass = type === 'error' ? 'error' : type === 'success' ? 'success' : type === 'warning' ? 'warning' : '';
            logElement.innerHTML += `<span class="${colorClass}">[${timestamp}] ${message}</span>\n`;
            logElement.scrollTop = logElement.scrollHeight;
            console.log(`[${timestamp}] ${message}`);
        }

        function clearLog() {
            document.getElementById('log').innerHTML = '';
        }

        function updateStatus(elementId, message, isSuccess = null) {
            const element = document.getElementById(elementId);
            element.innerHTML = message;
            if (isSuccess === true) {
                element.className = 'success';
            } else if (isSuccess === false) {
                element.className = 'error';
            } else {
                element.className = 'warning';
            }
        }

        async function testCameraAccess() {
            log('🎥 Testing camera access...');
            updateStatus('cameraStatus', 'Testing camera access...', null);

            try {
                // Check browser support
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('getUserMedia not supported');
                }

                log('📱 Requesting camera access...');
                testStream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 640, height: 480 },
                    audio: false
                });

                log('✅ Camera access granted');
                const video = document.getElementById('testVideo');
                video.srcObject = testStream;
                video.style.display = 'block';

                updateStatus('cameraStatus', '✅ Camera access successful', true);
                log(`📊 Stream info: ${testStream.getVideoTracks().length} video tracks, ${testStream.getAudioTracks().length} audio tracks`);

            } catch (error) {
                log(`❌ Camera access failed: ${error.message}`, 'error');
                updateStatus('cameraStatus', `❌ Camera access failed: ${error.message}`, false);
            }
        }

        async function testMediaRecorder() {
            log('🎬 Testing MediaRecorder...');
            updateStatus('recordingStatus', 'Testing MediaRecorder...', null);

            if (!testStream) {
                log('❌ No camera stream available. Test camera first.', 'error');
                updateStatus('recordingStatus', '❌ No camera stream. Test camera first.', false);
                return;
            }

            try {
                // Check MediaRecorder support
                if (!window.MediaRecorder) {
                    throw new Error('MediaRecorder not supported');
                }

                log('🎯 Creating MediaRecorder...');
                
                // Test codec support
                const codecs = [
                    'video/webm;codecs=vp9',
                    'video/webm;codecs=vp8',
                    'video/webm',
                    'video/mp4'
                ];

                let selectedCodec = null;
                for (let codec of codecs) {
                    if (MediaRecorder.isTypeSupported(codec)) {
                        selectedCodec = codec;
                        break;
                    }
                }

                log(`🎵 Selected codec: ${selectedCodec}`);

                testMediaRecorder = new MediaRecorder(testStream, {
                    mimeType: selectedCodec,
                    videoBitsPerSecond: 500000
                });

                testChunks = [];

                testMediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        testChunks.push(event.data);
                        log(`📦 Data chunk received: ${event.data.size} bytes (total chunks: ${testChunks.length})`);
                    }
                };

                testMediaRecorder.onstart = function() {
                    log('▶️ Recording started');
                    updateStatus('recordingStatus', '▶️ Recording in progress...', null);
                };

                testMediaRecorder.onstop = function() {
                    log('⏹️ Recording stopped');
                    updateStatus('recordingStatus', `⏹️ Recording stopped. Chunks: ${testChunks.length}`, true);
                };

                testMediaRecorder.onerror = function(event) {
                    log(`❌ Recording error: ${event.error}`, 'error');
                    updateStatus('recordingStatus', `❌ Recording error: ${event.error}`, false);
                };

                log('🚀 Starting recording...');
                testMediaRecorder.start(2000); // Request data every 2 seconds

            } catch (error) {
                log(`❌ MediaRecorder test failed: ${error.message}`, 'error');
                updateStatus('recordingStatus', `❌ MediaRecorder failed: ${error.message}`, false);
            }
        }

        function stopTestRecording() {
            if (testMediaRecorder && testMediaRecorder.state === 'recording') {
                log('🛑 Stopping recording...');
                testMediaRecorder.stop();
            } else {
                log('⚠️ No active recording to stop', 'warning');
            }
        }

        async function testBackendConnection() {
            log('🔗 Testing backend connection...');
            updateStatus('backendStatus', 'Testing backend connection...', null);

            try {
                // Setup CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (csrfToken) {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                    log(`🔑 CSRF token set: ${csrfToken.substring(0, 10)}...`);
                }

                log('📡 Sending test request...');
                const response = await axios.post('/livewire/message/admin.exam.detail.admin-exam-detail-index', {
                    fingerprint: {
                        id: 'test-component',
                        name: 'admin.exam.detail.admin-exam-detail-index',
                        locale: 'en',
                        path: window.location.pathname,
                        method: 'GET'
                    },
                    serverMemo: {
                        checksum: 'test-checksum',
                        htmlHash: 'test-hash',
                        data: [],
                        dataMeta: []
                    },
                    updates: [{
                        type: 'callMethod',
                        payload: {
                            id: 'test-id',
                            method: 'testConnection',
                            params: []
                        }
                    }]
                });

                log('✅ Backend connection successful');
                log(`📊 Response data: ${JSON.stringify(response.data, null, 2)}`);
                updateStatus('backendStatus', '✅ Backend connection successful', true);

            } catch (error) {
                log(`❌ Backend connection failed: ${error.message}`, 'error');
                if (error.response) {
                    log(`📊 Response status: ${error.response.status}`, 'error');
                    log(`📊 Response data: ${JSON.stringify(error.response.data, null, 2)}`, 'error');
                }
                updateStatus('backendStatus', `❌ Backend failed: ${error.message}`, false);
            }
        }

        async function testVideoSave() {
            log('💾 Testing video save...');
            updateStatus('saveStatus', 'Testing video save...', null);

            if (testChunks.length === 0) {
                log('⚠️ No video chunks available. Record some video first.', 'warning');
                updateStatus('saveStatus', '⚠️ No video chunks. Record video first.', false);
                return;
            }

            try {
                log(`🔄 Creating blob from ${testChunks.length} chunks...`);
                const blob = new Blob(testChunks, { type: 'video/webm' });
                log(`📦 Blob created: ${blob.size} bytes`);

                log('📖 Converting to base64...');
                const reader = new FileReader();
                
                reader.onload = async function(e) {
                    const base64Data = e.target.result;
                    log(`📋 Base64 data length: ${base64Data.length}`);

                    try {
                        // Setup CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (csrfToken) {
                            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
                        }

                        log('📤 Sending video save request...');
                        const saveData = {
                            videoBlob: base64Data,
                            chunkNumber: 999,
                            saveType: 'debug_test',
                            timestamp: new Date().toISOString(),
                            examStatus: { started: true, ended: false },
                            recordingInfo: { 
                                testMode: true,
                                blobSize: blob.size,
                                chunksCount: testChunks.length
                            }
                        };

                        const response = await axios.post('/livewire/message/admin.exam.detail.admin-exam-detail-index', {
                            fingerprint: {
                                id: 'test-component',
                                name: 'admin.exam.detail.admin-exam-detail-index',
                                locale: 'en',
                                path: window.location.pathname,
                                method: 'GET'
                            },
                            serverMemo: {
                                checksum: 'test-checksum',
                                htmlHash: 'test-hash',
                                data: [],
                                dataMeta: []
                            },
                            updates: [{
                                type: 'callMethod',
                                payload: {
                                    id: 'test-id',
                                    method: 'saveVideoChunk',
                                    params: [saveData]
                                }
                            }]
                        });

                        log('✅ Video save successful');
                        log(`📊 Save response: ${JSON.stringify(response.data, null, 2)}`);
                        updateStatus('saveStatus', '✅ Video save successful', true);

                    } catch (error) {
                        log(`❌ Video save failed: ${error.message}`, 'error');
                        if (error.response) {
                            log(`📊 Save response status: ${error.response.status}`, 'error');
                            log(`📊 Save response data: ${JSON.stringify(error.response.data, null, 2)}`, 'error');
                        }
                        updateStatus('saveStatus', `❌ Video save failed: ${error.message}`, false);
                    }
                };

                reader.onerror = function(error) {
                    log(`❌ FileReader error: ${error}`, 'error');
                    updateStatus('saveStatus', `❌ FileReader error: ${error}`, false);
                };

                reader.readAsDataURL(blob);

            } catch (error) {
                log(`❌ Video save test failed: ${error.message}`, 'error');
                updateStatus('saveStatus', `❌ Video save test failed: ${error.message}`, false);
            }
        }

        // Initialize
        log('🚀 Debug page loaded. Ready for testing!');
        log('📋 Available tests:');
        log('  1. Camera Access - Test camera permissions and stream');
        log('  2. MediaRecorder - Test video recording capability');
        log('  3. Backend Connection - Test server connectivity');
        log('  4. Video Save - Test video file saving');
    </script>
</body>
</html>
