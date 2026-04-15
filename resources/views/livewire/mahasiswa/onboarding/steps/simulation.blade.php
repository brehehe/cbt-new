<!-- Step 3: Interactive Simulation - Exact Replica of ExamContainer.jsx -->
<div wire:key="step-3" class="animate-fadeIn w-full relative" x-data="{ 
    showGuide: 1, 
    time: @entangle('simTimeSeconds'),
    timer: null,
    init() {
        this.timer = setInterval(() => { if(this.time > 0) this.time--; }, 1000);
    },
    formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return [h, m, s].map(v => v < 10 ? '0' + v : v).join(':');
    }
}">
    <!-- Header simulasi untuk Onboarding -->
    <div class="text-center mb-8 px-4">
        <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Simulasi Ujian Pintar</h2>
        <p class="mt-3 text-gray-600 max-w-2xl mx-auto">Kami telah menyamakan tampilan simulasi ini persis dengan
            halaman ujian asli. Pahami dan pelajari setiap fungsi fitur yang ada menggunakan tombol panduan di bawah.
        </p>
    </div>

    <!-- GUIDE SELECTOR / FOOTER -->
    <div class="flex flex-wrap items-center justify-center gap-3 mb-8">
        <button @click="showGuide = 1"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase transition-all shadow-sm border"
            :class="showGuide === 1 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">1.
            Waktu & Info</button>
        <button @click="showGuide = 2"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase transition-all shadow-sm border"
            :class="showGuide === 2 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">2.
            Panel Navigasi</button>
        <button @click="showGuide = 3"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase transition-all shadow-sm border"
            :class="showGuide === 3 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">3.
            Area Soal & Ragu</button>
        <button @click="showGuide = 4"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase transition-all shadow-sm border"
            :class="showGuide === 4 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">4.
            Auto-Save</button>
        <button @click="showGuide = 5"
            class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase transition-all shadow-sm border"
            :class="showGuide === 5 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">5.
            Kamera & Rekaman</button>
    </div>

    <!-- MAIN EXAM CONTAINER (Replica of ExamContainer.jsx layout) -->
    <div
        class="flex flex-col h-[1500px] max-h-[150vh] border border-gray-300 rounded-2xl overflow-hidden shadow-2xl relative font-sans text-gray-900 bg-white mx-auto">

        <!-- Header Replica -->
        <header class="flex-none p-4 text-white shadow-md relative" style="background-color: var(--primary, #f58634)">

            <!-- GUIDE 1 -->
            <div class="absolute left-1/2 top-16 transform -translate-x-1/2 w-[28rem] max-w-[95vw] bg-gray-900 border-2 border-white p-5 rounded-xl shadow-2xl text-white z-50 animate-bounce-subtle"
                x-show="showGuide === 1">
                <div
                    class="absolute -top-3 left-1/2 -translate-x-1/2 w-4 h-4 bg-gray-900 border-l-2 border-t-2 border-white rotate-45">
                </div>
                <div class="flex items-center gap-2 mb-3">
                    <div
                        class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">
                        1</div>
                    <span class="font-bold text-base text-orange-400">Header Informasi Ujian</span>
                </div>
                <div class="text-[13px] text-gray-300 leading-relaxed flex flex-col gap-3 font-medium">
                    <p>Bagian atas halaman ujian ini sangat penting karena memuat informasi utama sesi Anda:</p>
                    <ul class="list-disc pl-4 space-y-1.5 marker:text-orange-500">
                        <li><b>Modul Ujian:</b> Menunjukkan nama tes/mata pelajaran yang sedang Anda kerjakan saat ini.
                            Pastikan modul sudah sesuai.</li>
                        <li><b>Waktu Tersisa:</b> Indikator waktu berjalan mundur (countdown). Pastikan untuk selalu
                            memperhatikan sisa waktu. <span class="text-red-400 font-bold">Penting:</span> Jika waktu
                            habis (00:00:00), seluruh jawaban Anda akan dikirim ke server secara otomatis dan ujian akan
                            langsung tertutup.</li>
                        <li><b>Tombol Selesai Ujian:</b> Tombol ini dapat Anda klik secara manual jika Anda sudah yakin
                            dengan semua jawaban dan ingin mengakhiri ujian lebih cepat sebelum waktu habis.</li>
                    </ul>
                </div>
            </div>

            <div class="max-w-full mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg">
                        <span class="text-sm font-medium">Modul: Pengenalan Sistem CBT</span>
                    </div>
                </div>

                <div class="flex items-center justify-between md:gap-6">
                    <div
                        class="flex items-center gap-2 font-mono font-bold text-xl tracking-wider px-4 py-1.5 bg-black/10 rounded-lg">
                        <i class="fas fa-clock w-5 h-5"></i>
                        <span x-text="formatTime(time)"></span>
                    </div>
                    <button
                        class="px-6 py-2 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg font-bold text-sm transition-all shadow-sm backdrop-blur-sm">
                        Selesai Ujian
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Layout (3 Columns) -->
        <div class="flex flex-1 overflow-hidden relative">

            <!-- Left Sidebar (NavigationSidebar.jsx Replica) -->
            <aside class="hidden lg:flex flex-col z-40 h-full w-80 bg-white border-r border-gray-200 relative shrink-0">

                <!-- GUIDE 2 -->
                <div class="absolute right-[-360px] top-10 w-[22rem] bg-gray-900 border-2 border-white p-5 rounded-xl shadow-2xl text-white z-50 animate-bounce-subtle"
                    x-show="showGuide === 2">
                    <div
                        class="absolute top-6 -left-3 w-4 h-4 bg-gray-900 border-b-2 border-l-2 border-white rotate-45">
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">
                            2</div>
                        <span class="font-bold text-base text-orange-400">Panel Navigasi Soal</span>
                    </div>
                    <div class="text-[13px] text-gray-300 leading-relaxed mb-3 space-y-2 font-medium">
                        <p>Panel Navigasi (di sebelah kiri) berfungsi sebagai peta ujian Anda. Anda dapat langsung
                            melompat ke nomor soal mana pun dengan mengklik kotaknya, tidak perlu berurutan.</p>
                        <p>Arti warna pada setiap kotak soal:</p>
                    </div>
                    <ul class="text-xs space-y-2 text-gray-300 bg-gray-800/80 p-3 rounded-lg border border-gray-700/50">
                        <li class="flex items-start gap-2">
                            <div
                                class="w-3.5 h-3.5 mt-0.5 bg-blue-600 rounded-sm shrink-0 shadow-[0_0_8px_rgba(37,99,235,0.6)]">
                            </div><span><strong class="text-blue-400">Biru Terang:</strong> Posisi soal yang sedang
                                dikerjakan saat ini.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <div
                                class="w-3.5 h-3.5 mt-0.5 bg-green-500 rounded-sm shrink-0 shadow-[0_0_8px_rgba(34,197,94,0.6)]">
                            </div><span><strong class="text-green-500">Hijau:</strong> Soal sudah dijawab/diisi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <div
                                class="w-3.5 h-3.5 mt-0.5 bg-yellow-500 rounded-sm shrink-0 shadow-[0_0_8px_rgba(234,179,8,0.6)]">
                            </div><span><strong class="text-yellow-500">Kuning:</strong> Soal ditandai <b>Ragu-Ragu</b>
                                untuk diingat agar mengeceknya lagi nanti.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <div class="w-3.5 h-3.5 mt-0.5 bg-gray-300 rounded-sm shrink-0"></div><span><strong
                                    class="text-gray-300">Abu-abu:</strong> Soal masih kosong, belum dilihat, atau belum
                                dijawab.</span>
                        </li>
                    </ul>
                </div>

                <!-- Stats Overview -->
                <div class="p-5 border-b bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 mb-4">Navigasi Soal</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total Soal</span>
                            <span class="font-bold">{{ count($simQuestions) }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div
                                class="flex items-center gap-2 p-2 bg-green-50 text-green-700 rounded-lg border border-green-100">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>Dijawab: {{ collect($simAnswers)->filter(fn($a) => $a !== '')->count() }}</span>
                            </div>
                            <div
                                class="flex items-center gap-2 p-2 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-100">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <span>Ragu: {{ collect($simMarks)->filter(fn($m) => $m)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Legends -->
                <div class="p-4 border-b">
                    <div class="grid grid-cols-4 gap-2 text-[10px] text-gray-500 uppercase tracking-wider font-bold">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-5 h-5 bg-blue-600 rounded ring-2 ring-blue-200"></div>
                            <span>Aktif</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-5 h-5 bg-green-500 rounded"></div>
                            <span>Isi</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-5 h-5 bg-yellow-500 rounded"></div>
                            <span>Ragu</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-5 h-5 bg-gray-200 rounded"></div>
                            <span>Belum</span>
                        </div>
                    </div>
                </div>

                <!-- Grid Map -->
                <div class="p-4 overflow-y-auto" style="height: calc(100vh - 350px)">
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($simQuestions as $index => $q)
                            @php
                                $bgColor = 'bg-gray-200 text-gray-600 hover:bg-gray-300';
                                $ringColor = '';
                                if ($simCurrentIndex === $index) {
                                    $bgColor = 'bg-blue-600 text-white';
                                    $ringColor = 'ring-4 ring-blue-100 scale-110 z-10';
                                } elseif ($simMarks[$q['id']]) {
                                    $bgColor = 'bg-yellow-500 text-white hover:bg-yellow-600';
                                } elseif ($simAnswers[$q['id']]) {
                                    $bgColor = 'bg-green-500 text-white hover:bg-green-600';
                                }
                            @endphp
                            <button wire:click="setSimIndex({{ $index }})"
                                class="w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all {{ $bgColor }} {{ $ringColor }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="absolute bottom-0 w-full p-4 border-t bg-gray-50">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <i class="fas fa-info-circle w-4 h-4"></i>
                        <span>Klik nomor soal untuk berpindah</span>
                    </div>
                </div>
            </aside>

            <!-- Center Content (QuestionArea.jsx Replica) -->
            <main class="flex-1 flex flex-col h-full bg-white relative">

                <!-- GUIDE 3 -->
                <div class="absolute top-16 left-1/2 transform -translate-x-1/2 w-[28rem] max-w-[95vw] bg-gray-900 border-2 border-white p-5 rounded-xl shadow-2xl text-white z-50 animate-bounce-subtle"
                    x-show="showGuide === 3">
                    <div
                        class="absolute -top-3 left-[80%] -translate-x-1/2 w-4 h-4 bg-gray-900 border-l-2 border-t-2 border-white rotate-45">
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">
                            3</div>
                        <span class="font-bold text-base text-orange-400">Area Soal & Ragu-Ragu</span>
                    </div>
                    <div class="text-[13px] text-gray-300 leading-relaxed flex flex-col gap-3 font-medium">
                        <p>Ini adalah layar utama tempat Anda membaca narasi/teks soal dan menentukan jawaban Anda.</p>
                        <ul class="list-disc pl-4 space-y-1.5 marker:text-orange-500">
                            <li><b>Teks Soal & Opsi:</b> Bacalah dengan teliti. Untuk soal Pilihan Ganda, klik kotak
                                opsi A, B, C, D, atau E. Jawaban yang Anda pilih akan langsung tersorot warna oranye.
                                Untuk soal Esai, akan muncul kotak isian teks yang lebar.</li>
                            <li><b>Tombol Ragu-Ragu:</b> Jika Anda sudah menjawab namun merasa tidak yakin (atau ingin
                                melompati soal dan kembali nanti), klik tombol <b>Ragu-Ragu</b> di pojok kanan atas
                                layar ini. <i>Catatan: Tombol ini sama sekali tidak memengaruhi nilai akhir</i>,
                                melainkan hanya mengubah warna di panel navigasi (menjadi kuning) sebagai asisten
                                pengingat visual bagi Anda.</li>
                            <li><b>Navigasi Bawah:</b> Gunakan tombol "Sebelumnya" atau "Selanjutnya" di bagian paling
                                bawah untuk berpindah antar soal secara berurutan. Di soal nomor terakhir, tombol
                                "Selanjutnya" akan berubah menjadi hijau "Akhiri Ujian".</li>
                        </ul>
                    </div>
                </div>

                <!-- Header Question -->
                <div class="p-4 lg:p-6 border-b bg-gray-50 flex items-center justify-between relative">
                    <h2 class="text-xl font-bold text-gray-800">Soal No. {{ $simCurrentIndex + 1 }}</h2>
                    <button wire:click="toggleSimMark({{ $simQuestions[$simCurrentIndex]['id'] }})"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg border transition-all {{ $simMarks[$simQuestions[$simCurrentIndex]['id']] ? 'bg-yellow-50 border-yellow-400 text-yellow-700 shadow-sm' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                        <i
                            class="fas fa-question-circle w-5 h-5 {{ $simMarks[$simQuestions[$simCurrentIndex]['id']] ? 'text-yellow-500' : '' }}"></i>
                        <span class="font-medium">Ragu-Ragu</span>
                    </button>
                </div>

                <!-- Content Area -->
                <div class="flex-1 overflow-y-auto p-4 lg:p-10 relative">

                    <!-- GUIDE 4: AUTO SAVE -->
                    <div class="absolute right-10 top-1/2 transform w-[26rem] bg-gray-900 border-2 border-white p-5 rounded-xl shadow-2xl text-white z-50 animate-bounce-subtle"
                        x-show="showGuide === 4">
                        <div
                            class="absolute top-full -mt-2 left-[50%] -translate-x-1/2 w-4 h-4 bg-gray-900 border-b-2 border-r-2 border-white rotate-45">
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <div
                                class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">
                                4</div>
                            <span class="font-bold text-base text-orange-400">Cloud Auto-Save Real-Time</span>
                        </div>
                        <div class="text-[13px] text-gray-300 leading-relaxed mb-2 space-y-2 font-medium">
                            <p>Sistem ujian menggunakan teknologi <b>Penyimpanan Otomatis</b> canggih untuk mencegah
                                kehilangan data Anda.</p>
                            <ul class="list-disc pl-4 space-y-1.5 marker:text-orange-500">
                                <li><b>Tanpa Tombol Simpan Terpisah:</b> Anda tak perlu lagi repot mencari tombol
                                    "Simpan Jawaban". Begitu Anda mengklik sebuah opsi ganda, atau mengetik pada soal
                                    esai, jawaban akan langsung terkirim secara halus (di balik layar) ke peladen
                                    (server) kami di detik yang sama.</li>
                                <li><b>Terproteksi 100%:</b> Seandainya koneksi internet tiba-tiba anjlok, peramban
                                    (browser) Anda mati karena kehabisan baterai, atau listrik padam, jawaban dan
                                    progres pengerjaan terakhir Anda dipastikan utuh dan aman.</li>
                                <li><b>Indikator Loading <i
                                            class="fas fa-spinner animate-spin text-orange-400 mx-0.5"></i> :</b> Dapat
                                    Anda pantau sekilas saat ujian memuat/menyinkronkan proses penyimpanan tersebut.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="max-w-full mx-auto space-y-8">
                        <!-- Question Text -->
                        <div class="space-y-6">
                            <div
                                class="prose prose-xl max-w-none text-gray-900 leading-relaxed font-medium text-justify">
                                {{ $simQuestions[$simCurrentIndex]['question'] }}
                            </div>
                        </div>

                        <!-- Answers -->
                        <div class="pb-20">
                            @if($simQuestions[$simCurrentIndex]['type'] === 'single')
                                <div class="grid grid-cols-1 gap-4">
                                    @php $alphabets = ['A', 'B', 'C', 'D', 'E']; @endphp
                                    @foreach($simQuestions[$simCurrentIndex]['options'] as $i => $option)
                                        @php $isSelected = $simAnswers[$simQuestions[$simCurrentIndex]['id']] === $option['id']; @endphp
                                        <label
                                            wire:click="selectSimAnswer({{ $simQuestions[$simCurrentIndex]['id'] }}, '{{ $option['id'] }}')"
                                            class="group relative flex items-start gap-5 p-6 rounded-3xl border-2 cursor-pointer transition-all duration-300 {{ $isSelected ? 'border-orange-600 bg-orange-50/30' : 'border-gray-100 bg-white hover:border-orange-300 hover:shadow-xl hover:-translate-y-1' }}">
                                            <input type="radio" name="answer" class="hidden" {{ $isSelected ? 'checked' : '' }}>

                                            <div
                                                class="flex-none w-10 h-10 rounded-2xl flex items-center justify-center font-black text-lg transition-all duration-300 shadow-sm border-2 {{ $isSelected ? 'bg-orange-600 border-orange-600 text-white rotate-12 scale-110 shadow-orange-200' : 'bg-white border-gray-200 text-gray-400 group-hover:border-orange-400 group-hover:text-orange-500' }}">
                                                {{ strtoupper($option['id']) }}
                                            </div>

                                            <div class="flex-1 space-y-4 pt-1.5 align-middle">
                                                <div class="text-gray-800 text-lg font-bold leading-relaxed">
                                                    {{ $option['text'] }}
                                                </div>
                                            </div>

                                            <div
                                                class="flex-none w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 {{ $isSelected ? 'opacity-100 scale-100' : 'opacity-0 scale-50' }}">
                                                <i class="fas fa-check-circle text-orange-600 text-3xl"></i>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="space-y-4">
                                    <label class="block text-lg font-bold text-gray-700">Jawaban Anda:</label>
                                    <textarea wire:model.live="simAnswers.{{ $simQuestions[$simCurrentIndex]['id'] }}"
                                        placeholder="Ketik jawaban Anda di sini..."
                                        class="w-full h-64 p-6 rounded-3xl border-2 border-gray-100 focus:border-orange-600 focus:ring-4 focus:ring-orange-100 transition-all text-lg font-medium resize-none shadow-sm"></textarea>
                                    <p class="text-sm text-gray-400 italic">
                                        Jawaban Anda akan disimpan secara otomatis saat Anda mengetik. (Tanda indikator
                                        loader: <i class="fas fa-spinner animate-spin mx-1" wire:loading
                                            wire:target="simAnswers.{{ $simQuestions[$simCurrentIndex]['id'] }}"></i>)
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation Replica -->
                <div class="p-4 lg:p-6 border-t bg-white relative">
                    <div class="max-w-full mx-auto flex items-center justify-between">
                        <button wire:click="setSimIndex({{ max(0, $simCurrentIndex - 1) }})" @if($simCurrentIndex === 0)
                        disabled @endif
                            class="flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all {{ $simCurrentIndex === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">
                            <i class="fas fa-chevron-left w-5 h-5"></i> Sebelumnya
                        </button>

                        <div class="text-sm font-bold text-gray-500 hidden sm:block">
                            Soal {{ $simCurrentIndex + 1 }} dari {{ count($simQuestions) }}
                        </div>

                        @if($simCurrentIndex === count($simQuestions) - 1)
                            <button
                                class="flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white transition-all bg-green-600 hover:bg-green-700 shadow-lg shadow-green-200">
                                <i class="fas fa-check-circle w-5 h-5"></i> Akhiri Ujian
                            </button>
                        @else
                            <button wire:click="setSimIndex({{ $simCurrentIndex + 1 }})"
                                class="flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white transition-all bg-orange-600 hover:bg-orange-700 shadow-lg shadow-orange-200">
                                Selanjutnya <i class="fas fa-chevron-right w-5 h-5"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </main>

            <!-- Right Sidebar (MonitorSidebar.jsx Replica) -->
            <aside class="hidden lg:flex flex-col z-40 h-full w-80 bg-white border-l border-gray-200 relative shrink-0">

                <!-- GUIDE 5 -->
                <div class="absolute left-[-360px] top-10 w-[22rem] bg-gray-900 border-2 border-white p-5 rounded-xl shadow-2xl text-white z-50 animate-bounce-subtle"
                    x-show="showGuide === 5">
                    <div
                        class="absolute top-6 -right-3 w-4 h-4 bg-gray-900 border-t-2 border-r-2 border-white rotate-45">
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">
                            5</div>
                        <span class="font-bold text-base text-orange-400">Pengawasan Kamera Cerdas</span>
                    </div>
                    <div class="text-[13px] text-gray-300 leading-relaxed mb-2 space-y-2 font-medium">
                        <p>Demi menjaga keadilan dan integritas ujian Anda, sistem diintegrasikan dengan teknologi
                            <b>Active Proctoring</b> canggih.</p>
                        <ul class="list-disc pl-4 space-y-1.5 marker:text-orange-500">
                            <li><b class="text-red-400">Video Kamera Berkelanjutan:</b> Berbeda dengan sistem jadul yang
                                hanya mengambil foto acak sesekali, Sistem CBT Pintar ini merekam visual Anda
                                menggunakan format video langsung (LIVE) tanpa jeda. Sistem pendeteksi akan menganalisa
                                keberadaan orang asing atau aktivitas yang dilarang.</li>
                            <li><b>Pelacakan Aktivitas Browser:</b> Aktivitas layar browser Anda diawasi penuh.
                                Menyempil ke tab pencarian atau membuka aplikasi lain dapat terekam dan berdampak pada
                                pemutusan ujian secara otomatis.</li>
                            <li><b>Status Server & Streaming:</b> Panel indikator di bawah membantu Anda memastikan
                                bahwa ujian dan server terhubung sempurna tanpa ada masalah koneksi.</li>
                        </ul>
                    </div>
                </div>

                <!-- Profile Section -->
                <div class="p-6 border-b bg-gray-50/30 flex flex-col items-center">
                    <div
                        class="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4 ring-4 ring-orange-50">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg text-center line-clamp-2">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-orange-600 font-bold tracking-wider mt-1">
                        {{ Auth::user()->userDetail->nim ?? $nim }}
                    </p>
                </div>

                <!-- Camera Monitor -->
                <div class="p-5 border-b">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-camera w-4 h-4 text-orange-600"></i> Monitor Camera
                        </h4>
                        <div class="flex items-center gap-2">
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest bg-red-100 text-red-600 animate-pulse">
                                Live
                            </span>
                        </div>
                    </div>
                    <div
                        class="relative aspect-video bg-black rounded-2xl overflow-hidden shadow-inner group flex items-center justify-center">
                        <!-- Pseudo Camera View -->
                        <div class="absolute inset-0 bg-gray-800 flex flex-col justify-center items-center opacity-50">
                            <i class="fas fa-user-circle text-5xl text-gray-500"></i>
                            <span class="text-[10px] font-bold text-gray-400 mt-2 tracking-widest uppercase">Mock
                                Webcam</span>
                        </div>
                        <div
                            class="absolute bottom-2 left-2 flex items-center gap-1.5 px-2 py-1 bg-black/40 backdrop-blur-md rounded-lg text-[10px] text-white font-bold uppercase border border-white/10">
                            <div class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></div>
                            REC • Recording
                        </div>
                    </div>
                    @php
                        $answeredCount = collect($simAnswers)->filter(fn($a) => !empty($a))->count();
                        $percentage = ($answeredCount / count($simQuestions)) * 100;
                    @endphp
                    <div class="mt-4 p-3 bg-orange-50/50 rounded-xl border border-orange-100/50 flex gap-3">
                        <div
                            class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 flex-none self-center">
                            <i class="fas fa-desktop w-4 h-4"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-[10px] text-gray-400 font-bold uppercase">Progres Ujian</div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-bold text-orange-700">{{ round($percentage) }}%</span>
                                <span class="text-[10px] text-gray-400 font-medium">Terselesaikan</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-orange-600 rounded-full transition-all duration-700 ease-out"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Logs -->
                <div class="p-5 flex-1 overflow-y-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Aktivitas Sesi</h4>
                    </div>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div
                                class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-none mt-0.5">
                                <i class="fas fa-check-circle w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-700">Status Streaming</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Streaming aktif dan terpantau</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div
                                class="w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center flex-none mt-0.5">
                                <i class="fas fa-sync-alt w-3.5 h-3.5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-700">Auto-sync Aktif</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Sinkronisasi data otomatis berjalan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-subtle {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-5px);
        }
    }

    .animate-bounce-subtle {
        animation: bounce-subtle 2s infinite ease-in-out;
    }

    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>