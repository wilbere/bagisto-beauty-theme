<!-- Luxury Beauty Mini Cart Vue Component -->
<v-mini-cart>
    <button
        type="button"
        class="icon-cart relative cursor-pointer text-2xl text-[#18181b] transition-transform hover:scale-105"
        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
    ></button>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

        @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
            <x-shop::drawer @open="getCart">
                <!-- Drawer Toggler -->
                <x-slot:toggle>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

                    <span class="relative">
                        <button
                            type="button"
                            class="icon-cart relative cursor-pointer text-2xl text-[#18181b] transition-transform hover:scale-110"
                            aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                            @click="getCart"
                        ></button>

                        <span
                            class="absolute -top-2 -right-2.5 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-[#18181b] px-1 text-[10px] font-bold text-white shadow-xs"
                            v-if="cart?.items_qty"
                        >
                            @{{ cart.items_qty }}
                        </span>
                    </span>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}
                </x-slot>

                <!-- Drawer Header -->
                <x-slot:header>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}

                    <div class="flex flex-col gap-1 border-b border-[#eae5db]/60 pb-4">
                        <div class="flex items-center justify-between">
                            <p class="font-editorial text-2xl font-semibold tracking-tight text-[#18181b]">
                                Tu Carrito de Belleza
                            </p>
                            <span class="text-xs text-[#71717a]" v-if="cart?.items?.length">
                                (@{{ cart.items.length }} @{{ cart.items.length === 1 ? 'producto' : 'productos' }})
                            </span>
                        </div>

                        <!-- Progressive Free Shipping Indicator -->
                        <div class="mt-3 rounded-xl border border-[#eae5db] bg-[#f7f5f0] p-3" v-if="cart?.subtotal">
                            <div class="flex justify-between text-xs text-[#18181b]">
                                <span v-if="parseFloat(cart.subtotal) >= 50" class="font-medium text-[#78866b]">
                                    ¡Felicidades! Tienes <strong>Envío Gratis</strong>
                                </span>
                                <span v-else class="text-[#52525b]">
                                    Te faltan <strong>$@{{ (50 - parseFloat(cart.subtotal)).toFixed(2) }}</strong> para <strong>Envío Gratis</strong>
                                </span>
                                <span class="font-semibold text-[#18181b]">@{{ Math.min(Math.round((parseFloat(cart.subtotal) / 50) * 100), 100) }}%</span>
                            </div>

                            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-[#eae5db]">
                                <div
                                    class="h-full rounded-full bg-[#d4a373] transition-all duration-500 ease-out"
                                    :style="{ width: `${Math.min((parseFloat(cart.subtotal) / 50) * 100, 100)}%` }"
                                ></div>
                            </div>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}
                </x-slot>

                <!-- Drawer Content -->
                <x-slot:content>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}

                    <!-- Cart Item Listing -->
                    <div
                        class="divide-y divide-[#eae5db]/60"
                        v-if="cart?.items?.length"
                    >
                        <div
                            class="flex gap-x-4 py-4"
                            v-for="item in cart?.items"
                            :key="item.id"
                        >
                            <!-- Cart Item Image (Aspect Product) -->
                            <div class="relative h-20 w-16 shrink-0 overflow-hidden rounded-xl bg-[#f7f5f0]">
                                <a :href="item.product_url_key ? '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key) : '#'">
                                    <img
                                        :src="item.base_image?.small_image_url"
                                        :alt="item.base_image?.alt"
                                        class="h-full w-full object-cover"
                                    />
                                </a>
                            </div>

                            <!-- Cart Item Information -->
                            <div class="flex flex-1 flex-col justify-between">
                                <div class="flex justify-between gap-2">
                                    <a
                                        :href="item.product_url_key ? '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key) : '#'"
                                        class="line-clamp-2 text-sm font-medium text-[#18181b] hover:text-[#d4a373]"
                                    >
                                        @{{ item.name }}
                                    </a>

                                    <button
                                        type="button"
                                        class="text-[#a1a1aa] hover:text-red-500 transition-colors"
                                        @click="removeItem(item.id)"
                                        title="Eliminar"
                                    >
                                        <span class="icon-cancel text-lg"></span>
                                    </button>
                                </div>

                                <div class="mt-2 flex items-center justify-between">
                                    <!-- Quantity Controller -->
                                    <div class="flex items-center rounded-full border border-[#eae5db] bg-white px-2 py-0.5 text-xs text-[#18181b]">
                                        <button
                                            type="button"
                                            class="px-1 font-bold text-[#71717a] hover:text-[#18181b]"
                                            :disabled="item.quantity <= 1"
                                            @click="updateItemQuantity(item.id, item.quantity - 1)"
                                        >-</button>
                                        <span class="px-2 font-semibold">@{{ item.quantity }}</span>
                                        <button
                                            type="button"
                                            class="px-1 font-bold text-[#71717a] hover:text-[#18181b]"
                                            @click="updateItemQuantity(item.id, item.quantity + 1)"
                                        >+</button>
                                    </div>

                                    <!-- Price Display -->
                                    <p class="text-sm font-semibold text-[#18181b]">
                                        @{{ item.formatted_price }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Cart State -->
                    <div
                        class="flex flex-col items-center justify-center py-16 text-center"
                        v-else
                    >
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f7f4ee] text-2xl text-[#d4a373]">
                            <span class="icon-cart"></span>
                        </div>

                        <h3 class="mt-4 font-editorial text-lg font-semibold text-[#18181b]">
                            Tu bolsa está vacía
                        </h3>

                        <p class="mt-1 text-xs text-[#71717a] max-w-xs">
                            Descubre nuestros esmaltes, geles y tratamientos profesionales para comenzar.
                        </p>

                        <a
                            href="{{ route('shop.home.index') }}"
                            class="mt-6 rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white transition-colors hover:bg-[#d4a373]"
                        >
                            Explorar Colección
                        </a>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}
                </x-slot>

                <!-- Drawer Footer (Subtotal & Checkout Actions) -->
                <x-slot:footer>
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.footer.before') !!}

                    <div
                        class="border-t border-[#eae5db]/60 pt-4"
                        v-if="cart?.items?.length"
                    >
                        <div class="flex items-center justify-between text-base font-semibold text-[#18181b]">
                            <span>Subtotal</span>
                            <span>@{{ cart.formatted_subtotal }}</span>
                        </div>

                        <p class="mt-1 text-[11px] text-[#71717a]">
                            Impuestos y costo de envío calculados en el checkout.
                        </p>

                        <div class="mt-4 grid gap-2">
                            <a
                                href="{{ route('shop.checkout.onepage.index') }}"
                                class="flex w-full items-center justify-center rounded-full bg-[#18181b] py-3 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#d4a373]"
                            >
                                Finalizar Pedido
                            </a>

                            <a
                                href="{{ route('shop.checkout.cart.index') }}"
                                class="flex w-full items-center justify-center rounded-full border border-[#eae5db] bg-white py-2.5 text-xs font-semibold uppercase tracking-wider text-[#18181b] transition-colors hover:bg-[#f7f5f0]"
                            >
                                Ver Carrito Completo
                            </a>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.footer.after') !!}
                </x-slot>
            </x-shop::drawer>
        @endif

        {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
    </script>

    <script type="module">
        app.component('v-mini-cart', {
            template: '#v-mini-cart-template',

            data() {
                return {
                    cart: null,
                    isLoading: false,
                    displayTax: {
                        prices: "{{ core()->getConfigData('tax.calculation.display.prices') }}",
                        subtotal: "{{ core()->getConfigData('tax.calculation.display.subtotal') }}",
                    },
                };
            },

            mounted() {
                this.getCart();

                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });
            },

            methods: {
                getCart() {
                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then((response) => {
                            this.cart = response.data.data;
                            window.beautyEmitter.emit('cart:updated', this.cart);
                        })
                        .catch(() => {});
                },

                updateItemQuantity(itemId, quantity) {
                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', {
                        qty: { [itemId]: quantity }
                    })
                    .then((response) => {
                        this.cart = response.data.data;
                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        window.beautyEmitter.emit('cart:updated', this.cart);
                    })
                    .catch((error) => {
                        this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                    });
                },

                removeItem(itemId) {
                    this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                        _method: 'DELETE',
                        cart_item_id: itemId,
                    })
                    .then((response) => {
                        this.cart = response.data.data;
                        this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                        window.beautyEmitter.emit('cart:updated', this.cart);
                    })
                    .catch((error) => {
                        this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                    });
                },
            },
        });
    </script>
@endPushOnce
