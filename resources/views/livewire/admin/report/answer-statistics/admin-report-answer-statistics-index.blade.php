<div>
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#000' }}]">Laporan Statistik Jawaban</h1>
        <p class="text-gray-600">Analisis sebaran jawaban peserta per soal.</p>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label for="timetable_id" class="block text-sm font-medium text-gray-700">Pilih Jadwal Ujian</label>
                <div wire:key="select-timetable-{{ rand() }}">
                    <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                            wire:model.live="timetable_id"
                            id="timetable_id">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($timetables as $t)
                            <option value="{{ $t->id }}">
                                {{ $t->name }} ({{ $t->module?->name }}) - {{ \Carbon\Carbon::parse($t->start_time)->format('d M Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if($timetable_id && !empty($answerStats))
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

    @if($timetable_id)
        @if(empty($answerStats))
            <div class="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                <p class="text-gray-500">Tidak ada data jawaban ditemukan untuk jadwal ini. Pastikan ujian sudah berstatus 'Done'.</p>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden rounded-lg border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Per Soal</h3>
                    <span class="text-sm text-gray-500">Total Soal: {{ count($answerStats) }}</span>
                </div>
                <ul class="divide-y divide-gray-200">
                    @foreach($answerStats as $index => $stat)
                        <li class="p-4 hover:bg-gray-50">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="flex-shrink-0 w-12 text-center pt-1">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-100 text-blue-800 font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-grow">
                                    <div class="text-sm font-medium text-gray-900 mb-2">
                                        {!! \Str::limit($stat['question_text'], 200) !!}
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Statistik</h4>
                                            <div class="flex space-x-4 text-sm">
                                                <div class="flex flex-col">
                                                    <span class="text-gray-500 text-xs">Dijawab</span>
                                                    <span class="font-bold text-gray-900">{{ $stat['total_answered'] }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-gray-500 text-xs text-green-600">Benar</span>
                                                    <span class="font-bold text-green-700">{{ $stat['total_correct'] }}</span>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="text-gray-500 text-xs text-red-600">Salah</span>
                                                    <span class="font-bold text-red-700">{{ $stat['total_wrong'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sebaran Jawaban</h4>
                                            <div class="space-y-1">
                                                @foreach($stat['distribution'] as $optIndex => $opt)
                                                    <div class="flex items-center text-xs mb-1">
                                                        <span class="w-6 font-medium {{ $opt['is_correct'] ? 'text-green-600' : 'text-gray-500' }}">
                                                            {{ chr(65 + $optIndex) }}
                                                            @if($opt['is_correct']) <i class="fas fa-check ml-1"></i> @endif
                                                        </span>
                                                        <div class="flex-grow mx-2">
                                                            <div class="mb-1 text-gray-700">
                                                                {!! $opt['option_text'] !!}
                                                            </div>
                                                            <div class="flex items-center">
                                                                <div class="flex-grow h-2 bg-gray-100 rounded-full overflow-hidden">
                                                                    <div class="h-full {{ $opt['is_correct'] ? 'bg-green-500' : 'bg-blue-400' }}" style="width: {{ $opt['percentage'] }}%"></div>
                                                                </div>
                                                                <span class="ml-2 w-12 text-right text-gray-600">
                                                                    {{ $opt['count'] }}
                                                                </span>
                                                                <span class="ml-1 w-12 text-right text-gray-500 text-[10px]">
                                                                    ({{ $opt['percentage'] }}%)
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @else
        <div class="text-center py-20 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <i class="fas fa-arrow-up text-4xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Silakan pilih Jadwal Ujian terlebih dahulu untuk melihat statistik.</p>
        </div>
    @endif
</div>
