<div>
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-primary">Laporan Hasil Ujian Siswa</h1>
        <p class="text-gray-600">Lihat semua hasil ujian untuk siswa tertentu.</p>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                <div wire:key="select-user-{{ rand() }}">
                    <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            wire:model.live="user_id"
                            id="user_id">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->nim ?? $user->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if($user_id)
                <div class="text-right">
                    <button wire:click="exportPdf" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                    <div wire:loading wire:target="exportPdf" class="text-gray-500 text-sm ml-2">
                        <i class="fas fa-spinner fa-spin"></i> Generating...
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($user_id)
        <div class="bg-white shadow overflow-hidden rounded-lg border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div class="flex items-center">
                     <div class="h-10 w-10 flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="{{ $selectedUser->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($selectedUser->name).'&color=7F9CF5&background=EBF4FF' }}" alt="">
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $selectedUser->name }}</div>
                        <div class="text-sm text-gray-500">{{ $selectedUser->nim ?? $selectedUser->username }}</div>
                    </div>
                </div>
                <div>
                     <input wire:model.live.debounce.300ms="search" type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Cari ujian...">
                </div>
            </div>

            @if($examResults->isEmpty())
                 <div class="text-center py-10">
                    <p class="text-gray-500">Tidak ada data hasil ujian ditemukan untuk siswa ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Ujian / Modul
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu Pelaksanaan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nilai
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($examResults as $index => $result)
                                @php
                                    $grade = $this->getGradeDetail($result->mark);
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $examResults->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $result->timetable->name ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $result->timetable->module->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ \Carbon\Carbon::parse($result->created_at)->format('d M Y') }}</div>
                                        <div class="text-xs">{{ \Carbon\Carbon::parse($result->created_at)->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $result->mark ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                         @if($grade)
                                            <span class="font-bold" style="color: {{ $grade->color ?? '#000' }}">{{ $grade->grade_letter }}</span>
                                            <span class="text-xs">({{ $grade->description }})</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $examResults->firstItem() }}</span> sampai <span
                                class="font-medium">{{ $examResults->lastItem() }}</span> dari <span
                                class="font-medium">{{ $examResults->total() }}</span> hasil
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                {{ $examResults->links('vendor.livewire.custom') }}
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <i class="fas fa-user-graduate text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Silakan pilih Mahasiswa terlebih dahulu untuk melihat hasil ujian.</p>
        </div>
    @endif
</div>