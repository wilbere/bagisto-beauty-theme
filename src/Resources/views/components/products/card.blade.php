<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-card-template"
    >
        <!-- Luxury Beauty Product Card (Grid Mode) -->
        <div
            class="group relative flex w-full flex-col overflow-hidden rounded-2xl border border-[#eae5db]/60 bg-white p-3 transition-all duration-300 hover:border-[#d4a373]/50 hover:shadow-lg"
            v-if="mode != 'list'"
        >
            <!-- Media / Image Container (4:5 Aspect Ratio) -->
            <div class="relative w-full overflow-hidden rounded-xl bg-[#f7f5f0] aspect-product">
                {!! view_render_event('bagisto.shop.components.products.card.image.before') !!}

                <a
                    :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                    :aria-label="product.name"
                    class="block h-full w-full"
                >
                    <x-shop::media.images.lazy
                        class="h-full w-full object-cover object-center transition-transform duration-500 ease-out group-hover:scale-105"
                        ::src="product.base_image.medium_image_url"
                        ::srcset="`
                            ${product.base_image.small_image_url} 150w,
                            ${product.base_image.medium_image_url} 300w,
                        `"
                        sizes="(max-width: 768px) 150px, (max-width: 1200px) 300px, 600px"
                        ::key="product.id"
                        ::index="product.id"
                        width="291"
                        height="364"
                        ::alt="product.base_image.alt"
                    />
                </a>

                {!! view_render_event('bagisto.shop.components.products.card.image.after') !!}

                <!-- Badges (Top Left) -->
                <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
                    <span
                        class="inline-block rounded-full bg-[#18181b] px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white shadow-xs"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </span>

                    <span
                        class="inline-block rounded-full bg-[#d4a373] px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white shadow-xs"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
                    </span>
                </div>

                <!-- Floating Quick Actions (Top Right) -->
                <div class="absolute top-2.5 right-2.5 flex flex-col gap-1.5 z-10 opacity-0 transition-opacity duration-200 group-hover:opacity-100 max-lg:opacity-100">
                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                        <button
                            type="button"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-sm text-[#18181b] shadow-xs backdrop-blur-xs transition-transform hover:scale-110 hover:text-red-500"
                            :class="product.is_wishlist ? 'text-red-500' : ''"
                            @click="addToWishlist()"
                            aria-label="@lang('shop::app.components.products.card.add-to-wishlist')"
                        >
                            <span :class="product.is_wishlist ? 'icon-heart-fill' : 'icon-heart'"></span>
                        </button>
                    @endif

                    @if (core()->getConfigData('catalog.products.settings.compare_option'))
                        <button
                            type="button"
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-sm text-[#18181b] shadow-xs backdrop-blur-xs transition-transform hover:scale-110"
                            @click="addToCompare(product.id)"
                            aria-label="@lang('shop::app.components.products.card.add-to-compare')"
                        >
                            <span class="icon-compare"></span>
                        </button>
                    @endif
                </div>

                <!-- Average Ratings Badge (Bottom Left of Media) -->
                <div class="absolute bottom-2 left-2 z-10" v-if="product.ratings.total">
                    <x-shop::products.ratings
                        class="rounded-full bg-white/90 px-2 py-0.5 text-[11px] shadow-xs backdrop-blur-xs"
                        ::average="product.ratings.average"
                        ::total="product.ratings.total"
                        ::rating="false"
                    />
                </div>
            </div>

            <!-- Content Section -->
            <div class="flex flex-1 flex-col justify-between pt-3">
                <div>
                    {!! view_render_event('bagisto.shop.components.products.card.name.before') !!}

                    <a
                        :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                        class="line-clamp-2 text-sm font-medium text-[#18181b] transition-colors hover:text-[#d4a373]"
                    >
                        @{{ product.name }}
                    </a>

                    {!! view_render_event('bagisto.shop.components.products.card.name.after') !!}
                </div>

                <!-- Pricing & Quick CTA -->
                <div class="mt-3">
                    {!! view_render_event('bagisto.shop.components.products.card.price.before') !!}

                    <div
                        class="text-sm font-semibold text-[#18181b]"
                        v-html="product.price_html"
                    ></div>

                    {!! view_render_event('bagisto.shop.components.products.card.price.after') !!}

                    @if (core()->getConfigData('sales.checkout.shopping_cart.cart_page'))
                        <div class="mt-3">
                            <button
                                type="button"
                                class="w-full rounded-full bg-[#18181b] py-2 text-xs font-semibold uppercase tracking-wider text-white transition-all hover:bg-[#d4a373] disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="!product.is_saleable || isAddingToCart"
                                @click="addToCart()"
                            >
                                <span v-if="isAddingToCart">Agregando...</span>
                                <span v-else>@lang('shop::app.components.products.card.add-to-cart')</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- List View Mode (Fall back gracefully to compact horizontal row) -->
        <div
            class="flex gap-4 rounded-2xl border border-[#eae5db]/60 bg-white p-4 transition-all hover:shadow-md"
            v-else
        >
            <div class="relative h-32 w-28 shrink-0 overflow-hidden rounded-xl bg-[#f7f5f0]">
                <a :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)">
                    <img
                        :src="product.base_image.medium_image_url"
                        :alt="product.base_image.alt"
                        class="h-full w-full object-cover"
                    />
                </a>
            </div>

            <div class="flex flex-1 flex-col justify-between">
                <div>
                    <a
                        :href="'{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', product.url_key)"
                        class="text-base font-medium text-[#18181b] hover:text-[#d4a373]"
                    >
                        @{{ product.name }}
                    </a>

                    <div
                        class="mt-2 text-base font-semibold text-[#18181b]"
                        v-html="product.price_html"
                    ></div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        class="rounded-full bg-[#18181b] px-6 py-2 text-xs font-semibold uppercase tracking-wider text-white transition-colors hover:bg-[#d4a373]"
                        :disabled="!product.is_saleable || isAddingToCart"
                        @click="addToCart()"
                    >
                        @lang('shop::app.components.products.card.add-to-cart')
                    </button>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isAddingToCart: false,
                };
            },

            methods: {
                addToCart() {
                    if (! this.product.is_saleable) {
                        return;
                    }

                    if (this.product.type === 'configurable' || this.product.type === 'grouped' || this.product.type === 'bundle') {
                        window.location.href = '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', this.product.url_key);
                        return;
                    }

                    this.isAddingToCart = true;

                    this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', {
                        quantity: 1,
                        product_id: this.product.id,
                    })
                    .then((response) => {
                        this.isAddingToCart = false;

                        if (response.data.data) {
                            this.$emitter.emit('update-mini-cart', response.data.data);
                            window.beautyEmitter.emit('cart:updated', response.data.data);

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message,
                            });
                        } else {
                            this.$emitter.emit('add-flash', {
                                type: 'warning',
                                message: response.data.message,
                            });
                        }
                    })
                    .catch((error) => {
                        this.isAddingToCart = false;

                        this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: error.response.data.message,
                        });
                    });
                },

                addToWishlist() {
                    this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                        product_id: this.product.id,
                    })
                    .then((response) => {
                        this.product.is_wishlist = ! this.product.is_wishlist;

                        this.$emitter.emit('add-flash', {
                            type: 'success',
                            message: response.data.message,
                        });
                    })
                    .catch((error) => {
                        window.location.href = '{{ route('shop.customer.session.index') }}';
                    });
                },

                addToCompare(productId) {
                    this.$axios.post('{{ route('shop.api.compare.store') }}', {
                        product_id: productId,
                    })
                    .then((response) => {
                        this.$emitter.emit('add-flash', {
                            type: 'success',
                            message: response.data.message,
                        });
                    })
                    .catch((error) => {
                        this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: error.response.data.message,
                        });
                    });
                },
            },
        });
    </script>
@endPushOnce
