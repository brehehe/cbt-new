<div wire:ignore.self id="physical-exam-modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 800px; width: 100%;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Akun Biaya</h2>
            </div>
            <button wire:click="closeModalPhysicalExam()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 70vh; overflow-y: auto;">
            <div class="mb-4">
                <label for="patient_name" class="block text-sm font-medium text-gray-700">Pasien</label>
                <input type="text" id="patient_name" wire:model.defer="patient_name" placeholder="Masukkan Pasien"
                    disabled class="mt-1 form-control">
                @error('patient_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="doctor_name" class="block text-sm font-medium text-gray-700">Dokter</label>
                <input type="text" id="doctor_name" disabled wire:model.defer="doctor_name"
                    placeholder="Masukkan Dokter" class="mt-1 form-control">
                @error('doctor_name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="heart_rate" class="block text-sm font-medium text-gray-700">Detak Jantung</label>
                <input type="text" id="heart_rate" wire:model.defer="heart_rate" placeholder="Masukkan Detak Jantung"
                    class="mt-1 form-control">
                @error('heart_rate')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="breathing" class="block text-sm font-medium text-gray-700">Pernafasan</label>
                <input type="text" id="breathing" wire:model.defer="breathing" placeholder="Masukkan Pernafasan"
                    class="mt-1 form-control">
                @error('breathing')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="blood_pressure_sistole" class="block text-sm font-medium text-gray-700">Tekanan Darah
                    Sistolik</label>
                <input type="text" id="blood_pressure_sistole" wire:model.defer="blood_pressure_sistole"
                    placeholder="Masukkan Tekanan Darah Sistolik" class="mt-1 form-control">
                @error('blood_pressure_sistole')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="blood_pressure_diastole" class="block text-sm font-medium text-gray-700">Tekanan Darah
                    Diastolik</label>
                <input type="text" id="blood_pressure_diastole" wire:model.defer="blood_pressure_diastole"
                    placeholder="Masukkan Tekanan Darah Diastolik" class="mt-1 form-control">
                @error('blood_pressure_diastole')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="body_temperature" class="block text-sm font-medium text-gray-700">Suhu Tubuh</label>
                <input type="text" id="body_temperature" wire:model.defer="body_temperature"
                    placeholder="Masukkan Suhu Tubuh" class="mt-1 form-control">
                @error('body_temperature')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="height" class="block text-sm font-medium text-gray-700">Tinggi Badan</label>
                <input type="text" id="height" wire:model.defer="height" placeholder="Masukkan Tinggi Badan"
                    class="mt-1 form-control">
                @error('height')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="weight" class="block text-sm font-medium text-gray-700">Berat Badan</label>
                <input type="text" id="weight" wire:model.defer="weight" placeholder="Masukkan Berat Badan"
                    class="mt-1 form-control">
                @error('weight')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalPhysicalExam()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='confirmSubmitPhysicalExam'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
