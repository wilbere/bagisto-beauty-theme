<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.cart.index.cart')"/>
    <meta name="keywords" content="@lang('shop::app.checkout.cart.index.cart')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="true"
    :has-footer="true"
>
    <!-- Page Title Slot -->
    <x-slot:title>
        @lang('shop::app.checkout.cart.index.cart')
    </x-slot>

    {!! view_render_event('bagisto.shop.checkout.cart.header.before') !!}
    {!! view_render_event('bagisto.shop.checkout.cart.header.after') !!}

    <div class="min-h-[70vh] bg-[#fdfbf7] pb-28 lg:pb-0">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <!-- Breadcrumbs & Quick Store Return Navigation -->
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="group inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#71717a] transition-colors hover:text-[#9e8464]"
                >
                    <span class="transition-transform group-hover:-translate-x-1">&larr;</span>
                    <span>@lang('shop::app.checkout.cart.index.continue-shopping')</span>
                </a>

                {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.before') !!}

                @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
                    <x-shop::breadcrumbs name="cart" />
                @endif

                {!! view_render_event('bagisto.shop.checkout.cart.breadcrumbs.after') !!}
            </div>

            <!-- Page Title Area -->
            <div class="mb-8 border-b border-[#ede8dc] pb-6">
                <h1 class="font-editorial text-3xl font-semibold tracking-tight text-[#18181b] sm:text-4xl">
                    @lang('shop::app.checkout.cart.index.cart')
                </h1>
            </div>

            @php
                $errors = \Webkul\Checkout\Facades\Cart::getErrors();
            @endphp

            @if (! empty($errors) && $errors['error_code'] === 'MINIMUM_ORDER_AMOUNT')
                <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs font-medium text-amber-800">
                    {{ $errors['message'] }}: {{ $errors['amount'] }}
                </div>
            @endif

            <v-cart ref="vCart">
                <!-- Cart Shimmer Effect -->
                <x-shop::shimmer.checkout.cart :count="3" />
            </v-cart>
        </div>
    </div>

    @if (core()->getConfigData('sales.checkout.shopping_cart.cross_sell'))
        {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.before') !!}

        <!-- Cross-sell Product Carousel -->
        <x-shop::products.carousel
            :title="trans('shop::app.checkout.cart.index.cross-sell.title')"
            :src="route('shop.api.checkout.cart.cross_sell.index')"
        />

        {!! view_render_event('bagisto.shop.checkout.cart.cross_sell_carousel.after') !!}
    @endif

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-cart-template"
        >
            <div>
                <!-- Loading State Shimmer -->
                <template v-if="isLoading">
                    <x-shop::shimmer.checkout.cart :count="3" />
                </template>

                <!-- Cart Content -->
                <template v-else>
                    <div
                        class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12 lg:gap-10"
                        v-if="cart?.items?.length"
                    >
                        <!-- Left Column: Item List & Controls -->
                        <div class="space-y-6 lg:col-span-7 xl:col-span-8">
                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.before') !!}

                            <!-- Mass Action Bar -->
                            <div class="flex items-center justify-between rounded-xl border border-[#ede8dc] bg-white px-4 py-3 shadow-2xs">
                                <label class="flex cursor-pointer select-none items-center gap-3">
                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        class="h-4.5 w-4.5 cursor-pointer rounded border-[#ede8dc] text-[#18181b] accent-[#18181b] focus:ring-[#9e8464]"
                                        v-model="allSelected"
                                        @change="selectAll"
                                    >

                                    <span class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">
                                        @{{ "@lang('shop::app.checkout.cart.index.items-selected')".replace(':count', selectedItemsCount) }}
                                    </span>
                                </label>

                                <div class="flex items-center gap-4" v-if="selectedItemsCount">
                                    <button
                                        type="button"
                                        class="cursor-pointer text-xs font-semibold text-red-600 transition-colors hover:underline"
                                        @click="removeSelectedItems"
                                    >
                                        @lang('shop::app.checkout.cart.index.remove')
                                    </button>

                                    @if (
                                        auth()->guard()->check()
                                        && core()->getConfigData('customer.settings.wishlist.wishlist_option')
                                    )
                                        <span class="h-3 w-px bg-[#ede8dc]"></span>

                                        <button
                                            type="button"
                                            class="cursor-pointer text-xs font-medium text-[#71717a] transition-colors hover:text-[#18181b]"
                                            @click="moveToWishlistSelectedItems"
                                        >
                                            @lang('shop::app.checkout.cart.index.move-to-wishlist')
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.cart_mass_actions.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.before') !!}

                            <!-- Cart Items List -->
                            <div class="space-y-4">
                                <div
                                    class="rounded-2xl border border-[#ede8dc] bg-white p-4 transition-shadow hover:shadow-xs sm:p-5"
                                    v-for="item in cart?.items"
                                    :key="item.id"
                                >
                                    <div class="flex items-start gap-3 sm:gap-5">
                                        <!-- Item Selection Checkbox -->
                                        <div class="pt-2">
                                            <input
                                                type="checkbox"
                                                :id="'item_' + item.id"
                                                class="h-4.5 w-4.5 cursor-pointer rounded border-[#ede8dc] text-[#18181b] accent-[#18181b] focus:ring-[#9e8464]"
                                                v-model="item.selected"
                                                @change="updateAllSelected"
                                            >
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item_image.before') !!}

                                        <!-- Item Thumbnail -->
                                        <div class="h-24 w-20 shrink-0 overflow-hidden rounded-xl border border-[#ede8dc]/80 bg-[#f7f4ee] sm:h-28 sm:w-24">
                                            <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key)">
                                                <img
                                                    :src="item.base_image?.small_image_url"
                                                    :alt="item.base_image?.alt || item.name"
                                                    class="h-full w-full object-cover object-center"
                                                    loading="lazy"
                                                />
                                            </a>
                                        </div>

                                        {!! view_render_event('bagisto.shop.checkout.cart.item_image.after') !!}

                                        <!-- Item Information & Details -->
                                        <div class="flex flex-1 flex-col justify-between self-stretch">
                                            <div>
                                                <div class="flex items-start justify-between gap-4">
                                                    <div>
                                                        {!! view_render_event('bagisto.shop.checkout.cart.item_name.before') !!}

                                                        <a
                                                            :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key)"
                                                            class="line-clamp-2 text-sm font-medium text-[#18181b] transition-colors hover:text-[#9e8464] sm:text-base"
                                                        >
                                                            @{{ item.name }}
                                                        </a>

                                                        {!! view_render_event('bagisto.shop.checkout.cart.item_name.after') !!}

                                                        {!! view_render_event('bagisto.shop.checkout.cart.item_details.before') !!}

                                                        <!-- Item Options / Variant Tags -->
                                                        <div
                                                            class="mt-1 flex flex-wrap gap-1.5"
                                                            v-if="item.options && item.options.length"
                                                        >
                                                            <span
                                                                v-for="attribute in item.options"
                                                                class="inline-block rounded-md bg-[#f7f4ee] px-2 py-0.5 text-[11px] text-[#52525b]"
                                                            >
                                                                <span class="text-[#71717a]">@{{ attribute.attribute_name }}:</span> @{{ attribute.option_label }}
                                                            </span>
                                                        </div>

                                                        {!! view_render_event('bagisto.shop.checkout.cart.item_details.after') !!}
                                                    </div>

                                                    <!-- Desktop Item Total Price -->
                                                    <div class="text-right">
                                                        {!! view_render_event('bagisto.shop.checkout.cart.total.before') !!}

                                                        <span class="font-editorial text-lg font-semibold text-[#18181b] sm:text-xl">
                                                            <template v-if="displayTax.prices == 'including_tax'">
                                                                @{{ item.formatted_total_incl_tax }}
                                                            </template>
                                                            <template v-else-if="displayTax.prices == 'both'">
                                                                @{{ item.formatted_total_incl_tax }}
                                                                <span class="block text-xs font-normal text-[#71717a]">
                                                                    @lang('shopTheme::app.checkout.cart.index.excl-tax') @{{ item.formatted_total }}
                                                                </span>
                                                            </template>
                                                            <template v-else>
                                                                @{{ item.formatted_total }}
                                                            </template>
                                                        </span>

                                                        {!! view_render_event('bagisto.shop.checkout.cart.total.after') !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quantity Changer & Remove Button Row -->
                                            <div class="mt-4 flex items-center justify-between border-t border-[#ede8dc]/60 pt-3">
                                                <!-- Pill Quantity Selector -->
                                                <div class="inline-flex items-center gap-2 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-2.5 py-1 text-xs">
                                                    <button
                                                        type="button"
                                                        class="flex h-6 w-6 cursor-pointer items-center justify-center rounded-full text-base font-bold text-[#71717a] transition-colors hover:bg-[#ede8dc] hover:text-[#18181b] disabled:cursor-not-allowed disabled:opacity-30"
                                                        :disabled="(applied.quantity[item.id] !== undefined ? applied.quantity[item.id] : item.quantity) <= 1 || isStoring"
                                                        @click="setItemQuantity(item.id, (applied.quantity[item.id] !== undefined ? applied.quantity[item.id] : item.quantity) - 1)"
                                                        aria-label="Disminuir cantidad"
                                                    >-</button>

                                                    <span class="min-w-6 text-center text-xs font-semibold text-[#18181b]">
                                                        @{{ applied.quantity[item.id] !== undefined ? applied.quantity[item.id] : item.quantity }}
                                                    </span>

                                                    <button
                                                        type="button"
                                                        class="flex h-6 w-6 cursor-pointer items-center justify-center rounded-full text-base font-bold text-[#71717a] transition-colors hover:bg-[#ede8dc] hover:text-[#18181b] disabled:cursor-not-allowed disabled:opacity-30"
                                                        :disabled="isStoring"
                                                        @click="setItemQuantity(item.id, (applied.quantity[item.id] !== undefined ? applied.quantity[item.id] : item.quantity) + 1)"
                                                        aria-label="Aumentar cantidad"
                                                    >+</button>
                                                </div>

                                                <!-- Remove Button with Trash SVG -->
                                                <button
                                                    type="button"
                                                    class="inline-flex cursor-pointer items-center gap-1.5 text-xs font-medium text-[#71717a] transition-colors hover:text-red-600"
                                                    @click="removeItem(item.id)"
                                                    title="@lang('shop::app.checkout.cart.index.remove')"
                                                >
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>@lang('shop::app.checkout.cart.index.remove')</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.item.listing.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.before') !!}

                            <!-- Cart Bottom Actions -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                                <a
                                    href="{{ route('shop.home.index') }}"
                                    class="rounded-full border border-[#ede8dc] bg-white px-7 py-3 text-xs font-semibold uppercase tracking-wider text-[#18181b] shadow-2xs transition-colors hover:bg-[#f7f5f0]"
                                >
                                    @lang('shop::app.checkout.cart.index.continue-shopping')
                                </a>

                                <button
                                    type="button"
                                    class="cursor-pointer rounded-full bg-[#18181b] px-7 py-3 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-all hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                    :class="{'ring-2 ring-[#9e8464] ring-offset-2': Object.keys(applied.quantity).length}"
                                    :disabled="isStoring || !Object.keys(applied.quantity).length"
                                    @click="update()"
                                >
                                    <span v-if="! isStoring">
                                        @lang('shop::app.checkout.cart.index.update-cart')
                                        <span v-if="Object.keys(applied.quantity).length" class="ml-1 text-[10px] text-[#d4a373]">●</span>
                                    </span>
                                    <span v-else>@lang('beauty::app.products.processing')</span>
                                </button>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.cart.controls.after') !!}
                        </div>

                        <!-- Right Column: Order Summary -->
                        <div class="lg:col-span-5 xl:col-span-4">
                            {!! view_render_event('bagisto.shop.checkout.cart.summary.before') !!}

                            @include('shop::checkout.cart.summary')

                            {!! view_render_event('bagisto.shop.checkout.cart.summary.after') !!}
                        </div>
                    </div>

                    <!-- Mobile Sticky Checkout Bottom Bar -->
                    <div
                        v-if="cart?.items?.length"
                        class="fixed bottom-0 inset-x-0 z-40 border-t border-[#ede8dc] bg-white/95 px-4 py-3 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] backdrop-blur-md lg:hidden"
                    >
                        <div class="mx-auto flex max-w-lg items-center justify-between gap-4">
                            <div class="min-w-0">
                                <span class="block text-[10px] uppercase tracking-wider text-[#71717a]">@lang('shop::app.checkout.cart.summary.grand-total')</span>
                                <span class="font-editorial text-lg font-bold text-[#18181b] truncate block">
                                    @{{ cart.formatted_grand_total || cart.formatted_sub_total }}
                                </span>
                            </div>

                            <a
                                href="{{ route('shop.checkout.onepage.index') }}"
                                class="flex flex-1 items-center justify-center rounded-full bg-[#18181b] px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464]"
                            >
                                @lang('shop::app.checkout.cart.index.proceed-to-checkout') &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Empty Cart State -->
                    <div
                        class="flex flex-col items-center justify-center py-24 text-center"
                        v-else
                    >
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#f7f4ee] text-3xl text-[#9e8464]">
                            <svg class="h-10 w-10 text-[#9e8464]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>

                        <h2 class="mt-6 font-editorial text-3xl font-semibold text-[#18181b]">
                            @lang('beauty::app.cart.empty-title')
                        </h2>

                        <p class="mt-2 max-w-sm text-sm text-[#71717a]">
                            @lang('beauty::app.cart.empty-description')
                        </p>

                        <a
                            href="{{ route('shop.home.index') }}"
                            class="mt-8 rounded-full bg-[#18181b] px-8 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-colors hover:bg-[#9e8464]"
                        >
                            @lang('beauty::app.cart.explore-collection')
                        </a>
                    </div>
                </template>
            </div>
        </script>

        <script type="module">
            app.component("v-cart", {
                template: '#v-cart-template',

                data() {
                    return  {
                        refreshKey: 0,

                        cart: [],

                        allSelected: false,

                        applied: {
                            quantity: {},
                        },

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",

                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",

                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isLoading: true,

                        isStoring: false,
                    };
                },

                mounted() {
                    this.getCart();
                },

                computed: {
                    selectedItemsCount() {
                        return this.cart?.items?.filter(item => item.selected)?.length || 0;
                    },
                },

                methods: {
                    getCart() {
                        this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                            .then(response => {
                                this.cart = response.data.data;

                                this.isLoading = false;

                                if (response.data.message) {
                                    this.$emitter.emit('add-flash', { type: 'info', message: response.data.message });
                                }
                            })
                            .catch(error => {
                                this.isLoading = false;
                            });
                    },

                    setCart(cart) {
                        this.cart = cart;
                    },

                    selectAll() {
                        if (! this.cart?.items) return;

                        for (let item of this.cart.items) {
                            item.selected = this.allSelected;
                        }
                    },

                    updateAllSelected() {
                        if (! this.cart?.items) return;

                        this.allSelected = this.cart.items.every(item => item.selected);
                    },

                    update() {
                        this.isStoring = true;

                        this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', { qty: this.applied.quantity })
                            .then(response => {
                                if (response.data.data?.items !== undefined) {
                                    this.cart = response.data.data;

                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'warning',
                                        message: response.data.data?.message || response.data.message,
                                    });
                                }

                                this.isStoring = false;
                                this.applied.quantity = {};
                                this.refreshKey++;
                            })
                            .catch(error => {
                                this.isStoring = false;
                                this.applied.quantity = {};
                                this.refreshKey++;
                            });
                    },

                    setItemQuantity(itemId, quantity) {
                        if (quantity < 1) return;
                        this.applied.quantity[itemId] = quantity;
                    },

                    removeItem(itemId) {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                                        '_method': 'DELETE',
                                        'cart_item_id': itemId,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    removeSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                this.$axios.post('{{ route('shop.api.checkout.cart.destroy_selected') }}', {
                                        '_method': 'DELETE',
                                        'ids': selectedItemsIds,
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },

                    moveToWishlistSelectedItems() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                const selectedItemsIds = this.cart.items.flatMap(item => item.selected ? item.id : []);

                                const selectedItemsQty = this.cart.items.filter(item => item.selected).map(item => this.applied.quantity[item.id] ?? item.quantity);

                                this.$axios.post('{{ route('shop.api.checkout.cart.move_to_wishlist') }}', {
                                        'ids': selectedItemsIds,
                                        'qty': selectedItemsQty
                                    })
                                    .then(response => {
                                        this.cart = response.data.data;

                                        this.$emitter.emit('update-mini-cart', response.data.data);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            }
                        });
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
