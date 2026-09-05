<div wire:ignore.self id="modal-start-exam"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 100vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Ujian</h2>
            </div>
            <button wire:click="closeModalStartExam()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Token <span
                        class="text-red-600">*</span></label>
                <input type="text" id="code" wire:model.defer="code" placeholder="Masukan Token Ujian"
                    class="mt-1 form-control">
                @error('code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalStartExam()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitStartExam'
                class="px-4 py-2 bg-primary hover:bg-primary transition-colors text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<div wire:ignore.self id="modal-change-supervisor"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-indigo-600 text-xl"></i>
                <h2 class="text-xl font-semibold text-gray-800">Ubah Pengawas Ujian</h2>
            </div>
            <button wire:click="closeModalSupervisor()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 text-gray-600 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pengawas Ujian</label>
                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                    @forelse ($availableSupervisors as $supervisorId => $supervisorName)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition cursor-pointer text-sm font-medium text-gray-700">
                            <input type="checkbox"
                                wire:model="selectedSupervisors"
                                value="{{ $supervisorId }}"
                                class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span>{{ $supervisorName }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-3">Tidak ada data pengawas tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50 rounded-b-2xl">
            <button wire:click="closeModalSupervisor()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer font-medium text-sm">
                Batal
            </button>
            <button wire:click="saveSupervisor()"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition font-medium text-sm inline-flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                Simpan Pengawas
            </button>
        </div>
    </div>
</div>
