<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[{{ \App\Models\Company\Company::first()?->color_primary ?? '#f58634' }}]">Backup Database</h1>
                <p class="text-gray-600">Kelola backup database dan storage aplikasi Anda.</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="space-y-6 mb-2">
            @if(auth()->user()->username === 'procbt')
            {{-- Backup Options Section --}}
            <div class="p-6 bg-white shadow rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center gap-4 mb-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Backup System</h2>
                        <p class="text-sm text-gray-600">Backup database, storage, atau keduanya untuk keamanan data</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <!-- Backup Database -->
                    <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            <h3 class="font-semibold text-blue-800">Database</h3>
                        </div>
                        <p class="text-xs text-blue-700 mb-3">Backup semua data database</p>
                        <button wire:click="backupDatabase" wire:loading.attr="disabled"
                            class="w-full btn btn-primary btn-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span wire:loading.remove wire:target="backupDatabase">Backup DB</span>
                            <span wire:loading wire:target="backupDatabase">Proses...</span>
                        </button>
                    </div>

                    <!-- Backup Storage -->
                    <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <h3 class="font-semibold text-green-800">Storage</h3>
                        </div>
                        <p class="text-xs text-green-700 mb-3">Backup semua file storage</p>
                        <button wire:click="backupStorage" wire:loading.attr="disabled"
                            class="w-full btn btn-success btn-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span wire:loading.remove wire:target="backupStorage">Backup Storage</span>
                            <span wire:loading wire:target="backupStorage">Proses...</span>
                        </button>
                    </div>

                    <!-- Backup Full -->
                    <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200">
                        <div class="flex items-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            <h3 class="font-semibold text-purple-800">Full Backup</h3>
                        </div>
                        <p class="text-xs text-purple-700 mb-3">Backup database + storage</p>
                        <button wire:click="backupFull" wire:loading.attr="disabled"
                            class="w-full btn btn-warning btn-sm flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            <span wire:loading.remove wire:target="backupFull">Backup Lengkap</span>
                            <span wire:loading wire:target="backupFull">Proses...</span>
                        </button>
                    </div>
                </div>
            </div>
            @else
            {{-- Access Denied Message --}}
            <div class="p-6 bg-white shadow rounded-lg border-l-4 border-red-500">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-red-800">Akses Dibatasi</h2>
                        <p class="text-sm text-red-600">Hanya user dengan username "procbt" yang dapat mengakses fitur backup.</p>
                        <p class="text-xs text-red-500 mt-1">User Anda: <strong>{{ auth()->user()->username }}</strong></p>
                    </div>
                </div>
            </div>
            @endif

            {{-- List Backup Files / History --}}
            <div class="p-6 bg-white shadow rounded-lg border-l-4 border-green-500">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-800">History Backup</h2>
                        <p class="text-sm text-gray-600">Daftar file backup yang tersimpan - dapat didownload kapan saja</p>
                    </div>
                    @if(auth()->user()->username === 'procbt')
                            <div class="flex gap-2">
                                <button wire:click="$refresh" class="btn btn-sm btn-secondary flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Refresh
                                </button>
                                
                                @if(count($this->getBackupFiles()) > 0)
                                    <button wire:click="deleteAllBackups"
                                        wire:confirm="PERINGATAN: Apakah Anda yakin ingin menghapus SEMUA file backup? Tindakan ini tidak dapat dibatalkan!"
                                        class="btn btn-sm bg-red-600 hover:bg-red-700 text-white flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus Semua
                                    </button>
                                @endif
                            </div>
                    @endif
                </div>

                @php
                    $backupFiles = $this->getBackupFiles();
                @endphp

                @if(count($backupFiles) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama File
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ukuran
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($backupFiles as $file)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="truncate max-w-xs" title="{{ $file['name'] }}">{{ $file['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="font-mono">{{ $file['size'] }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $file['date'] }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.backup.download', ['path' => base64_encode($file['path'])]) }}"
                                                class="text-blue-600 hover:text-blue-900 inline-flex items-center mr-3 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                            @if(auth()->user()->username === 'procbt')
                                            <button wire:click="deleteBackup('{{ $file['path'] }}')"
                                                wire:confirm="Apakah Anda yakin ingin menghapus file backup ini?"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-gray-500 text-lg font-semibold mb-2">Belum Ada History Backup</p>
                        <p class="text-gray-400 text-sm mb-4">File backup yang telah dibuat akan muncul di sini</p>
                        @if(auth()->user()->username === 'procbt')
                        <p class="text-gray-400 text-xs">
                            💡 Klik salah satu tombol backup di atas untuk membuat backup pertama<br>
                            <span class="text-blue-600">Database</span> • <span class="text-green-600">Storage</span> • <span class="text-purple-600">Full Backup</span>
                        </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
