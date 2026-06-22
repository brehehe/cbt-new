<div>
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-[color:var(--primary)]">Tambah Jadwal Baru</h1>
            <p class="text-gray-600">Buat jadwal baru, atur ruang, pengawas, dan pilih peserta ujian secara massal menggunakan tab.</p>
        </div>
        <a href="{{ route('admin.master.timetable') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-4" wire:key="tab-nav-container-{{ count($schedules) }}">
        <div class="flex flex-wrap items-center gap-2">
            @foreach($schedules as $idx => $sched)
                @php
                    $hasTabError = false;
                    foreach ($errors->keys() as $errorKey) {
                        if (str_starts_with($errorKey, "schedules.{$idx}.")) {
                            $hasTabError = true;
                            break;
                        }
                    }
                @endphp
                <div class="flex items-center rounded-lg border {{ $activeTab === $idx ? 'border-primary bg-blue-50/30' : 'border-gray-200 hover:border-gray-300' }} transition-all p-1" wire:key="tab-button-wrapper-{{ $idx }}">
                    <button type="button" wire:click="changeTab({{ $idx }})" class="py-2 px-4 font-medium text-sm focus:outline-none whitespace-nowrap {{ $activeTab === $idx ? 'text-primary' : 'text-gray-600 hover:text-gray-900' }}">
                        @if($hasTabError)
                            <i class="fa-solid fa-circle-exclamation text-red-500 mr-1.5 animate-pulse"></i>
                        @endif
                        {{ $sched['name'] ?: 'Jadwal ' . ($idx + 1) }}
                    </button>
                    @if(count($schedules) > 1)
                        <button type="button" wire:click="removeTab({{ $idx }})" class="text-gray-400 hover:text-red-500 px-2 transition-colors duration-150" title="Hapus Jadwal">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    @endif
                </div>
            @endforeach

            <button type="button" wire:click="addTab" class="flex items-center gap-2 py-2.5 px-4 text-sm font-medium border border-dashed border-green-500 text-green-600 hover:bg-green-50 rounded-lg transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Jadwal
            </button>
        </div>
    </div>

    <!-- Active Tab Configuration Form -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-6" wire:key="form-container-tab-{{ $activeTab }}-{{ count($schedules) }}">
        <h3 class="text-xl font-semibold border-b pb-4 mb-4 text-gray-800">
            Informasi untuk <span class="text-primary font-bold">{{ $schedules[$activeTab]['name'] ?: 'Jadwal ' . ($activeTab + 1) }}</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Jadwal <span class="text-red-600">*</span></label>
                    <input type="text" id="name" wire:model.defer="schedules.{{ $activeTab }}.name" placeholder="Masukkan Nama Jadwal" class="mt-1 form-control">
                    @error("schedules.{$activeTab}.name") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                @if(optional(auth()->user()->company)->import_student_timetable)
                <div>
                    <label for="classmate_id" class="block text-sm font-medium text-gray-700">Peserta (Classmate) <span class="text-red-600">*</span></label>
                    <div wire:ignore wire:key="select-classmate-wrapper-{{ $activeTab }}-{{ $schedules[$activeTab]['classmate_id'] ?? '' }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) { @this.set('schedules.{{ $activeTab }}.classmate_id', e ? e : ''); }
                        });" wire:model.lazy="schedules.{{ $activeTab }}.classmate_id" id="classmate_id" wire:key="select-classmate-el-{{ $activeTab }}">
                            <option value="">-- Pilih Peserta (Classmate) --</option>
                            @foreach ($classmates as $classmate)
                                <option value="{{ $classmate->id }}" {{ ($schedules[$activeTab]['classmate_id'] ?? '') == $classmate->id ? 'selected' : '' }}>{{ $classmate->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error("schedules.{$activeTab}.classmate_id") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                @endif
                <div>
                    <label for="module_id" class="block text-sm font-medium text-gray-700">Modul <span class="text-red-600">*</span></label>
                    <div wire:ignore wire:key="select-module-wrapper-{{ $activeTab }}-{{ $schedules[$activeTab]['classmate_id'] ?? '' }}-{{ count($schedules) }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) { @this.set('schedules.{{ $activeTab }}.module_id', e ? e : ''); }
                        });" wire:model.lazy="schedules.{{ $activeTab }}.module_id" id="module_id" wire:key="select-module-el-{{ $activeTab }}">
                            <option value="">-- Pilih Modul --</option>
                            @foreach ($modules as $key => $module)
                                <option value="{{ $key }}" {{ ($schedules[$activeTab]['module_id'] ?? '') == $key ? 'selected' : '' }}>{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error("schedules.{$activeTab}.module_id") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="exam_room_id" class="block text-sm font-medium text-gray-700">Ruang Ujian <span class="text-red-600">*</span></label>
                    <div wire:ignore wire:key="select-room-wrapper-{{ $activeTab }}-{{ $schedules[$activeTab]['classmate_id'] ?? '' }}-{{ count($schedules) }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) { @this.set('schedules.{{ $activeTab }}.exam_room_id', e ? e : ''); }
                        });" wire:model.lazy="schedules.{{ $activeTab }}.exam_room_id" id="exam_room_id" wire:key="select-room-el-{{ $activeTab }}">
                            <option value="">-- Pilih Ruang Ujian --</option>
                            @foreach ($examRooms as $room)
                                <option value="{{ $room->id }}" {{ ($schedules[$activeTab]['exam_room_id'] ?? '') == $room->id ? 'selected' : '' }}>{{ $room->name }} - [CODE]:{{ $room?->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pilih ruang ujian untuk jadwal ini.</p>
                    @error("schedules.{$activeTab}.exam_room_id") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="exam_session_id" class="block text-sm font-medium text-gray-700">Sesi Ujian <span class="text-red-600">*</span></label>
                    <div wire:ignore wire:key="select-session-wrapper-{{ $activeTab }}-{{ $schedules[$activeTab]['classmate_id'] ?? '' }}-{{ count($schedules) }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) { @this.set('schedules.{{ $activeTab }}.exam_session_id', e ? e : ''); }
                        });" wire:model.lazy="schedules.{{ $activeTab }}.exam_session_id" id="exam_session_id" wire:key="select-session-el-{{ $activeTab }}">
                            <option value="">-- Pilih Sesi Ujian --</option>
                            @foreach ($examSessions as $session)
                                <option value="{{ $session->id }}" {{ ($schedules[$activeTab]['exam_session_id'] ?? '') == $session->id ? 'selected' : '' }}>{{ $session->name }} - [CODE]:{{ $session?->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error("schedules.{$activeTab}.exam_session_id") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas <span class="text-red-600">*</span></label>
                    <div wire:ignore wire:key="select-supervisors-wrapper-{{ $activeTab }}-{{ $schedules[$activeTab]['classmate_id'] ?? '' }}-{{ count($schedules) }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) { @this.set('schedules.{{ $activeTab }}.supervisors', e ? (typeof e === 'string' ? e.split(',') : e) : []); }
                        });" wire:model.lazy="schedules.{{ $activeTab }}.supervisors" id="supervisors" multiple wire:key="select-supervisors-el-{{ $activeTab }}">
                            <option value="">-- Pilih Pengawas --</option>
                            @foreach ($getSupervisors as $key => $supervisor)
                                <option value="{{ $key }}" {{ in_array($key, $schedules[$activeTab]['supervisors'] ?? []) ? 'selected' : '' }}>{{ $supervisor }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error("schedules.{$activeTab}.supervisors") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai <span class="text-red-600">*</span></label>
                    <input type="datetime-local" id="start_time" wire:model.live="schedules.{{ $activeTab }}.start_time" class="mt-1 form-control">
                    @error("schedules.{$activeTab}.start_time") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai <span class="text-red-600">*</span></label>
                    <input type="datetime-local" id="end_time" wire:model.live="schedules.{{ $activeTab }}.end_time" class="mt-1 form-control">
                    @error("schedules.{$activeTab}.end_time") <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" wire:model.defer="schedules.{{ $activeTab }}.description" placeholder="Masukkan Deskripsi" class="mt-1 form-control h-32"></textarea>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" wire:model.defer="schedules.{{ $activeTab }}.is_recording" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="text-sm font-medium text-gray-700">Recording</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors">
                        <input type="checkbox" wire:model.defer="schedules.{{ $activeTab }}.is_streaming" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="text-sm font-medium text-gray-700">Streaming</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Peserta Ujian -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-6" wire:key="peserta-section-tab-{{ $activeTab }}-{{ count($schedules) }}">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-4 mb-4 gap-4">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Peserta Ujian untuk <span class="text-primary font-bold">{{ $schedules[$activeTab]['name'] ?: 'Jadwal ' . ($activeTab + 1) }}</span></h3>
                <p class="text-xs text-gray-500 mt-1">Mahasiswa yang dipilih di sini tidak akan bisa dipilih di tab jadwal lain pada sesi ini.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-1">
                    <input type="number" wire:model.defer="generateCount" class="form-control w-24 text-center border-none bg-transparent focus:ring-0" placeholder="Jml" min="1" max="1000">
                    <button wire:click="generateStudents" class="btn btn-success flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-bolt"></i> Generate
                    </button>
                </div>
                <button wire:click="openModalStudent" class="btn btn-primary flex items-center gap-2">
                    <i class="fa-solid fa-users"></i> Pilih Peserta Manual
                </button>
            </div>
        </div>
        
        <div class="mb-2 text-sm text-gray-600">Total Peserta Dipilih: <span class="font-bold text-lg text-primary">{{ isset($schedules[$activeTab]['selectedStudents']) ? count($schedules[$activeTab]['selectedStudents']) : 0 }}</span> Siswa</div>

        @if(isset($schedules[$activeTab]['selectedStudents']) && count($schedules[$activeTab]['selectedStudents']) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-96 overflow-y-auto p-2 bg-gray-50 rounded-lg border border-gray-100 custom-scrollbar">
                @foreach ($this->selectedStudentsData as $student)
                    <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex justify-between items-center" wire:key="selected-student-{{ $student->id }}">
                        <span class="text-sm font-medium text-gray-800 truncate" title="{{ $student->name }}">{{ $student->name }}</span>
                        <button wire:click="removeStudent('{{ $student->id }}')" class="text-red-500 hover:text-red-700 transition px-2" title="Hapus">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <i class="fa-solid fa-users-slash text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Belum ada peserta yang dipilih untuk jadwal ini.</p>
                <p class="text-xs text-gray-400 mt-1">Pilih peserta secara manual atau klik tombol Generate.</p>
            </div>
        @endif
    </div>

    <!-- Submit Section -->
    <div class="flex justify-end gap-3 mb-10">
        <button wire:click='submit' class="btn btn-primary btn-lg shadow-lg">
            <i class="fa-solid fa-save mr-2"></i> Simpan Semua Jadwal & Peserta
        </button>
    </div>

    <!-- Modal Pilih Peserta Manual -->
    <div wire:ignore.self id="modalStudent" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all scale-95 duration-300 ease-out animate-fade-in flex flex-col" style="max-height: 90vh;">
            
            <div class="flex justify-between items-center p-6 border-b shrink-0">
                <h2 class="text-xl font-semibold text-gray-800">Pilih Peserta Ujian</h2>
                <button wire:click="closeModalStudent()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <div class="px-6 py-4 border-b shrink-0 bg-gray-50">
                <div class="relative max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm" placeholder="Cari nama peserta..." wire:model.live='searchStudent'>
                </div>
            </div>

            <div class="overflow-y-auto flex-1 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" wire:key="modal-students-grid-tab-{{ $activeTab }}">
                    @forelse ($studentsList as $user)
                        <div wire:click="toggleStudent('{{ $user->id }}')" class="flex items-center gap-3 p-3 rounded-lg border-2 cursor-pointer transition-all {{ in_array((string)$user->id, $schedules[$activeTab]['selectedStudents'] ?? []) ? 'border-primary bg-blue-50/50' : 'border-gray-200 hover:border-blue-300 bg-white' }}" wire:key="student-item-{{ $user->id }}">
                            <div class="flex-shrink-0">
                                <div class="w-5 h-5 rounded border {{ in_array((string)$user->id, $schedules[$activeTab]['selectedStudents'] ?? []) ? 'bg-primary border-primary flex items-center justify-center' : 'border-gray-300' }}">
                                    @if(in_array((string)$user->id, $schedules[$activeTab]['selectedStudents'] ?? []))
                                        <i class="fa-solid fa-check text-white text-xs"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500">
                            Tidak ada peserta ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 border-t shrink-0 flex justify-between items-center bg-gray-50 rounded-b-2xl">
                <div class="text-sm text-gray-600">
                    {{ $studentsList->links('vendor.livewire.custom') }}
                </div>
                <button wire:click="closeModalStudent()" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow">
                    Selesai
                </button>
            </div>
        </div>
    </div>
</div>
