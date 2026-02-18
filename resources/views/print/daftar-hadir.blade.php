<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Daftar Hadir Peserta Tes Substantif</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 0mm;
                }
            }
        </style>
    </head>

    <body class="font-sans text-[12px] text-gray-900">
        <div class="w-[210mm] mx-auto border p-4">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <!-- Logo kiri -->
                <img src="{{ asset('storage/' . $company->logo_potrait) }}" alt="Logo" class="w-16 h-16">

                <!-- Teks tengah -->
                <div class="text-center flex-1">
                    <h1 class="font-bold text-lg uppercase">Daftar Hadir Peserta</h1>
                    <p class="font-semibold">Tes Substantif Calon Mahasiswa PPG Prajabatan Tahun 2024</p>
                </div>

                <!-- QR Code kanan -->
                <div id="qrcode" class="w-20 h-20"></div>
            </div>



            <!-- Informasi Lokasi -->
            <table class="w-full mb-3 text-[12px]">
                <tbody>
                    <tr class="align-top">
                        <td class="py-[2px] w-32">Wilayah</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="5">: {{ $company?->code_region }} -
                            {{ $company?->region }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-[2px]">Tempat Pelaksanaan</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="5">
                            : {{ $company?->code_name }} - {{ $company?->name }}
                        </td>
                    </tr>
                    <tr class="align-top">
                        <td class="py-[2px]">Ruang</td>
                        <td class="py-[2px] border-b border-gray-400" colspan="3">: {{ $exam_room?->name }}</td>
                        <td class="py-[2px] w-20 pl-6">Sesi</td>
                        <td class="py-[2px] border-b border-gray-400">: {{ $exam_session?->name }}</td>
                    </tr>
                    @php
                        use Carbon\Carbon;

                        // Konversi ke waktu lokal dan formatkan
                        $hari = Carbon::parse($timetable->start_time)->translatedFormat('l'); // Senin
                        $tanggal = Carbon::parse($timetable->start_time)->translatedFormat('d F Y'); // 04 November 2025
                        $pukulMulai = Carbon::parse($timetable->start_time)->format('H.i');
                        $pukulSelesai = Carbon::parse($timetable->end_time)->format('H.i');
                    @endphp

                    <tr class="align-top">
                        <td class="py-[2px]">Hari</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $hari }}
                        </td>
                        <td class="py-[2px] pl-6">Tanggal</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $tanggal }}
                        </td>
                        <td class="py-[2px] pl-6">Pukul</td>
                        <td class="py-[2px] border-b border-gray-400">
                            : {{ $pukulMulai }} - {{ $pukulSelesai }}
                        </td>
                    </tr>

                </tbody>
            </table>


            <!-- Tabel Daftar Hadir -->
            <table class="w-full border-collapse border border-gray-400 text-center text-[11px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-400 p-1 w-8">No.</th>
                        <th class="border border-gray-400 p-1">Nomor Peserta</th>
                        <th class="border border-gray-400 p-1">Nama Peserta</th>
                        <th class="border border-gray-400 p-1">Foto Peserta</th>
                        <th class="border border-gray-400 p-1">Tanda Tangan</th>
                        <th class="border border-gray-400 p-1">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classmateDetail as $class)
                        @php
                            $profile = $class->user->profile
                                ? asset('storage/' . $class->user->profile)
                                : auth()->user()->profile ?? asset('asset/img/profile.png');
                        @endphp
                        <tr class="h-6">
                            <td class="border border-gray-400">{{ $loop->iteration }}</td>
                            <td class="border border-gray-400">{{ $class?->user?->nim ?? $class?->user?->username }}</td>
                            <td class="border border-gray-400 text-left pl-2">{{ $class->user->name }}</td>
                            <td class="border border-gray-400 p-1">
                                <img src="{{ $profile }}" alt="Foto Peserta" class="mx-auto object-cover"
                                    style="width: 100px;">
                            </td>
                            <td class="border border-gray-400"></td>
                            <td class="border border-gray-400"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <!-- Keterangan -->
            <div class="mt-3 text-[10px] leading-tight">
                <ol class="list-decimal ml-4">
                    <li>Daftar hadir dicetak 2 (dua) rangkap...</li>
                    <li>Daftar hadir yang sudah ditandatangani diunggah ke laman...</li>
                    <li>Pengawas menyilang peserta yang tidak hadir di kolom tanda tangan.</li>
                </ol>
            </div>

            <!-- Rekap Jumlah -->
            <div class="mt-3 border border-gray-400 text-[11px] w-1/2">
                <table class="w-full border-collapse">
                    <tr>
                        <td class="border border-gray-400 p-1">Jumlah Peserta yang Seharusnya Hadir</td>
                        <td class="border border-gray-400 p-1 w-16 text-center"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-1">Jumlah Peserta yang Tidak Hadir</td>
                        <td class="border border-gray-400 p-1 text-center"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-1">Jumlah Peserta Hadir</td>
                        <td class="border border-gray-400 p-1 text-center"></td>
                    </tr>
                </table>
            </div>

            <!-- Tanda Tangan -->
            <div class="flex justify-between mt-6">
                <div class="text-center">
                    <p class="font-semibold">Proktor</p>
                    <div class="h-12"></div>
                    <p>(................................)</p>
                    <p class="text-[10px]">NIP.</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold">Pengawas</p>
                    <div class="h-12"></div>
                    <p>(................................)</p>
                    <p class="text-[10px]">NIP.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center border-t mt-4 pt-1 text-[11px] uppercase font-semibold">
                Tes Substantif Calon Mahasiswa PPG Prajabatan
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        <script>
            new QRCode(document.getElementById("qrcode"), {
                // text: "9640007-20240603-04-1",
                text: "{{ $company->code_name }}",
                width: 120,
                height: 120
            });
        </script>
    </body>

</html>
