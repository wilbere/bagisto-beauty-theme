{!! view_render_event('bagisto.shop.checkout.onepage.address.guest.before') !!}

<!-- Guest Address Vue Component -->
<v-checkout-address-guest
    :cart="cart"
    :current-step="currentStep"
    @processing="stepForward"
    @processed="stepProcessed"
></v-checkout-address-guest>

{!! view_render_event('bagisto.shop.checkout.onepage.address.guest.after') !!}

@include('shop::checkout.onepage.address.form')

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-guest-template"
    >
        <!-- Address Form -->
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form
                @submit="handleSubmit($event, addAddress)"
                @input="handleAddressChange"
            >
                <!-- Guest Billing Address -->
                <div class="mb-6">
                    {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.billing.before') !!}

                    <!-- Billing Address Header -->
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                            @lang('shop::app.checkout.onepage.address.billing-address')
                        </h3>
                    </div>
                
                    <!-- Billing Address Form -->
                    <v-checkout-address-form
                        control-name="billing"
                        :address="cart.billing_address || undefined"
                    ></v-checkout-address-form>

                    <!-- Use for Shipping Checkbox -->
                    <div
                        class="mt-4 flex items-center gap-3"
                        v-if="cart.have_stockable_items"
                    >
                        <input
                            type="checkbox"
                            name="billing.use_for_shipping"
                            id="use_for_shipping"
                            value="1"
                            class="h-4 w-4 rounded border-[#ede8dc] text-[#18181b] focus:ring-[#9e8464]"
                            @change="useBillingAddressForShipping = ! useBillingAddressForShipping"
                            :checked="!! useBillingAddressForShipping"
                        >

                        <label
                            class="cursor-pointer select-none text-xs font-medium text-[#52525b]"
                            for="use_for_shipping"
                        >
                            @lang('shop::app.checkout.onepage.address.same-as-billing')
                        </label>
                    </div>

                    {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.billing.after') !!}
                </div>

                <!-- Guest Shipping Address -->
                <template v-if="cart.have_stockable_items">
                    <div
                        class="mb-6 border-t border-[#ede8dc] pt-6"
                        v-if="! useBillingAddressForShipping"
                    >
                        {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.shipping.before') !!}

                        <!-- Shipping Address Header -->
                        <div class="mb-3 flex items-center justify-between">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                                @lang('shop::app.checkout.onepage.address.shipping-address')
                            </h3>
                        </div>
                    
                        <!-- Shipping Address Form -->
                        <v-checkout-address-form
                            control-name="shipping"
                            :address="cart.shipping_address || undefined"
                        ></v-checkout-address-form>

                        {!! view_render_event('bagisto.shop.checkout.onepage.address.guest.shipping.after') !!}
                    </div>
                </template>

                <!-- Address Updated Notice -->
                <transition
                    enter-from-class="scale-95 opacity-0"
                    enter-active-class="transform transition duration-200 ease-in-out"
                    leave-active-class="transform transition duration-200 ease-in-out"
                    leave-to-class="scale-95 opacity-0"
                >
                    <div
                        class="mb-4 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-900"
                        role="status"
                        v-if="isAddressUpdated"
                    >
                        <span class="icon-toast-info flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm text-amber-800"></span>

                        <div>
                            <p class="font-semibold uppercase tracking-wider">
                                @lang('shop::app.checkout.onepage.address.address-updated-title')
                            </p>

                            <p class="mt-1 text-amber-800">
                                <template v-if="cart.have_stockable_items">
                                    @lang('shop::app.checkout.onepage.address.address-updated-shipping-info')
                                </template>

                                <template v-else>
                                    @lang('shop::app.checkout.onepage.address.address-updated-payment-info')
                                </template>
                            </p>
                        </div>
                    </div>
                </transition>

                <!-- Proceed Button -->
                <div
                    class="mt-6 flex justify-end"
                    v-if="currentStep == 'address' || isStoring"
                >
                    <button
                        type="submit"
                        class="cursor-pointer rounded-full bg-[#18181b] px-9 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="isStoring"
                    >
                        <span v-if="! isStoring">@lang('shop::app.checkout.onepage.address.proceed')</span>
                        <span v-else>@lang('beauty::app.products.processing')</span>
                    </button>
                </div>
            </form>
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-checkout-address-guest', {
            template: '#v-checkout-address-guest-template',

            props: ['cart', 'currentStep'],

            emits: ['processing', 'processed'],

            data() {
                return {
                    useBillingAddressForShipping: true,

                    isStoring: false,

                    isAddressUpdated: false,
                }
            },

            created() {
                if (this.cart.billing_address) {
                    this.useBillingAddressForShipping = this.cart.billing_address.use_for_shipping;
                }
            },

            methods: {
                handleAddressChange() {
                    if (this.currentStep != 'address') {
                        this.isAddressUpdated = true;
                    }

                    this.$emit('processing', 'address');
                },

                moveToNextStep() {
                    if (this.cart.have_stockable_items) {
                        this.$emit('processing', 'shipping');
                    } else {
                        this.$emit('processing', 'payment');
                    }
                },

                addAddress(params, { setErrors }) {
                    this.isStoring = true;

                    params['billing']['use_for_shipping'] = this.useBillingAddressForShipping;

                    this.moveToNextStep();

                    this.$axios.post("{{ route('shop.checkout.onepage.addresses.store') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.isAddressUpdated = false;

                            if (response.data.data.redirect_url) {
                                window.location.href = response.data.data.redirect_url;
                            } else {
                                if (this.cart.have_stockable_items) {
                                    this.$emit('processed', response.data.data.shippingMethods);
                                } else {
                                    this.$emit('processed', response.data.data.payment_methods);
                                }
                            }
                        })
                        .catch(error => {
                            this.isStoring = false;

                            this.$emit('processing', 'address');

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            },
        });
    </script>
@endPushOnce
