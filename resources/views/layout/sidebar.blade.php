<!-- Sidebar Container with Flex Structure -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 flex flex-col bg-white/80 backdrop-blur-sm w-64 border-r border-gray-100 shadow-lg z-40 transition-transform duration-300 ease-in-out transform translate-x-0">
    <!-- Sidebar content -->

    <!-- Logo Section -->
    <div class="flex-shrink-0 h-16 flex items-center gap-3 px-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-[#1E3A8A]">Mediction</h2>
            <p class="text-xs text-gray-500">Healthcare System</p>
        </div>
    </div>

    <!-- Scrollable Menu Section -->
    <div class="flex-1 overflow-y-auto" id="sidebar-menu">
        <div class="p-2">
            <nav class="space-y-1">
                <!-- Dashboard -->
                <div>
                    <a href="/user"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-light fa-house mr-2 text-lg {{ Request::is('user') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </div>
                    </a>
                </div>

                <!-- Divider: MENU PEMBELIAN -->
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Konsultasi
                    </div>
                </div>

                <div>
                    <a href="/user/consultation/patient"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/consultation/patient*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-user mr-2 text-lg {{ Request::is('user/consultation/patient*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pasien</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/consultation/consultation"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/consultation/consultation*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-stethoscope mr-2 text-lg {{ Request::is('user/consultation/consultation*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Konsultasi</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/consultation/history"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/consultation/history*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-clock-rotate-left mr-2 text-lg {{ Request::is('user/consultation/history*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Histori Konsultasi</span>
                        </div>
                    </a>
                </div>

                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Logistik
                    </div>
                </div>

                <div>
                    <a href="/user/purchase/defecta"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/purchase/defecta') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-light-emergency-on mr-2 text-lg {{ Request::is('user/purchase/defecta') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Defecta</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/purchase/draft-mail-order"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/purchase/draft-mail-order') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-regular fa-file-lines mr-2 text-lg {{ Request::is('user/purchase/draft-mail-order') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Draft Surat Pesanan</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/purchase/mail-order"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/purchase/mail-order*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-envelope-open-text mr-2 text-lg {{ Request::is('user/purchase/mail-order*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Surat Pesanan</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/good-come"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/good-come*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-check-circle mr-2 text-lg {{ Request::is('user/logistic/good-come*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penerimaan Barang</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/return"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/return*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-envelope mr-2 text-lg {{ Request::is('user/logistic/return*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Retur Pembelian</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/product-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/product-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-warehouse mr-2 text-lg {{ Request::is('user/logistic/product-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok Produk</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/stock-in"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/stock-in*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-inbox-in mr-2 text-lg {{ Request::is('user/logistic/stock-in*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok Masuk</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/stock-out"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/stock-out*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-inbox-out mr-2 text-lg {{ Request::is('user/logistic/stock-out*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok Keluar</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/import-stock-product"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/import-stock-product*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-up-to-line mr-2 text-lg {{ Request::is('user/logistic/import-stock-product*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Import Stok Barang</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/logistic/stock-product"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/stock-product*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-pen-to-square mr-2 text-lg {{ Request::is('user/logistic/stock-product*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stock Opname</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/logistic/dead-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/logistic/dead-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-skull-crossbones mr-2 text-lg {{ Request::is('user/logistic/dead-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dead Stock</span>
                        </div>
                    </a>
                </div>

                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Instalasi Farmasi
                    </div>
                </div>

                <div>
                    <a href="/user/pharmacy/consultation"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/pharmacy/consultation*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-stethoscope mr-2 text-lg {{ Request::is('user/pharmacy/consultation*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Konsultasi</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/pharmacy/sale"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/pharmacy/sale*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-cash-register mr-2 text-lg {{ Request::is('user/pharmacy/sale*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penjualan</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/pharmacy/take-medicine"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/pharmacy/take-medicine*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-pills mr-2 text-lg {{ Request::is('user/pharmacy/take-medicine*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pengambilan Obat</span>
                        </div>
                    </a>
                </div>

                <!-- Divider: Kasir -->
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Kasir
                    </div>
                </div>

                <div>
                    <a href="/user/sale/price"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/sale/price*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-money-check-dollar-pen mr-2 text-lg {{ Request::is('user/sale/price*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Update Harga Jual</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/sale/product-price"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/sale/product-price*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-money-check-dollar mr-2 text-lg {{ Request::is('user/sale/product-price*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Harga Jual</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/sale/pos" target="_blank"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/sale/pos*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-cash-register mr-2 text-lg {{ Request::is('user/sale/pos*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">POS</span>
                        </div>
                    </a>
                </div>

                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Finance
                    </div>
                </div>

                {{-- <div>
                    <a href="/user/finance/cost"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/cost*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-money-check-dollar-pen mr-2 text-lg {{ Request::is('user/finance/cost*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Biaya</span>
                        </div>
                    </a>
                </div> --}}
                <div>
                    <a href="/user/finance/sale"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/sale*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-cash-register mr-2 text-lg {{ Request::is('user/finance/sale*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penjualan</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/finance/purchase"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/purchase*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-envelope-open-text mr-2 text-lg {{ Request::is('user/finance/purchase*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pembelian</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/finance/dead-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/dead-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-skull-crossbones mr-2 text-lg {{ Request::is('user/finance/dead-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dead Stock</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/finance/stock-opname"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/stock-opname*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-box mr-2 text-lg {{ Request::is('user/finance/stock-opname*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stock Opname</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/finance/finance"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/finance*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-wallet mr-2 text-lg {{ Request::is('user/finance/finance*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Keuangan</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/finance/balance-sheet"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/balance-sheet*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-scale-balanced mr-2 text-lg {{ Request::is('user/finance/balance-sheet*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Neraca Keuangan</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/finance/profit-loss"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/profit-loss*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-file-invoice-dollar mr-2 text-lg {{ Request::is('user/finance/profit-loss*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Laba Rugi</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/finance/cash-flow"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/cash-flow*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-money-bill-transfer mr-2 text-lg {{ Request::is('user/finance/cash-flow*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Arus Kas</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/finance/ledger"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/ledger*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-book mr-2 text-lg {{ Request::is('user/finance/ledger*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Buku Besar</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/finance/journal"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/finance/journal*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-clipboard-list mr-2 text-lg {{ Request::is('user/finance/journal*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Jurnal</span>
                        </div>
                    </a>
                </div>

                <!-- Divider: Master -->
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Laporan
                    </div>
                </div>

                <div>
                    <a href="/user/report/incentive"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/incentive*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-coins mr-2 text-lg {{ Request::is('user/report/incentive*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Insentif</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-warehouse mr-2 text-lg {{ Request::is('user/report/stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/in-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/in-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-inbox-in mr-2 text-lg {{ Request::is('user/report/in-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok Masuk</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/out-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/out-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-inbox-out mr-2 text-lg {{ Request::is('user/report/out-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stok Keluar</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/purchase"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/purchase*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-cart-shopping mr-2 text-lg {{ Request::is('user/report/purchase*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pembelian</span>
                        </div>
                    </a>
                </div>

                {{-- <div>
                    <a href="/user/report/return-purchase"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/return-purchase*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-arrow-circle-left mr-2 text-lg {{ Request::is('user/report/return-purchase*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Retur Pembelian</span>
                        </div>
                    </a>
                </div> --}}

                <div>
                    <a href="/user/report/product-purchase"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/product-purchase*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-box-open mr-2 text-lg {{ Request::is('user/report/product-purchase*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pembelian Produk</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/goods-come"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/goods-come*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-truck mr-2 text-lg {{ Request::is('user/report/goods-come*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penerimaan Barang</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/sale"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/sale*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-cash-register mr-2 text-lg {{ Request::is('user/report/sale*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penjualan</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/product-sale"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/product-sale*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-boxes mr-2 text-lg {{ Request::is('user/report/product-sale*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Penjualan Produk</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/payment"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/payment*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-money-bill-wave mr-2 text-lg {{ Request::is('user/report/payment*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Terima Bayar</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/profit-loss"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/profit-loss*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-file-invoice-dollar mr-2 text-lg {{ Request::is('user/report/profit-loss*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Laba Rugi</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/dead-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/dead-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-skull-crossbones mr-2 text-lg {{ Request::is('user/report/dead-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dead Stock</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/opname-stock"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/opname-stock*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-clipboard-check mr-2 text-lg {{ Request::is('user/report/opname-stock*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stock Opname</span>
                        </div>
                    </a>
                </div>

                <div>
                    <a href="/user/report/product-stock-opname"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/report/product-stock-opname*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-clipboard-list mr-2 text-lg {{ Request::is('user/report/product-stock-opname*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Stock Opname Produk</span>
                        </div>
                    </a>
                </div>

                <!-- Divider: Master -->
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Master
                    </div>
                </div>
                <!-- Registration -->
                <div>
                    <button type="button"
                        class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg cursor-pointer {{ request()->is('user/master/product*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200"
                        onclick="togglemenu('master-product')">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 mr-2 {{ request()->is('user/master/product*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9.75L12 3l9 6.75M4.5 10.5V21h15V10.5M9 21V12h6v9" />
                            </svg>
                            <span>Produk</span>
                        </div>
                        <svg class="w-4 h-4 menu-arrow {{ request()->is('user/master/product*') ? 'rotate text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"
                            id="master-product-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="submenu p-1 pl-2 space-y-1 {{ request()->is('user/master/product*') ? 'open' : '' }}"
                        id="master-product">
                        <a href="/user/master/product/detail"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/detail', 'user/master/product/detail/data') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/detail', 'user/master/product/detail/data') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Detail Produk
                        </a>
                        <a href="/user/master/product/package"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/package', 'user/master/product/package/data') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/package', 'user/master/product/package/data') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Paket Produk
                        </a>
                        <a href="/user/master/product/category"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/category') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/category') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Kategori Produk
                        </a>
                        <a href="/user/master/product/factory"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/factory') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/factory') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Pabrik Produk
                        </a>
                        <a href="/user/master/product/rack"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/rack') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/rack') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Rak Produk
                        </a>
                        <a href="/user/master/product/unit"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/product/unit') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/product/unit') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Satuan Produk
                        </a>
                    </div>
                </div>
                <div>
                    <a href="/user/master/recipe"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/recipe*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-file-prescription mr-2 text-lg {{ Request::is('user/master/recipe*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Resep</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/action"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/action*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-stethoscope mr-2 text-lg {{ Request::is('user/master/action*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Tindakan</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/medicine-type"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/medicine-type*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-tablets mr-2 text-lg {{ Request::is('user/master/medicine-type*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Jenis Resep</span>
                        </div>
                    </a>
                </div>
                <div>
                    <button type="button"
                        class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg cursor-pointer {{ request()->is('user/master/account*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200"
                        onclick="togglemenu('master-account')">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-scale-balanced mr-2 text-lg {{ Request::is('user/master/account*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span>Akun Biaya</span>
                        </div>
                        <svg class="w-4 h-4 menu-arrow {{ request()->is('user/master/account*') ? 'rotate text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"
                            id="master-account-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div class="submenu p-1 pl-2 space-y-1 {{ request()->is('user/master/account*') ? 'open' : '' }}"
                        id="master-account">
                        <a href="/user/master/account/account"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/account/account') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/account/account') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Akun Biaya
                        </a>
                        <a href="/user/master/account/category-account"
                            class="group flex items-center gap-3 px-4 w-full  py-2 text-sm font-medium rounded-lg {{ request()->is('user/master/account/category-account') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <span
                                class="w-1.5 h-1.5 mr-2 {{ request()->is('user/master/account/category-account') ? 'bg-[#1E3A8A]' : 'bg-gray-400 group-hover:bg-[#1E3A8A]' }} rounded-full"></span>
                            Kategori Akun Biaya
                        </a>
                    </div>
                </div>
                <div>
                    <a href="/user/master/poly"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/poly*') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-hospital-alt mr-2 text-lg {{ Request::is('user/master/poly*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Poli</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/doctor-control"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/doctor-control') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-timer mr-2 text-lg {{ Request::is('user/master/doctor-control') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Jadwal Doktor</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/how-to-use"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/how-to-use') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-hourglass-half mr-2 text-lg {{ Request::is('user/master/how-to-use') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Aturan Pakai</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/supplier"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/supplier') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <!-- Users Icon -->
                            <svg class="w-5 h-5 mr-2 {{ Request::is('user/master/supplier') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }} shrink-0"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m9-7a4 4 0 11-8 0 4 4 0 018 0zM12 14a4 4 0 00-4 4v2h8v-2a4 4 0 00-4-4z" />
                            </svg>
                            <span class="sidebar-text">Supplier</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/discount"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/discount') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-percent mr-2 text-lg {{ Request::is('user/master/discount*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Diskon</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/promotion"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/promotion') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-bullhorn mr-2 text-lg {{ Request::is('user/master/promotion*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Promo</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/icd"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/icd') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-disease mr-2 text-lg {{ Request::is('user/master/icd*') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">ICD</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/payment-method"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/payment-method') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-credit-card-alt mr-2 text-lg {{ Request::is('user/master/payment-method') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Metode Pembayaran</span>
                        </div>
                    </a>
                </div>
                @if (Auth::user()->company->is_main)
                    <div>
                        <a href="/user/master/service-month"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/service-month') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid fa-tags mr-2 text-lg {{ Request::is('user/master/service-month') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                                <span class="sidebar-text">Service</span>
                            </div>
                        </a>
                    </div>
                @endif
                <div>
                    <a href="/user/master/patient"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/patient') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-user mr-2 text-lg {{ Request::is('user/master/patient') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Pasien</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/doctor"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/doctor') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-user-md mr-2 text-lg {{ Request::is('user/master/doctor') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dokter</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/user"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/user') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-users-medical mr-2 text-lg {{ Request::is('user/master/user') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">User</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/role"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/role') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-tag mr-2 text-lg {{ Request::is('user/master/role') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Role</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/user/master/setting"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('user/master/setting') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 mr-2 {{ Request::is('user/master/setting') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }} shrink-0"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                            </svg>

                            <span class="sidebar-text">Pengaturan</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Logout Button (Fixed) -->
    {{-- <div class="flex-shrink-0 border-t bg-white/80 backdrop-blur-sm border-gray-100">
        <div class="p-2">
            <a href="/logout" class="group flex items-center gap-3 px-4 py-2 rounded-xl text-red-600 hover:bg-red-50 transition-all duration-200">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="font-medium">Keluar</span>
            </a>
        </div>
    </div> --}}
</aside>
