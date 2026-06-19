<div class="p-6">
    @if (isset($error))
        <div class="max-w-md mx-auto mt-10">
            <div class="p-4 bg-red-100 border border-red-300 rounded-lg">
                <p class="text-red-800">{{ $error }}</p>
                <a href="{{ route('admin.exam.monitor') }}" class="inline-block mt-2 text-red-600 hover:text-red-800">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    @else
        <!-- Header -->
        <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('admin.exam.monitor') }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Session Monitor</h1>
                </div>
                <p class="text-sm text-gray-600">Monitor detail aktivitas {{ $session->user->name ?? 'Unknown' }}</p>
            </div>

            <div class="flex flex-col gap-2 lg:flex-row lg:items-center">
                <!-- Auto Refresh Toggle -->
                <div class="flex items-center gap-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="autoRefresh" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                        <span class="ml-3 text-sm font-medium text-gray-900">Auto Refresh</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <button wire:click="refreshSessionData"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Refresh
                    </button>

                    @if ($session->connection_status === 'connected')
                        <button wire:click="forceReconnect"
                            class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                            Force Reconnect
                        </button>
                    @endif

                    <button wire:click="terminateSession" wire:confirm="Apakah Anda yakin ingin menghentikan sesi ini?"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Terminate Session
                    </button>
                </div>
            </div>
        </div>

        <!-- Session Overview Cards -->
        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
            <!-- Student Info Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                            {{ strtoupper(substr($session->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $session->user->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $session->user->nim ?? ($session->user->username ?? 'N/A') }}
                            </p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Ujian:</span> {{ $session->timetable->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Modul:</span> {{ $session->timetable->module->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Session ID:</span> {{ $session->session_token }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Connection Status Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Koneksi</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Koneksi</span>
                        <span @class([
                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-green-100 text-green-800' =>
                                $session->connection_status === 'connected',
                            'bg-red-100 text-red-800' => $session->connection_status === 'disconnected',
                            'bg-yellow-100 text-yellow-800' =>
                                $session->connection_status === 'unstable',
                        ])>
                            {{ ucfirst($session->connection_status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Camera</span>
                        <span @class([
                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-green-100 text-green-800' => $session->camera_status === 'active',
                            'bg-red-100 text-red-800' => in_array($session->camera_status, [
                                'inactive',
                                'error',
                            ]),
                            'bg-yellow-100 text-yellow-800' => $session->camera_status === 'pending',
                        ])>
                            {{ ucfirst($session->camera_status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Activity</span>
                        <span class="text-sm text-gray-900">
                            {{ $session->last_activity ? $session->last_activity->diffForHumans() : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Session Active</span>
                        <span @class([
                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                            'bg-green-100 text-green-800' => $session->is_active,
                            'bg-red-100 text-red-800' => !$session->is_active,
                        ])>
                            {{ $session->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progress & Alerts Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Progress & Alerts</h3>
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-600">Progress Ujian</span>
                            <span class="text-sm text-gray-900">{{ $session->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $session->progress_percentage }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $session->current_question_number }} dari {{ $session->total_questions }} soal
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-200 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Dijawab</span>
                            <span class="text-sm font-medium text-green-600">{{ $session->answered_questions }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Ditandai</span>
                            <span class="text-sm font-medium text-yellow-600">{{ $session->marked_questions }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Alerts</span>
                            <span @class([
                                'text-sm font-medium',
                                'text-red-600' => $session->alert_count >= 3,
                                'text-yellow-600' =>
                                    $session->alert_count >= 1 && $session->alert_count < 3,
                                'text-green-600' => $session->alert_count == 0,
                            ])>
                                {{ $session->alert_count }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Risk Level</span>
                            <span @class([
                                'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                'bg-red-100 text-red-800' => $session->risk_level === 'high',
                                'bg-yellow-100 text-yellow-800' => $session->risk_level === 'medium',
                                'bg-primary text-[color:var(--primary)]' =>
                                    $session->risk_level === 'low',
                                'bg-green-100 text-green-800' => $session->risk_level === 'none',
                            ])>
                                {{ ucfirst($session->risk_level) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Alerts & Recordings -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Alerts -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Alerts</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($recentAlerts as $alert)
                        <div class="p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span @class([
                                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full mr-2',
                                            'bg-red-100 text-red-800' => in_array($alert->alert_type, [
                                                'dev_tools',
                                                'tab_switch',
                                                'fullscreen_exit',
                                            ]),
                                            'bg-yellow-100 text-yellow-800' => in_array($alert->alert_type, [
                                                'window_blur',
                                                'page_reload',
                                            ]),
                                            'bg-primary text-[color:var(--primary)]' => in_array(
                                                $alert->alert_type,
                                                ['camera_error', 'right_click']
                                            ),
                                        ])>
                                            {{ ucfirst(str_replace('_', ' ', $alert->alert_type)) }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $alert->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1">{{ $alert->description }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            Tidak ada alert ditemukan
                        </div>
                    @endforelse
                </div>
                @if ($recentAlerts->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $recentAlerts->links() }}
                    </div>
                @endif
            </div>

            <!-- Recordings -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Video Recordings</h3>
                </div>
                <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                    @forelse($recordings as $recording)
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Chunk #{{ $recording->chunk_number }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $recording->created_at->format('H:i:s') }} -
                                        @if ($recording->file_size)
                                            {{ round($recording->file_size / 1024 / 1024, 2) }} MB
                                        @else
                                            Size unknown
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span @class([
                                        'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                        'bg-green-100 text-green-800' => $recording->status === 'completed',
                                        'bg-yellow-100 text-yellow-800' => $recording->status === 'recording',
                                        'bg-red-100 text-red-800' => $recording->status === 'failed',
                                    ])>
                                        {{ ucfirst($recording->status) }}
                                    </span>
                                    @if ($recording->video_path && $recording->status === 'completed')
                                        <a href="{{ asset('storage/' . $recording->video_path) }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                            View
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            Tidak ada recording ditemukan
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Browser & Device Info -->
        @if ($session->browser_info || $session->device_info || $session->session_metadata)
            <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Technical Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        @if ($session->browser_info)
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Browser Information</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    @if (isset($session->browser_info['platform']))
                                        <p><span class="font-medium">Platform:</span>
                                            {{ $session->browser_info['platform'] }}</p>
                                    @endif
                                    @if (isset($session->browser_info['user_agent']))
                                        <p><span class="font-medium">User Agent:</span></p>
                                        <p class="text-xs bg-gray-50 p-2 rounded break-all">
                                            {{ $session->browser_info['user_agent'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($session->session_metadata)
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Session Metadata</h4>
                                <div class="space-y-1 text-sm text-gray-600">
                                    @if (isset($session->session_metadata['start_time']))
                                        <p><span class="font-medium">Start Time:</span>
                                            {{ \Carbon\Carbon::parse($session->session_metadata['start_time'])->format('d/m/Y H:i:s') }}
                                        </p>
                                    @endif
                                    @if (isset($session->session_metadata['ip_address']))
                                        <p><span class="font-medium">IP Address:</span>
                                            {{ $session->session_metadata['ip_address'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>

@push('scripts')
    <script src="{{ asset('vendor/peerjs/peerjs.min.js') }}"></script>
    <script>
        let peer = null,
            localStream = null,
            activeSessions = [],
            isConnecting = false;

        const PEER_CONFIG = {
            host: 'procbt.id',
            path: '/peerjs',
            secure: true,
            debug: 1, // 0 = silent, 1 = minimal log (recommended)
            pingInterval: 15000,
            config: {
                iceServers: [
                    // STUN fallback
                    {
                        urls: 'stun:stun.l.google.com:19302'
                    },
                    {
                        urls: 'stun:stun1.l.google.com:19302'
                    },
                    {
                        urls: 'stun:stun.cloudflare.com:3478'
                    },

                    // TURN UDP (utama)
                    {
                        urls: 'turn:procbt.id:3478?transport=udp',
                        username: 'admin',
                        credential: 'ProcbtSecure123!'
                    },

                    // TURN TLS (aktif kalau SSL Coturn sudah jalan)
                    {
                        urls: 'turns:procbt.id:5349?transport=tcp',
                        username: 'admin',
                        credential: 'ProcbtSecure123!'
                    }
                ],
                iceTransportPolicy: 'relay'
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            if (window.__liveMonitorInited) return;
            window.__liveMonitorInited = true;

            initSupervisorCamera();
            initPeer();
            setInterval(updateDurations, 1000);
        });

        async function initSupervisorCamera() {
            try {
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 360
                        },
                        frameRate: {
                            ideal: 15
                        }
                    },
                    audio: false
                });
                console.log('🎥 Supervisor camera ready');
            } catch (e) {
                console.warn('⚠️ Supervisor camera skipped:', e.message);
            }
        }

        function initPeer() {
            peer = new Peer(PEER_CONFIG);

            peer.on('open', id => {
                console.log('✅ Peer connected:', id);
                // optional: notify backend if needed
            });

            peer.on('call', call => {
                console.log('📞 Incoming call from', call.peer);
                call.answer(localStream);
                call.on('stream', s => showStream(call.peer, s, 'real'));
            });

            peer.on('disconnected', () => {
                console.warn('⚠️ Peer disconnected — trying to reconnect...');
                setTimeout(() => peer.reconnect(), 3000);
            });

            peer.on('error', err => console.error('❌ Peer error:', err));
        }

        async function connectToStudentStreams() {
            if (isConnecting) return;
            isConnecting = true;

            try {
                const res = await fetch('/api/stream/real-streams');
                const {
                    streams = []
                } = await res.json();

                if (!streams.length) return loadDemo();

                console.log(`Found ${streams.length} students`);
                for (let i = 0; i < streams.length; i += 5) {
                    const batch = streams.slice(i, i + 5);
                    await Promise.all(batch.map(connectStudent));
                    await new Promise(r => setTimeout(r, 2000));
                }
            } catch (e) {
                console.warn('Stream error → demo mode', e.message);
                await loadDemo();
            } finally {
                isConnecting = false;
            }
        }

        async function connectStudent(info) {
            return new Promise((resolve, reject) => {
                if (!peer) return reject('Peer not ready');
                const call = peer.call(info.peer_id, localStream);
                let timeout = setTimeout(() => {
                    call.close();
                    reject('timeout');
                }, 8000);

                call.on('stream', stream => {
                    clearTimeout(timeout);
                    showStream(info.session_id, stream, 'real', info);
                    activeSessions.push({
                        id: info.session_id,
                        call,
                        ts: Date.now()
                    });
                    resolve();
                });

                call.on('close', () => cleanup(info.session_id));
                call.on('error', e => {
                    cleanup(info.session_id);
                    reject(e);
                });
            });
        }

        function showStream(id, stream, type = 'demo', meta = {}) {
            ['streamContainer', 'galleryContainer', 'singleStreamContainer'].forEach(prefix => {
                const el = document.getElementById(`${prefix}-${id}`) ||
                    (prefix === 'singleStreamContainer' ? document.getElementById(prefix) : null);
                if (!el) return;
                el.innerHTML = '';

                const v = document.createElement('video');
                Object.assign(v, {
                    autoplay: true,
                    muted: true,
                    playsInline: true
                });
                v.srcObject = stream;
                v.className = 'w-full h-full object-cover rounded';
                el.appendChild(v);

                const tag = document.createElement('div');
                tag.className = 'absolute bottom-2 left-2 bg-black/70 text-white text-xs px-2 py-0.5 rounded';
                tag.textContent = type === 'real' ? '🔴 LIVE' : '🟡 DEMO';
                el.appendChild(tag);
            });
        }

        function cleanup(id) {
            const s = activeSessions.find(x => x.id === id);
            if (!s) return;
            try {
                s.call?.close();
            } catch { }
            activeSessions = activeSessions.filter(x => x.id !== id);
        }

        async function loadDemo() {
            const res = await fetch('/api/stream/sessions');
            const {
                sessions = []
            } = await res.json();
            sessions.forEach((s, i) => setTimeout(() => mockDemo(s), i * 400));
        }

        function mockDemo(s) {
            const canvas = document.createElement('canvas');
            canvas.width = 320;
            canvas.height = 240;
            const ctx = canvas.getContext('2d');
            const draw = () => {
                ctx.fillStyle = '#111';
                ctx.fillRect(0, 0, 320, 240);
                ctx.fillStyle = '#0f0';
                ctx.font = '16px sans-serif';
                ctx.fillText('DEMO ' + (s.user_name || 'Student'), 10, 30);
                ctx.fillText(new Date().toLocaleTimeString(), 10, 60);
            };
            const t = setInterval(draw, 100);
            const stream = canvas.captureStream(10);
            showStream(s.id, stream, 'demo');
            activeSessions.push({
                id: s.id,
                cleanup: () => {
                    clearInterval(t);
                    canvas.remove();
                }
            });
        }

        function updateDurations() {
            document.querySelectorAll('[id^="sessionDuration-"]').forEach(e => {
                const sec = Math.floor(Math.random() * 3600);
                e.textContent = format(sec);
            });
        }

        function format(sec) {
            const h = ~~(sec / 3600),
                m = ~~((sec % 3600) / 60),
                s = sec % 60;
            return [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');
        }

        window.addEventListener('beforeunload', () => {
            activeSessions.forEach(x => x.cleanup?.());
            peer?.destroy();
            localStream?.getTracks().forEach(t => t.stop());
        });

        setTimeout(connectToStudentStreams, 3000);
    </script>
@endpush