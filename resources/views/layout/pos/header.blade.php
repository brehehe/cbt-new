<!-- Fixed Header -->
@php
    use App\Models\Cash\Cash;
    $cash = Cash::where('user_id', Auth::id())
        ->where('company_id', Auth::user()->company_id)
        ->where('is_active', true)
        ->first();
@endphp
<header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50 px-6 py-3">
    <div class="max-w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Left: Back Button, Logo & Title -->
        <div class="flex items-center gap-4">
            <!-- Back Button -->
            @if (Request::is('user/sale/pos/detail') || Request::is('user/sale/pos/recipe'))
                <a href="/user/sale/pos" class="text-[#1E3A8A] hover:text-blue-700 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
            @endif

            <h1 class="font-bold text-xl text-[#1E3A8A]">
                <i class="fas fa-pills mr-2"></i>Mediction
            </h1>
        </div>

        <!-- Center: Date & Time and Cashbank Info -->
        <div class="flex flex-col items-center gap-2">
            <!-- Date & Time -->
            <div class="text-sm text-gray-500" id="currentDateTime">
                <i class="far fa-clock mr-1"></i>
                <span>Senin, 01 Januari 1900 00:00:00 WIB</span>
            </div>
        </div>

        <!-- Right: User Info -->
        <div class="flex items-center gap-4">
            <div class="text-sm text-gray-600">
                <i class="fas fa-money-bill-wave mr-1"></i>
                <span id="currentCashbank">Rp {{ number_format($cash->amount_real ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="far fa-user mr-1"></i>
                <span id="currentUser">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
</header>
