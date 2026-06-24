<div class="w-full h-full overflow-hidden flex flex-col bg-gray-50 text-gray-900 font-sans">
    @if($currentStep === 2)
        @include('livewire.mahasiswa.onboarding.steps.simulation')
    @else
        <!-- ── Top Header (100% Width) ── -->
        <header class="flex-none flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm z-30">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-orange-600/10">
                    <i class="fas fa-shield-alt text-orange-600"></i>
                </div>
                <div class="leading-none">
                    <div class="font-bold text-sm text-gray-800">Onboarding Mahasiswa</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Sistem Ujian CBT</div>
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-gray-500">
                <span>{{ Auth::user()->name }}</span>
            </div>
        </header>

        <!-- ── Main Grid (Split Screen, Full Height) ── -->
        <div class="flex-grow flex flex-col md:flex-row overflow-hidden">
            <!-- Left Panel: Information & Welcome -->
            <div class="w-full md:w-5/12 bg-gradient-to-br from-orange-600 to-amber-500 text-white p-8 flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-orange-100">Selamat Datang</span>
                        <h1 class="text-3xl font-black leading-tight">Ujian CBT Modern & Terpercaya</h1>
                    </div>
                    <p class="text-sm text-orange-50/95 leading-relaxed font-medium">
                        Sebelum memulai ujian sesungguhnya, mohon lengkapi data profil Anda terlebih dahulu. Data ini akan digunakan untuk kebutuhan verifikasi dan sertifikasi hasil ujian Anda.
                    </p>
                    <div class="space-y-3.5 pt-4">
                        <div class="flex items-center gap-3.5 bg-white/10 p-3.5 rounded-xl border border-white/10">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-sm">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold">1. Isi Data Diri</h4>
                                <p class="text-[10px] text-orange-100/90 leading-tight">Nama, NIM, WhatsApp, dan Domisili</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 bg-white/5 p-3.5 rounded-xl border border-white/5">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-sm text-orange-200/50">
                                <i class="fas fa-desktop"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-orange-200/70">2. Simulasi Ujian</h4>
                                <p class="text-[10px] text-orange-100/50 leading-tight">Uji coba aplikasi dan kamera</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-[10px] text-orange-100/70 mt-6 md:mt-0 font-medium">
                    &copy; {{ date('Y') }} PRO-CBT. All rights reserved.
                </div>
            </div>

            <!-- Right Panel: Form Inputs -->
            <div class="flex-grow bg-white p-8 md:p-12 overflow-y-auto flex flex-col justify-between">
                <div class="max-w-2xl w-full mx-auto space-y-8 my-auto">
                    @include('livewire.mahasiswa.onboarding.steps.profile')
                </div>

                <!-- Action Button at bottom right -->
                <div class="max-w-2xl w-full mx-auto flex justify-end mt-8 border-t border-gray-100 pt-6">
                    <button wire:click="nextStep" wire:loading.attr="disabled"
                        class="px-10 py-3 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-bold shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed group text-sm flex items-center gap-2">
                        <span wire:loading.remove>Lanjutkan <i class="fas fa-arrow-right"></i></span>
                        <span wire:loading><i class="fas fa-spinner animate-spin"></i> Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>