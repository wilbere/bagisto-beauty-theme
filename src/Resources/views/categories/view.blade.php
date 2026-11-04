<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="{{ trim($category->meta_description) != "" ? $category->meta_description : \Illuminate\Support\Str::limit(strip_tags($category->description), 120, '') }}"
    />

    <meta
        name="keywords"
        content="{{ $category->meta_keywords }}"
    />

    @if (core()->getConfigData('catalog.rich_snippets.categories.enable'))
        <script type="application/ld+json">
            {!! app(\Webkul\Product\Helpers\SEO::class)->getCategoryJsonLd($category) !!}
        </script>
    @endif
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($category->meta_title) != "" ? $category->meta_title : $category->name }}
    </x-slot>

    <!-- Category Editorial Header -->
    <div class="border-b border-[#eae5db]/60 bg-[#f7f5f0]/40 py-8 lg:py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            @if (core()->getConfigData('general.general.breadcrumbs.shop'))
                <nav aria-label="Breadcrumb" class="mb-4 flex items-center gap-2 text-xs text-[#71717a]">
                    <a href="{{ route('shop.home.index') }}" class="hover:text-[#18181b] transition-colors">
                        @lang('shop::app.customers.account.home')
                    </a>
                    <span class="text-[#a1a1aa]">/</span>
                    <span class="font-medium text-[#18181b]">{{ $category->name }}</span>
                </nav>
            @endif

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="font-editorial text-3xl font-bold tracking-tight text-[#18181b] sm:text-4xl lg:text-5xl">
                        {{ $category->name }}
                    </h1>

                    @if ($category->description && in_array($category->display_mode, [null, 'description_only', 'products_and_description']))
                        <div class="mt-3 max-w-2xl text-sm text-[#52525b] leading-relaxed">
                            {!! $category->description !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Optional Category Hero Banner -->
            @if ($category->banner_path)
                <div class="mt-6 overflow-hidden rounded-2xl border border-[#eae5db]/60 shadow-xs">
                    <x-shop::media.images.lazy
                        class="aspect-[21/9] w-full object-cover object-center max-h-72"
                        src="{{ $category->banner_url }}"
                        alt="{{ $category->banner_alt ?: $category->name }}"
                        width="1280"
                        height="400"
                    />
                </div>
            @endif
        </div>
    </div>

    @if (in_array($category->display_mode, [null, 'products_only', 'products_and_description']))
        <!-- Category Products Component -->
        <v-category>
            <x-shop::shimmer.categories.view />
        </v-category>
    @endif

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-category-template"
        >
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <div class="flex items-start gap-8 lg:gap-10">
                    <!-- Filters Sidebar (Desktop) -->
                    <div class="w-64 shrink-0 max-lg:hidden">
                        @include('shop::categories.filters')
                    </div>

                    <!-- Products Section -->
                    <div class="flex-1 min-w-0">
                        <!-- Toolbar (Sorting & Count) -->
                        <div class="mb-6 flex items-center justify-between border-b border-[#eae5db]/60 pb-4">
                            @include('shop::categories.toolbar')
                        </div>

                        <!-- Product Grid (4 Cols on Desktop for cosmetics & polish catalog) -->
                        <div>
                            <!-- Loading Skeletons -->
                            <template v-if="isLoading">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                    <x-shop::shimmer.products.cards.grid count="12" />
                                </div>
                            </template>

                            <!-- Product Cards -->
                            <template v-else>
                                <template v-if="products.length">
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

                                    <!-- Load More or Pagination Link -->
                                    <div class="mt-12 flex justify-center" v-if="links?.next">
                                        <button
                                            type="button"
                                            class="rounded-full border border-[#18181b] bg-white px-8 py-3 text-xs font-semibold uppercase tracking-wider text-[#18181b] transition-all hover:bg-[#18181b] hover:text-white"
                                            @click="loadMore"
                                        >
                                            @lang('beauty::app.products.load-more')
                                        </button>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <template v-else>
                                    <div class="flex flex-col items-center justify-center py-24 text-center">
                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f7f4ee] text-2xl text-[#d4a373]">
                                            <span class="icon-search"></span>
                                        </div>

                                        <h3 class="mt-4 font-editorial text-xl font-semibold text-[#18181b]">
                                            @lang('beauty::app.products.no-products-found')
                                        </h3>

                                        <p class="mt-1 text-xs text-[#71717a] max-w-sm">
                                            @lang('beauty::app.products.no-products-help')
                                        </p>

                                        <button
                                            type="button"
                                            class="mt-6 rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white transition-colors hover:bg-[#d4a373]"
                                            @click="clearFilters('filter', '')"
                                        >
                                            @lang('beauty::app.products.reset-filters')
                                        </button>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-category', {
                template: '#v-category-template',

                data() {
                    return {
                        isLoading: true,
                        isMobile: window.innerWidth <= 768,
                        filters: {
                            toolbar: { default: { mode: 'grid' }, applied: {} },
                            filter: {},
                        },
                        products: [],
                        links: {},
                    };
                },

                computed: {
                    queryParams() {
                        let queryParams = Object.assign({}, this.filters.filter, this.filters.toolbar.applied);
                        return this.cleanObject(queryParams);
                    },
                },

                watch: {
                    queryParams() {
                        this.getProducts();
                    },
                },

                mounted() {
                    this.getProducts();
                },

                methods: {
                    getProducts() {
                        this.isLoading = true;

                        this.$axios.get("{{ route('shop.api.products.index', ['category_id' => $category->id]) }}", {
                            params: this.queryParams,
                        })
                        .then((response) => {
                            this.isLoading = false;
                            this.products = response.data.data;
                            this.links = response.data.links;
                        })
                        .catch(() => {
                            this.isLoading = false;
                        });
                    },

                    setFilters(type, filters) {
                        this.filters[type] = filters;
                    },

                    clearFilters(type, attribute) {
                        if (attribute) {
                            delete this.filters[type][attribute];
                        } else {
                            this.filters[type] = {};
                        }
                    },

                    cleanObject(obj) {
                        return Object.entries(obj).reduce((a, [k, v]) => (v ? ((a[k] = v), a) : a), {});
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts>
