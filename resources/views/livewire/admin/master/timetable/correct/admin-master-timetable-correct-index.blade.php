<div>
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Koreksi Jawaban Essay
                </h1>
                <p class="text-gray-600 text-sm mt-1">Pilih peserta untuk mulai mengoreksi jawaban essay secara manual.</p>
            </div>
            <a href="{{ route('admin.master.timetable') }}" class="btn btn-outline flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-center gap-6">
            <div class="flex-1 min-w-[200px]">
                <h2 class="text-lg font-bold text-gray-800">{{ $timetable->name }}</h2>
                <p class="text-sm text-gray-500 italic">{{ $timetable->module->name ?? '-' }}</p>
            </div>
            <div class="flex gap-4">
                <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100">
                    <span class="block text-[10px] text-blue-500 font-bold uppercase tracking-wider">Total Peserta</span>
                    <span class="text-xl font-black text-blue-700">{{ $userTimetables->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari Peserta..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Saat Ini</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Essay</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($userTimetables as $index => $ut)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $userTimetables->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $ut->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">{{ $ut->user->nim ?? $ut->user->username ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-lg font-black {{ $ut->mark >= 70 ? 'text-green-600' : 'text-orange-600' }}">
                                    {{ $ut->mark ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-medium text-gray-600">Terjawab:</span>
                                        <span class="badge badge-neutral">{{ $ut->total_essay }}</span>
                                    </div>
                                    @if($ut->pending_essay > 0)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 animate-pulse">
                                            {{ $ut->pending_essay }} PENDING
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">
                                            SELESAI
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('admin.master.timetable.user-timetable.correct', $ut->id) }}" 
                                   class="btn {{ $ut->pending_essay > 0 ? 'btn-primary' : 'btn-outline' }} btn-sm w-full">
                                    <i class="fa-solid fa-pen-nib mr-1"></i>
                                    {{ $ut->pending_essay > 0 ? 'Koreksi' : 'Review' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                Tidak ada data peserta yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
        {{ $userTimetables->links() }}
    </div>
</div>
