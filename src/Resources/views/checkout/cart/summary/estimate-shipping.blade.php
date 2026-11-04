<!-- Estimate Tax and Shipping -->
{!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.before') !!}

<x-shop::accordion
    class="overflow-hidden rounded-xl border border-[#ede8dc] bg-[#fdfbf7]"
    :is-active="false"
>
    <x-slot:header class="py-3 px-4 font-editorial text-base font-medium text-[#18181b] hover:text-[#9e8464] transition-colors cursor-pointer">
        @lang('shop::app.checkout.cart.summary.estimate-shipping.title')
    </x-slot>

    <x-slot:content class="p-4 pt-1 border-t border-[#ede8dc]/60">
        <v-estimate-tax-shipping
            :cart="cart"
            @processed="setCart"
        ></v-estimate-tax-shipping>
    </x-slot>
</x-shop::accordion>

{!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-estimate-tax-shipping-template">
        <!-- Destination Location Form -->
        <x-shop::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @change="handleSubmit($event, estimateShipping)" class="space-y-3">
                <p class="text-xs text-[#71717a]">
                    @lang('shop::app.checkout.cart.summary.estimate-shipping.info')
                </p>
                
                <!-- Country -->
                <x-shop::form.control-group class="mb-0!">
                    <x-shop::form.control-group.label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#71717a] {{ core()->isCountryRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.country')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        name="country"
                        v-model="selectedCountry"
                        rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                        :label="trans('shop::app.checkout.cart.summary.estimate-shipping.country')"
                        :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.country')"
                        class="w-full rounded-xl border border-[#ede8dc] bg-white px-3 py-2 text-xs text-[#18181b] transition-colors focus:border-[#9e8464] focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        @change="selectedState = ''"
                    >
                        <option value="">
                            @lang('shop::app.checkout.cart.summary.estimate-shipping.select-country')
                        </option>

                        <option
                            v-for="country in countries"
                            :value="country.code"
                            v-text="country.name"
                        >
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error name="country" class="mt-1 text-xs text-red-500" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.country.after') !!}

                <!-- State -->
                <x-shop::form.control-group class="mb-0!">
                    <x-shop::form.control-group.label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#71717a] {{ core()->isStateRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.state')
                    </x-shop::form.control-group.label>

                    <template v-if="states">
                        <template v-if="haveStates">
                            <x-shop::form.control-group.control
                                type="select"
                                name="state"
                                v-model="selectedState"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                class="w-full rounded-xl border border-[#ede8dc] bg-white px-3 py-2 text-xs text-[#18181b] transition-colors focus:border-[#9e8464] focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.cart.summary.estimate-shipping.select-state')
                                </option>

                                <option
                                    v-for='(state, index) in states[selectedCountry]'
                                    :value="state.code"
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
                        </template>

                        <template v-else>
                            <x-shop::form.control-group.control
                                type="text"
                                name="state"
                                v-model="selectedState"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.state')"
                                class="w-full rounded-xl border border-[#ede8dc] bg-white px-3 py-2 text-xs text-[#18181b] transition-colors placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                            />
                        </template>
                    </template>

                    <x-shop::form.control-group.error name="state" class="mt-1 text-xs text-red-500" />
                </x-shop::form.control-group>

                <!-- Postcode -->
                <x-shop::form.control-group class="mb-0!">
                    <x-shop::form.control-group.label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-[#71717a] {{ core()->isPostCodeRequired() ? 'required' : '' }}">
                        @lang('shop::app.checkout.cart.summary.estimate-shipping.postcode')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        name="postcode"
                        rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                        :label="trans('shop::app.checkout.cart.summary.estimate-shipping.postcode')"
                        :placeholder="trans('shop::app.checkout.cart.summary.estimate-shipping.postcode')"
                        class="w-full rounded-xl border border-[#ede8dc] bg-white px-3 py-2 text-xs text-[#18181b] transition-colors placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                    />

                    <x-shop::form.control-group.error control-name="postcode" class="mt-1 text-xs text-red-500" />
                </x-shop::form.control-group>

                <!-- Estimated Shipping Methods -->
                <div
                    class="mt-3 divide-y divide-[#ede8dc] rounded-xl border border-[#ede8dc] bg-white"
                    v-if="methods.length"
                >
                    <template v-for="method in methods">
                        {!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.shipping_method.before') !!}

                        <div
                            class="relative flex items-center gap-3 p-3 text-xs"
                            v-for="rate in method.rates"
                        >
                            <input
                                type="radio"
                                name="shipping_method"
                                :id="rate.method"
                                :value="rate.method"
                                class="h-4 w-4 border-[#ede8dc] text-[#18181b] accent-[#18181b] focus:ring-[#9e8464] cursor-pointer"
                            />

                            <label 
                                class="flex-1 cursor-pointer"
                                :for="rate.method"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-[#18181b]">@{{ rate.method_title }}</span>
                                    <span class="font-editorial text-sm font-semibold text-[#18181b]">@{{ rate.base_formatted_price }}</span>
                                </div>
                                <p class="mt-0.5 text-[11px] text-[#71717a]" v-if="rate.method_description">
                                    @{{ rate.method_description }}
                                </p>
                            </label>
                        </div>

                        {!! view_render_event('bagisto.shop.checkout.cart.summary.estimate_shipping.shipping_method.after') !!}
                    </template>
                </div>
            </form>                    
        </x-shop::form>
    </script>

    <script type="module">
        app.component('v-estimate-tax-shipping', {
            template: '#v-estimate-tax-shipping-template',
            
            props: ['cart'],

            data() {
                return {
                    selectedCountry: '',

                    selectedState: '',

                    countries: [],

                    states: null,

                    methods: [],

                    isStoring: false,
                }
            },

            computed: {
                haveStates() {
                    return !! this.states[this.selectedCountry]?.length;
                },
            },

            mounted() {
                this.getCountries();

                this.getStates();
            },

            methods: {
                getCountries() {
                    this.$axios.get("{{ route('shop.api.core.countries') }}")
                        .then(response => {
                            this.countries = response.data.data;
                        })
                        .catch(() => {});
                },

                getStates() {
                    this.$axios.get("{{ route('shop.api.core.states') }}")
                        .then(response => {
                            this.states = response.data.data;
                        })
                        .catch(() => {});
                },

                estimateShipping(params, { setErrors }) {
                    this.isStoring = true;

                    Object.keys(params).forEach(key => params[key] == null && delete params[key]);
                    
                    this.$axios.post('{{ route('shop.api.checkout.cart.estimate_shipping') }}', params)
                        .then((response) => {
                            this.isStoring = false;

                            this.methods = response.data.data.shipping_methods;

                            this.$emit('processed', response.data.data.cart);
                        })
                        .catch(error => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
