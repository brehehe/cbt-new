<div class="space-y-6">
    @forelse ($timetables as $timetable)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm transition hover:shadow-md relative">
            <!-- Header Kartu Jadwal (Parent Timetable) -->
            <div class="p-5 bg-gradient-to-r from-slate-50 via-white to-slate-50 border-b border-slate-200 rounded-t-2xl flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center text-sm font-bold">
                                <i class="fas fa-calendar-check"></i>
                            </span>
                            {{ $timetable->name }}
                        </h3>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <i class="fas fa-users text-[10px]"></i>
                            Kelas: {{ $timetable->classmate?->name ?? 'Semua Kelas' }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                            {{ $timetable->timetableDetails->count() }} Kegiatan/Detail
                        </span>
                    </div>

                    @if($timetable->description)
                        <p class="text-xs text-slate-500 max-w-2xl">
                            {{ $timetable->description }}
                        </p>
                    @endif
                </div>

                <!-- Action Buttons Header -->
                <div class="flex flex-wrap items-center justify-start lg:justify-end gap-2">
                    <!-- Tombol Absensi Kamera -->
                    <button wire:click="openAttendanceScanner('{{ $timetable->id }}')"
                        class="h-8 inline-flex items-center gap-1.5 px-3 rounded-xl text-xs font-bold text-slate-950 bg-amber-400 hover:bg-amber-500 shadow-xs transition cursor-pointer"
                        title="Buka Scanner Kamera untuk Absensi Jadwal Ini">
                        <i class="fas fa-camera text-xs"></i>
                        <span>Absen Kamera</span>
                    </button>

                    <!-- Segmented Button: Presensi & Nilai (Buka Tampilan /Detail) -->
                    <div class="h-8 inline-flex items-center rounded-xl border border-slate-200 bg-white p-0.5 shadow-xs">
                        <a href="{{ route('admin.master.timetable.detail', ['timetable_id' => $timetable->id, 'tab' => 'attendance']) }}"
                            class="h-full inline-flex items-center gap-1.5 px-2.5 rounded-lg text-xs font-bold text-emerald-700 hover:bg-emerald-50 transition"
                            title="Rekap Presensi Kehadiran">
                            <i class="fas fa-clipboard-user text-emerald-600 text-xs"></i>
                            <span>Presensi</span>
                        </a>
                        <span class="w-px h-3.5 bg-slate-200"></span>
                        <a href="{{ route('admin.master.timetable.detail', ['timetable_id' => $timetable->id, 'tab' => 'scores']) }}"
                            class="h-full inline-flex items-center gap-1.5 px-2.5 rounded-lg text-xs font-bold text-blue-700 hover:bg-blue-50 transition"
                            title="Hasil & Nilai Ujian">
                            <i class="fas fa-chart-simple text-blue-600 text-xs"></i>
                            <span>Nilai</span>
                        </a>
                    </div>

                    <!-- Tombol Cetak QR Presensi -->
                    @if($timetable->timetableDetails->isNotEmpty())
                        <button wire:click="openDetailQrModal('{{ $timetable->timetableDetails->first()->id }}')"
                            class="h-8 inline-flex items-center gap-1.5 px-2.5 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 shadow-xs transition cursor-pointer"
                            title="Cetak Lembar QRCODE Presensi yang akan di-scan oleh {{ student_label() }}">
                            <i class="fas fa-qrcode text-xs"></i>
                            <span>Cetak QR</span>
                        </button>
                    @endif

                    <!-- Button Group: Edit & Hapus (Locked together, never separate!) -->
                    @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                        <div class="h-8 inline-flex items-center bg-slate-100/90 rounded-xl p-0.5 border border-slate-200 shadow-xs">
                            <button wire:click="edit('{{ $timetable->id }}')"
                                class="h-full px-2 rounded-lg text-slate-600 hover:text-blue-600 hover:bg-white transition cursor-pointer"
                                title="Edit Jadwal">
                                <i class="fas fa-pen-to-square text-xs"></i>
                            </button>
                            <button wire:click="confirmDelete('{{ $timetable->id }}')"
                                class="h-full px-2 rounded-lg text-slate-600 hover:text-rose-600 hover:bg-white transition cursor-pointer"
                                title="Hapus Jadwal">
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Dropdown Opsi Lainnya (Paling Kanan) -->
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" type="button"
                            class="h-8 w-8 inline-flex items-center justify-center rounded-xl text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 shadow-xs transition cursor-pointer"
                            title="Menu & Opsi Tambahan">
                            <i class="fas fa-ellipsis-vertical text-xs"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                            class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-slate-200/90 p-2 z-[999] text-xs text-slate-700 divide-y divide-slate-100">
                            <!-- Section: Dokumen & Kartu -->
                            <div class="pb-1.5">
                                <div class="px-2.5 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Dokumen & Kartu
                                </div>
                                <button wire:click="printCard('{{ $timetable->id }}')" @click="open = false"
                                    class="w-full text-left flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                                    <i class="fas fa-id-card text-slate-500 w-4 text-center"></i>
                                    <span>Cetak Kartu Peserta</span>
                                </button>
                                <a href="{{ route('admin.print.daftar-hadir', $timetable->id) }}" target="_blank" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition">
                                    <i class="fas fa-file-lines text-blue-500 w-4 text-center"></i>
                                    <span>Cetak Daftar Hadir</span>
                                </a>
                                <a href="{{ route('admin.print.berita-acara', $timetable->id) }}" target="_blank" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-slate-50 transition">
                                    <i class="fas fa-file-signature text-teal-500 w-4 text-center"></i>
                                    <span>Cetak Berita Acara</span>
                                </a>
                            </div>

                            <!-- Section: Sesi & Monitoring -->
                            <div class="py-1.5">
                                <div class="px-2.5 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    Monitoring & Evaluasi
                                </div>
                                <a href="{{ route('admin.master.timetable.session', $timetable->id) }}" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-indigo-50 text-indigo-700 transition">
                                    <i class="fas fa-users-gear text-indigo-500 w-4 text-center"></i>
                                    <span>Monitor Sesi Ujian</span>
                                </a>
                                <a href="{{ route('admin.master.timetable.correct', $timetable->id) }}" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-emerald-50 text-emerald-700 transition">
                                    <i class="fas fa-check-double text-emerald-500 w-4 text-center"></i>
                                    <span>Koreksi Jawaban Essay</span>
                                </a>
                                <a href="{{ route('admin.master.timetable.alert', $timetable->id) }}" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 text-amber-700 transition">
                                    <i class="fas fa-triangle-exclamation text-amber-500 w-4 text-center"></i>
                                    <span>Log Pelanggaran</span>
                                </a>
                                <a href="{{ route('admin.master.timetable.video', $timetable->id) }}" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-purple-50 text-purple-700 transition">
                                    <i class="fas fa-video text-purple-500 w-4 text-center"></i>
                                    <span>Rekaman Video Peserta</span>
                                </a>
                                <a href="{{ route('admin.master.timetable.streaming', $timetable->id) }}" @click="open = false"
                                    class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 text-rose-700 transition">
                                    <i class="fas fa-tower-broadcast text-rose-500 w-4 text-center"></i>
                                    <span>Live Streaming Proctor</span>
                                </a>
                            </div>

                            @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                                <!-- Section: Hapus -->
                                <div class="pt-1.5">
                                    <button wire:click="confirmDelete('{{ $timetable->id }}')" @click="open = false"
                                        class="w-full text-left flex items-center gap-2 px-2.5 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                        <i class="fas fa-trash-can text-rose-500 w-4 text-center"></i>
                                        <span>Hapus Jadwal</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail List Section (Timetable Details Repeater) -->
            <div class="p-0 overflow-x-auto rounded-b-2xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center whitespace-nowrap">#</th>
                            <th class="py-3 px-4 whitespace-nowrap">ID QRCODE</th>
                            <th class="py-3 px-4 whitespace-nowrap">Ruang & Sesi</th>
                            <th class="py-3 px-4 whitespace-nowrap">Tipe & Materi / Modul</th>
                            <th class="py-3 px-4 whitespace-nowrap">Pengawas</th>
                            <th class="py-3 px-4 whitespace-nowrap">Jadwal Pelaksanaan</th>
                            <th class="py-3 px-4 whitespace-nowrap">Pengaturan & Token</th>
                            <th class="py-3 px-4 text-center whitespace-nowrap">Absensi Wajib</th>
                            <th class="py-3 px-4 text-center w-28 whitespace-nowrap">Aksi Sesi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($timetable->timetableDetails as $idx => $detail)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Index -->
                                <td class="py-3 px-4 text-center font-bold text-slate-400 whitespace-nowrap">
                                    {{ $idx + 1 }}
                                </td>

                                <!-- ID QRCODE Badge & Cetak Lembar -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <button wire:click="openDetailQrModal('{{ $detail->id }}')"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-amber-50 border border-slate-200 hover:border-amber-400 font-mono text-[11px] font-bold text-slate-800 transition cursor-pointer"
                                            title="Klik untuk lihat & cetak QRCODE Sesi ini">
                                            <i class="fas fa-qrcode text-amber-500"></i>
                                            <span>{{ $detail->code }}</span>
                                        </button>
                                        <button wire:click="openDetailQrModal('{{ $detail->id }}')"
                                            class="p-1 rounded-md text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition cursor-pointer"
                                            title="Cetak Lembar QRCODE Presensi">
                                            <i class="fas fa-print text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>

                                <!-- Ruang & Sesi -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">
                                        {{ $detail->examRoom?->name ?? '-' }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $detail->examSession?->name ?? '-' }}
                                    </div>
                                </td>

                                <!-- Tipe & Materi / Modul -->
                                <td class="py-3 px-4">
                                    @if($detail->isMaterial())
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 whitespace-nowrap">
                                                <i class="fas fa-book-open mr-0.5"></i> MATERI
                                            </span>
                                        </div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $detail->digitalBook?->title ?? '-' }}
                                        </div>
                                        @if($detail->digitalBook?->content_type)
                                            <div class="text-[10px] text-slate-500 uppercase font-mono mt-0.5">
                                                Tipe: {{ $detail->digitalBook->content_type }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-300 whitespace-nowrap">
                                                <i class="fas fa-file-signature mr-0.5"></i> UJIAN
                                            </span>
                                            @if($detail->examType)
                                                <span class="text-[11px] text-slate-500 font-medium whitespace-nowrap">
                                                    ({{ $detail->examType->name }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $detail->module?->name ?? '-' }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Pengawas -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 font-medium text-slate-700">
                                        <i class="fas fa-user-tie text-slate-400"></i>
                                        <span>{{ $detail->supervisor_names ?: 'Belum Ditentukan' }}</span>
                                    </div>
                                </td>

                                <!-- Jadwal Pelaksanaan -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800 flex items-center gap-1.5">
                                        <i class="fas fa-calendar-day text-slate-400"></i>
                                        <span>{{ $detail->exam_date ? \Carbon\Carbon::parse($detail->exam_date)->isoFormat('DD MMM Y') : '-' }}</span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 font-mono flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-clock text-[10px]"></i>
                                        <span>{{ $detail->start_time ? \Carbon\Carbon::parse($detail->start_time)->format('H:i') : '-' }} - {{ $detail->end_time ? \Carbon\Carbon::parse($detail->end_time)->format('H:i') : '-' }}</span>
                                    </div>
                                </td>

                                <!-- Pengaturan & Token -->
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($detail->require_token)
                                            <div class="inline-flex items-center gap-1 text-[11px] font-mono px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 font-semibold">
                                                <i class="fas fa-key text-[10px] text-amber-600"></i>
                                                <span>{{ $detail->token ?: 'Wajib Token' }}</span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-medium">Tanpa Token</span>
                                        @endif

                                        @if($detail->isExam())
                                            <div class="flex items-center gap-1 text-[10px] text-slate-500 whitespace-nowrap">
                                                @if($detail->is_camera)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200" title="Kamera Aktif">
                                                        <i class="fas fa-video text-[9px]"></i> Cam
                                                    </span>
                                                @endif
                                                @if($detail->is_recording)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200" title="Recording Aktif">
                                                        <i class="fas fa-record-vinyl text-[9px]"></i> Rec
                                                    </span>
                                                @endif
                                                @if($detail->is_streaming)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200" title="Streaming Proctor Aktif">
                                                        <i class="fas fa-tower-broadcast text-[9px]"></i> Stream
                                                    </span>
                                                @endif
                                                @if($detail->allow_repeat)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200" title="Pengulangan Diizinkan">
                                                        <i class="fas fa-repeat text-[9px]"></i> Repeat
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Absensi Wajib Toggle Status -->
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    @if($detail->require_attendance)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                            <i class="fas fa-check-circle"></i> Wajib Absen
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-medium bg-slate-100 text-slate-500">
                                            Tidak Wajib
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi Sesi -->
                                <td class="py-3 px-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button wire:click="openDetailQrModal('{{ $detail->id }}')"
                                            class="p-1.5 rounded-lg text-indigo-600 hover:bg-indigo-50 border border-indigo-200 transition cursor-pointer"
                                            title="Cetak Lembar QRCODE Presensi Sesi Ini">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                        <button wire:click="openAttendanceScanner('{{ $timetable->id }}', '{{ $detail->id }}')"
                                            class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 border border-amber-200 transition cursor-pointer"
                                            title="Scan Absensi Kamera untuk detail sesi ini">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <a href="{{ route('admin.master.timetable.detail', ['timetable_id' => $timetable->id, 'detail_id' => $detail->id, 'tab' => 'attendance']) }}"
                                            class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 border border-emerald-200 transition cursor-pointer"
                                            title="Buka Halaman Detail & Rekap Presensi Sesi Ini">
                                            <i class="fas fa-clipboard-list"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-slate-400 text-xs italic">
                                    Belum ada rincian kegiatan/sesi pada jadwal ini. Klik Edit untuk menambahkan kegiatan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-calendar-xmark"></i>
            </div>
            <h4 class="text-base font-bold text-slate-800 mb-1">Belum Ada Jadwal</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto mb-4">
                Belum ada jadwal yang dibuat untuk saat ini. Silakan tambahkan jadwal baru dengan rincian ujian atau materi.
            </p>
            @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                <button wire:click="openCreateModal" class="btn btn-primary text-xs">
                    <i class="fas fa-plus mr-1"></i> Buat Jadwal Baru
                </button>
            @endif
        </div>
    @endforelse
</div>
