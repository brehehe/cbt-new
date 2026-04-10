<div wire:ignore.self id="modal-security"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-[100] transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-3xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 600px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-shield-alt text-lg"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Tambah Log Keamanan</h2>
                    <p class="text-xs text-gray-500">Buat catatan insiden keamanan secara manual</p>
                </div>
            </div>
            <button wire:click="closeModal('modal-security')"
                class="text-gray-400 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-8 py-6 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="space-y-5">
                <!-- User Attribution -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Atribusi Pengguna <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.defer="form.causer_id" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all appearance-none">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                    @error('form.causer_id') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Event <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.defer="form.event_type" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all appearance-none">
                            <option value="">-- Pilih Jenis Event --</option>
                            <option value="security.manual_note">Catatan Manual (Note)</option>
                            <option value="security.flag_incident">Insiden Mencurigakan (Flag)</option>
                            <option value="security.violation_confirmed">Pelanggaran Terkonfirmasi</option>
                            <option value="security.other">Lainnya</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                    @error('form.event_type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi / Detail Kejadian <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="form.description" rows="4" 
                              placeholder="Jelaskan detail insiden atau alasan pencatatan log ini..."
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:bg-white transition-all resize-none"></textarea>
                    @error('form.description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-8 py-6 border-t border-gray-50">
            <button wire:click="closeModal('modal-security')"
                class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 transition-colors">
                Batal
            </button>
            <button wire:click="submit"
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                <i class="fas fa-check"></i>
                Simpan Log
            </button>
        </div>
    </div>
</div>

<script>
    window.addEventListener('openModal', event => {
        const modal = document.getElementById(event.detail.id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.querySelector('.transform').classList.remove('scale-95');
                modal.querySelector('.transform').classList.add('scale-100');
            }, 10);
        }
    });

    window.addEventListener('closeModal', event => {
        const modal = document.getElementById(event.detail.id);
        if (modal) {
            modal.querySelector('.transform').classList.remove('scale-100');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }
    });
</script>
