<!-- LEMES Hierarchical Timetable Form: Informasi (Header) + Detail Repeater -->
<div class="space-y-6">
    <!-- Bagian 1: Informasi Header Jadwal -->
    <div class="bg-indigo-50/40 p-5 rounded-2xl border border-indigo-100/80 space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-indigo-900 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-indigo-600"></i> Informasi Utama Jadwal
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Jadwal -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Jadwal <span class="text-red-600">*</span>
                </label>
                <input type="text" wire:model="name" placeholder="Contoh: Jadwal Pelatihan Shorinji Kempo 2026"
                    class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Peserta Kelas (Classmate) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Peserta Kelas / Rombel <span class="text-red-600">*</span>
                </label>
                <select class="form-select w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" wire:model="classmate_id">
                    <option value="">-- Pilih Peserta Kelas --</option>
                    @foreach ($classmates as $key_cl => $clName)
                        <option value="{{ $key_cl }}">{{ $clName }}</option>
                    @endforeach
                </select>
                @error('classmate_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Deskripsi -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Deskripsi / Petunjuk Pelaksanaan
                </label>
                <textarea wire:model="description" rows="2" placeholder="Keterangan pelaksanaan jadwal untuk peserta dan pengawas..."
                    class="w-full text-sm rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition"></textarea>
                @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <!-- Bagian 2: Detail Jadwal (Repeater) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between border-b pb-2">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-indigo-600"></i> Detail Pelaksanaan
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Atur sesi, ruang, pengawas, tipe ujian/materi, dan absensi untuk tiap mata kegiatan.</p>
            </div>
            <button type="button" wire:click="addDetailRow" 
                class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm flex items-center gap-1.5 transition">
                <i class="fas fa-plus"></i> Tambah Detail
            </button>
        </div>

        <div class="space-y-4">
            @foreach($timetable_details as $index => $detail)
                <div class="bg-white rounded-2xl border-2 {{ ($detail['type'] ?? 'exam') === 'material' ? 'border-emerald-200 shadow-emerald-50' : 'border-indigo-200 shadow-indigo-50' }} p-5 shadow-md relative transition-all" wire:key="detail-row-{{ $index }}">
                    <!-- Header Card Detail -->
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-3 mb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <span class="flex items-center justify-center w-7 h-7 rounded-lg font-bold text-xs {{ ($detail['type'] ?? 'exam') === 'material' ? 'bg-emerald-600 text-white' : 'bg-indigo-600 text-white' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="font-bold text-sm text-slate-800">Detail Jadwal #{{ $index + 1 }}</span>
                            <!-- ID QRCODE Badge -->
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                <i class="fas fa-qrcode text-indigo-600"></i>
                                ID QR: {{ $detail['code'] ?? 'AUTO' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if(count($timetable_details) > 1)
                                <button type="button" wire:click="removeDetailRow({{ $index }})" 
                                    class="text-xs text-red-600 hover:text-red-800 font-semibold px-2.5 py-1 rounded-lg hover:bg-red-50 transition"
                                    title="Hapus detail ini">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Row 1: Ruang, Sesi, Pengawas -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Ruang <span class="text-red-500">*</span></label>
                            <select class="form-select w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.exam_room_id">
                                <option value="">-- Pilih Ruang --</option>
                                @foreach($examRooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                            @error("timetable_details.{$index}.exam_room_id") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Sesi <span class="text-red-500">*</span></label>
                            <select class="form-select w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.exam_session_id">
                                <option value="">-- Pilih Sesi --</option>
                                @foreach($examSessions as $sess)
                                    <option value="{{ $sess->id }}">{{ $sess->name }}</option>
                                @endforeach
                            </select>
                            @error("timetable_details.{$index}.exam_session_id") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Pengawas</label>
                            <select class="form-select w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.supervisors" multiple size="2">
                                @foreach($getSupervisors as $sId => $sName)
                                    <option value="{{ $sId }}">{{ $sName }}</option>
                                @endforeach
                            </select>
                            <span class="text-[10px] text-gray-400">Tekan Ctrl/Cmd untuk multi-pilih</span>
                        </div>
                    </div>

                    <!-- Row 2: Tanggal, Waktu Mulai, Waktu Selesai -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Tanggal</label>
                            <input type="date" class="form-control w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.exam_date">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Waktu Mulai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" class="form-control w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.start_time">
                            @error("timetable_details.{$index}.start_time") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase tracking-wider mb-1">Waktu Selesai <span class="text-red-500">*</span></label>
                            <input type="datetime-local" class="form-control w-full text-xs rounded-lg border-gray-300" wire:model="timetable_details.{{ $index }}.end_time">
                            @error("timetable_details.{$index}.end_time") <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Row 3: Pilihan Tipe (Ujian vs Materi) -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 mb-4">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                            Tipe Kegiatan:
                        </label>
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-indigo-900">
                                <input type="radio" name="type_{{ $index }}" value="exam" wire:model.live="timetable_details.{{ $index }}.type" class="text-indigo-600 focus:ring-indigo-500">
                                <i class="fas fa-file-signature text-indigo-600"></i> Ujian / Soal CBT
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-emerald-900">
                                <input type="radio" name="type_{{ $index }}" value="material" wire:model.live="timetable_details.{{ $index }}.type" class="text-emerald-600 focus:ring-emerald-500">
                                <i class="fas fa-book-reader text-emerald-600"></i> Materi / Buku Digital
                            </label>
                        </div>
                    </div>

                    <!-- Opsi Khusus Tipe Ujian -->
                    @if(($detail['type'] ?? 'exam') === 'exam')
                        <div class="p-4 bg-indigo-50/60 rounded-xl border border-indigo-100 space-y-3 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-indigo-900 uppercase tracking-wider mb-1">Modul Soal Ujian <span class="text-red-500">*</span></label>
                                    <select class="form-select w-full text-xs rounded-lg border-indigo-200" wire:model="timetable_details.{{ $index }}.module_id">
                                        <option value="">-- Pilih Modul Soal --</option>
                                        @foreach($modules as $mId => $mName)
                                            <option value="{{ $mId }}">{{ $mName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-indigo-900 uppercase tracking-wider mb-1">Tipe Ujian</label>
                                    <input type="text" class="form-control w-full text-xs rounded-lg border-indigo-200" placeholder="Contoh: Ujian Resmi / Simulasi" wire:model="timetable_details.{{ $index }}.exam_type">
                                </div>
                            </div>

                            <!-- Toggles Ujian -->
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 pt-2">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-indigo-600" wire:model="timetable_details.{{ $index }}.allow_repeat">
                                    <span>Pengulangan</span>
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-indigo-600" wire:model="timetable_details.{{ $index }}.require_token">
                                    <span>Wajib Token</span>
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-indigo-600" wire:model="timetable_details.{{ $index }}.is_camera">
                                    <span>Kamera</span>
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-indigo-600" wire:model="timetable_details.{{ $index }}.is_recording">
                                    <span>Recording</span>
                                </label>
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-indigo-600" wire:model="timetable_details.{{ $index }}.is_streaming">
                                    <span>Streaming</span>
                                </label>
                            </div>

                            <!-- Token Input if required -->
                            @if(!empty($detail['require_token']))
                                <div class="flex items-center gap-2 pt-1">
                                    <span class="text-xs font-bold text-slate-700">Token Ujian:</span>
                                    <input type="text" class="form-control text-xs w-32 font-mono font-bold tracking-wider uppercase rounded-lg border-indigo-200" wire:model="timetable_details.{{ $index }}.token" placeholder="TOKEN">
                                    <button type="button" wire:click="generateDetailToken({{ $index }})" class="btn btn-xs btn-outline-primary text-xs px-2 py-1 rounded">
                                        <i class="fas fa-arrows-rotate"></i> Acak Token
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Opsi Khusus Tipe Materi -->
                    @if(($detail['type'] ?? 'exam') === 'material')
                        <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100 space-y-3 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-emerald-900 uppercase tracking-wider mb-1">Pilih Buku Digital / Materi <span class="text-red-500">*</span></label>
                                <select class="form-select w-full text-xs rounded-lg border-emerald-200" wire:model="timetable_details.{{ $index }}.digital_book_id">
                                    <option value="">-- Pilih Buku Digital / Materi --</option>
                                    @foreach($digitalBooks as $book)
                                        <option value="{{ $book->id }}">[{{ strtoupper($book->content_type) }}] {{ $book->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-4 pt-1">
                                <label class="flex items-center gap-1.5 text-xs text-slate-700 cursor-pointer">
                                    <input type="checkbox" class="rounded text-emerald-600" wire:model="timetable_details.{{ $index }}.require_token">
                                    <span>Wajib Token untuk Buka Materi</span>
                                </label>
                                @if(!empty($detail['require_token']))
                                    <div class="flex items-center gap-2">
                                        <input type="text" class="form-control text-xs w-32 font-mono font-bold tracking-wider uppercase rounded-lg border-emerald-200" wire:model="timetable_details.{{ $index }}.token" placeholder="TOKEN">
                                        <button type="button" wire:click="generateDetailToken({{ $index }})" class="btn btn-xs btn-outline-success text-xs px-2 py-1 rounded">
                                            <i class="fas fa-arrows-rotate"></i> Acak
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Toggle Absensi Wajib -->
                    <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-qrcode text-amber-600 text-lg"></i>
                            <div>
                                <div class="text-xs font-bold text-amber-950">Wajib Absensi Kehadiran</div>
                                <div class="text-[11px] text-amber-800">Peserta wajib diverifikasi hadir via scan QRCODE sebelum dapat mengerjakan / membuka materi.</div>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" wire:model="timetable_details.{{ $index }}.require_attendance">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center pt-2">
            <button type="button" wire:click="addDetailRow" class="btn btn-outline-primary px-5 py-2 rounded-xl text-xs font-bold shadow-sm inline-flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Detail Jadwal Baru
            </button>
        </div>
    </div>
</div>
