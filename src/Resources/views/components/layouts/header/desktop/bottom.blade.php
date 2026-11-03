{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.before') !!}

<div class="flex min-h-20 w-full items-center justify-between border-b border-[#ede8dc] bg-[#fdfbf7] px-8 lg:px-12">
    <!-- Left Navigation Section: Logo & Categories -->
    <div class="flex items-center gap-x-8">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.before') !!}

        <a
            href="{{ route('shop.home.index') }}"
            aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.bagisto')"
            class="flex items-center"
        >
            <img
                src="{{ core()->getCurrentChannel()->logo_url ?? bagisto_asset('images/logo.svg') }}"
                width="140"
                height="32"
                alt="{{ core()->getCurrentChannel()->logo_alt ?: config('app.name') }}"
                class="h-8 w-auto object-contain"
            >
        </a>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.logo.after') !!}

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.before') !!}

        <v-desktop-category>
            <div class="flex items-center gap-4">
                <span class="shimmer h-6 w-24 rounded-md" role="presentation"></span>
                <span class="shimmer h-6 w-20 rounded-md" role="presentation"></span>
                <span class="shimmer h-6 w-20 rounded-md" role="presentation"></span>
            </div>
        </v-desktop-category>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.category.after') !!}
    </div>

    <!-- Right Navigation Section: Search Bar & User Actions -->
    <div class="flex items-center gap-x-6">
        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.before') !!}

        <!-- Search Bar Container -->
        <div class="relative w-72 lg:w-96">
            <form
                action="{{ route('shop.search.index') }}"
                class="flex items-center"
                role="search"
            >
                <label for="organic-search" class="sr-only">
                    @lang('shop::app.components.layouts.header.desktop.bottom.search')
                </label>

                <div class="icon-search pointer-events-none absolute top-3 flex items-center text-lg text-[#9e8464] ltr:left-3.5 rtl:right-3.5"></div>

                @php
                    $maxQueryLength = ((int) core()->getConfigData('search_engines.elastic.settings.max_query_length')) ?: 1000;
                    $minQueryLength = (int) core()->getConfigData('search_engines.elastic.settings.min_query_length');
                    $minQueryLength = $minQueryLength > 0 && $minQueryLength <= $maxQueryLength ? $minQueryLength : 0;
                @endphp

                <input
                    type="text"
                    id="organic-search"
                    name="query"
                    value="{{ request('query') }}"
                    class="block w-full rounded-full border border-[#ede8dc] bg-[#f7f4ee] py-2 text-xs font-medium text-[#18181b] transition-all placeholder:text-[#a1a1aa] hover:border-[#b59f7f] focus:border-[#9e8464] focus:bg-white focus:outline-hidden ltr:pl-10 ltr:pr-4 rtl:pr-10 rtl:pl-4"
                    minlength="{{ $minQueryLength }}"
                    maxlength="{{ $maxQueryLength }}"
                    placeholder="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
                    pattern="[^\\]+"
                    required
                >

                <button type="submit" class="hidden" aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.submit')"></button>

                @if (core()->getConfigData('catalog.products.settings.image_search'))
                    @include('shop::search.images.index')
                @endif
            </form>
        </div>

        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.search_bar.after') !!}

        <!-- Action Icons: Compare, Mini-Cart, User Profile -->
        <div class="flex items-center gap-x-5 text-[#18181b]">
            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.before') !!}

            @if(core()->getConfigData('catalog.products.settings.compare_option'))
                <a
                    href="{{ route('shop.compare.index') }}"
                    aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.compare')"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-xl transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                >
                    <span class="icon-compare" role="presentation"></span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.compare.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.before') !!}

            @if(core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                @include('shop::checkout.cart.mini-cart')
            @endif

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.mini_cart.after') !!}

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.before') !!}

            <!-- User Profile Dropdown -->
            <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                <x-slot:toggle>
                    <button
                        type="button"
                        class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full text-xl transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                        aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.profile')"
                    >
                        <span class="icon-users" role="presentation"></span>
                    </button>
                </x-slot>

                <!-- Guest Dropdown -->
                @guest('customer')
                    <x-slot:content class="w-72 rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-5 shadow-xl">
                        <div class="grid gap-2">
                            <p class="font-serif text-xl font-medium text-[#18181b]">
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome-guest')
                            </p>
                            <p class="text-xs text-[#7f684e]">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <div class="my-4 border-t border-[#ede8dc]"></div>

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.before') !!}

                        <div class="flex gap-2.5">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_in_button.before') !!}

                            <a
                                href="{{ route('shop.customer.session.create') }}"
                                class="flex-1 rounded-full bg-[#18181b] px-4 py-2 text-center text-xs font-semibold text-white transition-opacity hover:opacity-90"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-in')
                            </a>

                            <a
                                href="{{ route('shop.customers.register.index') }}"
                                class="flex-1 rounded-full border border-[#18181b] px-4 py-2 text-center text-xs font-semibold text-[#18181b] transition-colors hover:bg-[#ede8dc]/40"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.sign-up')
                            </a>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.sign_up_button.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.customers_action.after') !!}
                    </x-slot>
                @endguest

                <!-- Authenticated Customer Dropdown -->
                @auth('customer')
                    <x-slot:content class="w-64 rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-0 shadow-xl">
                        <div class="p-4 pb-2">
                            <p class="font-serif text-lg font-medium text-[#18181b]" v-pre>
                                @lang('shop::app.components.layouts.header.desktop.bottom.welcome')
                                {{ auth()->guard('customer')->user()->first_name }}
                            </p>
                            <p class="text-xs text-[#7f684e]">
                                @lang('shop::app.components.layouts.header.desktop.bottom.dropdown-text')
                            </p>
                        </div>

                        <div class="border-t border-[#ede8dc]"></div>

                        <div class="py-1 text-xs font-medium">
                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.before') !!}

                            <a
                                class="flex px-4 py-2 text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                                href="{{ route('shop.customers.account.profile.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.profile')
                            </a>

                            <a
                                class="flex px-4 py-2 text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                                href="{{ route('shop.customers.account.orders.index') }}"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.orders')
                            </a>

                            @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                <a
                                    class="flex px-4 py-2 text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                                    href="{{ route('shop.customers.account.wishlist.index') }}"
                                >
                                    @lang('shop::app.components.layouts.header.desktop.bottom.wishlist')
                                </a>
                            @endif

                            <x-shop::form
                                method="DELETE"
                                action="{{ route('shop.customer.session.destroy') }}"
                                id="customerLogout"
                            />

                            <a
                                class="flex px-4 py-2 text-[#b54a4a] transition-colors hover:bg-[#ede8dc]/50"
                                href="{{ route('shop.customer.session.destroy') }}"
                                onclick="event.preventDefault(); document.getElementById('customerLogout').submit();"
                            >
                                @lang('shop::app.components.layouts.header.desktop.bottom.logout')
                            </a>

                            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile_dropdown.links.after') !!}
                        </div>
                    </x-slot>
                @endauth
            </x-shop::dropdown>

            {!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.profile.after') !!}
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-desktop-category-template"
    >
        <!-- Loading State -->
        <div class="flex items-center gap-4" v-if="isLoading">
            <span class="shimmer h-6 w-24 rounded-md" role="presentation"></span>
            <span class="shimmer h-6 w-20 rounded-md" role="presentation"></span>
            <span class="shimmer h-6 w-20 rounded-md" role="presentation"></span>
        </div>

        <!-- Category Bar & Drawer -->
        <div class="flex items-center" v-else>
            <!-- Drawer Toggler Button -->
            <button
                type="button"
                class="flex h-12 cursor-pointer items-center gap-2 rounded-lg px-3 py-1.5 text-xs font-semibold uppercase tracking-wider text-[#18181b] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                @click="toggleCategoryDrawer"
                aria-label="Abrir catálogo de categorías"
            >
                <span class="icon-hamburger text-lg" role="presentation"></span>
                <span>@lang('shop::app.components.layouts.header.desktop.bottom.categories')</span>
            </button>

            <!-- First 4 categories in horizontal navbar -->
            <div
                class="group relative flex h-20 items-center border-b-2 border-transparent transition-all hover:border-[#9e8464]"
                v-for="category in categories.slice(0, 4)"
                :key="category.id"
            >
                <a
                    :href="category.url"
                    class="inline-block px-3.5 text-xs font-semibold uppercase tracking-wider text-[#27272a] transition-colors group-hover:text-[#9e8464]"
                >
                    @{{ category.name }}
                </a>

                <!-- Mega Menu Dropdown -->
                <div
                    class="pointer-events-none absolute top-20 z-50 max-h-145 w-max max-w-315 translate-y-1 overflow-auto rounded-b-2xl border border-[#ede8dc] bg-[#fdfbf7] p-8 opacity-0 shadow-2xl transition duration-200 ease-out group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100 ltr:left-0 rtl:right-0"
                    data-mega-menu-dropdown
                    v-if="category.children && category.children.length"
                >
                    <div class="flex gap-x-12">
                        <div
                            class="grid w-full min-w-[160px] max-w-[200px] content-start gap-4"
                            v-for="pairCategoryChildren in pairCategoryChildren(category)"
                        >
                            <template v-for="secondLevelCategory in pairCategoryChildren" :key="secondLevelCategory.id">
                                <p class="font-serif text-sm font-semibold tracking-wide text-[#18181b] hover:text-[#9e8464]">
                                    <a :href="secondLevelCategory.url">
                                        @{{ secondLevelCategory.name }}
                                    </a>
                                </p>

                                <ul
                                    class="grid gap-2 text-xs"
                                    v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                >
                                    <li
                                        v-for="thirdLevelCategory in secondLevelCategory.children"
                                        :key="thirdLevelCategory.id"
                                        class="text-[#62503d] transition-colors hover:text-[#18181b]"
                                    >
                                        <a :href="thirdLevelCategory.url">
                                            @{{ thirdLevelCategory.name }}
                                        </a>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categories Side Drawer -->
            <x-shop::drawer
                position="left"
                width="380px"
                ::is-active="isDrawerActive"
                @toggle="onDrawerToggle"
                @close="onDrawerClose"
            >
                <x-slot:toggle></x-slot>

                <x-slot:header class="border-b border-[#ede8dc] bg-[#fdfbf7] px-6 py-4">
                    <div class="flex items-center justify-between w-full">
                        <p class="font-serif text-2xl font-medium tracking-tight text-[#18181b]">
                            @lang('shop::app.components.layouts.header.desktop.bottom.categories')
                        </p>
                    </div>
                </x-slot>

                <x-slot:content class="bg-[#fdfbf7] px-0! py-2">
                    <div class="relative h-full overflow-hidden">
                        <!-- Sliding container for depth navigation -->
                        <div
                            class="flex h-full transition-transform duration-300"
                            :class="{
                                'ltr:translate-x-0 rtl:translate-x-0': currentViewLevel !== 'third',
                                'ltr:-translate-x-full rtl:translate-x-full': currentViewLevel === 'third'
                            }"
                        >
                            <!-- Main Tree (Levels 1 & 2) -->
                            <div class="h-[calc(100vh-100px)] w-full shrink-0 overflow-y-auto px-4 py-2">
                                <div
                                    v-for="category in categories"
                                    :key="category.id"
                                    class="mb-3 rounded-lg border border-[#ede8dc]/70 bg-white/70 p-1 transition-all"
                                >
                                    <div class="flex items-center justify-between px-3 py-2 transition-colors hover:text-[#9e8464]">
                                        <a
                                            :href="category.url"
                                            class="text-sm font-semibold tracking-wide text-[#18181b]"
                                        >
                                            @{{ category.name }}
                                        </a>
                                    </div>

                                    <!-- Subcategories -->
                                    <div v-if="category.children && category.children.length" class="border-t border-[#ede8dc]/50 pt-1">
                                        <div
                                            v-for="secondLevelCategory in category.children"
                                            :key="secondLevelCategory.id"
                                            class="flex cursor-pointer items-center justify-between rounded-md px-3 py-1.5 text-xs text-[#62503d] transition-colors hover:bg-[#ede8dc]/40 hover:text-[#18181b]"
                                            @click="showThirdLevel(secondLevelCategory, category, $event)"
                                        >
                                            <a :href="secondLevelCategory.url">
                                                @{{ secondLevelCategory.name }}
                                            </a>

                                            <span
                                                v-if="secondLevelCategory.children && secondLevelCategory.children.length"
                                                class="icon-arrow-right text-xs text-[#9e8464] rtl:icon-arrow-left"
                                            ></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Third Level View -->
                            <div class="h-[calc(100vh-100px)] w-full shrink-0 overflow-y-auto px-4 py-2" v-if="currentViewLevel === 'third'">
                                <div class="mb-4 border-b border-[#ede8dc] pb-3">
                                    <button
                                        type="button"
                                        @click="goBackToMainView"
                                        class="flex items-center gap-2 text-xs font-semibold text-[#9e8464] hover:text-[#18181b] transition-colors"
                                    >
                                        <span class="icon-arrow-left text-sm rtl:icon-arrow-right"></span>
                                        <span>@lang('shop::app.components.layouts.header.desktop.bottom.back-button')</span>
                                    </button>
                                    <p class="mt-2 font-serif text-lg font-medium text-[#18181b]">
                                        @{{ currentSecondLevelCategory?.name }}
                                    </p>
                                </div>

                                <div class="grid gap-1">
                                    <a
                                        v-for="thirdLevelCategory in currentSecondLevelCategory?.children"
                                        :key="thirdLevelCategory.id"
                                        :href="thirdLevelCategory.url"
                                        class="block rounded-md px-3 py-2 text-xs font-medium text-[#62503d] transition-colors hover:bg-[#ede8dc]/50 hover:text-[#18181b]"
                                    >
                                        @{{ thirdLevelCategory.name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-shop::drawer>
        </div>
    </script>

    <script type="module">
        app.component('v-desktop-category', {
            template: '#v-desktop-category-template',

            data() {
                return {
                    isLoading: true,
                    categories: [],
                    isDrawerActive: false,
                    currentViewLevel: 'main',
                    currentSecondLevelCategory: null,
                    currentParentCategory: null
                };
            },

            mounted() {
                this.initCategories();
                window.addEventListener('resize', this.positionMegaMenus);
            },

            beforeUnmount() {
                window.removeEventListener('resize', this.positionMegaMenus);
            },

            methods: {
                initCategories() {
                    try {
                        const stored = localStorage.getItem('categories');
                        if (stored) {
                            const parsed = JSON.parse(stored);
                            if (Array.isArray(parsed) && parsed.length > 0) {
                                this.categories = parsed;
                                this.isLoading = false;
                                this.$nextTick(this.positionMegaMenus);
                                return;
                            }
                        }
                    } catch (e) {}

                    this.getCategories();
                },

                getCategories() {
                    this.$axios.get("{{ route('shop.api.categories.tree') }}")
                        .then(response => {
                            this.isLoading = false;
                            if (response.data && Array.isArray(response.data.data) && response.data.data.length > 0) {
                                this.categories = response.data.data;
                                localStorage.setItem('categories', JSON.stringify(this.categories));
                            }
                            this.$nextTick(this.positionMegaMenus);
                        })
                        .catch(error => {
                            console.error("No se pudieron cargar las categorías:", error);
                            this.isLoading = false;
                        });
                },

                pairCategoryChildren(category) {
                    if (!category.children) return [];

                    return category.children.reduce((result, value, index, array) => {
                        if (index % 2 === 0) {
                            result.push(array.slice(index, index + 2));
                        }
                        return result;
                    }, []);
                },

                toggleCategoryDrawer() {
                    this.isDrawerActive = !this.isDrawerActive;
                    if (this.isDrawerActive) {
                        this.currentViewLevel = 'main';
                    }
                },

                onDrawerToggle(event) {
                    this.isDrawerActive = event.isActive;
                },

                onDrawerClose() {
                    this.isDrawerActive = false;
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
                },

                positionMegaMenus() {
                    const MARGIN = 12;
                    const viewport = document.documentElement.clientWidth;

                    (this.$el?.querySelectorAll?.('[data-mega-menu-dropdown]') ?? []).forEach(dropdown => {
                        const edge = getComputedStyle(dropdown).direction === 'rtl' ? 'right' : 'left';
                        dropdown.style.left = dropdown.style.right = '';
                        dropdown.style.maxWidth = Math.min(1260, viewport - 2 * MARGIN) + 'px';

                        const rect = dropdown.getBoundingClientRect();
                        const overflow = Math.max(0, rect.right - (viewport - MARGIN)) - Math.max(0, MARGIN - rect.left);

                        if (overflow) {
                            const base = parseFloat(getComputedStyle(dropdown)[edge]) || 0;
                            dropdown.style[edge] = base + (edge === 'left' ? -overflow : overflow) + 'px';
                        }
                    });
                }
            },
        });
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.bottom.after') !!}
