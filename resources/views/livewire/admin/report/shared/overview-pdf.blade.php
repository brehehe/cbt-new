<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>{{ $title }}</title>
@include('livewire.admin.report.shared.overview-styles')
<style>@page { margin:24px; } body {font-family:DejaVu Sans,sans-serif;font-size:11px;} .analysis-card {border:0;padding:0;font-size:11px;page-break-after:always;} .analysis-card:last-child {page-break-after:auto;} tr {page-break-inside:avoid;} h1{font-size:16px;} </style>
</head><body><h1>{{ $title }}</h1>
@foreach($analyses as $analysis)
@include('livewire.admin.report.shared.overview-card', ['number' => $loop->iteration])
@endforeach
</body></html>
