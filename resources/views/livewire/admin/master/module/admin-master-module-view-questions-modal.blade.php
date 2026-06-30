<div wire:ignore.self id="modal-view-questions"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-[60] transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[80vh] mx-auto flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in">

        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">
                    Data Soal Modul: <span class="text-blue-600">{{ $view_module?->name }}</span>
                </h2>
            </div>
            <button wire:click="closeViewQuestionsModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto px-6 py-4 text-gray-600">
            @if ($viewModuleId && $view_module)
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-1 center">No</th>
                                <th>Prodi</th>
                                <th>Tipe Ujian</th>
                                <th>Pertanyaan</th>
                                <th>Deskripsi</th>
                                <th class="w-1 center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($view_module_questions as $index => $result)
                                <tr wire:key="view-modal-mq-{{ $result->id }}">
                                    <td class="center">{{ $view_module_questions->firstItem() + $index }}</td>
                                    <td>{{ $result?->question?->study?->name }}</td>
                                    <td>{{ $result?->question?->questionType?->name }}</td>
                                    <td><div class="rich-content">{!! $result?->question?->question !!}</div></td>
                                    <td><div class="rich-content">{!! $result?->question?->description !!}</div></td>
                                    <td class="center">
                                        <a class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors"
                                            target="_blank"
                                            href="{{ route('admin.master.question.update', $result->question_id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="no-data text-center">Tidak ada data soal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($view_module_questions->hasPages())
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200 mt-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $view_module_questions->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $view_module_questions->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $view_module_questions->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {{ $view_module_questions->links('vendor.livewire.custom') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t flex-shrink-0">
            <button wire:click="closeViewQuestionsModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>
