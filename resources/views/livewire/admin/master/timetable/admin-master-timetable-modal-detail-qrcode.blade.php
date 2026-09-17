<!-- Modal Detail QRCODE Sesi Jadwal (Siap Cetak Admin) -->
<div wire:ignore.self id="modal-detail-qrcode"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-xl max-h-[92vh] flex flex-col border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100 bg-slate-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 flex items-center justify-center text-lg font-bold">
                    <i class="fas fa-qrcode"></i>
                </div>
                <div>
                    <h5 class="font-bold text-base text-slate-800">QRCODE Presensi Sesi Jadwal</h5>
                    <p class="text-xs text-slate-500">Cetak atau tampilkan QRCODE ini untuk di-scan oleh {{ student_label() }}</p>
                </div>
            </div>
            <button type="button" wire:click="closeDetailQrModal" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl transition cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Body (Scrollable printable preview) -->
        <div class="p-6 overflow-y-auto space-y-4 flex-1">
            @if($selected_qr_detail)
                <div id="printable-session-qr-card" class="bg-white text-slate-900 rounded-2xl border-2 border-slate-800 p-6 text-center shadow-md">
                    <div class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">
                        {{ auth()->user()->company?->name ?? 'CBT EXAMINATION SYSTEM' }}
                    </div>
                    <h3 class="text-lg font-black text-slate-900 uppercase">
                        LEMBAR QRCODE PRESENSI
                    </h3>
                    <div class="text-xs text-indigo-700 font-bold mt-0.5">
                        {{ $selected_qr_timetable?->name }} | {{ $selected_qr_timetable?->classmate?->name ?? 'Semua Kelas' }}
                    </div>

                    <!-- Meta Grid -->
                    <div class="grid grid-cols-2 gap-2 text-left text-xs bg-slate-50 rounded-xl p-3.5 border border-slate-200 mt-4 mb-4">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Ruang & Sesi</span>
                            <span class="font-bold text-slate-800">
                                {{ $selected_qr_detail->examRoom?->name ?? '-' }} | {{ $selected_qr_detail->examSession?->name ?? '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tipe Kegiatan</span>
                            <span class="font-bold text-slate-800">
                                @if($selected_qr_detail->isMaterial())
                                    [MATERI] {{ $selected_qr_detail->digitalBook?->title ?? '-' }}
                                @else
                                    [UJIAN] {{ $selected_qr_detail->module?->name ?? '-' }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Tanggal Pelaksanaan</span>
                            <span class="font-bold text-slate-800">
                                {{ $selected_qr_detail->exam_date ? \Carbon\Carbon::parse($selected_qr_detail->exam_date)->isoFormat('dddd, DD MMM Y') : '-' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Waktu Pelaksanaan</span>
                            <span class="font-bold text-slate-800">
                                {{ $selected_qr_detail->start_time ? \Carbon\Carbon::parse($selected_qr_detail->start_time)->format('H:i') : '-' }} - 
                                {{ $selected_qr_detail->end_time ? \Carbon\Carbon::parse($selected_qr_detail->end_time)->format('H:i') : '-' }}
                            </span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Pengawas Bertugas</span>
                            <span class="font-bold text-slate-800">{{ $selected_qr_detail->supervisor_names ?: 'Pengawas' }}</span>
                        </div>
                    </div>

                    <!-- QR Image -->
                    <div class="p-3 bg-white border-2 border-slate-900 rounded-xl inline-block shadow-inner mx-auto my-1">
                        @if($selected_qr_base64)
                            <img src="data:image/png;base64,{{ $selected_qr_base64 }}" alt="QRCODE Sesi" class="w-56 h-56 mx-auto">
                        @endif
                    </div>

                    <div class="mt-2">
                        <div class="inline-block px-3 py-1 rounded-md bg-slate-100 border border-slate-300 font-mono text-xs font-bold text-slate-800 tracking-wider">
                            ID: {{ $selected_qr_detail->code }}
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="mt-4 p-2.5 rounded-xl bg-blue-50 border border-blue-200 text-left text-[11px] text-blue-900 space-y-1">
                        <div class="font-bold flex items-center gap-1">
                            <i class="fas fa-info-circle text-blue-600"></i> Panduan Presensi:
                        </div>
                        <p class="text-blue-800">
                            Minta {{ student_label() }} membuka aplikasi CBT &gt; menu Jadwal &gt; klik tombol <strong>"Scan QRCODE Presensi"</strong> untuk memindai kode di atas.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 flex flex-wrap justify-between items-center gap-2">
            <div class="text-xs text-slate-500 font-medium">
                Format: PDF A4 Siap Cetak
            </div>
            <div class="flex items-center gap-2">
                @if($selected_qr_detail)
                    <button type="button" wire:click="printDetailQrPdf('{{ $selected_qr_detail->id }}')"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </button>
                    <button type="button" onclick="printElement('printable-session-qr-card')"
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-print"></i> Cetak Langsung
                    </button>
                @endif
                <button type="button" wire:click="closeDetailQrModal"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold rounded-xl transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function printElement(elementId) {
        const printContent = document.getElementById(elementId);
        if (!printContent) return;
        const win = window.open('', '', 'height=700,width=900');
        win.document.write('<html><head><title>Cetak QRCODE Presensi</title>');
        win.document.write('<style>');
        win.document.write('body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }');
        win.document.write('.text-xs { font-size: 10pt; } .text-lg { font-size: 14pt; } .font-black { font-weight: 900; }');
        win.document.write('.grid { display: table; width: 100%; text-align: left; margin: 15px 0; }');
        win.document.write('img { max-width: 250px; }');
        win.document.write('</style></head><body>');
        win.document.write(printContent.innerHTML);
        win.document.write('</body></html>');
        win.document.close();
        win.focus();
        setTimeout(function() {
            win.print();
            win.close();
        }, 500);
    }
</script>
