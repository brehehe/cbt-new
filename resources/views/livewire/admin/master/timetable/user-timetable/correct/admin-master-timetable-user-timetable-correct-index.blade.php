<div>
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Koreksi Detail: {{ $user_timetable->user->name }}
                </h1>
                <p class="text-gray-600 text-sm mt-1">Review dan berikan penilaian untuk jawaban essay.</p>
            </div>
            <a href="{{ route('admin.master.timetable.correct', $user_timetable->timetable_id) }}"
                class="btn btn-outline flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            @forelse ($essayQuestions as $index => $q)
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                    <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                        <span class="font-bold text-gray-700">Soal #{{ $index + 1 }}</span>
                        <div class="flex items-center gap-2">
                            @if($q->status === 'correct')
                                <span class="badge badge-success px-3 py-1 text-xs">BENAR</span>
                            @elseif($q->status === 'wrong')
                                <span class="badge badge-error px-3 py-1 text-xs">SALAH</span>
                            @else
                                <span class="badge badge-warning px-3 py-1 text-xs">PENDING</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div
                            class="prose prose-sm max-w-none text-gray-800 font-medium bg-blue-50/30 p-4 rounded-lg border border-blue-50">
                            {!! $q->timetableQuestion->question !!}
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jawaban
                                Peserta:</label>
                            <div
                                class="p-5 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 italic leading-relaxed whitespace-pre-wrap">
                                {{ $q->essay_answer ?: '(Peserta tidak mengisi jawaban)' }}
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-50">
                            <button wire:click="setWrong('{{ $q->id }}')"
                                class="btn {{ $q->status === 'wrong' ? 'bg-red-600 text-white' : 'border-[color:var(--primary)] text-black' }} btn-sm px-6 shadow-sm">
                                <i class="fa-solid fa-xmark mr-2"></i> Salah
                            </button>
                            <button wire:click="setCorrect('{{ $q->id }}')"
                                class="btn {{ $q->status === 'correct' ? 'bg-green-600 text-white' : 'border-[color:var(--primary)] text-black' }} btn-sm px-6 shadow-sm">
                                <i class="fa-solid fa-check mr-2"></i> Benar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border-2 border-dashed border-gray-200">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fa-solid fa-file-circle-xmark text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium">Tidak ada soal essay untuk peserta ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6 border-b pb-4">Ringkasan Nilai</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm text-gray-600">Nilai Akhir</span>
                        <span class="text-2xl font-black text-primary">{{ $user_timetable->mark }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4">
                        <div class="p-3 bg-orange-50 rounded-lg border border-orange-100 text-center">
                            <span class="block text-[10px] text-orange-500 font-bold uppercase mb-1">Pending</span>
                            <span class="text-lg font-bold text-orange-700">
                                {{ $essayQuestions->where('status', 'check')->count() }}
                            </span>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100 text-center">
                            <span class="block text-[10px] text-blue-500 font-bold uppercase mb-1">Total Essay</span>
                            <span class="text-lg font-bold text-blue-700">
                                {{ $essayQuestions->count() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                            Nilai di atas dihitung secara otomatis berdasarkan jumlah jawaban benar dibandingkan dengan
                            total soal dalam modul ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>