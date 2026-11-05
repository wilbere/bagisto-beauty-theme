{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.before') !!}

<v-shipping-methods
    :methods="shippingMethods"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Shipping Method Shimmer Effect -->
    <x-shop::shimmer.checkout.onepage.shipping-method />
</v-shipping-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.shipping_methods.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-shipping-methods-template"
    >
        <div class="rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs sm:p-8">
            <template v-if="! methods">
                <!-- Shipping Method Shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.shipping-method />
            </template>

            <template v-else>
                <!-- Step Header -->
                <div class="mb-6 flex items-center justify-between border-b border-[#ede8dc] pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#18181b] text-xs font-semibold text-white">
                            2
                        </span>
                        <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                            @lang('shop::app.checkout.onepage.shipping.shipping-method')
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <template v-for="method in methods">
                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.before') !!}

                        <div
                            class="relative select-none"
                            v-for="rate in method.rates"
                        >
                            <input 
                                type="radio"
                                name="shipping_method"
                                :id="rate.method"
                                :value="rate.method"
                                class="peer sr-only"
                                @change="store(rate.method)"
                            >

                            <label 
                                class="flex cursor-pointer flex-col justify-between rounded-2xl border border-[#ede8dc] bg-white p-5 transition-all hover:border-[#9e8464] hover:shadow-xs peer-checked:border-[#18181b] peer-checked:bg-[#fdfbf7] peer-checked:ring-1 peer-checked:ring-[#18181b]"
                                :for="rate.method"
                            >
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#fdfbf7] border border-[#ede8dc] text-[#9e8464]">
                                            <span class="icon-truck text-xl"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-[#18181b]">
                                                @{{ rate.method_title }}
                                            </p>
                                            <p class="text-xs text-[#71717a]">
                                                @{{ rate.method_description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-base font-bold text-[#18181b]">
                                            @{{ rate.base_formatted_price }}
                                        </p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.onepage.shipping_method.after') !!}
                    </template>
                </div>
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-shipping-methods', {
            template: '#v-shipping-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.$emit('processing', 'payment');

                    this.$axios.post("{{ route('shop.checkout.onepage.shipping_methods.store') }}", {    
                            shipping_method: selectedMethod,
                        })
                        .then(response => {
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            } else {
                                this.$emit('processed', response.data.payment_methods);
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'shipping');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
