<div>
    @include('livewire.admin.master.security.security-log-modal')
    
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[color:var(--primary)]">Security Audit Logs</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau percobaan pelanggaran keamanan sistem secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="clearLogs" 
                    wire:confirm="Apakah Anda yakin ingin menghapus semua log ini?"
                    class="px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all duration-200 text-sm font-medium flex items-center gap-2 text-nowrap">
                <i class="fas fa-trash-alt"></i>
                Hapus Semua Log
            </button>
        </div>
    </div>

    <!-- Tabs Section -->
    <div class="mb-6 flex items-center gap-1 bg-gray-100 p-1 rounded-2xl w-fit">
        <button wire:click="$set('logSource', 'security')"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ $logSource === 'security' ? 'bg-white shadow-sm text-[color:var(--primary)]' : 'text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-user-shield mr-2"></i>Security Violations
        </button>
        <button wire:click="$set('logSource', 'audit')"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ $logSource === 'audit' ? 'bg-white shadow-sm text-[color:var(--primary)]' : 'text-gray-500 hover:text-gray-700' }}">
            <i class="fas fa-database mr-2"></i>System Audit Logs
        </button>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Per Page</span>
                <select wire:model.live="perPage" class="bg-gray-50 border-none rounded-lg text-sm font-bold text-gray-700 focus:ring-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="h-6 w-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Event</span>
                <select wire:model.live="filterEvent" class="bg-gray-50 border-none rounded-lg text-sm font-bold text-gray-700 focus:ring-0">
                    <option value="">All Events</option>
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst(str_replace('.', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="relative w-full md:w-80">
            <input type="text" 
                   wire:model.live.debounce.300ms="search"
                   placeholder="Cari user, IP, atau deskripsi..."
                   class="w-full pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Event & Metadata</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">IP / Device</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-700">{{ $log->created_at->format('H:i:s') }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $log->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->causer)
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-[color:var(--primary)] flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($log->causer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $log->causer->name }}</div>
                                            <div class="text-[10px] text-gray-500">{{ $log->causer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 font-medium italic">System / Guest</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $eventColor = match($log->event) {
                                        'created' => 'bg-green-100 text-green-700',
                                        'updated' => 'bg-amber-100 text-amber-700',
                                        'deleted' => 'bg-red-100 text-red-700',
                                        default => 'bg-[color:var(--primary)] text-white'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $eventColor }}">
                                    {{ $log->event }}
                                </span>
                                @if($log->subject_type)
                                    <div class="mt-1.5 text-[10px] font-bold text-gray-400">
                                        <i class="fas fa-cube mr-1"></i>{{ class_basename($log->subject_type) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600 leading-relaxed max-w-md">
                                    {{ $log->description }}
                                    
                                    {{--@if($log->log_name === 'audit' && isset($log->properties['attributes']))
                                        <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-2">
                                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                                                <i class="fas fa-history text-blue-500"></i>Perubahan Atribut
                                            </div>
                                            <div class="space-y-1.5">
                                                @foreach($log->properties['attributes'] as $key => $value)
                                                    @if(!in_array($key, ['updated_at', 'password', 'remember_token']))
                                                        <div class="text-[10px] bg-white p-1.5 rounded-lg border border-gray-50 flex items-center justify-between">
                                                            <span class="font-bold text-gray-600 px-1 border-l-2 border-blue-500">{{ $key }}</span>
                                                            <div class="flex items-center gap-1.5 overflow-hidden">
                                                                @if(isset($log->properties['old'][$key]))
                                                                    <span class="text-red-400 line-through opacity-70">{{ is_array($log->properties['old'][$key]) ? 'JSON' : Str::limit($log->properties['old'][$key], 15) }}</span>
                                                                    <i class="fas fa-chevron-right text-[8px] text-gray-300"></i>
                                                                @endif
                                                                <span class="text-emerald-600 font-black">{{ is_array($value) ? 'JSON' : Str::limit($value, 15) }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif--}}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <div class="px-2 py-0.5 bg-gray-100 rounded text-[10px] font-black tracking-tight text-gray-600 border border-gray-200">
                                            {{ $log->properties['ip_address'] ?? 'N/A' }}
                                        </div>
                                        @if(isset($log->properties['location']))
                                            <div class="flex items-center gap-1 text-[10px] font-bold text-[color:var(--primary)] bg-blue-50 px-2 py-0.5 rounded border border-[color:var(--primary)]">
                                                <i class="fas fa-map-marker-alt text-[8px]"></i>
                                                {{ $log->properties['location']['city'] ?? '' }}{{ isset($log->properties['location']['city']) ? ',' : '' }} {{ $log->properties['location']['country'] ?? 'Unknown' }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                                        <i class="fas fa-desktop text-[9px] opacity-70"></i>
                                        <span class="truncate max-w-[200px]" title="{{ $log->properties['user_agent'] ?? '' }}">
                                            {{ Str::limit($log->properties['user_agent'] ?? 'Unknown Device', 40) }}
                                        </span>
                                    </div>
                                    @if(isset($log->properties['url']))
                                        <div class="text-[9px] text-gray-300 italic truncate max-w-[200px]">
                                            <span class="font-bold text-gray-400 uppercase tracking-tighter">{{ $log->properties['method'] ?? '' }}</span> {{ Str::after($log->properties['url'] ?? '', request()->getSchemeAndHttpHost()) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="h-16 w-16 rounded-3xl bg-gray-50 flex items-center justify-center text-gray-200 text-3xl">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                    <div class="text-gray-400 font-bold">Tidak ada log yang ditemukan</div>
                                    @if($search || $filterEvent)
                                        <button wire:click="$set('search', ''); $set('filterEvent', '')" class="text-sm font-bold text-blue-500 hover:text-[color:var(--primary)]">Reset Filter</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $logs->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $logs->lastItem() }}</span> dari <span
                        class="font-medium">{{ $logs->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $logs->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-2xl shadow-lg flex items-center gap-3 animate-bounce">
            <i class="fas fa-check-circle"></i>
            {{ session('message') }}
        </div>
    @endif
</div>
