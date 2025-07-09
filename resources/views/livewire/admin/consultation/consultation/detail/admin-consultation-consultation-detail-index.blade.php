<div>
    @php
        $status = $transaction->status;
    @endphp
    @include('livewire.admin.consultation.consultation.detail.admin-consultation-consultation-detail-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Konsultasi Detail</h1>
            </div>
            @if (in_array($status, ['consultation']))
                <div>
                    <button wire:click="confirmSave()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Simpan
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $transaction->doctor->name ?? $transaction->doctor_name }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                <p class="mt-1 text-gray-900">{{ $transaction->doctor->userDetail->specialization ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Praktik</label>
                <p class="mt-1 text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->end_time)->format('H:i') }}
                    WIB
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Antrian Saat Ini</label>
                <p class="mt-1 text-2xl font-bold text-blue-600">{{ $transaction->code_consultation }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                <p class="mt-1 text-gray-900">{{ $transaction->patient_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Poli</label>
                <p class="mt-1 text-orange-600 font-medium">{{ $transaction->location->name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah Antrian Tersisa</label>
                <p class="mt-1 text-red-600 font-medium">{{ $remaining_queue ?? 0 }} pasien</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <span
                    class="mt-1 inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full animate-pulse">
                    Sedang Melayani
                </span>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="overflow-x-auto  w-full">
            <nav class="flex w-full gap-2 px-2" aria-label="Tabs">
                @foreach ($get_tabs as $get_tab)
                    <button wire:click="changeTab('{{ $get_tab }}')"
                        class="text-center px-4 py-2 text-sm font-medium transition-all duration-300 cursor-pointer rounded-2xl
                               {{ $tab === $get_tab ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-black' }}">
                        {{ Str::title(Str::replace('-', ' ', $get_tab)) }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>
    @if ($tab == 'diagnosa')
        <div class="space-y-6 mb-6">
            <div class="p-6 bg-white shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- SOAP Fields -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900">SOAP Assessment</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subjective <span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="subjective" placeholder="Keluhan utama dan riwayat penyakit sekarang"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                        @error('subjective')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Objective <span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="objective" placeholder="Tanda vital, pemeriksaan fisik"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                        @error('objective')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Assessment <span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="assessment" placeholder="Diagnosis kerja dan diagnosis banding"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                        @error('assessment')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plan <span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="plan" placeholder="Rencana terapi, edukasi, dan tindak lanjut"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                        @error('plan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Anjuran Kembali -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Anjuran Kembali Ke Klinik</label>
                        <textarea wire:model.lazy="return_recommendation" placeholder="Anjuran Kembali ke Klinik"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                    </div>
                    <!-- Alergi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alergi Obat</label>
                        <textarea wire:model.lazy="allergy_name" placeholder="Alergi terhadap obat tertentu (jika ada)"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-20 auto-resize"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="transaction_nurses" class="block text-sm font-medium text-gray-700">Perawat</label>
                        <div wire:key="select-{{ rand() }}">
                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('transaction_nurses', e ? e : '');
                                    }
                                });" wire:model.lazy="transaction_nurses"
                                id="transaction_nurses" multiple>
                                <option value="">-- Pilih Perawat --</option>
                                @foreach ($nurses as $nurse)
                                    <option value="{{ $nurse['id'] }}">{{ $nurse['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- SATUSEHAT Condition Fields -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900">Keluhan Utama</h3>
                    </div>

                    <!-- Keluhan Utama untuk SATUSEHAT -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Keluhan Utama<span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="description_primary" placeholder="Contoh: Demam menggigil sejak 2 hari yang lalu"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-16"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Ringkasan keluhan utama yang akan dikirim ke SATUSEHAT
                        </p>
                        @error('description_primary')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700">Verification Status <span
                                class="text-red-600">*</span></label>
                        <select wire:model.lazy="verification_status"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control">
                            <option value="">-- Pilih Status --</option>
                            @foreach ($master_consultation_verification_statuses as $master_consultation_verification_statuse)
                                <option value="{{ $master_consultation_verification_statuse['code'] }}">
                                    {{ $master_consultation_verification_statuse['display'] }}
                                    ({{ $master_consultation_verification_statuse['code'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('verification_status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Klinis -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Clinical Status <span
                                class="text-red-600">*</span></label>
                        <select wire:model.lazy="clinical_status" {{ $status == 'consultation' ? null : 'disabled' }}
                            class="mt-1 form-control">
                            <option value="">-- Pilih Clinical Status --</option>
                            @foreach ($master_consultation_clinic_statuses as $master_consultation_clinic_statuse)
                                <option value="{{ $master_consultation_clinic_statuse['code'] }}">
                                    {{ $master_consultation_clinic_statuse['display'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SNOMED CT Code untuk Keluhan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SNOMED CT Code <span
                                class="text-red-600">*</span></label>
                        <div wire:key="snomed-{{ rand() }}">
                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('snomed_code', e ? e : '');
                                    }
                                });" wire:model.lazy="snomed_code"
                                id="snomed_code">
                                <option value="">-- Pilih Snomed CD --</option>
                                @foreach ($master_consultation_snomed_cts as $master_consultation_snomed_ct)
                                    <option value="{{ $master_consultation_snomed_ct['code'] }}">
                                        {{ $master_consultation_snomed_ct['display'] }}
                                        ({{ $master_consultation_snomed_ct['code'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('snomed_code')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai Keluhan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Onset Date Time <span
                                class="text-red-600">*</span></label>
                        <input type="datetime-local" wire:model.lazy="onset_datetime"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control" />
                        @error('onset_datetime')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICD-10 untuk Assessment -->
                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-medium text-gray-800 mb-2">ICD-10 Diagnosis (Optional)</h4>
                        @if ($status == 'consultation')
                            <button wire:click="createTransactionIcd10()"
                                class="mb-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                <i class="fa-solid fa-plus"></i> Tambahkan ICD 10
                            </button>
                        @endif

                        @forelse ($transaction_icd10s as $key => $transaction_icd10)
                            <div class="flex items-center justify-between bg-gray-100 p-3 rounded-lg mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $transaction_icd10['icd10_code'] ?? '-' }} |
                                        {{ $transaction_icd10['icd10_display'] ?? '-' }}
                                    </p>
                                </div>
                                @if ($status == 'consultation')
                                    <button
                                        wire:click="confirmDeleteTransactionIcd10('{{ $transaction_icd10['id'] }}')"
                                        class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada ICD-10 ditambahkan</p>
                        @endforelse
                    </div>
                </div>
            </div>
            {{-- <div class="p-6 bg-white shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- SATUSEHAT Condition Fields -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900">Riwayat Penyakit</h3>
                    </div>

                    <!-- Riwayat Penyakit  untuk SATUSEHAT -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Riwayat Penyakit <span
                                class="text-red-600">*</span></label>
                        <textarea wire:model.lazy="description_secondary" placeholder="Contoh: Demam menggigil sejak 2 hari yang lalu"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-16"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Ringkasan Riwayat penyakit yang akan dikirim ke SATUSEHAT
                        </p>
                        @error('description_secondary')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-medium text-gray-700">Verification Status <span
                                class="text-red-600">*</span></label>
                        <select wire:model.lazy="supporting_verification_status"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control">
                            <option value="">-- Pilih Status --</option>
                            @foreach ($master_consultation_verification_statuses as $master_consultation_verification_statuse)
                                <option value="{{ $master_consultation_verification_statuse['code'] }}">
                                    {{ $master_consultation_verification_statuse['display'] }}
                                    ({{ $master_consultation_verification_statuse['code'] }})
                                </option>
                            @endforeach
                        </select>
                        @error('supporting_verification_status')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Klinis -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Clinical Status <span
                                class="text-red-600">*</span></label>
                        <select wire:model.lazy="supporting_clinical_status"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control">
                            <option value="">-- Pilih Clinical Status --</option>
                            @foreach ($master_consultation_clinic_statuses as $master_consultation_clinic_statuse)
                                <option value="{{ $master_consultation_clinic_statuse['code'] }}">
                                    {{ $master_consultation_clinic_statuse['display'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SNOMED CT Code untuk Keluhan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SNOMED CT Code <span
                                class="text-red-600">*</span></label>
                        <div wire:key="supporting_snomed-{{ rand() }}">
                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('supporting_snomed_code', e ? e : '');
                                    }
                                });"
                                wire:model.lazy="supporting_snomed_code" id="supporting_snomed_code">
                                <option value="">-- Pilih Snomed CD --</option>
                                @foreach ($master_consultation_snomed_cts as $master_consultation_snomed_ct)
                                    <option value="{{ $master_consultation_snomed_ct['code'] }}">
                                        {{ $master_consultation_snomed_ct['display'] }}
                                        ({{ $master_consultation_snomed_ct['code'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('supporting_snomed_code')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai Keluhan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Onset Date Time <span
                                class="text-red-600">*</span></label>
                        <input type="datetime-local" wire:model.lazy="supporting_onset_datetime"
                            {{ $status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control" />
                        @error('supporting_onset_datetime')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ICD-10 untuk Assessment -->
                    <div class="md:col-span-2 mt-4">
                        <h4 class="text-md font-medium text-gray-800 mb-2">ICD-10 Diagnosis (Optional)</h4>
                        @if ($status == 'consultation')
                            <button wire:click="createSupportingTransactionIcd10()"
                                class="mb-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                <i class="fa-solid fa-plus"></i> Tambahkan ICD 10
                            </button>
                        @endif

                        @forelse ($supporting_transaction_icd10s as $key => $supporting_transaction_icd10)
                            <div class="flex items-center justify-between bg-gray-100 p-3 rounded-lg mb-2">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $supporting_transaction_icd10['icd10_code'] ?? '-' }} |
                                        {{ $supporting_transaction_icd10['icd10_display'] ?? '-' }}
                                    </p>
                                </div>
                                @if ($status == 'consultation')
                                    <button
                                        wire:click="confirmDeleteTransactionIcd10('{{ $supporting_transaction_icd10['id'] }}')"
                                        class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada ICD-10 ditambahkan</p>
                        @endforelse
                    </div>
                </div>
            </div> --}}
        </div>
    @elseif ($tab == 'tindakan')
        @if ($status == 'consultation')
            <div class="md:col-span-2 mb-4">
                <button wire:click="createActions()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i
                        class="fa-solid fa-plus"></i> Tambahkan Tindakan</button>
            </div>
        @endif
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Nama Tindakan</th>
                            <th>Harga</th>
                            <th style="width: 100px">Jumlah</th>
                            <th>Total</th>
                            <th>Deskripsi</th>
                            @if ($status == 'consultation')
                                <th class="w-1 center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction_actions as $key_transaction_action => $transaction_action)
                            <tr>
                                <td class="center">{{ $key_transaction_action + 1 }}</td>
                                <td>{{ $transaction_action['name'] ?? '-' }}</td>
                                <td>Rp{{ $transaction_action['price'] ?? '-' }}</td>
                                <td>
                                    @if ($status == 'consultation')
                                        <input type="number" class="form-control" style="width: 150px"
                                            wire:model.lazy="transaction_actions.{{ $key_transaction_action }}.quantity">
                                    @else
                                        {{ $transaction_action['quantity'] ?? '-' }}
                                    @endif
                                </td>
                                <td>Rp{{ $transaction_action['sub_total_price'] ?? '-' }}</td>
                                <td>
                                    @if ($status == 'consultation')
                                        <input type="text" class="form-control"
                                            wire:model.lazy="transaction_actions.{{ $key_transaction_action }}.description"
                                            placeholder="Masukan Deskripsi">
                                    @else
                                        {{ $transaction_action['description'] ?? '-' }}
                                    @endif
                                </td>
                                @if ($status == 'consultation')
                                    <td class="center">
                                        <div class="flex items-center">
                                            <button
                                                class="btn btn-icon text-red-600 hover:text-red-800 transition-colors edit-btn"
                                                wire:click="confirmDeleteAction('{{ $transaction_action['id'] }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($tab == 'bukti-tindakan')
        @if ($status == 'consultation')
            <div class="md:col-span-2 mb-4">
                <button wire:click="createProofOfAction()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i
                        class="fa-solid fa-plus"></i> Tambahkan Bukti Tindakan</button>
            </div>
        @endif
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Deskripsi</th>
                            <th>Sebelum Tindakan</th>
                            <th>Setelah Tindakan</th>
                            @if ($status == 'consultation')
                                <th class="w-1 center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($proof_of_actions as $key_proof_of_action => $proof_of_action)
                            <tr>
                                <td class="center">{{ $key_proof_of_action + 1 }}</td>
                                <td>{{ $proof_of_action['description'] ?? '-' }}</td>
                                <td>
                                    <img src="{{ $proof_of_action['before_photo'] ? asset('storage/' . $proof_of_action['before_photo']) : asset('asset/img/No-Image-Placeholder.svg.png') }}"
                                        alt="Sebelum Tindakan" style="width: 250px; height: 250px;"
                                        class=" object-cover rounded-lg cursor-pointer" data-easyzoom="true"
                                        id="before-image-{{ $proof_of_action['id'] }}">
                                </td>
                                <td>
                                    <img src="{{ $proof_of_action['after_photo'] ? asset('storage/' . $proof_of_action['after_photo']) : asset('asset/img/No-Image-Placeholder.svg.png') }}"
                                        alt="Setelah Tindakan" style="width: 250px; height: 250px;"
                                        class=" object-cover rounded-lg cursor-pointer" data-easyzoom="true"
                                        id="after-image-{{ $proof_of_action['id'] }}">
                                </td>
                                @if ($status == 'consultation')
                                    <td class="center">
                                        <div class="flex items-center">
                                            <button
                                                class="btn btn-icon text-red-600 hover:text-red-800 transition-colors edit-btn"
                                                wire:click="confirmDeleteProofOfAction('{{ $proof_of_action['id'] }}')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($tab == 'resep')
        @if ($status == 'consultation')
            <div class="md:col-span-2 mb-4">
                <button wire:click="createMedicine()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i
                        class="fa-solid fa-plus"></i> Tambahkan Resep</button>
            </div>
        @endif
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="border-b">
                            <th>Produk</th>
                            <th>Opsi Dosis</th>
                            <th>Dosis Dokter</th>
                            <th>Total Gramasi</th>
                            <th>Dosis Obat</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th class="py-2 w-8"></th>
                            {{-- @if ($transaction->status == 'draft')
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recipes as $key_recipe => $recipe)
                            <tr class="border-t-4">
                                <td colspan="8" class="py-3 px-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-blue-600"
                                            style="width: {{ $recipe['is_single'] ? '10%' : '15%' }};">/R-{{ $key_recipe + 1 }}</span>
                                        <select {{ $status == 'consultation' ? '' : 'disabled' }}
                                            class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                            wire:model.lazy='recipes.{{ $key_recipe }}.medicine_type_id'
                                            style="width: 50%;">
                                            <option value="">Jenis Resep</option>
                                            @foreach ($medicine_types as $medicine_type)
                                                <option value="{{ $medicine_type['id'] }}">
                                                    {{ $medicine_type['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed"
                                            style="width: 50%;">
                                            <span class="text-gray-500 mr-2 select-none">Rp</span>
                                            <input type="text" disabled
                                                wire:model='recipes.{{ $key_recipe }}.price_service_one'
                                                placeholder="Jasa 1"
                                                class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                        </div>
                                        <input type="text"
                                            wire:model.lazy='recipes.{{ $key_recipe }}.numero_recipe'
                                            placeholder="Numero Resep"
                                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                            class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                            style="width: 50%;">
                                        @if (!$recipe['is_single'])
                                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                wire:model.lazy='recipes.{{ $key_recipe }}.product_id'
                                                style="width: 100%;">
                                                <option value="">Jenis Produk Pendukung</option>
                                                @foreach ($supporting_products as $supporting_product)
                                                    <option value="{{ $supporting_product['id'] }}">
                                                        {{ $supporting_product['name'] }} -
                                                        {{ $supporting_product['product_stock']['quantity'] }} - Rp
                                                        {{ number_format($supporting_product['product_price']['price'], 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed"
                                                style="width: 50%;">
                                                <span class="text-gray-500 mr-2 select-none">Rp</span>
                                                <input type="text" disabled
                                                    wire:model='recipes.{{ $key_recipe }}.sub_total_price'
                                                    placeholder="Jasa 1"
                                                    class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                            </div>
                                            <button class="text-blue-500 hover:text-blue-700"
                                                wire:click="addDetail('{{ $recipe['id'] }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @endif
                                        <button class="text-red-600 hover:text-red-800 mx-1"
                                            wire:click="confirmDeleteTransactionRecipe('{{ $recipe['id'] }}')"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div wire:key="select-{{ rand() }}" class="flex-grow">
                                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control w-full"
                                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                                    dropdownParent: 'body',
                                                    allowClear: true,
                                                    plugins: ['clear_button'],
                                                    onChange: function(e) {
                                                        @this.set('recipes.{{ $key_recipe }}.how_to_use_id', e ? e : '');
                                                    }
                                                });"
                                                wire:model.lazy="recipes.{{ $key_recipe }}.how_to_use_id"
                                                id="recipes.{{ $key_recipe }}.how_to_use_id">
                                                <option value="">-- Pilih Rute Pemberian Obat --</option>
                                                @foreach ($how_to_uses as $key_how_to_use => $how_to_use)
                                                    <option value="{{ $key_how_to_use }}">{{ $how_to_use }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-warning px-3 py-1 ml-auto"
                                            wire:click="openModalHowToUse('{{ $recipe['id'] }}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <input type="text"
                                            wire:model.lazy='recipes.{{ $key_recipe }}.description'
                                            placeholder="Informasi Tambahan Aturan Pakai"
                                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                            class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} w-full border rounded px-2 py-1">
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <div wire:key="select-{{ rand() }}">
                                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                                    dropdownParent: 'body',
                                                    allowClear: true,
                                                    plugins: ['clear_button'],
                                                    onChange: function(e) {
                                                        @this.set('recipes.{{ $key_recipe }}.route_coding_code', e ? e : '');
                                                    }
                                                });"
                                                wire:model.lazy="recipes.{{ $key_recipe }}.route_coding_code"
                                                id="recipes.{{ $key_recipe }}.route_coding_code">
                                                <option value="">-- Pilih Rute Pemberian Obat --</option>
                                                @foreach ($master_medication_request_dosage_routes as $key_master_medication_request_dosage_route => $master_medication_request_dosage_route)
                                                    <option
                                                        value="{{ $key_master_medication_request_dosage_route }}">
                                                        {{ $master_medication_request_dosage_route }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @if (!empty($recipe['details']))
                                @forelse ($recipe['details'] as $index_detail => $item)
                                    <tr class="border-b">
                                        <td class="py-2" colspan="{{ !$recipe['is_single'] ? 1 : 5 }}">
                                            <p class="font-medium">{{ $item['product_name'] }}</p>
                                            <p class="text-xs text-gray-500">
                                                @Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </td>
                                        @if (!$recipe['is_single'])
                                            <td class="py-2">
                                                <div class="flex items-center gap-2">
                                                    <select
                                                        wire:model.lazy='recipes.{{ $key_recipe }}.details.{{ $index_detail }}.type'
                                                        {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                        class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                        style="width: 100%;">
                                                        <option value="single">Opsi Dosis</option>
                                                        <option value="partial">Partial</option>
                                                        <option value="gramasi">Gramasi</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <input
                                                    wire:model.lazy="recipes.{{ $key_recipe }}.details.{{ $index_detail }}.dosage_doctor"
                                                    type="text" placeholder="Dosis Dokter"
                                                    {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                    class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                    style="width: 100%;">
                                            </td>
                                            <td class="py-2">
                                                <input type="text" disabled
                                                    wire:model='recipes.{{ $key_recipe }}.details.{{ $index_detail }}.doctor_dosage_gram'
                                                    placeholder="Jasa 1"
                                                    class="text-sm border rounded px-2 py-1  bg-gray-100 cursor-not-allowed"
                                                    style="width: 100%;" />
                                            </td>
                                            <td class="py-2">
                                                <input
                                                    wire:model.lazy="recipes.{{ $key_recipe }}.details.{{ $index_detail }}.dosage_drug"
                                                    type="text" placeholder="Dosis Obat"
                                                    {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                                    class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                    style="width: 100%;">
                                            </td>
                                        @endif
                                        <td class="py-2 text-center">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="py-2 text-right">
                                            Rp{{ number_format($item['sub_total_price'], 0, ',', '.') }}</td>
                                        <td class="py-2 text-center">
                                            <button
                                                wire:click="confirmDeleteTransactionDetail('{{ $item['id'] }}')"
                                                class="text-red-500 hover:text-red-700"><i
                                                    class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="border-b">
                                        <td colspan="7" class="py-2 text-center text-gray-500">Tidak ada detail
                                            produk</td>
                                    </tr>
                                @endforelse
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($tab == 'jadwal-kontrol')
        <div class="space-y-6 mb-6">
            <!-- SECTION 1: Informasi Umum Produk -->
            <div class="p-6 bg-white shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" id="date" wire:model.lazy="date"
                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                            class="mt-1 form-control">
                    </div>
                    <div class="mb-4">
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700">Dokter</label>
                        <div wire:key="select-{{ rand() }}">
                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('doctor_id', e ? e : '');
                                    }
                                });" wire:model.lazy="doctor_id"
                                id="doctor_id">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="location_id" class="block text-sm font-medium text-gray-700">Poli</label>
                        <div wire:key="select-{{ rand() }}">
                            <select {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                                class="{{ $transaction->status == 'consultation' ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('location_id', e ? e : '');
                                    }
                                });" wire:model.lazy="location_id"
                                id="location_id">
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location['id'] }}">{{ $location['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="description_control"
                        class="block text-sm font-medium text-gray-700">Keterangan</label>
                    <textarea id="description_control" wire:model.lazy="description_control" rows="3"
                        {{ $transaction->status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control"
                        placeholder="Masukkan keterangan jadwal kontrol"></textarea>
                </div>
            </div>
        </div>
    @elseif ($tab == 'rujukan')
        <div class="space-y-6 mb-6">
            <!-- SECTION 1: Informasi Umum Produk -->
            <div class="p-6 bg-white shadow rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rujukan Ke</label>
                        <input type="text" wire:model.lazy="hospital_name" placeholder="Contoh: Rumah Sakit XYZ"
                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                            class="mt-1 form-control" />
                        @error('hospital_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dokter Rujukan</label>
                        <input type="text" wire:model.lazy="doctor_name" placeholder="Contoh: Dr. John Doe"
                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                            class="mt-1 form-control" />
                        @error('doctor_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Rujukan</label>
                        <input type="date" wire:model.lazy="date_refer"
                            {{ $transaction->status == 'consultation' ? null : 'disabled' }}
                            class="mt-1 form-control" />
                        @error('date_refer')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Keterangan Rujukan</label>
                        <textarea wire:model.lazy="description_refer" placeholder="Contoh: Pasien perlu dirujuk untuk penanganan lebih lanjut"
                            {{ $transaction->status == 'consultation' ? null : 'disabled' }} class="mt-1 form-control h-24"></textarea>
                        @error('description_refer')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
    <script>
        let currentStream = null;
        let availableCameras = [];
        let isDetectingCameras = false;
        let activeCamera = {
            before: null,
            after: null
        };

        // Initialize camera detection when button is clicked
        function initializeCameraSelect(type) {
            setTimeout(() => {
                detectCameras(type);
            }, 100);
        }

        // Event handler when camera dropdown selection changes
        function onCameraChange(type) {
            const select = document.getElementById(`camera-${type}-select`);
            const selectedDeviceId = select.value;

            if (selectedDeviceId) {
                console.log(`Camera changed for ${type}:`, selectedDeviceId);
                activeCamera[type] = selectedDeviceId;
                openCameraWithDeviceId(type, selectedDeviceId);
            } else {
                // If no camera selected, stop current camera
                stopCamera(type);
            }
        }

        async function detectCameras(type) {
            if (isDetectingCameras) return;

            isDetectingCameras = true;
            const select = document.getElementById(`camera-${type}-select`);

            try {
                select.innerHTML = '<option value="">Mendeteksi kamera...</option>';

                // Check if browser supports getUserMedia
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    throw new Error('Browser tidak mendukung akses kamera');
                }

                console.log('Requesting camera permission...');

                // Request permission and get temporary stream to trigger permission dialog
                const tempStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: false
                });

                // Stop temporary stream immediately
                tempStream.getTracks().forEach(track => track.stop());

                console.log('Permission granted, enumerating devices...');

                // Now enumerate devices (labels will be available after permission is granted)
                const devices = await navigator.mediaDevices.enumerateDevices();
                availableCameras = devices.filter(device => device.kind === 'videoinput');

                console.log('Found cameras:', availableCameras);

                // Populate select options
                select.innerHTML = '<option value="">Pilih kamera...</option>';

                if (availableCameras.length === 0) {
                    select.innerHTML = '<option value="">Tidak ada kamera ditemukan</option>';
                } else {
                    availableCameras.forEach((camera, index) => {
                        const option = document.createElement('option');
                        option.value = camera.deviceId;

                        // Use label if available, otherwise create generic name
                        let label = camera.label;
                        if (!label || label.trim() === '') {
                            label = `Kamera ${index + 1}`;
                        }

                        // Add more descriptive names based on common patterns
                        if (label.toLowerCase().includes('integrated') || label.toLowerCase().includes(
                                'built-in')) {
                            label += ' (Built-in)';
                        } else if (label.toLowerCase().includes('usb')) {
                            label += ' (USB)';
                        } else if (label.toLowerCase().includes('virtual')) {
                            label += ' (Virtual)';
                        }

                        option.textContent = label;
                        select.appendChild(option);
                    });
                }

                // Also populate the other camera select if it exists
                const otherType = type === 'before' ? 'after' : 'before';
                const otherSelect = document.getElementById(`camera-${otherType}-select`);
                if (otherSelect && otherSelect.innerHTML.includes('Memuat kamera')) {
                    populateSelect(otherSelect);
                }

            } catch (error) {
                console.error('Error detecting cameras:', error);

                let errorMessage = 'Tidak dapat mendeteksi kamera';
                if (error.name === 'NotAllowedError') {
                    errorMessage = 'Izin kamera ditolak - refresh halaman dan izinkan akses kamera';
                } else if (error.name === 'NotFoundError') {
                    errorMessage = 'Tidak ada kamera ditemukan';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'Browser tidak mendukung akses kamera';
                }

                select.innerHTML = `<option value="">${errorMessage}</option>`;
            } finally {
                isDetectingCameras = false;
            }
        }

        function populateSelect(select) {
            select.innerHTML = '<option value="">Pilih kamera...</option>';

            availableCameras.forEach((camera, index) => {
                const option = document.createElement('option');
                option.value = camera.deviceId;

                let label = camera.label || `Kamera ${index + 1}`;
                if (label.toLowerCase().includes('integrated') || label.toLowerCase().includes('built-in')) {
                    label += ' (Built-in)';
                } else if (label.toLowerCase().includes('usb')) {
                    label += ' (USB)';
                }

                option.textContent = label;
                select.appendChild(option);
            });
        }

        function openCameraWithDeviceId(type, deviceId) {
            const constraints = {
                video: {
                    deviceId: {
                        exact: deviceId
                    },
                    width: {
                        ideal: 1280
                    },
                    height: {
                        ideal: 720
                    }
                }
            };

            // Stop existing stream if any
            stopCamera(type);

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    currentStream = stream;
                    const video = document.getElementById(`camera-${type}-video`);
                    const preview = document.getElementById(`camera-${type}-preview`);
                    const button = document.getElementById(`camera-${type}-button`);
                    const result = document.getElementById(`camera-${type}-result`);

                    video.srcObject = stream;
                    preview.style.display = 'block';
                    button.style.display = 'none';
                    result.style.display = 'none';

                    console.log(`Camera ${type} started successfully with device:`, deviceId);
                })
                .catch(function(error) {
                    console.error('Error accessing camera:', error);
                    handleCameraError(error);

                    // Reset dropdown to no selection on error
                    const select = document.getElementById(`camera-${type}-select`);
                    select.selectedIndex = 0;
                });
        }

        // Legacy function for backward compatibility
        function openCamera(type) {
            const selectedDeviceId = document.getElementById(`camera-${type}-select`).value;

            if (!selectedDeviceId) {
                alert('Silakan pilih kamera terlebih dahulu');
                return;
            }

            openCameraWithDeviceId(type, selectedDeviceId);
        }

        function restartCamera(type) {
            const selectedDeviceId = activeCamera[type] || document.getElementById(`camera-${type}-select`).value;

            if (selectedDeviceId) {
                openCameraWithDeviceId(type, selectedDeviceId);
            } else {
                alert('Silakan pilih kamera terlebih dahulu');
            }
        }

        function capturePhoto(type) {
            const video = document.getElementById(`camera-${type}-video`);
            const canvas = document.getElementById(`camera-${type}-canvas`);
            const context = canvas.getContext('2d');
            const preview = document.getElementById(`camera-${type}-preview`);
            const result = document.getElementById(`camera-${type}-result`);
            const image = document.getElementById(`camera-${type}-image`);

            // Set canvas size to match video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw video frame to canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert canvas to data URL with better quality
            const dataURL = canvas.toDataURL('image/jpeg', 0.9);

            // Show captured image
            image.src = dataURL;
            preview.style.display = 'none';
            result.style.display = 'block';

            // Stop camera stream
            stopCamera(type);

            // Convert data URL to blob and send to Livewire
            fetch(dataURL)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], `camera-${type}-${Date.now()}.jpg`, {
                        type: 'image/jpeg'
                    });

                    // Trigger Livewire file upload
                    const property = type === 'before' ? 'before_photo' : 'after_photo';
                    @this.upload(property, file,
                        (uploadedFilename) => {
                            console.log('Photo uploaded successfully:', uploadedFilename);
                        },
                        (error) => {
                            console.error('Upload error:', error);
                            alert('Gagal mengunggah foto. Silakan coba lagi.');
                            deletePhoto(type);
                        }
                    );
                })
                .catch(error => {
                    console.error('Error processing captured photo:', error);
                    alert('Gagal memproses foto. Silakan coba lagi.');
                    deletePhoto(type);
                });
        }

        function deletePhoto(type) {
            const result = document.getElementById(`camera-${type}-result`);
            const button = document.getElementById(`camera-${type}-button`);
            const image = document.getElementById(`camera-${type}-image`);

            // Clear the image
            image.src = '';
            result.style.display = 'none';
            button.style.display = 'block';

            // Clear Livewire property
            const property = type === 'before' ? 'before_photo' : 'after_photo';
            @this.set(property, null);
        }

        function stopCamera(type) {
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }

            const preview = document.getElementById(`camera-${type}-preview`);
            const button = document.getElementById(`camera-${type}-button`);

            if (preview) preview.style.display = 'none';
            if (button) button.style.display = 'block';
        }

        function handleCameraError(error) {
            let errorMessage = 'Tidak dapat mengakses kamera.';

            switch (error.name) {
                case 'NotAllowedError':
                    errorMessage = 'Izin kamera ditolak. Silakan izinkan akses kamera di browser dan refresh halaman.';
                    break;
                case 'NotFoundError':
                    errorMessage = 'Kamera tidak ditemukan pada perangkat ini.';
                    break;
                case 'NotSupportedError':
                    errorMessage = 'Browser tidak mendukung akses kamera.';
                    break;
                case 'OverconstrainedError':
                    errorMessage = 'Kamera yang dipilih tidak mendukung pengaturan yang diminta.';
                    break;
                case 'SecurityError':
                    errorMessage = 'Akses kamera diblokir karena alasan keamanan. Pastikan menggunakan HTTPS.';
                    break;
                default:
                    errorMessage = `Error kamera: ${error.message}`;
            }

            alert(errorMessage);
        }

        // Clean up camera when page/modal is closed
        window.addEventListener('beforeunload', function() {
            stopCamera('before');
            stopCamera('after');
        });

        // Listen for Livewire navigation
        document.addEventListener('livewire:navigated', function() {
            availableCameras = [];
            activeCamera = {
                before: null,
                after: null
            };
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('textarea.auto-resize').forEach(function(textarea) {
                textarea.style.overflow = 'hidden';
                textarea.style.resize = 'none';

                const resize = () => {
                    textarea.style.height = 'auto';
                    textarea.style.height = textarea.scrollHeight + 'px';
                };

                textarea.addEventListener('input', resize);
                resize(); // Initial call
            });
        });
    </script>
@endpush
