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
<script src="https://unpkg.com/peerjs@1.5.0/dist/peerjs.min.js"></script>
<script>
        let peer=null, localStream=null, activeSessions=[], isConnecting=false;
        const PEER_CONFIG = {
        host: 'ups.procbt.id',
        path: '/peerjs',
        secure: true,
        debug: 0,
        pingInterval: 15000,
        config: {
            iceServers: [
            // Bisa tetap pakai STUN publik untuk fallback
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' },
            { urls: 'stun:stun.cloudflare.com:3478' },

            // TURN UDP (utama)
            {
                urls: 'turn:procbt.id:3478?transport=udp',
                username: 'admin',
                credential: 'ProcbtSecure123!'
            },

            // TURN TLS (aktif kalau nanti sudah pakai SSL)
            {
                urls: 'turns:procbt.id:5349?transport=tcp',
                username: 'admin',
                credential: 'ProcbtSecure123!'
            }
            ],
            iceTransportPolicy: 'relay'
        }
        };

        document.addEventListener('DOMContentLoaded',()=>{
            if(window.__liveMonitorInited)return;
            window.__liveMonitorInited=true;
            initSupervisorCamera();
            initPeer();
            setInterval(updateDurations,1000);
        });

        async function initSupervisorCamera(){
            try{
                localStream=await navigator.mediaDevices.getUserMedia({
                    video:{width:{ideal:640},height:{ideal:360},frameRate:{ideal:15}},
                    audio:false
                });
                console.log('🎥 Supervisor camera ready');
            }catch(e){console.log('Supervisor camera skipped:',e.message);}
        }

        function initPeer(){
            peer=new Peer(PEER_CONFIG);
            peer.on('open',id=>{console.log('✅ Peer connected',id);});
            peer.on('call',call=>{
                call.answer(localStream);
                call.on('stream',s=>showStream(call.peer,s,'real'));
            });
            peer.on('disconnected',()=>{setTimeout(()=>peer.reconnect(),3000);});
            peer.on('error',err=>console.warn('⚠️ Peer error:',err.message));
        }

        async function connectToStudentStreams(){
            if(isConnecting)return;isConnecting=true;
            try{
                const res=await fetch('/api/stream/real-streams');
                const {streams=[]}=await res.json();
                if(!streams.length){return loadDemo();}
                console.log(`Found ${streams.length} students`);
                // batch 5 per 2s
                for(let i=0;i<streams.length;i+=5){
                    const batch=streams.slice(i,i+5);
                    await Promise.all(batch.map(connectStudent));
                    await new Promise(r=>setTimeout(r,2000));
                }
            }catch(e){
                console.warn('Stream error → demo mode',e.message);
                await loadDemo();
            }finally{isConnecting=false;}
        }

        async function connectStudent(info){
            return new Promise((resolve,reject)=>{
                if(!peer)return reject('Peer not ready');
                const call=peer.call(info.peer_id,localStream);
                let timeout=setTimeout(()=>{call.close();reject('timeout');},8000);
                call.on('stream',stream=>{
                    clearTimeout(timeout);
                    showStream(info.session_id,stream,'real',info);
                    activeSessions.push({id:info.session_id,call,ts:Date.now()});
                    resolve();
                });
                call.on('close',()=>cleanup(info.session_id));
                call.on('error',e=>{cleanup(info.session_id);reject(e);});
            });
        }

        function showStream(id,stream,type='demo',meta={}){
            ['streamContainer','galleryContainer','singleStreamContainer'].forEach(prefix=>{
                const el=document.getElementById(`${prefix}-${id}`)|| (prefix==='singleStreamContainer'?document.getElementById(prefix):null);
                if(!el)return;
                el.innerHTML='';
                const v=document.createElement('video');
                Object.assign(v,{autoplay:true,muted:true,playsInline:true});
                v.srcObject=stream;
                v.className='w-full h-full object-cover rounded';
                el.appendChild(v);
                const tag=document.createElement('div');
                tag.className='absolute bottom-2 left-2 bg-black bg-opacity-60 text-white text-xs px-2 py-0.5 rounded';
                tag.textContent=type==='real'?'🔴 LIVE':'🟡 DEMO';
                el.appendChild(tag);
            });
        }

        function cleanup(id){
            const s=activeSessions.find(x=>x.id===id);
            if(!s)return;
            try{s.call?.close();}catch{}
            activeSessions=activeSessions.filter(x=>x.id!==id);
        }

        async function loadDemo(){
            const res=await fetch('/api/stream/sessions');
            const {sessions=[]}=await res.json();
            sessions.forEach((s,i)=>setTimeout(()=>mockDemo(s),i*400));
        }

        function mockDemo(s){
            const canvas=document.createElement('canvas');
            canvas.width=320;canvas.height=240;
            const ctx=canvas.getContext('2d');let frame=0;
            const draw=()=>{
                ctx.fillStyle='#111';ctx.fillRect(0,0,320,240);
                ctx.fillStyle='#0f0';ctx.font='16px sans-serif';
                ctx.fillText('DEMO '+(s.user_name||'Student'),10,30);
                ctx.fillText(new Date().toLocaleTimeString(),10,60);
                frame++;
            };
            const t=setInterval(draw,100); // 10 fps
            const stream=canvas.captureStream(10);
            showStream(s.id,stream,'demo');
            activeSessions.push({id:s.id,cleanup:()=>{clearInterval(t);canvas.remove();}});
        }

        function updateDurations(){
            document.querySelectorAll('[id^="sessionDuration-"]').forEach(e=>{
                const sec=Math.floor(Math.random()*3600);
                e.textContent=format(sec);
            });
        }
        function format(sec){const h=~~(sec/3600),m=~~((sec%3600)/60),s=sec%60;
            return [h,m,s].map(v=>v.toString().padStart(2,'0')).join(':');
        }

        window.addEventListener('beforeunload',()=>{
            activeSessions.forEach(x=>x.cleanup?.());
            peer?.destroy();
            localStream?.getTracks().forEach(t=>t.stop());
        });

        // optional auto connect
        setTimeout(connectToStudentStreams,3000);
    </script>
@endpush
