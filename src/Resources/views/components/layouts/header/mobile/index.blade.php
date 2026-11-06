@php
    $showCompare = (bool) core()->getConfigData('catalog.products.settings.compare_option');
    $showWishlist = (bool) core()->getConfigData('customer.settings.wishlist.wishlist_option');
@endphp

<div class="flex flex-col gap-3 border-b border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 shadow-2xs lg:hidden">
    <div class="flex items-center justify-between w-full">
        <!-- Left Navigation: Hamburger & Logo -->
        <div class="flex items-center gap-3">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.before') !!}

            <!-- Drawer -->
            <v-mobile-drawer></v-mobile-drawer>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.before') !!}

            <a
                href="{{ route('shop.home.index') }}"
                class="flex items-center"
                aria-label="@lang('shop::app.components.layouts.header.mobile.bagisto')"
            >
                <img
                    class="h-7 w-auto object-contain"
                    src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                    alt="{{ core()->getCurrentChannel()->logo_alt ?: config('app.name') }}"
                    width="120"
                    height="28"
                >
            </a>

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.logo.after') !!}
        </div>

        <!-- Right Navigation: Actions & Cart -->
        <div class="flex items-center gap-2">
            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.before') !!}

            @if($showCompare)
                <a
                    href="{{ route('shop.compare.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.compare')"
                >
                    <span class="icon-compare text-xl"></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.compare.after') !!}

            @if ($showWishlist)
                <a
                    href="{{ route('shop.customers.account.wishlist.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.wishlist')"
                >
                    <span class="icon-heart text-xl"></span>
                </a>
            @endif

            <!-- Account Icon -->
            @guest('customer')
                <a
                    href="{{ route('shop.customer.session.create') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                >
                    <span class="icon-users text-xl"></span>
                </a>
            @endguest

            @auth('customer')
                <a
                    href="{{ route('shop.customers.account.index') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.components.layouts.header.mobile.account')"
                >
                    <span class="icon-users text-xl"></span>
                </a>
            @endauth

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.before') !!}

            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.mobile.mini_cart.after') !!}
        </div>
    </div>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.before') !!}

    <!-- Search Catalog Form -->
    <form action="{{ route('shop.search.index') }}" class="relative w-full" role="search">
        <label for="mobile-organic-search" class="sr-only">
            @lang('shop::app.components.layouts.header.mobile.search')
        </label>

        <div class="icon-search pointer-events-none absolute top-2.5 flex items-center text-lg text-[#9e8464] ltr:left-3 rtl:right-3"></div>

        @php
            $maxQueryLength = ((int) core()->getConfigData('search_engines.elastic.settings.max_query_length')) ?: 1000;
            $minQueryLength = (int) core()->getConfigData('search_engines.elastic.settings.min_query_length');
            $minQueryLength = $minQueryLength > 0 && $minQueryLength <= $maxQueryLength ? $minQueryLength : 0;
        @endphp

        <input
            type="text"
            id="mobile-organic-search"
            class="block w-full rounded-full border border-[#ede8dc] bg-[#f7f4ee] py-2 text-xs font-medium text-[#18181b] placeholder:text-[#a1a1aa] transition-colors focus:border-[#9e8464] focus:bg-white focus:outline-hidden ltr:pl-9 ltr:pr-4 rtl:pr-9 rtl:pl-4"
            name="query"
            value="{{ request('query') }}"
            minlength="{{ $minQueryLength }}"
            maxlength="{{ $maxQueryLength }}"
            placeholder="@lang('shop::app.components.layouts.header.mobile.search-text')"
            pattern="[^\\]+"
            required
        >

        @if (core()->getConfigData('catalog.products.settings.image_search'))
            @include('shop::search.images.index')
        @endif
    </form>

    {!! view_render_event('bagisto.shop.components.layouts.header.mobile.search.after') !!}
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mobile-drawer-template">
        <x-shop::drawer
            position="left"
            width="100%"
            @close="onDrawerClose"
        >
            <x-slot:toggle>
                <button
                    type="button"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="Menú"
                >
                    <span class="icon-hamburger text-2xl"></span>
                </button>
            </x-slot>

            <x-slot:header>
                <div class="flex items-center justify-between">
                    <a href="{{ route('shop.home.index') }}">
                        <img
                            src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                            alt="{{ core()->getCurrentChannel()->logo_alt ?: config('app.name') }}"
                            class="h-7 w-auto object-contain"
                            width="120"
                            height="28"
                        >
                    </a>
                </div>
            </x-slot>

            <x-slot:content class="p-0!">
                <!-- Account Profile Hero Section -->
                <div class="border-b border-[#ede8dc] bg-[#fdfbf7] p-5">
                    <div class="rounded-2xl border border-[#ede8dc] bg-white p-4 shadow-2xs">
                        @guest('customer')
                            <div class="flex flex-col gap-3 text-center">
                                <p class="font-editorial text-lg font-semibold text-[#18181b]">
                                    @lang('shop::app.components.layouts.header.mobile.welcome-guest')
                                </p>
                                <p class="text-xs text-[#71717a]">
                                    Accede a tu cuenta para gestionar pedidos y listas de deseos
                                </p>
                                <div class="mt-1 flex items-center justify-center gap-3">
                                    <a
                                        href="{{ route('shop.customer.session.create') }}"
                                        class="rounded-full bg-[#18181b] px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464]"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.sign-in')
                                    </a>
                                    <a
                                        href="{{ route('shop.customers.register.index') }}"
                                        class="rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-2 text-xs font-semibold uppercase tracking-wider text-[#18181b] shadow-2xs transition-colors hover:bg-[#ede8dc]"
                                    >
                                        @lang('shop::app.components.layouts.header.mobile.sign-up')
                                    </a>
                                </div>
                            </div>
                        @endguest

                        @auth('customer')
                            <div class="flex items-center gap-3" v-pre>
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#18181b] text-sm font-bold text-white">
                                    {{ strtoupper(substr(auth()->user()?->first_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-editorial text-base font-semibold text-[#18181b] truncate">
                                        {{ trans('shop::app.components.layouts.account.greeting', ['name' => auth()->user()?->first_name]) }}
                                    </p>
                                    <p class="text-xs text-[#71717a] truncate">{{ auth()->user()?->email }}</p>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.before') !!}

                <!-- Mobile category view -->
                <div class="p-4">
                    <v-mobile-category ref="mobileCategory"></v-mobile-category>
                </div>

                {!! view_render_event('bagisto.shop.components.layouts.header.mobile.drawer.categories.after') !!}
            </x-slot>

            <x-slot:footer>
                @if (
                    core()->getCurrentChannel()->locales()->count() > 1
                    || core()->getCurrentChannel()->currencies()->count() > 1
                )
                    <div class="fixed bottom-0 z-10 grid w-full max-w-full grid-cols-[1fr_auto_1fr] items-center justify-items-center border-t border-[#ede8dc] bg-[#fdfbf7] px-5 ltr:left-0 rtl:right-0">
                        <x-shop::drawer position="bottom" width="100%">
                            <x-slot:toggle>
                                <button
                                    type="button"
                                    class="flex cursor-pointer items-center gap-x-2 px-2.5 py-3 text-xs font-semibold uppercase tracking-wider text-[#18181b]"
                                    v-pre
                                >
                                    {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                                </button>
                            </x-slot>

                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="font-editorial text-lg font-semibold text-[#18181b]">
                                        @lang('shop::app.components.layouts.header.mobile.currencies')
                                    </p>
                                </div>
                            </x-slot>

                            <x-slot:content class="px-0!">
                                <div class="overflow-auto" :style="{ height: getCurrentScreenHeight }">
                                    <v-currency-switcher></v-currency-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>

                        <span class="h-5 w-px bg-[#ede8dc]"></span>

                        <x-shop::drawer position="bottom" width="100%">
                            <x-slot:toggle>
                                <button
                                    type="button"
                                    class="flex cursor-pointer items-center gap-x-2 px-2.5 py-3 text-xs font-semibold uppercase tracking-wider text-[#18181b]"
                                    v-pre
                                >
                                    <img
                                        src="{{ ! empty(core()->getCurrentLocale()->logo_url) ? core()->getCurrentLocale()->logo_url : bagisto_asset('images/default-language.svg') }}"
                                        class="h-3.5 w-auto"
                                        alt="Locale"
                                        width="20"
                                        height="14"
                                    />
                                    {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') }}
                                </button>
                            </x-slot>

                            <x-slot:header>
                                <div class="flex items-center justify-between">
                                    <p class="font-editorial text-lg font-semibold text-[#18181b]">
                                        @lang('shop::app.components.layouts.header.mobile.locales')
                                    </p>
                                </div>
                            </x-slot>

                            <x-slot:content class="px-0!">
                                <div class="overflow-auto" :style="{ height: getCurrentScreenHeight }">
                                    <v-locale-switcher></v-locale-switcher>
                                </div>
                            </x-slot>
                        </x-shop::drawer>
                    </div>
                @endif
            </x-slot>
        </x-shop::drawer>
    </script>

    <script type="text/x-template" id="v-mobile-category-template">
        <div class="relative h-full overflow-hidden">
            <div
                class="flex h-full transition-transform duration-300"
                :class="{
                    'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                    'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                }"
            >
                <!-- First level view -->
                <div class="shrink-0 w-full h-full px-2 overflow-auto">
                    <div class="space-y-1">
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="rounded-xl transition-colors hover:bg-[#ede8dc]/30"
                        >
                            <div class="flex items-center justify-between px-3 py-2.5 transition-colors duration-200 cursor-pointer">
                                <a :href="category.url" class="text-sm font-medium text-[#18181b]">
                                    @{{ category.name }}
                                </a>

                                <span
                                    v-if="category.children && category.children.length"
                                    class="icon-arrow-right text-xs text-[#9e8464] rtl:icon-arrow-left cursor-pointer p-1"
                                    @click="showThirdLevel(category, category, $event)"
                                ></span>
                            </div>

                            <!-- Second Level Categories -->
                            <div v-if="category.children && category.children.length" class="ml-4 pl-2 border-l border-[#ede8dc] space-y-1 my-1">
                                <div
                                    v-for="secondLevelCategory in category.children"
                                    :key="secondLevelCategory.id"
                                >
                                    <div
                                        class="flex items-center justify-between py-1.5 px-2 rounded-lg transition-colors hover:bg-[#ede8dc]/40 cursor-pointer"
                                        @click="showThirdLevel(secondLevelCategory, category, $event)"
                                    >
                                        <a :href="secondLevelCategory.url" class="text-xs text-[#52525b] hover:text-[#18181b]">
                                            @{{ secondLevelCategory.name }}
                                        </a>

                                        <span
                                            v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                            class="icon-arrow-right text-[10px] text-[#9e8464] rtl:icon-arrow-left"
                                        ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third level view -->
                <div
                    class="shrink-0 w-full h-full"
                    v-if="currentViewLevel === 'third'"
                >
                    <div class="px-4 py-3 border-b border-[#ede8dc] bg-[#f7f5f0]">
                        <button
                            @click="goBackToMainView"
                            class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#18181b]"
                            aria-label="Volver"
                        >
                            <span class="icon-arrow-left text-sm rtl:icon-arrow-right"></span>
                            <span>@lang('shop::app.components.layouts.header.mobile.back-button')</span>
                        </button>
                    </div>

                    <div class="px-4 py-3 space-y-1">
                        <div
                            v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                            :key="thirdLevelCategory.id"
                        >
                            <a
                                :href="thirdLevelCategory.url"
                                class="block px-3 py-2 text-xs text-[#52525b] rounded-lg transition-colors hover:bg-[#ede8dc]/40 hover:text-[#18181b]"
                            >
                                @{{ thirdLevelCategory.name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mobile-category', {
            template: '#v-mobile-category-template',

            data() {
                return {
                    categories: [],
                    currentViewLevel: 'main',
                    currentSecondLevelCategory: null,
                    currentParentCategory: null
                }
            },

            mounted() {
                this.initCategories();
            },

            computed: {
                getCurrentScreenHeight() {
                    return window.innerHeight - (window.innerWidth < 920 ? 61 : 0) + 'px';
                },
            },

            methods: {
                initCategories() {
                    try {
                        const stored = localStorage.getItem('categories');
                        if (stored) {
                            this.categories = JSON.parse(stored);
                            return;
                        }
                    } catch (e) {}

                    this.getCategories();
                },

                getCategories() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.categories = response.data.data;
                            try {
                                localStorage.setItem('categories', JSON.stringify(this.categories));
                            } catch (e) {}
                        })
                        .catch(error => {});
                },

                showThirdLevel(secondLevelCategory, parentCategory, event) {
                    if (secondLevelCategory.children && secondLevelCategory.children.length) {
                        this.currentSecondLevelCategory = secondLevelCategory;
                        this.currentParentCategory = parentCategory;
                        this.currentViewLevel = 'third';

                        if (event) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }
                },

                goBackToMainView() {
                    this.currentViewLevel = 'main';
                }
            },
        });

        app.component('v-mobile-drawer', {
            template: '#v-mobile-drawer-template',

            methods: {
                onDrawerClose() {
                    if (this.$refs.mobileCategory) {
                        this.$refs.mobileCategory.currentViewLevel = 'main';
                    }
                }
            },
        });
    </script>
@endPushOnce
