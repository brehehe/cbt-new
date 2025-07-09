<div wire:ignore.self id="modalAction"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Tindakan</h2>
            </div>
            <button wire:click="closeModalAction"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow" style="max-height: 80vh">
            <!-- Button Produk Baru dan Lama -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPage'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                        wire:model.live='search'>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            @if (in_array($type, ['action']))
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 text-center font-medium">No</th>
                                    <th class="py-3 px-4 text-left font-medium">Nama Tindakan</th>
                                    <th class="py-3 px-4 text-left font-medium">Deskripsi</th>
                                    <th class="py-3 px-4 text-left font-medium">Harga</th>
                                    <th class="w-1 center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($actions as $index => $action)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $actions->firstItem() + $index }}</td>
                                        <td class="py-3 px-4">{{ $action->name }}</td>
                                        <td class="py-3 px-4">{{ $action->description ?? '-' }}</td>
                                        <td class="py-3 px-4">Rp
                                            {{ number_format($action->productPrice?->price ?? 0, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            <button class="text-blue-600 hover:text-blue-800 mx-1"
                                                wire:click="choiceAction('{{ $action->id }}')" title="Edit">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 px-4 text-center text-gray-500">Tidak ada data
                                            produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $actions->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $actions->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $actions->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $actions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div wire:ignore.self id="modalProduct"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Obat</h2>
            </div>
            <button wire:click="closeModalProduct"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow" style="max-height: 80vh">
            <!-- Button Produk Baru dan Lama -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPage'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                        wire:model.live='search'>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            @if (in_array($type, ['medicine']))
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="py-3 px-4 text-center font-medium">No</th>
                                    <th class="py-3 px-4 text-left font-medium">Nama Tindakan</th>
                                    <th class="py-3 px-4 text-left font-medium">Deskripsi</th>
                                    <th class="py-3 px-4 text-left font-medium">Harga</th>
                                    <th style="width: 10%" class="py-3 px-4 text-center font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($medicines as $index => $medicine)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $medicines->firstItem() + $index }}</td>
                                        <td class="py-3 px-4">{{ $medicine->name }}</td>
                                        <td class="py-3 px-4">{{ $medicine->description ?? '-' }}</td>
                                        <td class="py-3 px-4">Rp
                                            {{ number_format($medicine->productPrice?->price ?? 0, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            <button class="text-blue-600 hover:text-blue-800 mx-1"
                                                wire:click="choiceProduct('{{ $medicine->id }}')" title="Edit">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-3 px-4 text-center text-gray-500">Tidak ada data
                                            produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $medicines->firstItem() }}</span> sampai
                                <span class="font-medium">{{ $medicines->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $medicines->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $medicines->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal ICD</h2>
            </div>
            <button wire:click="closeModal"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow" style="max-height: 80vh">
            <!-- Button Produk Baru dan Lama -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPage'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                        wire:model.live='search'>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            @if (in_array($type, ['icd10', 'icd9']))
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-1 center">No</th>
                                    <th>Code</th>
                                    <th>Display</th>
                                    <th class="w-1 center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($icds as $index => $icd)
                                    <tr>
                                        <td class="center">{{ $icds->firstItem() + $index }}</td>
                                        <td>{{ $icd->code }}</td>
                                        <td>{{ $icd->display }}</td>
                                        <td class="center">
                                            <div class="flex items-center">
                                                <button
                                                    class="btn btn-icon text-blue-500 hover:text-blue-600 transition-colors edit-btn"
                                                    wire:click="choiceICD('{{ $icd->id }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="no-data">
                                            Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $icds->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $icds->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $icds->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $icds->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($type == 'supporting_icd10')
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-1 center">No</th>
                                    <th>Code</th>
                                    <th>Display</th>
                                    <th class="w-1 center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($icds as $index => $icd)
                                    <tr>
                                        <td class="center">{{ $icds->firstItem() + $index }}</td>
                                        <td>{{ $icd->code }}</td>
                                        <td>{{ $icd->display }}</td>
                                        <td class="center">
                                            <div class="flex items-center">
                                                <button
                                                    class="btn btn-icon text-blue-500 hover:text-blue-600 transition-colors edit-btn"
                                                    wire:click="choiceSupportingICD('{{ $icd->id }}')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="no-data">
                                            Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $icds->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $icds->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $icds->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $icds->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div wire:ignore.self id="modalProofOfAction"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-3xl shadow-rounded-3xl max-w-rounded-3xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Form Bukti Tindakan</h2>
            </div>
            <button wire:click="closeModalProofOfAction"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 70vh; overflow-y: auto;">
            <!-- Description Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Deskripsi Tindakan <span
                        class="text-red-600">*</span></label>
                <textarea wire:model.lazy='description' rows="3" placeholder="Masukkan deskripsi tindakan yang akan dilakukan"
                    class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sebelum Tindakan</label>

                    <!-- Type Selection for Before Photo -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Tipe Foto Sebelum</label>
                        <div class="mt-1 flex gap-4">
                            <button
                                class="{{ $type_before_photo == 'upload' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-2"
                                wire:click="$set('type_before_photo', 'upload')">
                                <i class="fas fa-upload"></i>
                                <span>Upload</span>
                            </button>
                            <button
                                class="{{ $type_before_photo == 'kamera' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-2"
                                wire:click="$set('type_before_photo', 'kamera')"
                                onclick="initializeCameraSelect('before')">
                                <i class="fas fa-camera"></i>
                                <span>Kamera</span>
                            </button>
                        </div>
                    </div>

                    @if ($type_before_photo == 'upload')
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Upload Foto Sebelum</label>
                            <input type="file" wire:model='before_photo' accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    @elseif($type_before_photo == 'kamera')
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Ambil Foto dengan Kamera</label>

                            <!-- Camera Selection -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">Pilih Kamera</label>
                                <div class="flex gap-2 items-center">
                                    <select id="camera-before-select" onchange="onCameraChange('before')"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Memuat kamera...</option>
                                    </select>
                                    <button type="button" onclick="detectCameras('before')"
                                        class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm whitespace-nowrap">
                                        <i class="fas fa-refresh"></i> Refresh
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Kamera akan otomatis aktif saat dipilih dari
                                    dropdown</p>
                            </div>

                            <div id="camera-before-container"
                                class="mt-1 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                                <div id="camera-before-preview" style="display: none;">
                                    <video id="camera-before-video" width="320" height="240" autoplay
                                        class="mx-auto rounded-lg"></video>
                                    <canvas id="camera-before-canvas" width="320" height="240"
                                        style="display: none;"></canvas>
                                    <div class="mt-2 flex gap-2 justify-center flex-wrap">
                                        <button type="button"
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                                            onclick="capturePhoto('before')">
                                            <i class="fas fa-camera"></i> Ambil Foto
                                        </button>
                                        <button type="button"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                                            onclick="stopCamera('before')">
                                            <i class="fas fa-times"></i> Tutup Kamera
                                        </button>
                                    </div>
                                </div>
                                <div id="camera-before-button">
                                    <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Pilih kamera dari dropdown untuk memulai</p>
                                </div>
                                <div id="camera-before-result" style="display: none;">
                                    <img id="camera-before-image" class="w-32 h-32 object-cover rounded-lg mx-auto">
                                    <div class="mt-2 flex gap-2 justify-center">
                                        <button type="button"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                            onclick="restartCamera('before')">
                                            <i class="fas fa-redo"></i> Ambil Ulang
                                        </button>
                                        <button type="button"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                                            onclick="deletePhoto('before')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($before_photo)
                        <div class="mt-2 flex items-start gap-3">
                            <img src="{{ $before_photo->temporaryUrl() }}" class="object-cover rounded-lg"
                                style="width: 300px; height: 300px;">
                            <button type="button" wire:click="$set('before_photo', null)"
                                class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    @endif

                    @error('before_photo')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Sesudah Tindakan</label>

                    <!-- Type Selection for After Photo -->
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Tipe Foto Sesudah</label>
                        <div class="mt-1 flex gap-4">
                            <button
                                class="{{ $type_after_photo == 'upload' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-2"
                                wire:click="$set('type_after_photo', 'upload')">
                                <i class="fas fa-upload"></i>
                                <span>Upload</span>
                            </button>
                            <button
                                class="{{ $type_after_photo == 'kamera' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-2"
                                wire:click="$set('type_after_photo', 'kamera')"
                                onclick="initializeCameraSelect('after')">
                                <i class="fas fa-camera"></i>
                                <span>Kamera</span>
                            </button>
                        </div>
                    </div>

                    @if ($type_after_photo == 'upload')
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Upload Foto Sesudah</label>
                            <input type="file" wire:model='after_photo' accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    @elseif($type_after_photo == 'kamera')
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700">Ambil Foto dengan Kamera</label>

                            <!-- Camera Selection -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700">Pilih Kamera</label>
                                <div class="flex gap-2 items-center">
                                    <select id="camera-after-select" onchange="onCameraChange('after')"
                                        class="mt-1 block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Memuat kamera...</option>
                                    </select>
                                    <button type="button" onclick="detectCameras('after')"
                                        class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm whitespace-nowrap">
                                        <i class="fas fa-refresh"></i> Refresh
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Kamera akan otomatis aktif saat dipilih dari
                                    dropdown</p>
                            </div>

                            <div id="camera-after-container"
                                class="mt-1 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center">
                                <div id="camera-after-preview" style="display: none;">
                                    <video id="camera-after-video" width="320" height="240" autoplay
                                        class="mx-auto rounded-lg"></video>
                                    <canvas id="camera-after-canvas" width="320" height="240"
                                        style="display: none;"></canvas>
                                    <div class="mt-2 flex gap-2 justify-center flex-wrap">
                                        <button type="button"
                                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
                                            onclick="capturePhoto('after')">
                                            <i class="fas fa-camera"></i> Ambil Foto
                                        </button>
                                        <button type="button"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                                            onclick="stopCamera('after')">
                                            <i class="fas fa-times"></i> Tutup Kamera
                                        </button>
                                    </div>
                                </div>
                                <div id="camera-after-button">
                                    <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm text-gray-500">Pilih kamera dari dropdown untuk memulai</p>
                                </div>
                                <div id="camera-after-result" style="display: none;">
                                    <img id="camera-after-image" class="w-32 h-32 object-cover rounded-lg mx-auto">
                                    <div class="mt-2 flex gap-2 justify-center">
                                        <button type="button"
                                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                                            onclick="restartCamera('after')">
                                            <i class="fas fa-redo"></i> Ambil Ulang
                                        </button>
                                        <button type="button"
                                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                                            onclick="deletePhoto('after')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    @if ($after_photo)
                        <div class="mt-2 flex items-start gap-3">
                            <img src="{{ $after_photo->temporaryUrl() }}" class="object-cover rounded-lg"
                                style="width: 300px; height: 300px;">
                            <button type="button" wire:click="$set('after_photo', null)"
                                class="px-3 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    @endif

                    @error('after_photo')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
            <button wire:click="closeModalProofOfAction"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button wire:click="saveAction"
                class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class="fas fa-save"></i>
                <span>Simpan</span>
            </button>
        </div>
    </div>
</div>
{{-- Modal How To Use --}}
<div wire:ignore.self id="modalHowToUse"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Aturan Pakai</h2>
            </div>
            <button wire:click="closeModalHowToUse()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name_how_to_use" class="block text-sm font-medium text-gray-700">Nama Aturan Pakai <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name_how_to_use" wire:model.defer="name_how_to_use"
                    placeholder="Masukkan nama Aturan Pakai" class="mt-1 form-control">
                @error('name_how_to_use')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description_how_to_use" class="block text-sm font-medium text-gray-700">Deskripsi <span
                        class="text-red-600">*</span></label>
                <textarea id="description_how_to_use" wire:model.defer="description_how_to_use" placeholder="Masukkan deskripsi poli"
                    class="mt-1 form-control"></textarea>
                @error('description_how_to_use')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="time_how_to_use" class="block text-sm font-medium text-gray-700">Aturan Pakai (Berapa x /
                    Hari)
                    <span class="text-red-600">*</span></label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" wire:model.defer="time_how_to_use" class="form-control rounded-r-none"
                        placeholder="0" />
                    <span class="inline-flex items-center border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        /Hari
                    </span>
                    <input type="text" wire:model.defer="day_how_to_use" class="form-control rounded-l-none"
                        placeholder="0" />
                </div>
                @error('time_how_to_use')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('day_how_to_use')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalHowToUse()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='saveHowToUse'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
