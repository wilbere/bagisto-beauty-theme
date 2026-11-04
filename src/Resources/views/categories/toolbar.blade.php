{!! view_render_event('bagisto.shop.categories.view.toolbar.before') !!}

<v-toolbar @filter-applied='setFilters("toolbar", $event)'></v-toolbar>

{!! view_render_event('bagisto.shop.categories.view.toolbar.after') !!}

@inject('toolbar' , \Webkul\Product\Helpers\Toolbar::class)

@pushOnce('scripts')
    <script
        type="text/x-template"
        id='v-toolbar-template'
    >
        <div class="w-full">
            <!-- Desktop Toolbar -->
            <div class="flex items-center justify-between gap-4 max-md:hidden w-full">
                {!! view_render_event('bagisto.shop.categories.toolbar.filter.before') !!}

                <!-- Product Sorting Filters -->
                <x-shop::dropdown
                    class="z-10"
                    position="bottom-left"
                >
                    <x-slot:toggle>
                        <!-- Dropdown Toggler -->
                        <button class="flex cursor-pointer items-center justify-between gap-3 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-4 py-2 text-xs font-medium text-[#18181b] transition-all hover:border-[#9e8464] hover:bg-white">
                            <span>@{{ sortLabel ?? "@lang('shop::app.products.sort-by.title')" }}</span>

                            <span
                                class="icon-arrow-down text-sm text-[#9e8464]"
                                role="presentation"
                            ></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:menu class="rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-1.5 shadow-xl text-xs">
                        <x-shop::dropdown.menu.item
                            v-for="(sort, key) in filters.available.sort"
                            ::class="{'bg-[#f7f4ee] font-semibold text-[#9e8464]': sort.value == filters.applied.sort, 'text-[#18181b] hover:bg-[#ede8dc]/50': sort.value != filters.applied.sort}"
                            @click="apply('sort', sort.value)"
                            class="rounded-lg px-3 py-2 text-xs cursor-pointer transition-colors"
                        >
                            @{{ sort.title }}
                        </x-shop::dropdown.menu.item>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.categories.toolbar.filter.after') !!}

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.before') !!}

                <!-- Product Pagination Limit & Grid/List switcher -->
                <div class="flex items-center gap-4">
                    <x-shop::dropdown position="bottom-right" class="z-10">
                        <x-slot:toggle>
                            <!-- Dropdown Toggler -->
                            <button class="flex cursor-pointer items-center justify-between gap-2.5 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-3.5 py-2 text-xs font-medium text-[#18181b] transition-all hover:border-[#9e8464] hover:bg-white">
                                <span class="text-[#71717a]">@lang('shop::app.categories.toolbar.show'):</span>
                                <span class="font-semibold">@{{ filters.applied.limit ?? '12' }}</span>

                                <span
                                    class="icon-arrow-down text-sm text-[#9e8464]"
                                    role="presentation"
                                ></span>
                            </button>
                        </x-slot>

                        <!-- Dropdown Content -->
                        <x-slot:menu class="rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-1.5 shadow-xl text-xs min-w-[70px]">
                            <x-shop::dropdown.menu.item
                                v-for="(limit, key) in filters.available.limit"
                                ::class="{'bg-[#f7f4ee] font-semibold text-[#9e8464]': limit == filters.applied.limit, 'text-[#18181b] hover:bg-[#ede8dc]/50': limit != filters.applied.limit}"
                                @click="apply('limit', limit)"
                                class="rounded-lg px-3 py-2 text-xs cursor-pointer text-center transition-colors"
                            >
                                @{{ limit }}
                            </x-shop::dropdown.menu.item>
                        </x-slot>
                    </x-shop::dropdown>

                    <!-- Listing Mode Switcher -->
                    <div class="flex items-center gap-1 rounded-full border border-[#ede8dc] bg-[#fdfbf7] p-1">
                        <button
                            type="button"
                            class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-full transition-colors"
                            :class="(filters.applied.mode === 'grid') ? 'bg-[#18181b] text-white shadow-xs' : 'text-[#71717a] hover:text-[#18181b]'"
                            @click="changeMode('grid')"
                            aria-label="@lang('shop::app.categories.toolbar.grid')"
                        >
                            <span class="icon-grid-view text-sm"></span>
                        </button>

                        <button
                            type="button"
                            class="flex h-7 w-7 cursor-pointer items-center justify-center rounded-full transition-colors"
                            :class="(filters.applied.mode === 'list') ? 'bg-[#18181b] text-white shadow-xs' : 'text-[#71717a] hover:text-[#18181b]'"
                            @click="changeMode('list')"
                            aria-label="@lang('shop::app.categories.toolbar.list')"
                        >
                            <span class="icon-listing text-sm"></span>
                        </button>
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.categories.toolbar.pagination.after') !!}
            </div>

            <!-- Mobile Toolbar -->
            <div class="md:hidden">
                <ul class="divide-y divide-[#ede8dc]">
                    <li
                        class="cursor-pointer px-4 py-3 text-xs font-medium transition-colors"
                        :class="{'bg-[#f7f4ee] font-semibold text-[#9e8464]': sort.value == filters.applied.sort, 'text-[#18181b] hover:bg-[#ede8dc]/40': sort.value != filters.applied.sort}"
                        v-for="(sort, key) in filters.available.sort"
                        @click="apply('sort', sort.value)"
                    >
                        @{{ sort.title }}
                    </li>
                </ul>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-toolbar', {
            template: '#v-toolbar-template',

            data() {
                return {
                    filters: {
                        available: {
                            sort: @json($toolbar->getAvailableOrders()),

                            limit: @json($toolbar->getAvailableLimits()),

                            mode: @json($toolbar->getAvailableModes()),
                        },

                        default: {
                            sort: '{{ $toolbar->getOrder([])['value'] }}',

                            limit: '{{ $toolbar->getLimit([]) }}',

                            mode: '{{ $toolbar->getMode([]) }}',
                        },

                        applied: {
                            sort: '{{ $toolbar->getOrder($params ?? [])['value'] }}',

                            limit: '{{ $toolbar->getLimit($params ?? []) }}',

                            mode: '{{ $toolbar->getMode($params ?? []) }}',
                        }
                    }
                };
            },

            created() {
                let queryParams = new URLSearchParams(window.location.search);

                queryParams.forEach((value, filter) => {
                    if (this.isAvailable(filter, value)) {
                        this.filters.applied[filter] = value;
                    }
                });
            },

            mounted() {
                this.setFilters();
            },

            computed: {
                sortLabel() {
                    const match = this.filters.available.sort.find(sort => sort.value === this.filters.applied.sort);
                    return match ? match.title : null;
                }
            },

            methods: {
                isAvailable(filter, value) {
                    if (! ['sort', 'limit', 'mode'].includes(filter)) {
                        return false;
                    }

                    if (filter === 'sort') {
                        return this.filters.available.sort.some((sort) => sort.value === value);
                    }

                    return this.filters.available[filter].some((available) => String(available) === value);
                },

                apply(type, value) {
                    this.filters.applied[type] = value;

                    this.setFilters();
                },

                changeMode(value = 'grid') {
                    this.filters.applied['mode'] = value;

                    this.setFilters();
                },

                setFilters() {
                    let filters = {};

                    for (let key in this.filters.applied) {
                        if (this.filters.applied[key] != this.filters.default[key]) {
                            filters[key] = this.filters.applied[key];
                        }
                    }

                    this.$emit('filter-applied', {
                        default: this.filters.default,
                        applied: filters,
                    });
                }
            },
        });
    </script>
@endPushOnce
