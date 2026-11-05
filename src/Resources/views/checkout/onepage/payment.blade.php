{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.before') !!}

<v-payment-methods
    :methods="paymentMethods"
    @payment-method-selected="setSelectedPaymentMethod"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <x-shop::shimmer.checkout.onepage.payment-method />
</v-payment-methods>

{!! view_render_event('bagisto.shop.checkout.onepage.payment_methods.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-payment-methods-template"
    >
        <div class="rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs sm:p-8">
            <template v-if="! methods">
                <!-- Payment Method shimmer Effect -->
                <x-shop::shimmer.checkout.onepage.payment-method />
            </template>
    
            <template v-else>
                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.before') !!}

                <!-- Step Header -->
                <div class="mb-6 flex items-center justify-between border-b border-[#ede8dc] pb-4">
                    <div class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#18181b] text-xs font-semibold text-white">
                            3
                        </span>
                        <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                            @lang('shop::app.checkout.onepage.payment.payment-method')
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div 
                        class="relative select-none"
                        v-for="(payment, index) in methods"
                    >
                        {!! view_render_event('bagisto.shop.checkout.payment-method.before') !!}

                        <input 
                            type="radio" 
                            name="payment[method]" 
                            :value="payment.payment"
                            :id="payment.method"
                            class="peer sr-only"
                            @change="store(payment)"
                        >

                        <label
                            :for="payment.method"
                            class="flex cursor-pointer items-start gap-4 rounded-2xl border border-[#ede8dc] bg-white p-5 transition-all hover:border-[#9e8464] hover:shadow-xs peer-checked:border-[#18181b] peer-checked:bg-[#fdfbf7] peer-checked:ring-1 peer-checked:ring-[#18181b]"
                        >
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-2">
                                <img
                                    v-if="payment.image"
                                    class="max-h-8 max-w-8 object-contain"
                                    :src="payment.image"
                                    :alt="payment.method_title"
                                    :title="payment.method_title"
                                />
                                <span
                                    v-else
                                    class="icon-payment text-2xl text-[#9e8464]"
                                ></span>
                            </div>

                            <div class="flex-1">
                                <p class="text-sm font-semibold text-[#18181b]">
                                    @{{ payment.method_title }}
                                </p>
                                <p class="mt-1 text-xs text-[#71717a]">
                                    @{{ payment.description }}
                                </p>
                            </div>
                        </label>

                        {!! view_render_event('bagisto.shop.checkout.payment-method.after') !!}
                    </div>
                </div>

                {!! view_render_event('bagisto.shop.checkout.onepage.payment_method.accordion.after') !!}
            </template>
        </div>
    </script>

    <script type="module">
        app.component('v-payment-methods', {
            template: '#v-payment-methods-template',

            props: {
                methods: {
                    type: Object,
                    required: true,
                    default: () => null,
                },
            },

            emits: ['payment-method-selected', 'processing', 'processed'],

            methods: {
                store(selectedMethod) {
                    this.$emit('payment-method-selected', selectedMethod.method);

                    this.$emit('processing', 'review');

                    this.$axios.post("{{ route('shop.checkout.onepage.payment_methods.store') }}", {
                            payment: selectedMethod
                        })
                        .then(response => {
                            this.$emit('processed', response.data.cart);

                            // Used in mobile view. 
                            if (window.innerWidth <= 768) {
                                window.scrollTo({
                                    top: document.body.scrollHeight,
                                    behavior: 'smooth'
                                });
                            }
                        })
                        .catch(error => {
                            this.$emit('processing', 'payment');

                            if (error.response.data.redirect_url) {
                                window.location.href = error.response.data.redirect_url;
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
