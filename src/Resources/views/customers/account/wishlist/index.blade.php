<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.wishlist.page-title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="wishlist" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-1 min-w-0 w-full">
        <!-- Wishlist Vue Component -->
        <v-wishlist-products>
            <!-- Wishlist Shimmer Effect -->
            <x-shop::shimmer.customers.account.wishlist :count="4" />
        </v-wishlist-products>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-wishlist-products-template"
        >
            <div>
                <!-- Wishlist Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.wishlist :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.before') !!}

                <!-- Wishlist Information -->
                <template v-else>
                    <div class="flex items-center justify-between border-b border-[#ede8dc] pb-5">
                        <div class="flex items-center gap-3">
                            <!-- Back Button -->
                            <a
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] md:hidden shadow-xs transition-colors"
                                href="{{ route('shop.customers.account.index') }}"
                            >
                                <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                            </a>

                            <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                                @lang('shop::app.customers.account.wishlist.page-title')
                            </h1>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.before') !!}

                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-5 py-2 text-xs font-semibold text-red-600 transition-all hover:bg-red-50 hover:border-red-300 shadow-xs cursor-pointer"
                            @click="removeAll"
                            v-if="wishlistItems.length"
                        >
                            <span class="icon-bin text-base"></span>
                            @lang('shop::app.customers.account.wishlist.delete-all')
                        </button>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.delete_all.after') !!}
                    </div>

                    <!-- Wishlist Items -->
                    <template v-if="wishlistItems.length">
                        <div class="mt-6 flex flex-col gap-4">
                            <v-wishlist-products-item
                                v-for="(wishlist, index) in wishlistItems"
                                :wishlist="wishlist"
                                :key="wishlist.id"
                                @wishlist-items="(items) => wishlistItems = items"
                            >
                                <x-shop::shimmer.customers.account.wishlist />
                            </v-wishlist-products-item>
                        </div>
                    </template>

                    <!-- Empty Wishlist -->
                    <template v-else>
                        <div class="m-auto grid w-full place-content-center items-center justify-items-center py-24 text-center">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fdfbf7] border border-[#ede8dc] text-[#9e8464] mb-4">
                                <span class="icon-heart text-3xl"></span>
                            </div>

                            <h3 class="font-serif text-xl font-bold text-[#18181b]">
                                @lang('shop::app.customers.account.wishlist.empty')
                            </h3>

                            <p class="mt-1 text-sm text-[#71717a] max-w-sm">
                                Explora nuestra tienda y guarda tus productos favoritos aquí.
                            </p>

                            <a
                                href="{{ route('shop.home.index') }}"
                                class="mt-6 inline-flex items-center justify-center rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold tracking-wide text-white transition-all hover:bg-[#9e8464] shadow-xs"
                            >
                                Explorar Colección
                            </a>
                        </div>
                    </template>
                </template>

                {!! view_render_event('bagisto.shop.customers.account.wishlist.list.after') !!}
            </div>
        </script>

        <script
            type="text/x-template"
            id="v-wishlist-products-item-template"
        >
            <div class="rounded-2xl border border-[#ede8dc] bg-white p-5 sm:p-6 shadow-xs transition-all hover:border-[#9e8464]/60">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    <div class="flex items-start gap-4 sm:gap-6 flex-1 min-w-0">
                        {!! view_render_event('bagisto.shop.customers.account.wishlist.image.before') !!}

                        <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', wishlist.product.url_key)" class="shrink-0">
                            <!-- Wishlist Item Image -->
                            <img
                                class="h-20 w-20 sm:h-24 sm:w-24 rounded-xl object-cover border border-[#ede8dc]"
                                :src="wishlist.product.base_image.small_image_url"
                                :alt="wishlist.product.base_image.alt"
                            />
                        </a>

                        {!! view_render_event('bagisto.shop.customers.account.wishlist.image.after') !!}

                        <div class="flex-1 min-w-0">
                            <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', wishlist.product.url_key)">
                                <p class="text-base font-semibold text-[#18181b] hover:text-[#9e8464] transition-colors truncate">
                                    @{{ wishlist.product.name }}
                                </p>
                            </a>

                            <div class="mt-1 font-serif text-lg font-bold text-[#18181b]" v-html="wishlist.product.price_html"></div>

                            <!-- Wishlist Item attributes -->
                            <div
                                class="mt-2 flex flex-wrap gap-2"
                                v-if="wishlist.options?.attributes"
                            >
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 text-xs font-medium text-[#9e8464] hover:underline cursor-pointer"
                                    @click="wishlist.option_show = ! wishlist.option_show"
                                >
                                    @lang('shop::app.customers.account.wishlist.see-details')

                                    <span
                                        class="text-base"
                                        :class="{
                                            'icon-arrow-up': wishlist.option_show,
                                            'icon-arrow-down': ! wishlist.option_show
                                        }"
                                    ></span>
                                </button>

                                <div
                                    class="w-full mt-2 grid gap-1 text-xs text-[#71717a] bg-[#fdfbf7] p-3 rounded-xl border border-[#ede8dc]"
                                    v-show="wishlist.option_show"
                                >
                                    <div v-for="option in wishlist.options?.attributes" class="flex gap-2">
                                        <span class="font-semibold text-[#18181b]">@{{ option.attribute_name }}:</span>
                                        <template v-if="option?.attribute_type === 'file'">
                                            <a :href="option.file_url" class="text-[#9e8464] underline" target="_blank" :download="option.file_name">
                                                @{{ option.file_name }}
                                            </a>
                                        </template>
                                        <template v-else>
                                            <span>@{{ option.option_label }}</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions & Quantity -->
                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto border-t sm:border-t-0 border-[#ede8dc] pt-3 sm:pt-0">
                        <x-shop::quantity-changer
                            name="quantity"
                            ::value="wishlist.options.quantity ?? 1"
                            class="flex h-9 items-center gap-2 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-3 text-xs"
                            :removable="true"
                            @change="(qty) => wishlist.quantity = qty"
                            @remove="remove"
                        />

                        @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                            <x-shop::button
                                class="inline-flex items-center justify-center rounded-full bg-[#18181b] px-5 py-2 text-xs font-semibold text-white transition-all hover:bg-[#9e8464] shadow-xs cursor-pointer active:scale-[0.99]"
                                :title="trans('shop::app.customers.account.wishlist.move-to-cart')"
                                ::loading="movingToCart"
                                ::disabled="movingToCart"
                                @click="moveToCart"
                            />
                        @endif

                        <button
                            type="button"
                            class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full border border-red-200 text-red-600 transition-colors hover:bg-red-50 hover:border-red-300"
                            @click="remove"
                            aria-label="@lang('shop::app.customers.account.wishlist.remove')"
                        >
                            <span class="icon-bin text-base"></span>
                        </button>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component("v-wishlist-products", {
                template: '#v-wishlist-products-template',

                data() {
                    return {
                        isLoading: true,

                        wishlistItems: [],
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.api.customers.account.wishlist.index') }}")
                            .then(response => {
                                this.isLoading = false;

                                this.wishlistItems = response.data.data;
                            })
                            .catch(error => {});
                    },

                    removeAll() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios.post("{{ route('shop.api.customers.account.wishlist.destroy_all') }}", {
                                        '_method': 'DELETE',
                                    })
                                    .then(response => {
                                        this.wishlistItems = [];

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                                    })
                                    .catch(error => {});
                            },
                        });
                    },
                },
            });

            app.component('v-wishlist-products-item', {
                template: '#v-wishlist-products-item-template',

                props: ['wishlist'],

                emits: ['wishlist-items'],

                data() {
                    return {
                        movingToCart: false,
                    };
                },

                methods: {
                    remove() {
                        this.$emitter.emit('open-confirm-modal', {
                            agree: () => {
                                this.$axios
                                    .delete('{{ route('shop.api.customers.account.wishlist.destroy', ':id') }}'.replace(':id', this.wishlist.id))
                                    .then(response => {
                                        this.$emit('wishlist-items', response.data.data);

                                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                    })
                                    .catch(error => {});
                            },
                        });
                    },

                    moveToCart() {
                        this.movingToCart = true;

                        const endpoint = `{{ route('shop.api.customers.account.wishlist.move_to_cart', ':wishlistId:') }}`.replace(':wishlistId:', this.wishlist.id);

                        this.$axios.post(endpoint, {
                                quantity: (this.wishlist.quantity ?? this.wishlist.options.quantity) ?? 1,
                                product_id: this.wishlist.product.id,
                            })
                            .then(response => {
                                if (response.data?.redirect) {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });

                                    window.location.href = response.data.data;

                                    return;
                                }

                                this.$emit('wishlist-items', response.data.data?.wishlist);

                                this.$emitter.emit('update-mini-cart', response.data.data.cart);

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.movingToCart = false;
                            })
                            .catch(error => {
                                this.movingToCart = false;

                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });
                            });
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
