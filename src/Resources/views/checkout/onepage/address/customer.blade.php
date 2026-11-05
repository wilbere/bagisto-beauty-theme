{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.before') !!}

<!-- Customer Address Vue Component -->
<v-checkout-address-customer
    :cart="cart"
    :current-step="currentStep"
    @processing="stepForward"
    @processed="stepProcessed"
>
    <!-- Billing Address Shimmer -->
    <x-shop::shimmer.checkout.onepage.address />
</v-checkout-address-customer>

{!! view_render_event('bagisto.shop.checkout.onepage.address.customer.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-customer-template"
    >
        <template v-if="isLoading">
            <!-- Billing Address Shimmer -->
            <x-shop::shimmer.checkout.onepage.address />
        </template>

        <template v-else>
            <!-- Saved Addresses -->
            <template v-if="! activeAddressForm && customerSavedAddresses.billing.length">
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, addAddressToCart)"
                        @input="handleAddressChange"
                    >
                        <!-- Billing Address Header -->
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                                @lang('shop::app.checkout.onepage.address.billing-address')
                            </h3>
                        </div>

                        <!-- Saved Customer Addresses Cards -->
                        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div
                                class="relative cursor-pointer select-none rounded-2xl border p-5 transition-all"
                                :class="selectedAddresses.billing_address_id == address.id ? 'border-[#18181b] bg-[#fdfbf7] shadow-xs ring-1 ring-[#18181b]' : 'border-[#ede8dc] bg-white hover:border-[#9e8464]'"
                                v-for="address in customerSavedAddresses.billing"
                                @click="selectedAddresses.billing_address_id = address.id"
                            >
                                <!-- Actions -->
                                <div class="absolute top-4 flex items-center gap-3 ltr:right-4 rtl:left-4">
                                    <input
                                        type="radio"
                                        name="billing.id"
                                        :id="`billing_address_id_${address.id}`"
                                        :value="address.id"
                                        v-model="selectedAddresses.billing_address_id"
                                        class="h-4 w-4 border-[#ede8dc] text-[#18181b] focus:ring-[#9e8464]"
                                    >

                                    <!-- Edit Icon -->
                                    <span
                                        class="icon-edit cursor-pointer text-lg text-[#71717a] transition-colors hover:text-[#9e8464]"
                                        @click.stop="
                                            selectedAddressForEdit = address;
                                            activeAddressForm = 'billing';
                                            saveAddress = address.address_type == 'customer'
                                        "
                                    ></span>
                                </div>

                                <!-- Details -->
                                <label
                                    class="block cursor-pointer pt-1"
                                    :for="`billing_address_id_${address.id}`"
                                >
                                    <p class="text-sm font-semibold text-[#18181b]">
                                        @{{ address.first_name + ' ' + address.last_name }}
                                        <template v-if="address.company_name">
                                            (@{{ address.company_name }})
                                        </template>
                                    </p>

                                    <p class="mt-2 text-xs leading-relaxed text-[#71717a]">
                                        <template v-if="address.address">
                                            @{{ address.address.join(', ') }},
                                        </template>
                                        @{{ address.city }}, @{{ address.state }}, @{{ address.country }}, @{{ address.postcode }}
                                    </p>
                                </label>
                            </div>

                            <!-- New Address Card -->
                            <div
                                class="flex min-h-[120px] cursor-pointer items-center justify-center rounded-2xl border border-dashed border-[#ded5c3] p-5 transition-all hover:border-[#9e8464] hover:bg-[#fdfbf7]"
                                @click="activeAddressForm = 'billing'"
                            >
                                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#9e8464]">
                                    <span class="icon-plus text-base"></span>
                                    <span>@lang('shop::app.checkout.onepage.address.add-new-address')</span>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message Block -->
                        <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" name="billing.id" />

                        <!-- Shipping Address Block if have stockable items -->
                        <template v-if="cart.have_stockable_items">
                            <!-- Use for Shipping Checkbox -->
                            <div class="mt-5 flex items-center gap-3 border-t border-[#ede8dc] pt-5">
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

                            <!-- Customer Shipping Address -->
                            <div
                                class="mt-6 border-t border-[#ede8dc] pt-6"
                                v-if="! useBillingAddressForShipping"
                            >
                                <div class="mb-4 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                                        @lang('shop::app.checkout.onepage.address.shipping-address')
                                    </h3>
                                </div>

                                <!-- Saved Customer Addresses Cards -->
                                <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div
                                        class="relative cursor-pointer select-none rounded-2xl border p-5 transition-all"
                                        :class="selectedAddresses.shipping_address_id == address.id ? 'border-[#18181b] bg-[#fdfbf7] shadow-xs ring-1 ring-[#18181b]' : 'border-[#ede8dc] bg-white hover:border-[#9e8464]'"
                                        v-for="address in customerSavedAddresses.shipping"
                                        @click="selectedAddresses.shipping_address_id = address.id"
                                    >
                                        <div class="absolute top-4 flex items-center gap-3 ltr:right-4 rtl:left-4">
                                            <input
                                                type="radio"
                                                name="shipping.id"
                                                :id="`shipping_address_id_${address.id}`"
                                                :value="address.id"
                                                v-model="selectedAddresses.shipping_address_id"
                                                class="h-4 w-4 border-[#ede8dc] text-[#18181b] focus:ring-[#9e8464]"
                                            >

                                            <span
                                                class="icon-edit cursor-pointer text-lg text-[#71717a] transition-colors hover:text-[#9e8464]"
                                                @click.stop="
                                                    selectedAddressForEdit = address;
                                                    activeAddressForm = 'shipping';
                                                    saveAddress = address.address_type == 'customer'
                                                "
                                            ></span>
                                        </div>

                                        <label
                                            class="block cursor-pointer pt-1"
                                            :for="`shipping_address_id_${address.id}`"
                                        >
                                            <p class="text-sm font-semibold text-[#18181b]">
                                                @{{ address.first_name + ' ' + address.last_name }}
                                                <template v-if="address.company_name">
                                                    (@{{ address.company_name }})
                                                </template>
                                            </p>

                                            <p class="mt-2 text-xs leading-relaxed text-[#71717a]">
                                                <template v-if="address.address">
                                                    @{{ address.address.join(', ') }},
                                                </template>
                                                @{{ address.city }}, @{{ address.state }}, @{{ address.country }}, @{{ address.postcode }}
                                            </p>
                                        </label>
                                    </div>

                                    <!-- New Shipping Address Card -->
                                    <div
                                        class="flex min-h-[120px] cursor-pointer items-center justify-center rounded-2xl border border-dashed border-[#ded5c3] p-5 transition-all hover:border-[#9e8464] hover:bg-[#fdfbf7]"
                                        @click="selectedAddressForEdit = null; activeAddressForm = 'shipping'"
                                    >
                                        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#9e8464]">
                                            <span class="icon-plus text-base"></span>
                                            <span>@lang('shop::app.checkout.onepage.address.add-new-address')</span>
                                        </div>
                                    </div>
                                </div>

                                <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" name="shipping.id" />
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
                                class="mt-4 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs text-amber-900"
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
            </template>

            <!-- Create/Edit Address Form -->
            <template v-else>
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form
                        @submit="handleSubmit($event, updateOrCreateAddress)"
                        @input="handleAddressChange"
                    >
                        <!-- Form Header -->
                        <div class="mb-4 flex items-center justify-between border-b border-[#ede8dc] pb-3">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                                <template v-if="activeAddressForm == 'billing'">
                                    @lang('shop::app.checkout.onepage.address.billing-address')
                                </template>
                                <template v-else>
                                    @lang('shop::app.checkout.onepage.address.shipping-address')
                                </template>
                            </h3>

                            <span
                                class="flex cursor-pointer items-center gap-1 text-xs font-semibold uppercase tracking-wider text-[#9e8464] transition-colors hover:text-[#18181b]"
                                v-show="customerSavedAddresses.billing.length && ['billing', 'shipping'].includes(activeAddressForm)"
                                @click="selectedAddressForEdit = null; activeAddressForm = null"
                            >
                                <span>&larr;</span>
                                <span>@lang('shop::app.checkout.onepage.address.back')</span>
                            </span>
                        </div>
                        
                        <!-- Address Form Vue Component -->
                        <v-checkout-address-form
                            :control-name="activeAddressForm"
                            :address="selectedAddressForEdit || undefined"
                        ></v-checkout-address-form>

                        <!-- Save Address Checkbox -->
                        <div class="mt-4 flex items-center gap-3">
                            <input
                                type="checkbox"
                                :name="activeAddressForm + '.save_address'"
                                id="save_address"
                                value="1"
                                v-model="saveAddress"
                                class="h-4 w-4 rounded border-[#ede8dc] text-[#18181b] focus:ring-[#9e8464]"
                            >

                            <label
                                class="cursor-pointer select-none text-xs font-medium text-[#52525b]"
                                for="save_address"
                            >
                                @lang('shop::app.checkout.onepage.address.save-address')
                            </label>
                        </div>

                        <!-- Save Button -->
                        <div class="mt-6 flex justify-end">
                            <button
                                type="submit"
                                class="cursor-pointer rounded-full bg-[#18181b] px-9 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                :disabled="isStoring"
                            >
                                <span v-if="! isStoring">@lang('shop::app.checkout.onepage.address.save')</span>
                                <span v-else>@lang('beauty::app.products.processing')</span>
                            </button>
                        </div>
                    </form>
                </x-shop::form>
            </template>
        </template>
    </script>

    <script type="module">
        app.component('v-checkout-address-customer', {
            template: '#v-checkout-address-customer-template',

            props: ['cart', 'currentStep'],

            emits: ['processing', 'processed'],

            data() {
                return {
                    customerSavedAddresses: {
                        'billing': [],
                        'shipping': [],
                    },

                    useBillingAddressForShipping: true,

                    activeAddressForm: null,

                    selectedAddressForEdit: null,

                    saveAddress: false,

                    selectedAddresses: {
                        billing_address_id: null,
                        shipping_address_id: null,
                    },

                    isLoading: true,

                    isStoring: false,

                    isAddressUpdated: false,
                }
            },

            created() {
                if (this.cart.billing_address) {
                    this.useBillingAddressForShipping = this.cart.billing_address.use_for_shipping;
                }
            },

            mounted() {
                this.getCustomerSavedAddresses();
            },

            methods: {
                getCustomerSavedAddresses() {
                    this.$axios.get('{{ route('shop.api.customers.account.addresses.index') }}')
                        .then(response => {
                            this.initializeAddresses('billing', structuredClone(response.data.data));
                            this.initializeAddresses('shipping', structuredClone(response.data.data));

                            if (! this.customerSavedAddresses.billing.length) {
                                this.activeAddressForm = 'billing';
                            }

                            this.isLoading = false;
                        })
                        .catch((error) => {
                            console.error(error);
                        });
                },

                initializeAddresses(type, addresses) {
                    this.customerSavedAddresses[type] = addresses;

                    let cartAddress = this.cart[type + '_address'];

                    if (! cartAddress) {
                        addresses.forEach(address => {
                            if (address.default_address) {
                                this.selectedAddresses[type + '_address_id'] = address.id;
                            }
                        });
                        return;
                    }

                    if (cartAddress.address_type == 'customer') {
                        this.selectedAddresses[type + '_address_id'] = cartAddress.id;
                    } else {
                        this.selectedAddresses[type + '_address_id'] = cartAddress.address_id;
                    }
                },

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

                addAddressToCart(params, { resetForm, setErrors }) {
                    let payload = {
                        billing: {
                            ...this.getSelectedAddress('billing', params.billing.id),
                            use_for_shipping: this.useBillingAddressForShipping
                        },
                    };

                    if (params.shipping !== undefined) {
                        payload.shipping = this.getSelectedAddress('shipping', params.shipping.id);
                    }

                    this.isStoring = true;

                    this.moveToNextStep();

                    this.$axios.post("{{ route('shop.checkout.onepage.addresses.store') }}", payload)
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

                updateOrCreateAddress(params, { resetForm, setErrors }) {
                    this.isStoring = true;
                    params[this.activeAddressForm].address_type = 'customer';

                    this.$axios.post("{{ route('shop.checkout.onepage.addresses.store') }}", params)
                        .then((response) => {
                            this.isStoring = false;
                            this.isAddressUpdated = false;

                            if (this.selectedAddressForEdit) {
                                let addressIndex = this.customerSavedAddresses[this.activeAddressForm].findIndex(address => address.id === this.selectedAddressForEdit.id);
                                if (addressIndex !== -1) {
                                    this.customerSavedAddresses[this.activeAddressForm][addressIndex] = response.data.address;
                                }
                            } else {
                                this.customerSavedAddresses[this.activeAddressForm].push(response.data.address);
                            }

                            this.selectedAddresses[this.activeAddressForm + '_address_id'] = response.data.address.id;
                            this.activeAddressForm = null;
                            this.selectedAddressForEdit = null;
                        })
                        .catch(error => {
                            this.isStoring = false;
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
