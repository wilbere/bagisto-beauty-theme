{!! view_render_event('bagisto.shop.categories.view.filters.before') !!}

<!-- Desktop Filters Navigation -->
<div class="w-64 shrink-0 max-lg:hidden">
    <!-- Filters Vue Component -->
    <v-filters
        @filter-applied="setFilters('filter', $event)"
        @filter-clear="clearFilters('filter', $event)"
    >
        <!-- Category Filter Shimmer Effect -->
        <x-shop::shimmer.categories.filters />
    </v-filters>
</div>

<!-- Mobile Filters Navigation -->
<div
    class="fixed bottom-0 inset-x-0 z-30 grid grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-[#ede8dc] bg-[#fdfbf7]/95 px-5 py-2 shadow-lg backdrop-blur-md lg:hidden"
>
    <!-- Filter Drawer -->
    <x-shop::drawer
        position="left"
        width="100%"
        ::is-active="isDrawerActive.filter"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2 px-3 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#18181b]"
                @click="isDrawerActive.filter = true"
            >
                <span class="icon-funnel text-base text-[#9e8464]"></span>
                @lang('shop::app.categories.filters.filter')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header class="border-b border-[#ede8dc] bg-[#fdfbf7] px-5 py-4">
            <div class="flex items-center justify-between w-full">
                <p class="font-editorial text-xl font-bold text-[#18181b]">
                    @lang('shop::app.categories.filters.filters')
                </p>

                <button
                    type="button"
                    class="cursor-pointer text-xs font-semibold text-[#9e8464] hover:text-[#18181b] ltr:mr-8 rtl:ml-8"
                    @click="clearFilters('filter', '')"
                >
                    @lang('shop::app.categories.filters.clear-all')
                </button>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content class="bg-[#fdfbf7] p-5">
            <v-filters
                @filter-applied="setFilters('filter', $event)"
                @filter-clear="clearFilters('filter', $event)"
            >
                <x-shop::shimmer.categories.filters />
            </v-filters>
        </x-slot>
    </x-shop::drawer>

    <!-- Separator -->
    <span class="h-5 w-[1px] bg-[#ede8dc]"></span>

    <!-- Sort Drawer -->
    <x-shop::drawer
        position="bottom"
        width="100%"
        ::is-active="isDrawerActive.toolbar"
    >
        <!-- Drawer Toggler -->
        <x-slot:toggle>
            <div
                class="flex cursor-pointer items-center gap-x-2 px-3 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#18181b]"
                @click="isDrawerActive.toolbar = true"
            >
                <span class="icon-sort text-base text-[#9e8464]"></span>
                @lang('shop::app.categories.filters.sort')
            </div>
        </x-slot>

        <!-- Drawer Header -->
        <x-slot:header class="border-b border-[#ede8dc] bg-[#fdfbf7] px-5 py-4">
            <div class="flex items-center justify-between w-full">
                <p class="font-editorial text-xl font-bold text-[#18181b]">
                    @lang('shop::app.categories.filters.sort')
                </p>
            </div>
        </x-slot>

        <!-- Drawer Content -->
        <x-slot:content class="bg-[#fdfbf7] p-4">
            @include('shop::categories.toolbar')
        </x-slot>
    </x-shop::drawer>
</div>

{!! view_render_event('bagisto.shop.categories.view.filters.after') !!}

