<?php
    $searchTitle = $suggestion ?? $query;
    $title = $searchTitle ? trans('shop::app.search.title', ['query' => $searchTitle]) : trans('shop::app.search.results');
    $searchInstead = $suggestion ? $query : null;
?>
<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="{{ $title }}"
    />

    <meta
        name="keywords"
        content="{{ $title }}"
    />
@endPush

<x-shop::layouts :has-feature="false">
    <!-- Page Title -->
    <x-slot:title>
        {{ $title }}
    </x-slot>

    <!-- Search Editorial Header -->
    <div class="border-b border-[#eae5db]/60 bg-[#f7f5f0]/40 py-8 lg:py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (request()->has('image-search'))
                @include('shop::search.images.results')
            @endif

            <h1 class="font-editorial text-3xl font-bold tracking-tight text-[#18181b] sm:text-4xl">
                <span>{{ preg_replace('/[,\\"\\\']+/', '', $title) }}</span>
            </h1>

            @if ($searchInstead)
                <form
                    action="{{ route('shop.search.index', ['suggest' => false]) }}"
                    class="mt-3 flex items-center gap-2"
                    role="search"
                >
                    <input type="hidden" name="query" value="{{ $searchInstead }}">
                    <input type="hidden" name="suggest" value="0">

                    <p class="text-xs text-[#71717a]" v-pre>
                        {{ trans('shop::app.search.suggest') }}
                        <button
                            type="submit"
                            class="font-medium text-[#9e8464] hover:underline"
                            aria-label="{{ trans('shop::app.components.layouts.header.desktop.bottom.submit') }}"
                        >
                            {{ $searchInstead }}
                        </button>
                    </p>
                </form>
            @endif
        </div>
    </div>

    <!-- Product Listing Component -->
    <v-search>
        <x-shop::shimmer.categories.view />
    </v-search>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-search-template"
        >
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex items-start gap-8 lg:gap-10">
                    <!-- Filters Sidebar (Desktop) -->
                    <div class="w-64 shrink-0 max-lg:hidden">
                        @include('shop::categories.filters')
                    </div>

                    <!-- Products Section -->
                    <div class="flex-1 min-w-0">
                        <!-- Toolbar (Sorting & Limit & Mode Switcher) -->
                        <div class="mb-6 flex items-center justify-between border-b border-[#eae5db]/60 pb-4">
                            @include('shop::categories.toolbar')
                        </div>

                        <!-- Product Grid/List -->
                        <div>
                            <!-- Loading Skeletons -->
                            <template v-if="isLoading">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            <!-- Products Loaded -->
                            <template v-else>
                                <template v-if="products.length">
                                    <!-- Grid Mode (4 cols on desktop) -->
                                    <div
                                        class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4"
                                        v-if="(filters.toolbar.applied.mode ?? filters.toolbar.default.mode) === 'grid'"
                                    >
                                        <x-shop::products.card
                                            ::mode="'grid'"
                                            v-for="product in products"
                                            ::key="product.id"
                                        />
                                    </div>

                                    <!-- List Mode -->
                                    <div
                                        class="grid grid-cols-1 gap-4"
                                        v-else
                                    >
                                        <x-shop::products.card
                                            ::mode="'list'"
                                            v-for="product in products"
                                            ::key="product.id"
                                        />
                                    </div>

                                    <!-- Load More Button -->
                                    <div class="mt-12 flex justify-center" v-if="links.next">
                                        <button
                                            type="button"
                                            class="cursor-pointer rounded-full border border-[#18181b] bg-white px-8 py-2.5 text-xs font-semibold uppercase tracking-wider text-[#18181b] transition-all hover:bg-[#18181b] hover:text-white"
                                            @click="loadMoreProducts"
                                        >
                                            @lang('shop::app.categories.view.load-more')
                                        </button>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <template v-else>
                                    <div class="flex flex-col items-center justify-center py-24 text-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f7f4ee] text-2xl text-[#9e8464]">
                                            <span class="icon-search"></span>
                                        </div>

                                        <h3 class="mt-4 font-editorial text-xl font-semibold text-[#18181b]">
                                            @lang('shop::app.categories.view.empty')
                                        </h3>

                                        <p class="mt-2 text-xs text-[#71717a]">
                                            No se encontraron productos para los filtros seleccionados.
                                        </p>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-search', {
                template: '#v-search-template',

                data() {
                    return {
                        isMobile: window.innerWidth <= 767,

                        isLoading: true,

                        isDrawerActive: {
                            toolbar: false,
                            filter: false,
                        },

                        filters: {
                            toolbar: {
                                default: {},
                                applied: {},
                            },
                            filter: {},
                        },

                        products: [],

                        links: {},
                    };
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar.applied);

                        return this.removeJsonEmptyValues(queryParams);
                    },

                    queryString() {
                        return this.jsonToQueryString(this.queryParams);
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },

                    queryString() {
                        window.history.pushState({}, '', '?' + this.queryString);
                    },
                },

                methods: {
                    setFilters(type, filters) {
                        this.filters[type] = filters;
                    },

                    clearFilters(type, filters) {
                        this.filters[type] = {};
                    },

                    getProducts() {
                        this.isDrawerActive = {
                            toolbar: false,
                            filter: false,
                        };

                        this.$axios.get("{{ route('shop.api.products.index') }}", {
                            params: this.queryParams
                        })
                        .then(response => {
                            this.isLoading = false;
                            this.products = response.data.data;
                            this.links = response.data.links;
                        })
                        .catch(error => {
                            console.error("Error al buscar productos:", error);
                            this.isLoading = false;
                        });
                    },

                    loadMoreProducts() {
                        if (this.links.next) {
                            this.$axios.get(this.links.next)
                                .then(response => {
                                    this.products = [...this.products, ...response.data.data];
                                    this.links = response.data.links;
                                })
                                .catch(error => {
                                    console.error("Error al cargar más productos:", error);
                                });
                        }
                    },

                    removeJsonEmptyValues(params) {
                        Object.keys(params).forEach(function (key) {
                            if (! params[key] && params[key] !== undefined) {
                                delete params[key];
                            }

                            if (Array.isArray(params[key])) {
                                params[key] = params[key].join(',');
                            }
                        });

                        return params;
                    },

                    jsonToQueryString(params) {
                        let parameters = new URLSearchParams();

                        for (const key in params) {
                            parameters.append(key, params[key]);
                        }

                        return parameters.toString();
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
