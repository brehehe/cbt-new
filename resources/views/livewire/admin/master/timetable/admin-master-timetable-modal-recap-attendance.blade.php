<!-- Modal Rekap Kehadiran Peserta (LEMES) -->
<div wire:ignore.self id="modal-recap-attendance"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-3 sm:p-5 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-200 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600">
                    <i class="fas fa-clipboard-user text-xl"></i>
                </div>
                <div>
                    <h5 class="font-bold text-lg text-slate-800">
                        Rekap Kehadiran {{ student_label() }}
                    </h5>
                    <p class="text-xs text-slate-500">
                        Pantau status kehadiran dan tandai absensi manual peserta
                    </p>
                </div>
            </div>
            <button type="button" wire:click="closeAttendanceRecap" class="text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1">
            @php
                $totalPeserta = count($attendanceSummary);
                $totalHadir = collect($attendanceSummary)->where('has_attended', true)->count();
                $persentase = $totalPeserta > 0 ? round(($totalHadir / $totalPeserta) * 100) : 0;
            @endphp

            <!-- Summary Stats Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center font-bold text-base">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase">Total Peserta</div>
                        <div class="text-lg font-extrabold text-slate-800">{{ $totalPeserta }} <span class="text-xs font-normal text-slate-400">Orang</span></div>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-600 flex items-center justify-center font-bold text-base">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-emerald-700 uppercase">Sudah Hadir</div>
                        <div class="text-lg font-extrabold text-emerald-800">{{ $totalHadir }} <span class="text-xs font-normal text-emerald-600">({{ $persentase }}%)</span></div>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-600 flex items-center justify-center font-bold text-base">
                        <i class="fas fa-user-xmark"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-rose-700 uppercase">Belum Hadir</div>
                        <div class="text-lg font-extrabold text-rose-800">{{ $totalPeserta - $totalHadir }} <span class="text-xs font-normal text-rose-600">Orang</span></div>
                    </div>
                </div>
            </div>

            <!-- Peserta List Table -->
            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">#</th>
                                <th class="py-3 px-4">Nama & Username {{ student_label() }}</th>
                                <th class="py-3 px-4 text-center">Status Kehadiran</th>
                                <th class="py-3 px-4 text-center">Waktu Scan / Absen</th>
                                <th class="py-3 px-4 text-center w-36">Aksi Manual</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse($attendanceSummary as $idx => $st)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-4 text-center font-medium text-slate-400">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-slate-800">{{ $st['name'] }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono">{{ $st['username'] }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($st['has_attended'])
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fas fa-check-circle"></i> Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                                <i class="fas fa-clock"></i> Belum Hadir
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center font-mono text-slate-600">
                                        {{ $st['attended_at'] }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($st['has_attended'])
                                            <button type="button" wire:click="markManualAttendance('{{ $st['id'] }}', 'absent')"
                                                class="px-2.5 py-1 text-[11px] font-semibold text-rose-700 hover:bg-rose-100 rounded-lg border border-rose-300 transition cursor-pointer"
                                                title="Batalkan kehadiran peserta ini">
                                                <i class="fas fa-times mr-1"></i> Batal Hadir
                                            </button>
                                        @else
                                            <button type="button" wire:click="markManualAttendance('{{ $st['id'] }}', 'present')"
                                                class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 hover:bg-emerald-100 rounded-lg border border-emerald-300 transition cursor-pointer"
                                                title="Tandai peserta ini hadir manual">
                                                <i class="fas fa-check mr-1"></i> Tandai Hadir
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs italic">
                                        Tidak ada data peserta untuk jadwal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50/80 flex justify-between items-center">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 cursor-pointer"
                wire:click="openAttendanceScanner('{{ $scan_timetable_id }}', '{{ $scan_detail_id }}')">
                <i class="fas fa-camera"></i> Buka Kamera Scan
            </button>
            <button type="button" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold rounded-xl transition cursor-pointer"
                wire:click="closeAttendanceRecap">
                Tutup
            </button>
        </div>
    </div>
</div>
