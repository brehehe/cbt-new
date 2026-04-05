<div wire:ignore.self id="modal-module-question"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-screen-2xl h-[90vh] mx-auto flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in">

        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Data Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <!-- Body -->
        @if ($openQuestion)
            <div class="flex-1 overflow-y-auto px-6 py-4 text-gray-600" style="max-height: 80vh;">
                <div class="space-y-6 mb-4">
                    <!-- SECTION 1: Informasi Umum Produk -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prodi</label>
                            <select wire:model.live="filterStudyId" class="mt-1 form-control">
                                <option value="">Semua Prodi</option>
                                @foreach ($get_studys as $key_get_study => $get_study)
                                    <option value="{{ $key_get_study }}">{{ $get_study }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Topik</label>
                            <select wire:model.live="filterTopicId" class="mt-1 form-control">
                                <option value="">Semua Topik</option>
                                @foreach ($topics as $key_topic => $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cari</label>
                            <input type="text" class="mt-1 form-control" placeholder="Cari Sesuatu..."
                                wire:model.live='search'>
                        </div>
                    </div>
                </div>
                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prodi</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pertanyaan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($questions->groupBy('topic.name') as $topicName => $topicQuestions)
                                    <tr class="bg-gray-50">
                                        <td colspan="3"
                                            class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            <div class="flex items-center justify-between">
                                                <span>Topik: {{ $topicName ?? 'Tanpa Topik' }}</span>
                                                <span class="text-[11px] text-gray-500">{{ $topicQuestions->count() }}
                                                    soal</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($topicQuestions as $index => $result)
                                        <tr class="hover:bg-gray-50 cursor-pointer {{ $selected_all[$result->id] ?? false ? 'bg-yellow-100' : '' }}"
                                            wire:click="choiceQuestion('{{ $result->id }}')">
                                            <td class="px-4 py-3 text-sm text-gray-900">
                                                {{ $questions->firstItem() + $loop->parent->index + $index }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $result?->study?->name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900">{{ $result?->question }}</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada
                                            data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        {{ $questions->links('vendor.livewire.custom') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="flex justify-between items-center gap-4 px-6 py-4 border-t">
            <!-- Info total soal terpilih (kiri) -->
            <span class="text-sm font-medium text-gray-700">
                Total soal terpilih:
                <span class="text-blue-600 font-semibold">
                    {{ count($selected_all) }}
                </span>
            </span>

            <!-- Tombol aksi (kanan) -->
            <div class="flex gap-2">
                <button wire:click="closeModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                    Batal
                </button>
                <button wire:click='submitModuleQuestion()'
                    class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>