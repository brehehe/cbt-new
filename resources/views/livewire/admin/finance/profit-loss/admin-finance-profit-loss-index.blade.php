<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Laba Rugi</h1>
            </div>
            <div>
                Rp {{ number_format($grandTotal['total'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" wire:model.live="start_date" placeholder="Contoh: Dari Tanggal"
                        class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" wire:model.live="end_date" placeholder="Contoh: Sampai Tanggal"
                        class="mt-1 form-control" />
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle All Button -->
    <div class="mb-4">
        <button id="toggleAllBtn" onclick="TableController.toggleAll()"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <span id="toggleAllText">Buka Semua</span>
        </button>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailCategoryAccounts as $index => $detailCategoryAccount)
                        {{-- Level 1: Detail Category --}}
                        <tr class="cursor-pointer hover:bg-gray-100 transition-colors category-row"
                            data-category-index="{{ $index }}"
                            onclick="TableController.toggleCategory('{{ $index }}')">
                            <td class="center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 transition-transform duration-200 category-arrow"
                                        data-category-arrow="{{ $index }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="font-medium">{{ $detailCategoryAccount ?? '-' }}</span>
                                </div>
                            </td>
                            <td>Rp {{ number_format($detailCategoryTotals[$index]['total_debit'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td>Rp {{ number_format($detailCategoryTotals[$index]['total_credit'] ?? 0, 0, ',', '.') }}
                            </td>
                            <td>Rp {{ number_format($detailCategoryTotals[$index]['total'] ?? 0, 0, ',', '.') }}</td>
                            <td>
                                <button
                                    onclick="event.stopPropagation(); TableController.toggleCategory('{{ $index }}')"
                                    class="text-blue-500 hover:text-blue-700 font-medium transition-colors">
                                    <span class="category-btn-text" data-category-btn="{{ $index }}">Buka</span>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 2: Category Accounts --}}
                        @foreach ($categoryAccounts[$index] as $key => $categoryAccount)
                            <tr class="bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors subcategory-row"
                                data-parent-category="{{ $index }}"
                                data-subcategory-key="{{ $index }}_{{ $key }}" style="display: none;"
                                onclick="TableController.toggleSubCategory('{{ $index }}', '{{ $key }}')">
                                <td class="center pl-6">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="flex items-center gap-2 pl-4">
                                        <svg class="w-4 h-4 transition-transform duration-200 subcategory-arrow"
                                            data-subcategory-arrow="{{ $index }}_{{ $key }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                        <span class="font-medium text-gray-700">{{ $categoryAccount ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>Rp
                                    {{ number_format($categoryAccountTotals[$index][$key]['total_debit'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td>Rp
                                    {{ number_format($categoryAccountTotals[$index][$key]['total_credit'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td>Rp
                                    {{ number_format($categoryAccountTotals[$index][$key]['total'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    <button
                                        onclick="event.stopPropagation(); TableController.toggleSubCategory('{{ $index }}', '{{ $key }}')"
                                        class="text-blue-500 hover:text-blue-700 font-medium transition-colors text-sm">
                                        <span class="subcategory-btn-text"
                                            data-subcategory-btn="{{ $index }}_{{ $key }}">Buka</span>
                                    </button>
                                </td>
                            </tr>

                            {{-- Level 3: Individual Accounts --}}
                            @foreach ($accounts[$index][$key] as $key_account => $account)
                                <tr class="bg-blue-50/50 hover:bg-blue-100/50 transition-colors account-row"
                                    data-parent-subcategory="{{ $index }}_{{ $key }}"
                                    style="display: none;">
                                    <td class="center pl-8">{{ $loop->iteration }}</td>
                                    <td class="pl-8">
                                        <span class="text-gray-600">{{ $account ?? '-' }}</span>
                                    </td>
                                    <td>Rp
                                        {{ number_format($accountTotals[$index][$key][$key_account]['total_debit'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>Rp
                                        {{ number_format($accountTotals[$index][$key][$key_account]['total_credit'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>Rp
                                        {{ number_format($accountTotals[$index][$key][$key_account]['total'] ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="text-gray-400 text-sm">Detail</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="no-data text-center py-8 text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7">
                                        </path>
                                    </svg>
                                    <span>Tidak ada data</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        class TableController {
            static openCategories = {};
            static openSubCategories = {};
            static allExpanded = false;

            static init() {
                console.log('Pure JS Table Controller initialized');

                // Initialize all categories from DOM
                document.querySelectorAll('[data-category-index]').forEach(element => {
                    const index = element.getAttribute('data-category-index');
                    this.openCategories[index] = false;
                });

                // Initialize all subcategories from DOM
                document.querySelectorAll('[data-subcategory-key]').forEach(element => {
                    const key = element.getAttribute('data-subcategory-key');
                    this.openSubCategories[key] = false;
                });

                console.log('Initialized categories:', Object.keys(this.openCategories));
                console.log('Initialized subcategories:', Object.keys(this.openSubCategories));
            }

            static toggleCategory(index) {
                // Initialize if not exists
                if (this.openCategories[index] === undefined) {
                    this.openCategories[index] = false;
                }

                // Toggle state
                this.openCategories[index] = !this.openCategories[index];
                const isOpen = this.openCategories[index];

                console.log(`Toggling category ${index} to ${isOpen ? 'open' : 'closed'}`);

                // Update UI
                this.updateCategoryUI(index, isOpen);

                // If closing category, also close all subcategories
                if (!isOpen) {
                    for (let key in this.openSubCategories) {
                        if (key.startsWith(index + '_')) {
                            this.openSubCategories[key] = false;
                            this.updateSubCategoryUI(key, false);
                        }
                    }
                }

                this.updateToggleAllButton();
            }

            static toggleSubCategory(categoryIndex, subIndex) {
                const key = `${categoryIndex}_${subIndex}`;

                // Initialize if not exists
                if (this.openSubCategories[key] === undefined) {
                    this.openSubCategories[key] = false;
                }

                // Toggle state
                this.openSubCategories[key] = !this.openSubCategories[key];
                const isOpen = this.openSubCategories[key];

                console.log(`Toggling subcategory ${key} to ${isOpen ? 'open' : 'closed'}`);

                // Update UI
                this.updateSubCategoryUI(key, isOpen);
                this.updateToggleAllButton();
            }

            static updateCategoryUI(index, isOpen) {
                // Update arrow rotation using data attribute
                const arrow = document.querySelector(`[data-category-arrow="${index}"]`);
                if (arrow) {
                    arrow.style.transform = isOpen ? 'rotate(90deg)' : 'rotate(0deg)';
                }

                // Update button text using data attribute
                const btnText = document.querySelector(`[data-category-btn="${index}"]`);
                if (btnText) {
                    btnText.textContent = isOpen ? 'Tutup' : 'Buka';
                }

                // Show/hide subcategory rows using data attribute
                const subcategoryRows = document.querySelectorAll(`[data-parent-category="${index}"]`);
                subcategoryRows.forEach(row => {
                    if (isOpen) {
                        row.style.display = 'table-row';
                        // Add transition effect
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.style.transition = 'opacity 0.2s ease-out';
                            row.style.opacity = '1';
                        }, 10);
                    } else {
                        row.style.transition = 'opacity 0.2s ease-out';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.style.display = 'none';
                        }, 200);
                    }
                });
            }

            static updateSubCategoryUI(key, isOpen) {
                // Update arrow rotation using data attribute
                const arrow = document.querySelector(`[data-subcategory-arrow="${key}"]`);
                if (arrow) {
                    arrow.style.transform = isOpen ? 'rotate(90deg)' : 'rotate(0deg)';
                }

                // Update button text using data attribute
                const btnText = document.querySelector(`[data-subcategory-btn="${key}"]`);
                if (btnText) {
                    btnText.textContent = isOpen ? 'Tutup' : 'Buka';
                }

                // Show/hide account rows using data attribute
                const accountRows = document.querySelectorAll(`[data-parent-subcategory="${key}"]`);
                accountRows.forEach(row => {
                    if (isOpen) {
                        row.style.display = 'table-row';
                        // Add transition effect
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.style.transition = 'opacity 0.2s ease-out';
                            row.style.opacity = '1';
                        }, 10);
                    } else {
                        row.style.transition = 'opacity 0.2s ease-out';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.style.display = 'none';
                        }, 200);
                    }
                });
            }

            static toggleAll() {
                this.allExpanded = !this.allExpanded;

                // Initialize if needed
                if (Object.keys(this.openCategories).length === 0) {
                    this.init();
                }

                console.log(`Toggle All: ${this.allExpanded ? 'Expanding' : 'Collapsing'} all items`);

                // Toggle all categories
                Object.keys(this.openCategories).forEach(index => {
                    this.openCategories[index] = this.allExpanded;
                    this.updateCategoryUI(index, this.allExpanded);
                });

                // Toggle all subcategories
                Object.keys(this.openSubCategories).forEach(key => {
                    this.openSubCategories[key] = this.allExpanded;
                    this.updateSubCategoryUI(key, this.allExpanded);
                });

                this.updateToggleAllButton();
            }

            static updateToggleAllButton() {
                const totalCategories = Object.keys(this.openCategories).length;
                const totalSubCategories = Object.keys(this.openSubCategories).length;

                if (totalCategories === 0 && totalSubCategories === 0) {
                    this.allExpanded = false;
                } else {
                    const allCategoriesExpanded = Object.values(this.openCategories).every(state => state === true);
                    const allSubCategoriesExpanded = Object.values(this.openSubCategories).every(state => state ===
                        true);
                    this.allExpanded = allCategoriesExpanded && allSubCategoriesExpanded && totalCategories > 0;
                }

                const toggleAllText = document.getElementById('toggleAllText');
                if (toggleAllText) {
                    toggleAllText.textContent = this.allExpanded ? 'Tutup Semua' : 'Buka Semua';
                }
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded - Initializing TableController');
            TableController.init();
        });

        // For Livewire compatibility - reinitialize after Livewire updates
        document.addEventListener('livewire:navigated', function() {
            console.log('Livewire navigated - Reinitializing TableController');
            setTimeout(() => {
                TableController.init();
            }, 100);
        });

        // Additional event listener for Livewire content updates
        document.addEventListener('livewire:updated', function() {
            console.log('Livewire updated - Reinitializing TableController');
            setTimeout(() => {
                TableController.init();
            }, 100);
        });
    </script>
@endpush
