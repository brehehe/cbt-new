@section('title', 'Detail Analisis Butir Soal')
<div>
@include('livewire.admin.report.shared.overview-styles')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div><h1 class="text-2xl font-bold">Analisis Butir Soal</h1><p>{{ $timetable->name }} · {{ count($itemAnalysisData) }} soal</p></div>
    <div><a href="{{ route('admin.report.item-analysis') }}" class="btn btn-secondary">Kembali</a>
    <button wire:click="exportPdf" wire:loading.attr="disabled" class="btn btn-primary">Unduh PDF Overview</button></div>
</div>
<div wire:loading wire:target="exportPdf">Menyiapkan PDF…</div>
@forelse($itemAnalysisData as $analysis)
@include('livewire.admin.report.shared.overview-card', ['number' => $loop->iteration])
@empty
<p>Belum ada data soal.</p>
@endforelse
</div>
