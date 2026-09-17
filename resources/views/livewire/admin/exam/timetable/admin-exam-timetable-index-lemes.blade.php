<div class="space-y-6 mb-6">
    @forelse ($timetables as $timetable)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden transition hover:shadow-md">
            <!-- Header Kartu Jadwal -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-indigo-50/70 via-white to-purple-50/70 border-b border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                                <i class="fas fa-calendar-days"></i>
                            </span>
                            <h3 class="text-lg font-black text-slate-800">
                                {{ $timetable->name }}
                            </h3>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                <i class="fas fa-users text-[10px]"></i>
                                Kelas: {{ $timetable->classmate?->name ?? 'Semua Kelas' }}
                            </span>
                        </div>
                        @if($timetable->description)
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $timetable->description }}
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 self-start sm:self-auto">
                        <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">
                            {{ $timetable->timetableDetails->count() }} Kegiatan
                        </span>
                    </div>
                </div>
            </div>

            <!-- List Kegiatan / Details -->
            <div class="p-5 sm:p-6">
                @if($timetable->timetableDetails->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($timetable->timetableDetails as $detail)
                            @php
                                $now = \Carbon\Carbon::now();
                                $startTime = $detail->start_time ? \Carbon\Carbon::parse($detail->start_time) : null;
                                $endTime = $detail->end_time ? \Carbon\Carbon::parse($detail->end_time) : null;
                                $isEarlyTolerance = $startTime && $now->lt($startTime) && $now->gte($startTime->copy()->subMinutes(5));
                                $isActiveNow = $startTime && $now->gte($startTime) && (!$endTime || $now->lte($endTime));

                                $hasAttended = $timetable->attendances
                                    ->filter(fn($att) => is_null($att->timetable_detail_id) || $att->timetable_detail_id == $detail->id)
                                    ->isNotEmpty();
                            @endphp
                            <div class="rounded-2xl border {{ $detail->isMaterial() ? 'border-amber-200 bg-amber-50/20' : 'border-blue-200 bg-blue-50/20' }} p-4 flex flex-col justify-between space-y-3 transition hover:border-indigo-400 shadow-xs">
                                <!-- Top Row: Tipe & Status Kehadiran -->
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @if($detail->isMaterial())
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-amber-500 text-slate-950 shadow-xs">
                                                <i class="fas fa-book-open text-[10px]"></i> MATERI PEMBELAJARAN
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-extrabold bg-blue-600 text-white shadow-xs">
                                                <i class="fas fa-file-lines text-[10px]"></i> UJIAN CBT
                                            </span>
                                        @endif

                                        @if($isEarlyTolerance)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300" title="Toleransi buka 5 menit sebelum waktu mulai">
                                                <i class="fas fa-clock text-[9px]"></i> Buka Awal (Mulai {{ $startTime->format('H:i') }})
                                            </span>
                                        @elseif($isActiveNow)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                <i class="fas fa-circle-play text-[8px] animate-pulse"></i> Sedang Berlangsung
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Attendance Badge -->
                                    <div>
                                        @if($detail->require_attendance)
                                            @if($hasAttended)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300" title="Kehadiran Anda telah dicatat">
                                                    <i class="fas fa-check-circle"></i> Hadir
                                                </span>
                                            @else
                                                <button wire:click="openStudentScanner('{{ $detail->id }}')"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 cursor-pointer animate-pulse"
                                                    title="Klik untuk Buka Kamera dan Scan QRCODE Sesi">
                                                    <i class="fas fa-camera text-[10px]"></i> Scan QR Absen
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-[10px] text-slate-400">Tanpa Absensi</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Middle: Judul & Informasi -->
                                <div class="space-y-1.5">
                                    <h4 class="font-bold text-sm text-slate-800 line-clamp-1">
                                        @if($detail->isMaterial())
                                            {{ $detail->digitalBook?->title ?? 'Materi Belum Dipilih' }}
                                        @else
                                            {{ $detail->module?->name ?? 'Modul Soal' }}
                                        @endif
                                    </h4>

                                    <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-500 pt-1">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-location-dot text-slate-400 w-3.5"></i>
                                            <span>{{ $detail->examRoom?->name ?? 'Ruang -' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-layer-group text-slate-400 w-3.5"></i>
                                            <span>{{ $detail->examSession?->name ?? 'Sesi -' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-calendar-day text-slate-400 w-3.5"></i>
                                            <span>{{ $detail->exam_date ? \Carbon\Carbon::parse($detail->exam_date)->isoFormat('DD MMM Y') : '-' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-clock text-slate-400 w-3.5"></i>
                                            <span>{{ $detail->start_time ? \Carbon\Carbon::parse($detail->start_time)->format('H:i') : '-' }} - {{ $detail->end_time ? \Carbon\Carbon::parse($detail->end_time)->format('H:i') : '-' }}</span>
                                        </div>
                                    </div>

                                    @if($detail->supervisor_names && $detail->supervisor_names !== '-')
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1 pt-0.5">
                                            <i class="fas fa-user-tie text-slate-400 w-3.5"></i>
                                            <span>Pengawas: {{ $detail->supervisor_names }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Bottom Action Button -->
                                <div class="pt-2 border-t border-slate-200/60">
                                    @if($detail->isMaterial())
                                        <button wire:click="openMaterial('{{ $detail->id }}')"
                                            class="w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                            <i class="fas fa-book-open"></i>
                                            <span>Buka Materi Pembelajaran</span>
                                        </button>
                                    @else
                                        @if(!$timetable->userTimetable)
                                            <button wire:click="openModalStartExam('{{ $timetable->id }}', '{{ $detail->id }}')"
                                                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                                <i class="fas fa-rocket"></i>
                                                <span>Masuk Ujian</span>
                                            </button>
                                        @else
                                            @if($timetable->userTimetable->status === 'done')
                                                <div class="{{ $timetable->allowsRepeat() ? 'grid grid-cols-2 gap-2' : '' }}">
                                                    @if($timetable->allowsRepeat())
                                                        <button wire:click="repeatExam('{{ $timetable->userTimetable->id }}')"
                                                            class="py-2.5 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-1.5 cursor-pointer">
                                                            <i class="fas fa-rotate-right"></i> Ulang
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('admin.exam.history-timetable.detail', ['timetable_id' => $timetable->id, 'user_timetable_id' => $timetable->userTimetable->id]) }}"
                                                        class="{{ $timetable->allowsRepeat() ? '' : 'w-full' }} py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition flex items-center justify-center gap-1.5 cursor-pointer">
                                                        <i class="fas fa-eye"></i> Hasil
                                                    </a>
                                                </div>
                                            @else
                                                <button wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')"
                                                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                                                    <i class="fas fa-arrow-right"></i>
                                                    <span>Lanjutkan Ujian</span>
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback jika timetable belum memiliki rincian details -->
                    <div class="p-6 text-center text-slate-400 text-xs italic">
                        Belum ada kegiatan yang dijadwalkan pada jadwal ini.
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="p-10 sm:p-14 text-center bg-white rounded-3xl border border-slate-200 shadow-xs">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto mb-4 text-2xl shadow-xs">
                <i class="fas fa-camera"></i>
            </div>
            <h4 class="font-black text-lg text-slate-800 mb-2">Belum Ada Jadwal Ujian Terbuka</h4>
            <p class="text-xs text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
                Jadwal ujian atau materi pembelajaran memerlukan <b>presensi kehadiran</b> terlebih dahulu. Silakan scan lembar QRCODE sesi yang telah dicetak atau disediakan oleh Pengawas untuk membuka jadwal Anda.
            </p>
            <button wire:click="openStudentScanner"
                class="inline-flex items-center gap-2.5 px-6 py-3 bg-amber-400 hover:bg-amber-500 text-slate-950 font-black text-xs rounded-2xl shadow-md transition transform hover:scale-105 active:scale-95 cursor-pointer animate-pulse hover:animate-none">
                <i class="fas fa-camera text-base"></i>
                <span>Scan QRCODE Presensi Sekarang</span>
            </button>
        </div>
    @endforelse
</div>
