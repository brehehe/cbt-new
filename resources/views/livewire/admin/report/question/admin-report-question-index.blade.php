@section('title', 'Analisis Soal')
<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Data Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            {{-- <div>
                <button wire:click="openModal()" class="{{in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'btn btn-primary' : 'btn btn-warning'}}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal
                </button>
            </div> --}}
        </div>
    </div>
    <!-- Table Controls -->
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
            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                @php
                    $count_timetable = empty($timetables) ? 1 : count($timetables);
                @endphp
                <thead>
                    <tr>
                        <th rowspan="3" class="w-1 center">No</th>
                        <th rowspan="3">Soal</th>
                        <th colspan="{{ $count_timetable }}" class="center">Daftar Jadwal</th>
                        <th rowspan="3">Total</th>
                    </tr>
                    <tr>
                        @foreach ($timetables as $key => $timetable)
                            <th class="center">{{ $timetable?->name }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($timetables as $key => $timetable)
                            <th class="center">{{ $timetable?->userTimetables()->count() }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questions as $index => $result)
                        <tr>
                            <td class="center ">{{ $questions->firstItem() + $index }}</td>
                            <td>{{ $result?->question ?? '-' }}</td>
                            @if (empty($timetables))
                                <td class="center">-</td>
                            @else
                                @foreach ($timetables as $key => $timetable)
                                    <td class="center">{{ $this->getQuestionCorrect($result, $timetable) }}</td>
                                @endforeach
                            @endif
                            <td>{{ $this->getQuestionCorrect($result) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $questions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $questions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $questions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $questions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
