<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Berita Acara Tes Substantif</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 20mm;
                }

                body {
                    -webkit-print-color-adjust: exact;
                }
            }
        </style>
    </head>

    <body class="bg-white text-gray-900 font-sans">

        <div class="max-w-[210mm] mx-auto border border-gray-300 p-8 text-[13px] leading-relaxed">
            <!-- Header -->
            <div class="flex items-center mb-6">
                <!-- Logo -->
                <img src="{{ asset('storage/' . $company->logo_potrait) }}" alt="Logo" class="w-20 h-20 mr-4">

                <!-- Teks -->
                <div class="flex-1 text-center">
                    <h1 class="font-bold text-lg uppercase">BERITA ACARA PELAKSANAAN</h1>
                    <p class="font-semibold uppercase">
                        {{ $timetable->name }}<br>
                        TAHUN 2024
                    </p>
                </div>
            </div>


            @php
                use Carbon\Carbon;

                $tanggal = Carbon::parse($timetable->start_time);
                $hari = $tanggal->translatedFormat('l'); // contoh: Senin
                $tgl = $tanggal->translatedFormat('d'); // contoh: 03
                $bulan = $tanggal->translatedFormat('F'); // contoh: Juni
                $tahun = $tanggal->translatedFormat('Y'); // contoh: 2024

                $pukulMulai = Carbon::parse($timetable->start_time)->format('H:i');
                $pukulSelesai = Carbon::parse($timetable->end_time)->format('H:i');

                $lokasi = $exam_room->name ?? 'Lokasi Tidak Diketahui';
                $namaKampus = $company->name ?? 'UNIVERSITAS PANCASAKTI TEGAL';
            @endphp

            <!-- Deskripsi -->
            <p class="text-justify mb-6 leading-relaxed">
                Pada hari ini {{ $hari }} tanggal <strong>{{ $tgl }}</strong> bulan
                <strong>{{ $bulan }}</strong> tahun <strong>{{ $tahun }}</strong>,
                di <strong>{{ strtoupper($namaKampus) }}</strong> (ruang <strong>{{ $lokasi }}</strong>)
                telah diselenggarakan
                <strong>{{ $timetable->name }} Tahun {{ $tahun }}</strong>
                dengan {{ $timetable->module->questionType->name }} dari pukul
                <strong>{{ $pukulMulai }}</strong> sampai dengan
                <strong>{{ $pukulSelesai }}</strong>.
            </p>

            <!-- Detail Informasi -->
            <div class="text-sm leading-relaxed mb-4 p-2">
                <div class="flex">
                    <div class="w-[40%]">1. Kode Lokasi</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Lokasi Ujian</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Ruang</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Sesi</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Jumlah Peserta Seharusnya</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Jumlah Hadir (Ikut Ujian)</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Jumlah Tidak Hadir</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
                <div class="flex">
                    <div class="w-[40%]">Username Tidak Hadir</div>
                    <div class="flex-1 border-b border-gray-400 ml-2"></div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mb-6 text-sm">
                <p class="font-semibold mb-1">2. Catatan selama pelaksanaan:</p>
                <div class="border border-gray-400 p-2 h-20">
                </div>
            </div>


            <!-- Tanda tangan -->
            <p class="mb-2">Yang membuat berita acara :</p>
            <div class="flex justify-between">
                <div class="text-center">
                    <p class="font-semibold">Proktor</p>
                    <div class="h-12"></div>
                    <p>(..............................................)</p>
                    <p class="text-[10px]">NIP.</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold">Pengawas</p>
                    <div class="h-12"></div>
                    <p>(..............................................)</p>
                    <p class="text-[10px]">NIP.</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold">Penanggung Jawab</p>
                    <div class="h-12"></div>
                    <p>(..............................................)</p>
                    <p class="text-[10px]">NIP.</p>
                </div>
            </div>

            <!-- Catatan Bawah -->
            <div class="mt-6 text-[11px] border-t border-gray-300 pt-2">
                <p>1. Berita Acara dicetak 2 (dua) rangkap, 1 (satu) untuk panitia lokasi dan 1 (satu) untuk pengawas
                    utama</p>
                <p>2. Berita Acara yang sudah ditandatangani, dipindai dan diunggah ke laman</p>
            </div>

            <!-- Footer -->
            <div class="mt-6 border-t border-gray-400 text-center text-[11px] py-1 font-semibold uppercase">
                {{ $timetable->name }}
            </div>
        </div>

    </body>

</html>
