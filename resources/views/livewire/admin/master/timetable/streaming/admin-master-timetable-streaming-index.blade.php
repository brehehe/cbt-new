<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 line-clamp-1">
                    Monitor Streaming: {{ $timetable->module->name ?? 'Ujian' }}
                </h1>
                <p class="text-sm text-gray-500">
                    Sesi: {{ $timetable->name }} | {{ Carbon\Carbon::parse($timetable->start_date)->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div id="connection-status-dot" class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-sm font-medium text-gray-600">LiveKit Cloud Activated</span>
            </div>
        </div>
    </div>

    <div id="admin-monitor-app" data-timetable-id="{{ $timetableId }}">
        <div class="flex items-center justify-center min-h-[400px]">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-gray-600 font-medium tracking-tight">Memuat Dashboard Monitoring...</span>
        </div>
    </div>

    @push('scripts')
    @vite(['resources/js/exam-react.jsx'])
    @endpush
</div>
