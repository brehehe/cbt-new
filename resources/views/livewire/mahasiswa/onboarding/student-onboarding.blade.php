<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-purple-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-full mx-auto">
        <!-- Progress Bar -->
        <div class="mb-12">
            <div class="flex items-center justify-between">
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-orange-600 h-2.5 rounded-full transition-all duration-500"
                        style="width: {{ ($currentStep / 2) * 100 }}%"></div>
                </div>
            </div>
            <div class="flex justify-between mt-4">
                <div class="text-center">
                    <div
                        class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-500' }} shadow-lg transition-all duration-300">
                        <i class="fas fa-user"></i>
                    </div>
                    <p class="mt-2 text-xs font-semibold {{ $currentStep >= 1 ? 'text-orange-600' : 'text-gray-500' }}">
                        Profil</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-500' }} shadow-lg transition-all duration-300">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <p class="mt-2 text-xs font-semibold {{ $currentStep >= 2 ? 'text-orange-600' : 'text-gray-500' }}">
                        Panduan</p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform transition-all duration-500 hover:shadow-orange-100">
            <div class="p-8 sm:p-12">

                @if($currentStep === 1)
                    @include('livewire.mahasiswa.onboarding.steps.profile')
                @elseif($currentStep === 2)
                    @include('livewire.mahasiswa.onboarding.steps.simulation')
                @endif

                <!-- Actions -->
                <div class="mt-12 flex justify-between">
                    @if($currentStep > 1)
                        <button wire:click="prevStep"
                            class="px-8 py-3 text-gray-600 hover:text-gray-900 font-bold transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </button>
                    @else
                        <div></div>
                    @endif

                    @if($currentStep < 2)
                        <button wire:click="nextStep" wire:loading.attr="disabled"
                            class="px-10 py-3 bg-orange-600 text-white rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed group">
                            <span wire:loading.remove>Lanjutkan <i class="fas fa-arrow-right ml-2"></i></span>
                            <span wire:loading><i class="fas fa-spinner animate-spin mr-2"></i> Memproses...</span>
                        </button>
                    @else
                        <button wire:click="finish" wire:loading.attr="disabled"
                            class="px-10 py-3 bg-orange-600 text-white rounded-xl font-bold shadow-xl shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed group">
                            <span wire:loading.remove>Mulai Simulasikan Ujian <i class="fas fa-rocket ml-2"></i></span>
                            <span wire:loading><i class="fas fa-spinner animate-spin mr-2"></i> Menyiapkan...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <p class="text-center mt-8 text-sm text-gray-500">
            Sistem CBT Modern & Terpercaya &nbsp;&middot;&nbsp; &copy; {{ date('Y') }} PRO-CBT
        </p>
    </div>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>