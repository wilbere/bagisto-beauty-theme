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
                            :value="payment.method"
                            :id="payment.method"
                            :checked="selectedMethodCode === payment.method"
                            class="peer sr-only"
                            @change="store(payment)"
                        >

                        <label
                            :for="payment.method"
                            class="flex cursor-pointer flex-col rounded-2xl border bg-white p-5 transition-all"
                            :class="selectedMethodCode === payment.method ? 'border-[#18181b] bg-[#fdfbf7] ring-2 ring-[#18181b] shadow-xs' : 'border-[#ede8dc] hover:border-[#9e8464] hover:shadow-2xs'"
                        >
                            <div class="flex items-start gap-4">
                                <div
                                    class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border transition-all"
                                    :class="selectedMethodCode === payment.method ? 'border-[#18181b] bg-[#18181b]' : 'border-[#d4cebe] bg-white'"
                                >
                                    <span v-if="selectedMethodCode === payment.method" class="h-2 w-2 rounded-full bg-white"></span>
                                </div>

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

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#18181b]">
                                        @{{ payment.method_title }}
                                    </p>
                                    <p class="mt-1 text-xs text-[#71717a]">
                                        @{{ payment.description }}
                                    </p>
                                </div>
                            </div>

                            <!-- Explanatory Payment Instructions Card (Shown when selected) -->
                            <div
                                v-if="selectedMethodCode === payment.method"
                                class="mt-4 rounded-xl border border-[#ede8dc] bg-white p-3.5 text-xs text-[#52525b]"
                            >
                                <div class="flex items-center gap-2 font-semibold text-[#18181b] mb-1">
                                    <span class="icon-information text-base text-[#9e8464]"></span>
                                    <span>Instrucciones del Método de Pago</span>
                                </div>
                                <p class="leading-relaxed text-[#71717a]">
                                    @{{ payment.description || 'Al presionar "Realizar Pedido", se confirmará tu compra y recibirás los datos e instrucciones correspondientes para completar tu pago de forma segura.' }}
                                </p>
                                <div class="mt-2.5 flex items-center gap-2 text-[11px] text-[#71717a]">
                                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Garantía de procesamiento seguro y soporte personalizado</span>
                                </div>
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

            data() {
                return {
                    selectedMethodCode: null,
                };
            },

            methods: {
                store(selectedMethod) {
                    this.selectedMethodCode = selectedMethod.method;

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
