<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
    <meta name="keywords" content="@lang('shop::app.checkout.onepage.index.checkout')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.onepage.index.checkout')
    </x-slot>

    <div class="min-h-screen bg-[#fdfbf7] py-8 pb-28 sm:py-12 lg:pb-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Top Context Navigation -->
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-[#ede8dc] pb-5">
                <div class="flex items-center gap-4">
                    <a
                        href="{{ route('shop.checkout.cart.index') }}"
                        class="group inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#71717a] transition-colors hover:text-[#9e8464]"
                    >
                        <span class="transition-transform group-hover:-translate-x-1">&larr;</span>
                        <span>@lang('shop::app.checkout.cart.index.cart')</span>
                    </a>

                    <span class="text-[#ded5c3]">/</span>

                    <h1 class="font-editorial text-2xl font-semibold text-[#18181b]">
                        @lang('shop::app.checkout.onepage.index.checkout')
                    </h1>
                </div>

                <div class="flex items-center gap-6">
                    <div class="inline-flex items-center gap-2 text-xs font-medium text-[#71717a]">
                        <span class="icon-gdpr-safe text-base text-[#9e8464]"></span>
                        <span>Pago Seguro SSL 256-bit</span>
                    </div>

                    @guest('customer')
                        <div class="border-l border-[#ede8dc] pl-6">
                            @include('shop::checkout.login')
                        </div>
                    @endguest
                </div>
            </div>

            {!! view_render_event('bagisto.shop.checkout.onepage.header.after') !!}

            <!-- Checkout Vue Component -->
            <v-checkout>
                <!-- Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage />
            </v-checkout>
        </div>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-checkout-template"
        >
            <template v-if="! cart">
                <!-- Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage />
            </template>

            <template v-else>
                <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-[1fr_420px]">
                    <!-- Included Checkout Summary Blade File For Mobile view -->
                    <div class="block lg:hidden rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs">
                        @include('shop::checkout.onepage.summary')
                    </div>

                    <!-- Steps Column -->
                    <div
                        class="space-y-6"
                        id="steps-container"
                    >
                        <!-- Included Addresses Blade File -->
                        <template v-if="['address', 'shipping', 'payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.address')
                        </template>

                        <!-- Included Shipping Methods Blade File -->
                        <template v-if="cart.have_stockable_items && ['shipping', 'payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.shipping')
                        </template>

                        <!-- Included Payment Methods Blade File -->
                        <template v-if="['payment', 'review'].includes(currentStep)">
                            @include('shop::checkout.onepage.payment')
                        </template>

                        <!-- Mobile In-Flow Place Order Card (shown when review/payment step reached) -->
                        <div
                            class="block lg:hidden rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs"
                            v-if="canPlaceOrder"
                        >
                            <div class="flex items-center justify-between pb-4 border-b border-[#ede8dc]">
                                <div>
                                    <span class="text-xs text-[#71717a]">Total a Pagar</span>
                                    <p class="font-editorial text-2xl font-bold text-[#18181b]">
                                        @{{ cart.formatted_grand_total }}
                                    </p>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Paso Final
                                </span>
                            </div>

                            <div class="mt-4">
                                <template v-if="(selectedPaymentMethod || cart.payment_method) == 'paypal_smart_button'">
                                    <v-paypal-smart-button></v-paypal-smart-button>
                                </template>

                                <template v-else>
                                    <button
                                        type="button"
                                        class="flex w-full cursor-pointer items-center justify-center rounded-full bg-[#18181b] px-8 py-4 text-xs font-semibold uppercase tracking-wider text-white shadow-md transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="isPlacingOrder"
                                        @click="placeOrder"
                                    >
                                        <svg
                                            v-if="isPlacingOrder"
                                            class="mr-2 h-4 w-4 animate-spin text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>

                                        <span v-if="! isPlacingOrder">
                                            @lang('shop::app.checkout.onepage.summary.place-order')
                                        </span>
                                        <span v-else>
                                            @lang('beauty::app.products.processing')
                                        </span>
                                    </button>
                                </template>
                            </div>

                            <div class="mt-4 flex items-center justify-center gap-2 text-xs text-[#71717a]">
                                <span class="icon-gdpr-safe text-sm text-[#9e8464]"></span>
                                <span>Compra 100% protegida y garantizada</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Sticky Bottom Checkout Bar -->
                    <div
                        v-if="canPlaceOrder"
                        class="fixed bottom-0 inset-x-0 z-40 border-t border-[#ede8dc] bg-white/95 px-4 py-3 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] backdrop-blur-md lg:hidden"
                    >
                        <div class="mx-auto flex max-w-lg items-center justify-between gap-4">
                            <div class="min-w-0">
                                <span class="block text-[10px] uppercase tracking-wider text-[#71717a]">Total</span>
                                <span class="font-editorial text-lg font-bold text-[#18181b] truncate block">
                                    @{{ cart.formatted_grand_total }}
                                </span>
                            </div>

                            <button
                                type="button"
                                class="flex flex-1 cursor-pointer items-center justify-center rounded-full bg-[#18181b] px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="isPlacingOrder"
                                @click="placeOrder"
                            >
                                <svg
                                    v-if="isPlacingOrder"
                                    class="mr-2 h-4 w-4 animate-spin text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>

                                <span v-if="! isPlacingOrder">
                                    @lang('shop::app.checkout.onepage.summary.place-order')
                                </span>
                                <span v-else>
                                    @lang('beauty::app.products.processing')
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Included Checkout Summary Blade File For Desktop view -->
                    <div class="sticky top-24 hidden lg:block">
                        <div class="rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs">
                            @include('shop::checkout.onepage.summary')

                            <div
                                class="mt-6 pt-6 border-t border-[#ede8dc]"
                                v-if="canPlaceOrder"
                            >
                                <template v-if="(selectedPaymentMethod || cart.payment_method) == 'paypal_smart_button'">
                                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.before') !!}

                                    <!-- Paypal Smart Button Vue Component -->
                                    <v-paypal-smart-button></v-paypal-smart-button>

                                    {!! view_render_event('bagisto.shop.checkout.onepage.summary.paypal_smart_button.after') !!}
                                </template>

                                <template v-else>
                                    <button
                                        type="button"
                                        class="flex w-full cursor-pointer items-center justify-center rounded-full bg-[#18181b] px-8 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="isPlacingOrder"
                                        @click="placeOrder"
                                    >
                                        <svg
                                            v-if="isPlacingOrder"
                                            class="mr-2 h-4 w-4 animate-spin text-white"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                            ></path>
                                        </svg>

                                        <span v-if="! isPlacingOrder">
                                            @lang('shop::app.checkout.onepage.summary.place-order')
                                        </span>
                                        <span v-else>
                                            @lang('beauty::app.products.processing')
                                        </span>
                                    </button>
                                </template>
                            </div>

                            <!-- Trust Reassurance badges -->
                            <div class="mt-6 border-t border-[#ede8dc] pt-5 space-y-2.5">
                                <div class="flex items-center gap-2.5 text-xs text-[#71717a]">
                                    <span class="icon-gdpr-safe text-sm text-[#9e8464]"></span>
                                    <span>Transacción encriptada de 256 bits</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-[#71717a]">
                                    <span class="icon-truck text-sm text-[#9e8464]"></span>
                                    <span>Despacho rápido y embalaje protegido</span>
                                </div>
                                <div class="flex items-center gap-2.5 text-xs text-[#71717a]">
                                    <span class="icon-check-box text-sm text-[#9e8464]"></span>
                                    <span>Garantía de calidad profesional 100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </script>

        <script type="module">
            app.component('v-checkout', {
                template: '#v-checkout-template',

                data() {
                    return {
                        cart: null,

                        displayTax: {
                            prices: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_prices') }}",
                            subtotal: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_subtotal') }}",
                            shipping: "{{ core()->getConfigData('sales.taxes.shopping_cart.display_shipping_amount') }}",
                        },

                        isPlacingOrder: false,

                        currentStep: 'address',

                        shippingMethods: null,

                        paymentMethods: null,

                        selectedPaymentMethod: null,

                        canPlaceOrder: false,
                    }
                },

                mounted() {
                    this.getCart();
                },

                methods: {
                    getCart() {
                        this.$axios.get("{{ route('shop.checkout.onepage.summary') }}")
                            .then(response => {
                                this.cart = response.data.data;
                                this.scrollToCurrentStep();
                            })
                            .catch(error => {});
                    },

                    stepForward(step) {
                        this.currentStep = step;

                        if (step == 'review') {
                            this.canPlaceOrder = true;
                            return;
                        }

                        this.canPlaceOrder = false;

                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = null;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = null;
                        }
                    },

                    stepProcessed(data) {
                        if (this.currentStep == 'shipping') {
                            this.shippingMethods = data;
                        } else if (this.currentStep == 'payment') {
                            this.paymentMethods = data;
                        }

                        this.getCart();
                    },

                    scrollToCurrentStep() {
                        let container = document.getElementById('steps-container');

                        if (! container) {
                            return;
                        }

                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    },

                    setSelectedPaymentMethod(method) {
                        this.selectedPaymentMethod = method;
                    },

                    placeOrder() {
                        if ((this.selectedPaymentMethod || this.cart.payment_method) == 'paypal_smart_button') {
                            return;
                        }

                        this.isPlacingOrder = true;

                        this.$axios.post('{{ route('shop.checkout.onepage.orders.store') }}')
                            .then(response => {
                                if (response.data.data.redirect) {
                                    window.location.href = response.data.data.redirect_url;
                                } else {
                                    window.location.href = '{{ route('shop.checkout.onepage.success') }}';
                                }

                                this.isPlacingOrder = false;
                            })
                            .catch(error => {
                                this.isPlacingOrder = false;
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                            });
                    }
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>