@pushOnce('scripts')
    <!-- Filters Vue template -->
    <script
        type="text/x-template"
        id="v-filters-template"
    >
        <!-- Filter Shimmer Effect -->
        <template v-if="isLoading">
            <x-shop::shimmer.categories.filters />
        </template>

        <!-- Filters Container -->
        <template v-else>
            <div class="w-full lg:w-64 lg:shrink-0">
                <!-- Filters Header Container -->
                <div class="flex items-center justify-between border-b border-[#ede8dc] pb-3.5 mb-2 max-lg:hidden">
                    <h3 class="font-editorial text-lg font-bold tracking-tight text-[#18181b]">
                        @lang('shop::app.categories.filters.filters')
                    </h3>

                    <button
                        type="button"
                        class="cursor-pointer text-xs font-medium text-[#9e8464] hover:text-[#18181b] transition-colors"
                        @click="clear()"
                    >
                        @lang('shop::app.categories.filters.clear-all')
                    </button>
                </div>

                <!-- Filters Items Vue Component -->
                <div class="divide-y divide-[#ede8dc]/80">
                    <v-filter-item
                        ref="filterItemComponent"
                        :key="filterIndex"
                        :filter="filter"
                        v-for='(filter, filterIndex) in filters.available'
                        @values-applied="applyFilter(filter, $event)"
                    >
                    </v-filter-item>
                </div>
            </div>
        </template>
    </script>

    <!-- Filter Item Vue template -->
    <script
        type="text/x-template"
        id="v-filter-item-template"
    >
        <x-shop::accordion class="border-none py-1">
            <!-- Filter Item Header -->
            <x-slot:header class="px-0 py-3 cursor-pointer">
                <div class="flex items-center justify-between w-full">
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">
                        @{{ filter.name }}
                    </span>
                </div>
            </x-slot>

            <!-- Filter Item Content -->
            <x-slot:content class="px-0 pt-1 pb-3">
                <!-- Price Range Filter -->
                <ul v-if="filter.type === 'price'">
                    <li>
                        <v-price-filter
                            :key="refreshKey"
                            :default-price-range="appliedValues"
                            :default-attribute-code="filter.code"
                            @set-price-range="applyValue($event)"
                        >
                        </v-price-filter>
                    </li>
                </ul>

                <!-- Checkbox Filter Options -->
                <template v-else>
                    <!-- Search Box For Options -->
                    <div
                        class="mb-3 flex flex-col gap-1"
                        v-if="filter.type !== 'boolean'"
                    >
                        <div class="relative">
                            <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-sm text-[#9e8464] ltr:left-3 rtl:right-3"></div>

                            <input
                                type="text"
                                class="block w-full rounded-full border border-[#ede8dc] bg-[#f7f4ee] py-2 text-xs font-medium text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden ltr:pl-9 ltr:pr-3 rtl:pr-9 rtl:pl-3"
                                placeholder="@lang('shop::app.categories.filters.search.title')"
                                v-model="searchQuery"
                                v-debounce:500="searchOptions"
                            />
                        </div>

                        <p
                            class="mt-1 text-[11px] text-[#71717a] text-right"
                            v-text="
                                '@lang('shop::app.categories.filters.search.results-info', ['currentCount' => 'currentCount', 'totalCount' => 'totalCount'])'
                                    .replace('currentCount', options.length)
                                    .replace('totalCount', meta.total)
                            "
                            v-if="meta && meta.total > 0"
                        >
                        </p>
                    </div>

                    <!-- Filter Options -->
                    <ul class="space-y-1 max-h-56 overflow-y-auto pr-1">
                        <template v-if="options.length">
                            <li
                                :key="`${filter.id}_${option.id}`"
                                v-for="(option, optionIndex) in options"
                            >
                                <div class="group flex cursor-pointer select-none items-center gap-2.5 rounded-lg px-2 py-1.5 transition-colors hover:bg-[#f7f4ee]">
                                    <input
                                        type="checkbox"
                                        :id="`filter_${filter.id}_option_${option.id}`"
                                        class="h-4 w-4 rounded border-[#d1c7bd] text-[#18181b] accent-[#18181b] focus:ring-1 focus:ring-[#9e8464] cursor-pointer"
                                        :value="option.id"
                                        v-model="appliedValues"
                                        @change="applyValue"
                                    />

                                    <label
                                        class="w-full cursor-pointer text-xs font-medium text-[#52525b] group-hover:text-[#18181b]"
                                        :for="`filter_${filter.id}_option_${option.id}`"
                                    >
                                        @{{ option.name }}
                                    </label>
                                </div>
                            </li>
                        </template>

                        <template v-else>
                            <li
                                class="flex flex-col items-center justify-center gap-2 py-3 text-xs text-[#a1a1aa]"
                                v-if="! isLoadingMore"
                            >
                                @lang('shop::app.categories.filters.search.no-options-available')
                            </li>

                            <div
                                class="mt-2 space-y-2"
                                v-else
                            >
                                <div class="shimmer h-4 w-3/4 rounded-sm"></div>
                                <div class="shimmer h-4 w-1/2 rounded-sm"></div>
                                <div class="shimmer h-4 w-2/3 rounded-sm"></div>
                            </div>
                        </template>
                    </ul>

                    <!-- Load More Button -->
                    <div class="flex justify-center pt-2.5" v-if="meta && meta.current_page < meta.last_page">
                        <button
                            type="button"
                            class="cursor-pointer rounded-full border border-[#ede8dc] bg-[#f7f4ee] px-3.5 py-1 text-[11px] font-medium text-[#18181b] transition-colors hover:border-[#9e8464] hover:bg-white"
                            @click="loadMoreOptions"
                            :disabled="isLoadingMore"
                        >
                            <span v-if="isLoadingMore">
                                @lang('shop::app.categories.filters.search.loading')
                            </span>

                            <span v-else>
                                @lang('shop::app.categories.filters.search.load-more')
                            </span>
                        </button>
                    </div>
                </template>
            </x-slot>
        </x-shop::accordion>
    </script>

    <script
        type="text/x-template"
        id="v-price-filter-template"
    >
        <div class="pt-1 pb-2">
            <!-- Price Range Filter Shimmer -->
            <template v-if="isLoading">
                <x-shop::shimmer.range-slider />
            </template>

            <template v-else>
                <x-shop::range-slider
                    ::key="refreshKey"
                    default-type="price"
                    ::default-allowed-max-range="allowedMaxPrice"
                    ::default-min-range="minRange"
                    ::default-max-range="maxRange"
                    @change-range="setPriceRange($event)"
                />
            </template>
        </div>
    </script>

    <script type='module'>
        app.component('v-filters', {
            template: '#v-filters-template',

            data() {
                return {
                    isLoading: true,

                    filters: {
                        available: {},

                        applied: {},
                    },
                };
            },

            mounted() {
                this.getFilters();

                this.setFilters();
            },

            methods: {
                getFilters() {
                    this.$axios.get('{{ route("shop.api.categories.attributes") }}', {
                            params: {
                                category_id: "{{ isset($category) ? $category->id : ''  }}",
                            }
                        })
                        .then((response) => {
                            this.isLoading = false;

                            this.filters.available = response.data.data;
                        })
                        .catch((error) => {
                            console.error("No se pudieron cargar los filtros:", error);
                            this.isLoading = false;
                        });
                },

                setFilters() {
                    let queryParams = new URLSearchParams(window.location.search);

                    queryParams.forEach((value, filter) => {
                        if (! ['sort', 'limit', 'mode'].includes(filter)) {
                            this.filters.applied[filter] = value.split(',');
                        }
                    });

                    this.$emit('filter-applied', this.filters.applied);
                },

                applyFilter(filter, values) {
                    if (values.length) {
                        this.filters.applied[filter.code] = values;
                    } else {
                        delete this.filters.applied[filter.code];
                    }

                    this.$emit('filter-applied', this.filters.applied);
                },

                clear() {
                    this.filters.applied = {};

                    if (this.$refs.filterItemComponent) {
                        this.$refs.filterItemComponent.forEach((filterItem) => {
                            if (filterItem.filter.type === 'price') {
                                filterItem.$data.appliedValues = null;
                            } else {
                                filterItem.$data.appliedValues = [];
                            }
                        });
                    }

                    this.$emit('filter-applied', this.filters.applied);
                },
            },
        });

        app.component('v-filter-item', {
            template: '#v-filter-item-template',

            props: ['filter'],

            data() {
                return {
                    options: [],

                    meta: null,

                    appliedValues: null,

                    currentPage: 1,

                    searchQuery: '',

                    isLoadingMore: true,

                    refreshKey: 0,
                }
            },

            created() {
                if (this.filter.type === 'price') {
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code]?.join(',');
                } else {
                    this.appliedValues = this.$parent.$data.filters.applied[this.filter.code] ?? [];
                }
            },

            mounted() {
                this.fetchFilterOptions();
            },

            watch: {
                appliedValues: {
                    handler(newVal, oldVal) {
                        if (
                            this.filter.type === 'price' &&
                            newVal !== oldVal &&
                            !newVal
                        ) {
                            this.refreshKey++;
                        }
                    }
                }
            },

            methods: {
                applyValue($event) {
                    if (this.filter.type === 'price') {
                        this.appliedValues = $event;

                        this.$emit('values-applied', this.appliedValues);

                        return;
                    }

                    this.$emit('values-applied', this.appliedValues);
                },

                searchOptions() {
                    this.currentPage = 1;

                    this.fetchFilterOptions(true);
                },

                loadMoreOptions() {
                    this.currentPage++;

                    this.fetchFilterOptions(false);
                },

                fetchFilterOptions(replace = true) {
                    this.isLoadingMore = true;

                    const url = `{{ route("shop.api.categories.attribute_options", 'attribute_id') }}`.replace('attribute_id', this.filter.id);

                    this.$axios.get(url, {
                        params: {
                            page: this.currentPage,
                            search: this.searchQuery,
                        }
                    })
                    .then(response => {
                        this.isLoadingMore = false;

                        this.options = replace
                            ? response.data.data
                            : [...this.options, ...response.data.data];

                        this.meta = response.data.meta;
                    })
                    .catch(error => {
                        this.isLoadingMore = false;
                    });
                },
            },
        });

        app.component('v-price-filter', {
            template: '#v-price-filter-template',

            props: ['defaultPriceRange', 'defaultAttributeCode'],

            data() {
                return {
                    refreshKey: 0,
                    isLoading: true,
                    allowedMaxPrice: 100,
                    priceRange: null,
                };
            },

            computed: {
                minRange() {
                    let priceRange = (this.priceRange || '0,100').split(',');
                    return priceRange[0];
                },

                maxRange() {
                    let priceRange = (this.priceRange || '0,100').split(',');
                    return priceRange[1];
                }
            },

            created() {
                let defaultRange = Array.isArray(this.defaultPriceRange)
                    ? this.defaultPriceRange.join(',')
                    : this.defaultPriceRange;

                this.priceRange = defaultRange || [0, 100].join(',');
            },

            mounted() {
                this.getMaxPrice();
            },

            methods: {
                getMaxPrice() {
                    this.$axios.get('{{ route("shop.api.categories.max_price", isset($category) && $category->id ? $category->id : null) }}', {
                            params: {
                                attribute_code: this.defaultAttributeCode || 'price',
                            }
                        })
                        .then((response) => {
                            this.isLoading = false;

                            if (response.data.data.max_price) {
                                this.allowedMaxPrice = response.data.data.max_price;
                            }

                            if (! this.defaultPriceRange) {
                                this.priceRange = [0, this.allowedMaxPrice].join(',');
                            }

                            ++this.refreshKey;
                        })
                        .catch((error) => {
                            console.error("No se pudo obtener el precio máximo:", error);
                        });
                },

                setPriceRange($event) {
                    this.priceRange = [$event.minRange, $event.maxRange].join(',');

                    this.$emit('set-price-range', this.priceRange);
                },
            },
        });
    </script>
@endPushOnce
