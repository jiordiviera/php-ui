@props([
    'columns' => [],
    'data' => [],
    'searchable' => true,
    'filterable' => true,
    'sortable' => true,
    'paginated' => true,
    'perPage' => 10,
    'loading' => false,
    'emptyMessage' => 'No data available',
    'searchPlaceholder' => 'Search...',
])

@php
$tableId = 'data-table-' . uniqid();
@endphp

<div
    {{ $attributes->merge(['class' => 'w-full']) }}
    x-data="{
        search: '',
        sortColumn: null,
        sortDirection: 'asc',
        filters: {},
        currentPage: 1,
        perPage: {{ $perPage }},
        loading: {{ $loading ? 'true' : 'false' }},
        data: {{ json_encode($data) }},
        columns: {{ json_encode($columns) }},

        get filteredData() {
            let result = [...this.data];

            // Apply search
            if (this.search) {
                const searchLower = this.search.toLowerCase();
                result = result.filter(row => {
                    return this.columns.some(col => {
                        const value = row[col.key];
                        return value && String(value).toLowerCase().includes(searchLower);
                    });
                });
            }

            // Apply column filters
            Object.keys(this.filters).forEach(key => {
                const filterValue = this.filters[key];
                if (filterValue && filterValue !== 'all') {
                    result = result.filter(row => String(row[key]) === String(filterValue));
                }
            });

            // Apply sorting
            if (this.sortColumn) {
                result.sort((a, b) => {
                    const aVal = a[this.sortColumn] ?? '';
                    const bVal = b[this.sortColumn] ?? '';

                    if (typeof aVal === 'number' && typeof bVal === 'number') {
                        return this.sortDirection === 'asc' ? aVal - bVal : bVal - aVal;
                    }

                    const comparison = String(aVal).localeCompare(String(bVal));
                    return this.sortDirection === 'asc' ? comparison : -comparison;
                });
            }

            return result;
        },

        get paginatedData() {
            const start = (this.currentPage - 1) * this.perPage;
            return this.filteredData.slice(start, start + this.perPage);
        },

        get totalPages() {
            return Math.ceil(this.filteredData.length / this.perPage);
        },

        get pageNumbers() {
            const total = this.totalPages;
            const current = this.currentPage;
            const pages = [];

            if (total <= 7) {
                for (let i = 1; i <= total; i++) pages.push(i);
            } else if (current <= 3) {
                pages.push(1, 2, 3, 4, '...', total);
            } else if (current >= total - 2) {
                pages.push(1, '...', total - 3, total - 2, total - 1, total);
            } else {
                pages.push(1, '...', current - 1, current, current + 1, '...', total);
            }

            return pages;
        },

        get showingFrom() {
            return this.filteredData.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
        },

        get showingTo() {
            return Math.min(this.currentPage * this.perPage, this.filteredData.length);
        },

        sort(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
        },

        setFilter(column, value) {
            this.filters[column] = value;
            this.currentPage = 1;
        },

        getUniqueValues(column) {
            const values = new Set();
            this.data.forEach(row => {
                if (row[column] !== null && row[column] !== undefined) {
                    values.add(row[column]);
                }
            });
            return Array.from(values).sort();
        },

        resetFilters() {
            this.search = '';
            this.filters = {};
            this.sortColumn = null;
            this.sortDirection = 'asc';
            this.currentPage = 1;
        },

        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },

        previousPage() {
            if (this.currentPage > 1) this.currentPage--;
        },

        nextPage() {
            if (this.currentPage < this.totalPages) this.currentPage++;
        }
    }"
    x-init="$watch('search', () => currentPage = 1)"
    data-test="data-table-container"
