<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 text-xs font-bold">
                <i class="fas fa-filter"></i>
            </span>
            <h3 class="text-sm font-bold text-gray-800">Filter Soal</h3>
            @if ($this->hasActiveQuestionFilters())
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                    Aktif
                </span>
            @endif
        </div>
        @if ($this->hasActiveQuestionFilters())
            <button type="button" 
                    wire:click="resetQuestionFilters" 
                    class="text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1.5 transition-colors cursor-pointer">
                <i class="fas fa-rotate-left"></i>
                Reset Filter
            </button>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <!-- Topik Ujian -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Topik Ujian</label>
            <select wire:model.live="filterTopicId" 
                    class="block w-full pl-3 pr-8 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="">Semua Topik</option>
                @foreach ($this->topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kategori Materi -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Materi</label>
            <select wire:model.live="filterMaterialCategoryId" 
                    class="block w-full pl-3 pr-8 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="">Semua Kategori Materi</option>
                @foreach ($this->materialCategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Materi -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Materi</label>
            <select wire:model.live="filterMaterialId" 
                    class="block w-full pl-3 pr-8 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="">Semua Materi</option>
                @foreach ($this->materials as $mat)
                    <option value="{{ $mat->id }}">{{ $mat->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipe Ujian -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Ujian</label>
            <select wire:model.live="filterQuestionTypeId" 
                    class="block w-full pl-3 pr-8 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="">Semua Tipe Ujian</option>
                @foreach ($this->questionTypes as $qt)
                    <option value="{{ $qt->id }}">{{ $qt->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kategori Soal -->
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori Soal</label>
            <select wire:model.live="filterCategoryQuestionId" 
                    class="block w-full pl-3 pr-8 py-2 text-xs border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <option value="">Semua Kategori Soal</option>
                @foreach ($this->categoryQuestions as $cq)
                    <option value="{{ $cq->id }}">{{ $cq->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
