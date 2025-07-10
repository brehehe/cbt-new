<div wire:ignore.self id="modal" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in" style="max-width: 100vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Modul Soal</h2>
            </div>
            <button wire:click="closeModal()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
             <div class="mb-4">
                <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Soal <span class="text-red-600">*</span></label>
                <select class="mt-1 form-control" wire:model='question_type_id'>
                    <option value="">Pilih tipe soal</option>
                    @foreach ($question_types as $question_type)
                        <option {{ $question_type?->id == $question_type_id ? 'selected' : '' }} value="{{ $question_type?->id }}">{{ $question_type?->name }}</option>
                    @endforeach
                </select>
                @error('question_type_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Modul Soal <span class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Nama Modul Soal" class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="duration" class="block text-sm font-medium text-gray-700">Durasi Pengerjaan <span class="text-red-600">*</span></label>
                    <div class="relative mt-1">
                        <input type="number" id="duration" wire:model.defer="duration" placeholder="Durasi Pengerjaan" class="mt-1 form-control" min="0">
                        <div class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm"> Menit</div>
                    </div>
                    @error('duration')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="random_question" class="block text-sm font-medium text-gray-700">Acak Soal <span class="text-red-600">*</span></label>
                    <div class="flex items-center mt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="random_question" class="sr-only peer">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    @error('random_question')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Modul Soal</label>
                <textarea id="description" wire:model.defer="description" placeholder="" class="mt-1 form-control"></textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit' class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
