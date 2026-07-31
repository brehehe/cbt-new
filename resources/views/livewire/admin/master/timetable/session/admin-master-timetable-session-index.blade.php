<div>
    @if($autoRefresh)
        <div wire:poll.10s="$refresh"></div>
    @endif
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Sesi Ujian</h1>
                {{-- <p class="text-gray-600 text-sm">{{ $timetable->name }} • {{ $timetable->module->name ?? '-' }}</p>
                --}}
            </div>
            {{-- <div class="flex gap-2">
                <a href="{{ route('admin.master.timetable') }}" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div> --}}
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-20"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="10000">Semua</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Search -->
            <div class="w-full md:w-72">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Cari Sesuatu..." wire:model.live='search'>
                </div>
            </div>

            <!-- Manual Refresh -->
            <button wire:click="$refresh" wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary disabled:opacity-60 transition-colors">
                <i class="fa-solid fa-arrows-rotate" wire:loading.class="animate-spin" wire:target="$refresh"></i>
                <span>Refresh</span>
            </button>

            <!-- Download Excel -->
            <button wire:click="exportExcel" wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-60 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <span wire:loading wire:target="exportExcel">Memproses...</span>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
            </button>

            <!-- Adjust Time Bulk -->
            <button onclick="adjustTimeBulk()"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-60 transition-colors">
                <i class="fa-solid fa-clock"></i>
                <span>Sesuaikan Waktu (Semua)</span>
            </button>

            <!-- Pause Time Bulk -->
            <button wire:click="pauseTimeBulk" wire:confirm="Apakah Anda yakin ingin mem-pause waktu ujian semua peserta aktif?"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-amber-600 rounded-lg hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 disabled:opacity-60 transition-colors">
                <i class="fa-solid fa-pause"></i>
                <span>Pause Waktu (Semua)</span>
            </button>

            <!-- Resume Time Bulk -->
            <button wire:click="resumeTimeBulk" wire:confirm="Apakah Anda yakin ingin melanjutkan waktu ujian semua peserta?"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-60 transition-colors">
                <i class="fa-solid fa-play"></i>
                <span>Lanjutkan Waktu (Semua)</span>
            </button>

            <!-- Download PDF -->
            <button wire:click="exportPdf" wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-60 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span wire:loading wire:target="exportPdf">Memproses...</span>
                <span wire:loading.remove wire:target="exportPdf">PDF</span>
            </button>
        </div>
    </div>

    <!-- Table Section (match style with admin-master-question-index) -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Login / Aktif</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Soal / Terjawab</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamera</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sessions as $index => $session)
                        @php
                            $user = $session->user;
                            $liveSession = $user?->examLiveSessions->first();
                            $userTimetable = $user?->userTimetables->first();

                            // Kehadiran
                            $kehadiranText = $userTimetable ? 'Hadir' : 'Tidak Hadir';
                            $kehadiranColor = $userTimetable ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';

                            // Status Login/Aktif
                            $statusText = 'Belum Login';
                            $statusColor = 'bg-gray-100 text-gray-600';
                            if ($liveSession) {
                                if ($liveSession->is_active) {
                                    $statusText = 'Aktif (Online)';
                                    $statusColor = 'bg-emerald-100 text-emerald-700';
                                } else {
                                    $statusText = $liveSession->connection_status === 'disconnected' ? 'Offline' : 'Login';
                                    $statusColor = 'bg-blue-100 text-blue-700';
                                }
                            }

                            // Jumlah Soal & Terjawab
                            $totalSoal = $userTimetable ? $userTimetable->userModuleQuestions->count() : 0;
                            $terjawab = $userTimetable ? $userTimetable->userModuleQuestions->filter(fn($q) => $q->timetable_answer_id || $q->essay_answer)->count() : 0;

                            // Sisa Waktu
                            $sisaWaktuText = '-';
                            if ($userTimetable) {
                                if (!$userTimetable->start_exam) {
                                    $sisaWaktuText = 'Belum Mulai';
                                } elseif ($userTimetable->status === 'done') {
                                    $sisaWaktuText = 'Selesai';
                                } else {
                                    $remainingSeconds = $userTimetable->getRemainingTime();
                                    $hours = floor($remainingSeconds / 3600);
                                    $mins = floor(($remainingSeconds % 3600) / 60);
                                    $secs = $remainingSeconds % 60;
                                    $sisaWaktuText = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

                                    $adjMinutes = (int)(($userTimetable->additional_time_seconds ?? 0) / 60);
                                    if ($adjMinutes !== 0) {
                                        $sign = $adjMinutes > 0 ? '+' : '';
                                        $sisaWaktuText .= " ({$sign}{$adjMinutes}m)";
                                    }

                                    if (!is_null($userTimetable->paused_at)) {
                                        $sisaWaktuText .= ' (Di-pause)';
                                    }
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                {{ $sessions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ $user->nim ?? ($user->username ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $user->decrypted_password ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $kehadiranColor }}">
                                    {{ $kehadiranText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-slate-700">
                                {{ $totalSoal }} / {{ $terjawab }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $liveSession?->last_activity ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-mono font-semibold {{ $userTimetable && $userTimetable->status === 'exam' ? 'text-indigo-600' : 'text-gray-500' }}">
                                {{ $sisaWaktuText }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $liveSession?->camera_status ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center relative">
                                @if($userTimetable)
                                    <div x-data="{ open: false, x: 0, y: 0 }"
                                         x-init="
                                             $watch('open', value => {
                                                 if (value) {
                                                     $nextTick(() => {
                                                         const btn = $refs.btn;
                                                         const dropdown = $refs.dropdown;
                                                         if (btn && dropdown) {
                                                             const rect = btn.getBoundingClientRect();
                                                             const dropdownHeight = dropdown.offsetHeight;
                                                             const dropdownWidth = dropdown.offsetWidth;
                                                             
                                                             x = rect.right + window.scrollX - dropdownWidth;
                                                             
                                                             const spaceAbove = rect.top;
                                                             const spaceBelow = window.innerHeight - rect.bottom;
                                                             
                                                             if (spaceBelow < dropdownHeight + 10 && spaceAbove > spaceBelow) {
                                                                 y = rect.top + window.scrollY - dropdownHeight - 4;
                                                             } else {
                                                                 y = rect.bottom + window.scrollY + 4;
                                                             }
                                                         }
                                                     });
                                                 }
                                             })
                                         "
                                         class="inline-block text-left">
                                        <button x-ref="btn"
                                            @click="open = !open"
                                            class="px-2.5 py-1.5 bg-gray-100 rounded-md hover:bg-gray-200 transition text-gray-700 font-semibold text-xs inline-flex items-center gap-1">
                                            <span>Aksi</span>
                                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                                        </button>

                                        <!-- Dropdown keluar body -->
                                        <template x-teleport="body">
                                            <div x-show="open" x-ref="dropdown" x-transition.opacity @click.away="open = false"
                                                class="absolute z-50 w-56 bg-white border border-gray-200 rounded-lg shadow-xl max-h-72 overflow-y-auto"
                                                :style="`top:${y}px; left:${x}px`">

                                                <ul class="py-1 text-sm text-gray-700">

                                                    @if ($userTimetable->status === 'done')
                                                        <li>
                                                            <button wire:click="reopenStudentExam('{{ $userTimetable->id }}')"
                                                                wire:confirm="Apakah Anda yakin ingin membuka kembali ujian peserta ini? Soal dan jawaban tidak akan ter-reset."
                                                                class="w-full text-left px-4 py-2 hover:bg-amber-50 text-amber-700 font-semibold flex items-center gap-2">
                                                                <i class="fa-solid fa-rotate-left text-amber-600"></i>
                                                                Buka Kembali Ujian Peserta
                                                            </button>
                                                        </li>
                                                    @endif

                                                    @if ($userTimetable->status === 'suspend')
                                                        <li>
                                                            <button wire:click="unsuspendSession('{{ $user->id }}')"
                                                                wire:confirm="Apakah Anda yakin ingin mencabut suspend peserta ini?"
                                                                class="w-full text-left px-4 py-2 hover:bg-green-50 text-green-700 flex items-center gap-2">
                                                                <i class="fa-solid fa-user-check text-green-600"></i>
                                                                Cabut Suspend
                                                            </button>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <button wire:click="suspendSession('{{ $user->id }}')"
                                                                wire:confirm="Apakah Anda yakin ingin mensuspend peserta ini?"
                                                                class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-700 flex items-center gap-2">
                                                                <i class="fa-solid fa-user-slash text-red-600"></i>
                                                                Suspend Peserta
                                                            </button>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        <button wire:click="forceLogoutUser('{{ $user->id }}')"
                                                            wire:confirm="Apakah Anda yakin ingin force logout peserta ini?"
                                                            class="w-full text-left px-4 py-2 hover:bg-blue-50 text-blue-700 flex items-center gap-2">
                                                            <i class="fa-solid fa-right-from-bracket text-blue-600"></i>
                                                            Force Logout
                                                        </button>
                                                    </li>

                                                    @if(in_array($userTimetable->status, ['exam', 'warning', 'suspend']))
                                                        @php
                                                            $currentAdjMinutes = (int)(($userTimetable->additional_time_seconds ?? 0) / 60);
                                                        @endphp
                                                        <li>
                                                            <button onclick="adjustTimeIndividual('{{ $user->id }}', {{ $currentAdjMinutes }})"
                                                                class="w-full text-left px-4 py-2 hover:bg-indigo-50 text-indigo-700 flex items-center gap-2">
                                                                <i class="fa-solid fa-clock text-indigo-600"></i>
                                                                Sesuaikan Waktu
                                                            </button>
                                                        </li>

                                                        @if(!is_null($userTimetable->paused_at))
                                                            <li>
                                                                <button wire:click="resumeTimeIndividual('{{ $user->id }}')"
                                                                    class="w-full text-left px-4 py-2 hover:bg-emerald-50 text-emerald-700 flex items-center gap-2">
                                                                    <i class="fa-solid fa-play text-emerald-600"></i>
                                                                    Lanjutkan Waktu (Resume)
                                                                </button>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <button wire:click="pauseTimeIndividual('{{ $user->id }}')"
                                                                    class="w-full text-left px-4 py-2 hover:bg-amber-50 text-amber-700 flex items-center gap-2">
                                                                    <i class="fa-solid fa-pause text-amber-600"></i>
                                                                    Pause Waktu Ujian
                                                                </button>
                                                            </li>
                                                        @endif
                                                    @endif
                                                </ul>
                                            </div>
                                        </template>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data text-center py-6 text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $sessions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $sessions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $sessions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $sessions->links('vendor.livewire.custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
    function adjustTimeBulk() {
        Swal.fire({
            title: 'Sesuaikan Waktu Semua Peserta',
            text: 'Masukkan jumlah menit untuk ditambahkan/dikurangi ke semua peserta yang sedang aktif (contoh: 15 untuk menambah, -15 untuk mengurangi)',
            input: 'number',
            inputAttributes: {
                step: 1
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4F46E5',
            inputValidator: (value) => {
                if (!value || isNaN(value)) {
                    return 'Jumlah menit harus berupa angka dan tidak boleh kosong!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('adjustTimeBulk', result.value);
            }
        });
    }

    function adjustTimeIndividual(userId, currentAdditionMinutes) {
        Swal.fire({
            title: 'Sesuaikan Waktu Peserta',
            text: 'Masukkan total penyesuaian waktu untuk peserta ini (menit):',
            input: 'number',
            inputValue: currentAdditionMinutes,
            inputAttributes: {
                step: 1
            },
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#4F46E5',
            inputValidator: (value) => {
                if (value === '' || isNaN(value)) {
                    return 'Jumlah menit harus berupa angka dan tidak boleh kosong!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('adjustTimeIndividual', userId, result.value);
            }
        });
    }
    </script>
</div>