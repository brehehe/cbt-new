<!DOCTYPE html>
<html lang="id" class="light">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Daftar Hadir Peserta - {{ $timetable->name }}</title>
        @vite(['resources/css/app.css'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @media print {
                @page {
                    size: A4 portrait;
                    margin: 12mm 10mm 15mm 10mm;
                }
                body {
                    background: #fff !important;
                    color: #000 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    font-size: 11px !important;
                }
                .no-print {
                    display: none !important;
                }
                .print-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: none !important;
                    box-shadow: none !important;
                }
                table.daftar-hadir-table {
                    width: 100% !important;
                    page-break-inside: auto !important;
                }
                table.daftar-hadir-table thead {
                    display: table-header-group !important;
                }
                table.daftar-hadir-table tr {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                .print-avoid-break {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
            }

            @media screen {
                body {
                    background-color: #f3f4f6;
                }
                .print-container {
                    min-height: 297mm;
                }
            }
        </style>
    </head>

    <body class="font-sans text-[12px] text-gray-900 antialiased">
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
                        <span class="mx-1.5 text-gray-300">|</span>
                        <span>Total: <strong class="text-blue-600">{{ count($classmateDetail) }}</strong> Peserta</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.print()" 
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md hover:shadow transition cursor-pointer">
                        <i class="fa-solid fa-print"></i>
                        Cetak / Print Daftar Hadir
                    </button>
                </div>
            </div>
        </div>

        <!-- Main A4 Print Container -->
        <div class="max-w-[210mm] mx-auto bg-white p-8 shadow-md border border-gray-200 print-container">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4 border-b-2 border-gray-900 pb-3">
                <!-- Logo kiri -->
                <div class="w-16 h-16 flex items-center justify-center">
                    @if($company?->logo_potrait && file_exists(public_path('storage/' . $company->logo_potrait)))
                        <img src="{{ asset('storage/' . $company->logo_potrait) }}" alt="Logo" class="max-w-16 max-h-16 object-contain">
                    @elseif($company?->logo && file_exists(public_path('storage/' . $company->logo)))
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="max-w-16 max-h-16 object-contain">
                    @else
                        <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center text-gray-400 font-bold text-xs">LOGO</div>
                    @endif
                </div>

                <!-- Teks tengah -->
                <div class="text-center flex-1 px-4">
                    <h1 class="font-bold text-lg uppercase tracking-wider text-gray-900 leading-tight">DAFTAR HADIR PESERTA</h1>
                    <p class="font-bold text-sm uppercase text-gray-800 tracking-wide mt-1">{{ $timetable->name }}</p>
                    <p class="text-[11px] text-gray-600 mt-0.5">{{ $company?->name ?? 'INSTITUSI' }}</p>
                </div>

                <!-- QR Code kanan -->
                <div class="w-16 h-16 flex items-center justify-center">
                    <div id="qrcode" class="w-16 h-16"></div>
                </div>
            </div>

            <!-- Informasi Lokasi & Waktu Pelaksanaan -->
            <table class="w-full mb-3 text-[11px]">
                <tbody>
                    <tr class="align-top">
                        <td class="py-[2px] w-36 font-semibold text-gray-700">Wilayah</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="5">: {{ $company?->code_region ?? '-' }} - {{ $company?->region ?? '-' }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-[2px] font-semibold text-gray-700">Tempat Pelaksanaan</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="5">
                            : {{ $company?->code_name ?? '-' }} - {{ $company?->name ?? '-' }}
                        </td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-[2px] font-semibold text-gray-700">Ruang</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="3">: {{ $exam_room?->name ?? '-' }}</td>
                        <td class="py-[2px] w-20 pl-6 font-semibold text-gray-700">Sesi</td>
                        <td class="py-[2px] border-b border-gray-400">: {{ $exam_session?->name ?? '-' }}</td>
                    </tr>
                    @php
                        use Carbon\Carbon;

                        // Konversi ke waktu lokal dan formatkan
                        $start = $timetable->start_time ? Carbon::parse($timetable->start_time) : null;
                        $end = $timetable->end_time ? Carbon::parse($timetable->end_time) : null;

                        $hari = $start ? $start->translatedFormat('l') : '-';
                        $tanggal = $start ? $start->translatedFormat('d F Y') : '-';
                        $pukulMulai = $start ? $start->format('H.i') : '-';
                        $pukulSelesai = $end ? $end->format('H.i') : '-';
                    @endphp

                    <tr class="align-top">
                        <td class="py-[2px] font-semibold text-gray-700">Hari</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $hari }}
                        </td>
                        <td class="py-[2px] pl-6 font-semibold text-gray-700">Tanggal</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $tanggal }}
                        </td>
                        <td class="py-[2px] pl-6 font-semibold text-gray-700">Pukul</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $pukulMulai }} - {{ $pukulSelesai }} WIB
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Tabel Daftar Hadir Peserta -->
            <table class="daftar-hadir-table w-full border-collapse border border-gray-600 text-center text-[11px] mb-4">
                <thead>
                    <tr class="bg-gray-100 font-bold uppercase text-[10px] text-gray-800">
                        <th class="border border-gray-600 py-1.5 px-1 w-8">No.</th>
                        <th class="border border-gray-600 py-1.5 px-2 w-32">Nomor Peserta</th>
                        <th class="border border-gray-600 py-1.5 px-3 text-left">Nama Peserta</th>
                        <th class="border border-gray-600 py-1.5 px-1 w-14">Foto</th>
                        <th class="border border-gray-600 py-1.5 px-2 w-48">Tanda Tangan</th>
                        <th class="border border-gray-600 py-1.5 px-1 w-20">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classmateDetail as $class)
                        @php
                            $user = $class->user;
                            $profilePath = $user?->profile;
                            $hasProfile = $profilePath && file_exists(public_path('storage/' . $profilePath));
                            $profileUrl = $hasProfile ? asset('storage/' . $profilePath) : null;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-600 py-1 font-medium">{{ $loop->iteration }}</td>
                            <td class="border border-gray-600 py-1 font-mono text-[11px] text-gray-800">{{ $user?->nim ?? ($user?->username ?? '-') }}</td>
                            <td class="border border-gray-600 py-1 px-3 text-left font-semibold text-gray-900">{{ $user?->name ?? '-' }}</td>
                            <td class="border border-gray-600 py-1 px-1">
                                @if($profileUrl)
                                    <img src="{{ $profileUrl }}" alt="Foto" class="w-8 h-10 mx-auto object-cover border border-gray-300 rounded-sm">
                                @else
                                    <div class="w-8 h-10 mx-auto border border-dashed border-gray-300 rounded-sm bg-gray-50 flex items-center justify-center text-[8px] text-gray-400 font-bold">FOTO</div>
                                @endif
                            </td>
                            <td class="border border-gray-600 py-1 px-2">
                                @if($loop->iteration % 2 == 1)
                                    <div class="text-left font-mono text-[11px] text-gray-800">
                                        <span class="font-bold text-gray-600 inline-block w-6">{{ $loop->iteration }}.</span> ............................
                                    </div>
                                @else
                                    <div class="text-right font-mono text-[11px] text-gray-800">
                                        <span class="font-bold text-gray-600 inline-block w-6 text-left">{{ $loop->iteration }}.</span> ............................
                                    </div>
                                @endif
                            </td>
                            <td class="border border-gray-600 py-1 text-[10px] text-gray-500"></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-gray-600 py-6 text-gray-500 font-medium">Tidak ada peserta pada jadwal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Bagian Bawah: Petunjuk, Rekapitulasi, & Tanda Tangan (page-break-inside: avoid) -->
            <div class="print-avoid-break mt-4">
                <!-- Keterangan / Petunjuk Pengawas -->
                <div class="text-[10px] text-gray-700 leading-relaxed mb-3">
                    <ol class="list-decimal ml-4">
                        <li>Daftar hadir dicetak rangkap untuk arsip dan verifikasi pelaksanaan ujian.</li>
                        <li>Daftar hadir yang sudah ditandatangani diunggah ke sistem atau diserahkan ke panitia pelaksana.</li>
                        <li>Pengawas menyilang peserta yang tidak hadir pada kolom tanda tangan dengan tinta merah.</li>
                    </ol>
                </div>

                <!-- Rekap Jumlah & Tanda Tangan Grid -->
                <div class="flex justify-between items-start gap-6 mt-4">
                    <!-- Rekap Jumlah -->
                    <div class="w-72 border border-gray-600 text-[11px]">
                        <table class="w-full border-collapse">
                            <tr>
                                <td class="border-b border-r border-gray-600 p-1.5 font-medium bg-gray-50">Jumlah Peserta Seharusnya</td>
                                <td class="border-b border-gray-600 p-1.5 w-24 text-center font-bold text-gray-900">{{ count($classmateDetail) }} Orang</td>
                            </tr>
                            <tr>
                                <td class="border-b border-r border-gray-600 p-1.5 font-medium bg-gray-50">Jumlah Tidak Hadir</td>
                                <td class="border-b border-gray-600 p-1.5 text-center font-mono">....... Orang</td>
                            </tr>
                            <tr>
                                <td class="border-r border-gray-600 p-1.5 font-medium bg-gray-50">Jumlah Hadir</td>
                                <td class="p-1.5 text-center font-mono">....... Orang</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tanda Tangan Proktor & Pengawas -->
                    <div class="flex-1 flex justify-around text-center text-[11px]">
                        <div class="w-44">
                            <p class="font-bold text-gray-900">Proktor Ujian</p>
                            <div class="h-16"></div>
                            <p class="font-semibold underline">( ........................................ )</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">NIP/ID. ..............................</p>
                        </div>
                        <div class="w-44">
                            <p class="font-bold text-gray-900">Pengawas Ujian</p>
                            <div class="h-16"></div>
                            <p class="font-semibold underline">( ........................................ )</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">NIP/ID. ..............................</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Dinamis -->
                <div class="text-center border-t border-gray-400 mt-6 pt-2 text-[11px] uppercase font-bold text-gray-700 tracking-wider">
                    {{ $timetable->name }}
                </div>
            </div>
        </div>

        <script src="{{ asset('vendor/qrcodejs/qrcode.min.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var qrContainer = document.getElementById("qrcode");
                if (qrContainer) {
                    new QRCode(qrContainer, {
                        text: "{{ $company?->code_name ?? ($timetable->code ?? $timetable->id) }}",
                        width: 64,
                        height: 64
                    });
                }
            });
        </script>
    </body>

</html>
