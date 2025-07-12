<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Nilai Ujian Detail</h1>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" value="{{ $timetable['name'] }}" disabled placeholder="Masukkan Nama"
                class="mt-1 form-control">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="module_id" class="block text-sm font-medium text-gray-700">Modul</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('module_id', e ? e : '');
                    }
                });"
                    wire:model='module_id' id="module_id">
                    <option value="">-- Pilih Modul --</option>
                    @foreach ($modules as $key_module => $module)
                        <option value="{{ $key_module }}">{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            @error('module_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('supervisors', e ? e : '');
                    }
                });"
                    wire:model.lazy="supervisors" id="supervisors" multiple>
                    <option value="">-- Pilih Pengawas --</option>
                    @foreach ($getSupervisors as $key_getSupervisor => $getSupervisor)
                        <option value="{{ $key_getSupervisor }}">{{ $getSupervisor }}</option>
                    @endforeach
                </select>
            </div>
            @error('supervisors')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
            <input disabled type="datetime-local" id="start_time" value="{{ $timetable['start_time'] }}"
                placeholder="Masukkan" class="mt-1 form-control">
            @error('start_time')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
            <input disabled type="datetime-local" id="end_time" value="{{ $timetable['end_time'] }}"
                placeholder="Masukkan" class="mt-1 form-control">
            @error('end_time')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="description" disabled value="{{ $timetable['description'] }}" placeholder="Masukkan Deskripsi"
                class="mt-1 form-control"></textarea>
        </div>
    </div>
    <div class="grid grid-cols-5 gap-4 mb-4">
        <div>
            <label for="total_soal" class="block text-sm font-medium text-gray-700">Total Soal</label>
            <input disabled type="number" id="total_soal" value="{{ $user_timetable->userModuleQuestions->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="terjawab" class="block text-sm font-medium text-gray-700">Terjawab</label>
            <input disabled type="number" id="terjawab"
                value="{{ $user_timetable->userModuleQuestions->whereNotNull('answer_id')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="tidak_terjawab" class="block text-sm font-medium text-gray-700">Tidak Terjawab</label>
            <input disabled type="number" id="tidak_terjawab"
                value="{{ $user_timetable->userModuleQuestions->whereNull('answer_id')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="benar" class="block text-sm font-medium text-gray-700">Benar</label>
            <input disabled type="number" id="benar"
                value="{{ $user_timetable->userModuleQuestions->where('status', 'correct')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="salah" class="block text-sm font-medium text-gray-700">Salah</label>
            <input disabled type="number" id="salah"
                value="{{ $user_timetable->userModuleQuestions->where('status', 'wrong')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div class="md:col-span-5">
            <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai</label>
            <input disabled type="number" id="nilai" value="{{ $user_timetable->mark }}" placeholder="Masukkan"
                class="mt-1 form-control">
            @error('nilai')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
