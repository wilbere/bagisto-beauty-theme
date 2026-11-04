<!-- Luxury Beauty Mini Cart Vue Component -->
<v-mini-cart>
    <button
        type="button"
        class="relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-full text-[#18181b] transition-all hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
        aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
    >
        <span class="icon-cart text-2xl" role="presentation"></span>
    </button>
</v-mini-cart>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mini-cart-template"
    >
        <div class="relative">
            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.before') !!}

            @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
                <button
                    type="button"
                    class="relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-full text-[#18181b] transition-all hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                    @click="openDrawer"
                >
                    <span class="icon-cart text-2xl" role="presentation"></span>

                    <span
                        class="absolute -top-1 -right-1 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-[#18181b] px-1 text-[10px] font-bold text-white shadow-xs"
                        v-if="cart?.items_qty"
                    >
                        @{{ cart.items_qty }}
                    </span>
                </button>
            @else
                <a
                    href="{{ route('shop.checkout.cart.index') }}"
                    class="relative flex h-10 w-10 cursor-pointer items-center justify-center rounded-full text-[#18181b] transition-all hover:bg-[#ede8dc]/50 hover:text-[#9e8464]"
                    aria-label="@lang('shop::app.checkout.cart.mini-cart.shopping-cart')"
                >
                    <span class="icon-cart text-2xl" role="presentation"></span>

                    <span
                        class="absolute -top-1 -right-1 flex h-4.5 min-w-4.5 items-center justify-center rounded-full bg-[#18181b] px-1 text-[10px] font-bold text-white shadow-xs"
                        v-if="cart?.items_qty"
                    >
                        @{{ cart.items_qty }}
                    </span>
                </a>
            @endif

            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.toggle.after') !!}

            @if (core()->getConfigData('sales.checkout.mini_cart.display_mini_cart'))
                <!-- Bespoke Luxury Drawer Teleported directly to Body -->
                <teleport to="body">
                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.before') !!}

                    <!-- Backdrop -->
                    <transition
                        enter-active-class="transition-opacity ease-out duration-300"
                        enter-from-class="opacity-0"
                        enter-to-class="opacity-100"
                        leave-active-class="transition-opacity ease-in duration-200"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <div
                            v-if="isOpen"
                            class="fixed inset-0 z-[999] bg-black/40 backdrop-blur-xs transition-opacity"
                            @click="closeDrawer"
                            aria-hidden="true"
                        ></div>
                    </transition>

                    <!-- Slide-over Drawer -->
                    <transition
                        enter-active-class="transition-transform ease-out duration-300"
                        enter-from-class="translate-x-full rtl:-translate-x-full"
                        enter-to-class="translate-x-0"
                        leave-active-class="transition-transform ease-in duration-200"
                        leave-from-class="translate-x-0"
                        leave-to-class="translate-x-full rtl:-translate-x-full"
                    >
                        <aside
                            v-if="isOpen"
                            class="fixed inset-y-0 top-0 bottom-0 right-0 rtl:left-0 rtl:right-auto z-[1000] flex h-screen max-h-screen w-full max-w-[440px] flex-col border-l border-[#ede8dc] bg-[#fdfbf7] shadow-2xl"
                            style="top: 0; bottom: 0; right: 0; height: 100vh; max-height: 100vh;"
                            role="dialog"
                            aria-modal="true"
                            aria-label="@lang('beauty::app.cart.title')"
                        >
                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.before') !!}

                            <!-- Header -->
                            <div class="flex flex-col border-b border-[#ede8dc] bg-[#fdfbf7] px-6 py-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-baseline gap-2 min-w-0 flex-1">
                                        <h2 class="font-editorial text-2xl font-semibold tracking-tight text-[#18181b] truncate">
                                            @lang('beauty::app.cart.title')
                                        </h2>
                                        <span class="text-xs font-medium text-[#71717a] shrink-0" v-if="cart?.items?.length">
                                            (@{{ cart.items.length }})
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        class="flex h-9 w-9 shrink-0 cursor-pointer items-center justify-center rounded-full text-[#71717a] transition-colors hover:bg-[#ede8dc]/60 hover:text-[#18181b]"
                                        @click="closeDrawer"
                                        aria-label="Cerrar carrito"
                                    >
                                        <span class="icon-cancel text-xl" role="presentation"></span>
                                    </button>
                                </div>

                                <!-- Progressive Free Shipping Indicator -->
                                <div
                                    class="mt-4 rounded-xl border border-[#ede8dc] bg-[#f7f5f0] p-3.5"
                                    v-if="cart && (cart.sub_total || cart.subtotal)"
                                >
                                    <div class="flex items-center justify-between text-xs">
                                        <span v-if="parseFloat(cart.sub_total || cart.subtotal) >= 50" class="flex items-center gap-1.5 font-medium text-[#78866b]">
                                            <span class="inline-block h-2 w-2 rounded-full bg-[#78866b]"></span>
                                            {!! trans('beauty::app.cart.free-shipping-success') !!}
                                        </span>
                                        <span v-else class="text-[#52525b]">
                                            {!! trans('beauty::app.cart.free-shipping-remaining', ['amount' => '$' . '@{{ (50 - parseFloat(cart.sub_total || cart.subtotal)).toFixed(2) }}']) !!}
                                        </span>
                                        <span class="font-semibold text-[#18181b]">
                                            @{{ Math.min(Math.round((parseFloat(cart.sub_total || cart.subtotal) / 50) * 100), 100) }}%
                                        </span>
                                    </div>

                                    <div class="mt-2.5 h-1.5 w-full overflow-hidden rounded-full bg-[#eae5db]">
                                        <div
                                            class="h-full rounded-full bg-[#9e8464] transition-all duration-500 ease-out"
                                            :style="{ width: `${Math.min((parseFloat(cart.sub_total || cart.subtotal) / 50) * 100, 100)}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.header.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.before') !!}

                            <!-- Content / Item List -->
                            <div class="flex-1 overflow-y-auto px-6 py-4">
                                <!-- Loading State -->
                                <div v-if="isLoading && !cart" class="flex flex-col gap-4 py-4">
                                    <div class="shimmer h-20 w-full rounded-xl"></div>
                                    <div class="shimmer h-20 w-full rounded-xl"></div>
                                    <div class="shimmer h-20 w-full rounded-xl"></div>
                                </div>

                                <!-- Cart Item Listing -->
                                <div class="divide-y divide-[#ede8dc]" v-else-if="cart?.items?.length">
                                    <div
                                        class="flex gap-x-4 py-4 first:pt-0 last:pb-0"
                                        v-for="item in cart.items"
                                        :key="item.id"
                                    >
                                        <!-- Cart Item Image -->
                                        <div class="relative h-22 w-18 shrink-0 overflow-hidden rounded-xl border border-[#ede8dc]/80 bg-[#f7f4ee]">
                                            <a :href="item.product_url_key ? '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key) : '#'">
                                                <img
                                                    :src="item.base_image?.small_image_url"
                                                    :alt="item.name"
                                                    class="h-full w-full object-cover object-center"
                                                    loading="lazy"
                                                />
                                            </a>
                                        </div>

                                        <!-- Cart Item Information -->
                                        <div class="flex flex-1 flex-col justify-between">
                                            <div class="flex justify-between gap-2">
                                                <div>
                                                    <a
                                                        :href="item.product_url_key ? '{{ route('shop.product_or_category.index', ':slug') }}'.replace(':slug', item.product_url_key) : '#'"
                                                        class="line-clamp-2 text-sm font-medium text-[#18181b] transition-colors hover:text-[#9e8464]"
                                                    >
                                                        @{{ item.name }}
                                                    </a>

                                                    <!-- Options / Attributes if any (e.g. Color, Tamaño) -->
                                                    <div class="mt-1 flex flex-wrap gap-1 text-[11px] text-[#71717a]" v-if="item.options && item.options.attributes">
                                                        <span
                                                            v-for="attribute in item.options.attributes"
                                                            class="inline-block rounded-md bg-[#f7f4ee] px-1.5 py-0.5 text-[#52525b]"
                                                        >
                                                            @{{ attribute.attribute_name }}: @{{ attribute.option_label }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="text-[#a1a1aa] transition-colors hover:text-red-600 cursor-pointer p-1"
                                                    @click="removeItem(item.id)"
                                                    title="Eliminar"
                                                    aria-label="Eliminar producto"
                                                    :disabled="isUpdating"
                                                >
                                                    <span class="icon-cancel text-base" role="presentation"></span>
                                                </button>
                                            </div>

                                            <div class="mt-3 flex items-center justify-between">
                                                <!-- Quantity Controller -->
                                                <div class="flex items-center rounded-full border border-[#ede8dc] bg-white px-2 py-0.5 text-xs text-[#18181b] shadow-2xs">
                                                    <button
                                                        type="button"
                                                        class="px-1.5 py-0.5 font-bold text-[#71717a] transition-colors hover:text-[#18181b] disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                                                        :disabled="item.quantity <= 1 || isUpdating"
                                                        @click="updateItemQuantity(item.id, item.quantity - 1)"
                                                        aria-label="Disminuir cantidad"
                                                    >-</button>
                                                    <span class="min-w-5 text-center font-semibold">@{{ item.quantity }}</span>
                                                    <button
                                                        type="button"
                                                        class="px-1.5 py-0.5 font-bold text-[#71717a] transition-colors hover:text-[#18181b] disabled:opacity-30 disabled:cursor-not-allowed cursor-pointer"
                                                        :disabled="isUpdating"
                                                        @click="updateItemQuantity(item.id, item.quantity + 1)"
                                                        aria-label="Aumentar cantidad"
                                                    >+</button>
                                                </div>

                                                <!-- Price Display -->
                                                <p class="text-sm font-semibold text-[#18181b]">
                                                    @{{ item.formatted_price || item.formatted_total }}
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
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-[#f7f4ee] text-2xl text-[#9e8464]">
                                        <span class="icon-cart" role="presentation"></span>
                                    </div>

                                    <h3 class="mt-4 font-editorial text-xl font-semibold text-[#18181b]">
                                        @lang('beauty::app.cart.empty-title')
                                    </h3>

                                    <p class="mt-2 text-xs leading-relaxed text-[#71717a] max-w-xs">
                                        @lang('beauty::app.cart.empty-description')
                                    </p>

                                    <a
                                        href="{{ route('shop.home.index') }}"
                                        @click="closeDrawer"
                                        class="mt-6 rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464]"
                                    >
                                        @lang('beauty::app.cart.explore-collection')
                                    </a>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.content.after') !!}

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.footer.before') !!}

                            <!-- Footer (Subtotal & Actions) -->
                            <div
                                class="border-t border-[#ede8dc] bg-[#fdfbf7] p-6"
                                v-if="cart?.items?.length"
                            >
                                <div class="flex items-center justify-between text-base font-semibold text-[#18181b]">
                                    <span>@lang('beauty::app.cart.subtotal')</span>
                                    <span class="font-editorial text-lg">@{{ cart.formatted_sub_total || cart.formatted_subtotal || cart.formatted_grand_total }}</span>
                                </div>

                                <p class="mt-1 text-[11px] text-[#71717a]">
                                    @lang('beauty::app.cart.tax-shipping-note')
                                </p>

                                <div class="mt-4 grid gap-2.5">
                                    <a
                                        href="{{ route('shop.checkout.onepage.index') }}"
                                        class="flex w-full items-center justify-center rounded-full bg-[#18181b] py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-colors hover:bg-[#9e8464]"
                                    >
                                        @lang('beauty::app.cart.checkout')
                                    </a>

                                    <a
                                        href="{{ route('shop.checkout.cart.index') }}"
                                        class="flex w-full items-center justify-center rounded-full border border-[#ede8dc] bg-white py-3 text-xs font-semibold uppercase tracking-wider text-[#18181b] shadow-2xs transition-colors hover:bg-[#f7f5f0]"
                                    >
                                        @lang('beauty::app.cart.view-cart')
                                    </a>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.footer.after') !!}
                        </aside>
                    </transition>

                    {!! view_render_event('bagisto.shop.checkout.mini-cart.drawer.after') !!}
                </teleport>
            @endif
        </div>
    </script>

    <script type="module">
        app.component('v-mini-cart', {
            template: '#v-mini-cart-template',

            data() {
                return {
                    cart: null,
                    isOpen: false,
                    isLoading: false,
                    isUpdating: false,
                };
            },

            mounted() {
                this.getCart();

                this.$emitter.on('update-mini-cart', (cart) => {
                    this.cart = cart;
                });

                this.$emitter.on('cart:open', () => {
                    if (this.$el && this.$el.offsetParent !== null) {
                        this.openDrawer();
                    }
                });

                if (window.beautyEmitter) {
                    window.beautyEmitter.on('cart:open', () => {
                        if (this.$el && this.$el.offsetParent !== null) {
                            this.openDrawer();
                        }
                    });

                    window.beautyEmitter.on('cart:updated', (cart) => {
                        this.cart = cart;
                    });
                }

                window.addEventListener('keydown', this.handleKeydown);
            },

            beforeUnmount() {
                window.removeEventListener('keydown', this.handleKeydown);
                if (this.isOpen) {
                    document.body.style.overflow = '';
                }
            },

            methods: {
                openDrawer() {
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                    this.getCart();
                },

                closeDrawer() {
                    this.isOpen = false;
                    document.body.style.overflow = '';
                },

                handleKeydown(e) {
                    if (e.key === 'Escape' && this.isOpen) {
                        this.closeDrawer();
                    }
                },

                getCart() {
                    this.isLoading = true;

                    this.$axios.get('{{ route('shop.api.checkout.cart.index') }}')
                        .then((response) => {
                            this.cart = response.data.data;
                            if (window.beautyEmitter) {
                                window.beautyEmitter.emit('cart:updated', this.cart);
                            }
                        })
                        .catch(() => {})
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                updateItemQuantity(itemId, quantity) {
                    if (this.isUpdating) return;
                    this.isUpdating = true;

                    this.$axios.put('{{ route('shop.api.checkout.cart.update') }}', {
                        qty: { [itemId]: quantity }
                    })
                    .then((response) => {
                        this.cart = response.data.data;
                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        if (window.beautyEmitter) {
                            window.beautyEmitter.emit('cart:updated', this.cart);
                        }
                    })
                    .catch((error) => {
                        const message = error.response?.data?.message || 'Error al actualizar el carrito';
                        this.$emitter.emit('add-flash', { type: 'error', message: message });
                    })
                    .finally(() => {
                        this.isUpdating = false;
                    });
                },

                removeItem(itemId) {
                    if (this.isUpdating) return;
                    this.isUpdating = true;

                    this.$axios.post('{{ route('shop.api.checkout.cart.destroy') }}', {
                        _method: 'DELETE',
                        cart_item_id: itemId,
                    })
                    .then((response) => {
                        this.cart = response.data.data;
                        this.$emitter.emit('add-flash', { type: 'warning', message: response.data.message });
                        if (window.beautyEmitter) {
                            window.beautyEmitter.emit('cart:updated', this.cart);
                        }
                    })
                    .catch((error) => {
                        const message = error.response?.data?.message || 'Error al eliminar el producto';
                        this.$emitter.emit('add-flash', { type: 'error', message: message });
                    })
                    .finally(() => {
                        this.isUpdating = false;
                    });
                },
            },
        });
    </script>
@endPushOnce
