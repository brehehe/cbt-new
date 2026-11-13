<!DOCTYPE html>
<html lang="id" class="light">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Berita Acara - {{ $timetable->name }}</title>
        @vite(['resources/css/app.css'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 15mm 20mm;
                }

                body {
                    background: #fff !important;
                    color: #000 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                .no-print {
                    display: none !important;
                }

                .print-container {
                    border: none !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                    max-width: 100% !important;
                }
            }

            @media screen {
                body {
                    background-color: #f3f4f6;
                }
            }
        </style>
    </head>

    <body class="bg-gray-100 text-gray-900 font-sans text-[13px] antialiased">

        <!-- Floating Action Bar (Hidden on Print) -->
        <div class="no-print sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 px-6 py-3 shadow-sm mb-4">
            <div class="max-w-[210mm] mx-auto flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <button onclick="window.history.back()" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold shadow-sm transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali
                    </button>
                    <div class="text-xs text-gray-600 font-medium hidden sm:block">
                        <span class="text-gray-900 font-bold">{{ $timetable->name }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.print()" 
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md hover:shadow transition cursor-pointer">
                        <i class="fa-solid fa-print"></i>
                        Cetak / Print Berita Acara
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-[210mm] mx-auto bg-white border border-gray-300 p-8 text-[13px] leading-relaxed shadow-sm print-container">
            <!-- Header -->
            <div class="flex items-center mb-6 border-b-2 border-gray-900 pb-4">
                <!-- Logo -->
                <div class="w-20 h-20 mr-4 flex items-center justify-center">
                    @if($company?->logo_potrait && file_exists(public_path('storage/' . $company->logo_potrait)))
                        <img src="{{ asset('storage/' . $company->logo_potrait) }}" alt="Logo" class="max-w-20 max-h-20 object-contain">
                    @elseif($company?->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="max-w-20 max-h-20 object-contain">
                    @else
                        <div class="w-16 h-16 rounded bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-xs">LOGO</div>
                    @endif
                </div>

                <!-- Teks -->
                <div class="flex-1 text-center">
                    <h1 class="font-bold text-lg uppercase tracking-wider text-gray-900">BERITA ACARA PELAKSANAAN</h1>
                    <p class="font-semibold uppercase text-gray-800 mt-1">
                        {{ $timetable->name }}<br>
                        TAHUN {{ $tahun ?? now()->format('Y') }}
                    </p>
                    <p class="text-[11px] text-gray-600 mt-0.5">{{ $company?->name ?? 'INSTITUSI' }}</p>
                </div>
            </div>

            @php
                use Carbon\Carbon;

                $tanggal = Carbon::parse($timetable->start_time);
                $hari = $tanggal->translatedFormat('l');
                $tgl = $tanggal->translatedFormat('d');
                $bulan = $tanggal->translatedFormat('F');
                $tahun = $tanggal->translatedFormat('Y');

                $pukulMulai = Carbon::parse($timetable->start_time)->format('H:i');
                $pukulSelesai = Carbon::parse($timetable->end_time)->format('H:i');

                $lokasi = $exam_room->name ?? 'Lokasi Tidak Diketahui';
                $namaKampus = $company->name ?? 'INSTITUSI';
            @endphp

            <!-- Deskripsi -->
            <p class="text-justify mb-6 leading-relaxed">
                Pada hari ini {{ $hari }} tanggal <strong>{{ $tgl }}</strong> bulan
                <strong>{{ $bulan }}</strong> tahun <strong>{{ $tahun }}</strong>,
                di <strong>{{ strtoupper($namaKampus) }}</strong> (ruang <strong>{{ $lokasi }}</strong>)
                telah diselenggarakan
                <strong>{{ $timetable->name }} Tahun {{ $tahun }}</strong>
                dengan {{ $timetable->timetableModule?->questionType?->name ?? $timetable->module?->questionType?->name ?? '-' }} dari pukul
                <strong>{{ $pukulMulai }}</strong> sampai dengan
                <strong>{{ $pukulSelesai }}</strong>.
            </p>

            <!-- Detail Informasi -->
            <div class="text-sm leading-relaxed mb-4 p-2">
                <div class="flex">
                    <div class="w-[40%]">1. Kode Lokasi</div>
                    <div class="flex-1 border-b border-gray-400 ml-2 font-mono">: {{ $company?->code_name ?? '-' }}</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Lokasi Ujian</div>
                    <div class="flex-1 border-b border-gray-400 ml-2">: {{ $company?->name ?? '-' }}</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Ruang</div>
                    <div class="flex-1 border-b border-gray-400 ml-2">: {{ $lokasi }}</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Sesi</div>
                    <div class="flex-1 border-b border-gray-400 ml-2">: {{ $exam_session?->name ?? '-' }}</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Jumlah Peserta Seharusnya</div>
                    <div class="flex-1 border-b border-gray-400 ml-2 font-mono">: {{ count($classmateDetail ?? []) }} Orang</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Jumlah Hadir (Ikut Ujian)</div>
                    <div class="flex-1 border-b border-gray-400 ml-2 font-mono">: ............... Orang</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Jumlah Tidak Hadir</div>
                    <div class="flex-1 border-b border-gray-400 ml-2 font-mono">: ............... Orang</div>
                </div>
                <div class="flex mt-1">
                    <div class="w-[40%]">Username Tidak Hadir</div>
                    <div class="flex-1 border-b border-gray-400 ml-2">: ............................................................................................</div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-6 text-sm">
                <p class="font-semibold mb-1">2. Catatan selama pelaksanaan:</p>
                <div class="border border-gray-400 p-2 min-h-20">
                </div>
            </div>

            <!-- Tanda tangan -->
            <p class="mb-2 font-semibold">Yang membuat berita acara :</p>
            <div class="flex justify-between text-center pt-2">
                <div class="w-1/3">
                    <p class="font-semibold">Proktor</p>
                    <div class="h-16"></div>
                    <p class="font-semibold underline">( .............................................. )</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">NIP/ID. ..............................</p>
                </div>
                <div class="w-1/3">
                    <p class="font-semibold">Pengawas</p>
                    <div class="h-16"></div>
                    <p class="font-semibold underline">( .............................................. )</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">NIP/ID. ..............................</p>
                </div>
                <div class="w-1/3">
                    <p class="font-semibold">Penanggung Jawab</p>
                    <div class="h-16"></div>
                    <p class="font-semibold underline">( .............................................. )</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">NIP/ID. ..............................</p>
                </div>
            </div>

            <!-- Catatan Bawah -->
            <div class="mt-8 text-[11px] border-t border-gray-300 pt-2 text-gray-600">
                <p>1. Berita Acara dicetak 2 (dua) rangkap, 1 (satu) untuk panitia lokasi dan 1 (satu) untuk pengawas utama.</p>
                <p>2. Berita Acara yang sudah ditandatangani, dipindai dan diunggah ke laman sistem.</p>
            </div>

            <!-- Footer -->
            <div class="mt-6 border-t border-gray-400 text-center text-[11px] py-1 font-semibold uppercase tracking-wider text-gray-700">
                {{ $timetable->name }}
            </div>
        </div>

    </body>

</html>