>
    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-4" data-test="data-table-toolbar">
        {{-- Search --}}
        @if($searchable)
            <div class="relative flex-1 max-w-sm">
                <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                <input
                    type="text"
                    x-model.debounce.300ms="search"
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-background border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring transition-colors"
                    data-test="data-table-search"
                />
            </div>
        @endif

        {{-- Column Filters --}}
        @if($filterable)
            <div class="flex flex-wrap gap-2" data-test="data-table-filters">
                <template x-for="col in columns.filter(c => c.filterable)" :key="col.key">
                    <div class="relative">
                        <select
                            x-model="filters[col.key]"
                            @change="setFilter(col.key, $event.target.value)"
                            class="appearance-none pl-3 pr-8 py-2 text-sm bg-background border border-input rounded-lg focus:outline-none focus:ring-2 focus:ring-ring cursor-pointer"
                        >
                            <option value="all" x-text="`All ${col.label}`"></option>
                            <template x-for="val in getUniqueValues(col.key)" :key="val">
                                <option :value="val" x-text="val"></option>
                            </template>
                        </select>
                        <x-lucide-chevron-down class="absolute right-2 top-1/2 -translate-y-1/2 size-4 text-muted-foreground pointer-events-none" />
                    </div>
                </template>

                {{-- Reset Filters --}}
                <button
                    type="button"
                    @click="resetFilters()"
                    x-show="search || Object.values(filters).some(v => v && v !== 'all') || sortColumn"
                    x-transition
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-muted-foreground hover:text-foreground bg-muted/50 hover:bg-muted rounded-lg transition-colors"
                    data-test="data-table-reset"
                >
                    <x-lucide-x class="size-3.5" />
                    Reset
                </button>
            </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto border border-border rounded-xl" data-test="data-table-wrapper">
        <table class="w-full table-auto caption-bottom text-sm" data-test="data-table">
            <thead class="bg-muted/50 [&_tr]:border-b">
                <tr class="hover:bg-muted/50 data-[state=selected]:bg-muted border-b transition-colors">
                    <template x-for="col in columns" :key="col.key">
                        <th
                            class="px-4 py-3 text-left text-sm font-semibold text-foreground w-[100px]"
                            :class="{ 'cursor-pointer select-none hover:bg-muted/80 transition-colors': col.sortable !== false && {{ $sortable ? 'true' : 'false' }} }"
                            @click="col.sortable !== false && {{ $sortable ? 'true' : 'false' }} ? sort(col.key) : null"
                        >
                            <div class="flex items-center gap-2">
                                <span x-text="col.label"></span>
                                <template x-if="col.sortable !== false && {{ $sortable ? 'true' : 'false' }}">
                                    <div class="flex flex-col">
                                        <x-lucide-chevron-up
                                            class="size-3 -mb-1"
                                            ::class="sortColumn === col.key && sortDirection === 'asc' ? 'text-primary' : 'text-muted-foreground/40'"
                                        />
                                        <x-lucide-chevron-down
                                            class="size-3"
                                            ::class="sortColumn === col.key && sortDirection === 'desc' ? 'text-primary' : 'text-muted-foreground/40'"
                                        />
                                    </div>
                                </template>
                            </div>
                        </th>
                    </template>
                </tr>
            </thead>

            <tbody class="divide-y divide-border">
                {{-- Loading State --}}
                <template x-if="loading">
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                            <template x-for="col in columns" :key="col.key + '-skeleton-{{ $i }}'">
                                <td class="px-4 py-3">
                                    <div class="h-4 bg-muted animate-pulse rounded"></div>
                                </td>
                            </template>
                        </tr>
                    @endfor
                </template>

                {{-- Data Rows --}}
                <template x-if="!loading">
                    <template x-for="(row, index) in paginatedData" :key="index">
                        <tr class="hover:bg-muted/30 transition-colors" data-test="data-table-row">
                            <template x-for="col in columns" :key="col.key">
                                <td class="px-4 py-3 text-sm text-foreground" x-html="row[col.key] ?? '-'"></td>
                            </template>
                        </tr>
                    </template>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && paginatedData.length === 0">
                    <tr>
                        <td :colspan="columns.length" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <x-lucide-inbox class="size-12 text-muted-foreground/50" />
                                <p class="text-muted-foreground">{{ $emptyMessage }}</p>
                                <button
                                    type="button"
                                    @click="resetFilters()"
                                    x-show="search || Object.values(filters).some(v => v && v !== 'all')"
                                    class="text-sm text-primary hover:underline"
                                >
                                    Clear filters
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($paginated)
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-4" x-show="filteredData.length > 0" data-test="data-table-pagination">
            {{-- Info --}}
            <div class="text-sm text-muted-foreground" data-test="data-table-info">
                Showing <span class="font-medium text-foreground" x-text="showingFrom"></span> to
                <span class="font-medium text-foreground" x-text="showingTo"></span> of
                <span class="font-medium text-foreground" x-text="filteredData.length"></span> results
            </div>

            {{-- Controls --}}
            <div class="flex items-center gap-1" data-test="data-table-page-controls">
                {{-- Previous --}}
                <button
                    type="button"
                    @click="previousPage()"
                    :disabled="currentPage <= 1"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-border bg-background text-foreground transition-colors hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-background"
                >
                    <x-lucide-chevron-left class="size-4" />
                    <span class="hidden sm:inline">Previous</span>
                </button>

                {{-- Page Numbers --}}
                <div class="hidden sm:flex items-center gap-1">
                    <template x-for="page in pageNumbers" :key="page">
                        <template x-if="page === '...'">
                            <span class="px-2 py-1 text-sm text-muted-foreground">...</span>
                        </template>
                        <template x-if="page !== '...'">
                            <button
                                type="button"
                                @click="goToPage(page)"
                                class="min-w-[36px] px-2 py-1.5 text-sm font-medium rounded-lg border transition-colors"
                                :class="currentPage === page
                                    ? 'bg-primary text-primary-foreground border-primary'
                                    : 'bg-background text-foreground border-border hover:bg-accent hover:text-accent-foreground'"
                                x-text="page"
                            ></button>
                        </template>
                    </template>
                </div>

                {{-- Mobile Page Indicator --}}
                <span class="sm:hidden px-3 py-2 text-sm text-muted-foreground">
                    Page <span x-text="currentPage" class="font-medium text-foreground"></span> of <span x-text="totalPages" class="font-medium text-foreground"></span>
                </span>

                {{-- Next --}}
                <button
                    type="button"
                    @click="nextPage()"
                    :disabled="currentPage >= totalPages"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-lg border border-border bg-background text-foreground transition-colors hover:bg-accent hover:text-accent-foreground disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-background"
                >
                    <span class="hidden sm:inline">Next</span>
                    <x-lucide-chevron-right class="size-4" />
                </button>
            </div>
        </div>
    @endif
</div>
