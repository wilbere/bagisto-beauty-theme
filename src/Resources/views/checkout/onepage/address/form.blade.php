@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-4 space-y-4">
            <x-shop::form.control-group class="hidden">
                <x-shop::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-shop::form.control-group>

            <!-- Company Name -->
            <x-shop::form.control-group class="mb-0">
                <x-shop::form.control-group.label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_company_name'">
                    @lang('shop::app.checkout.onepage.address.company-name')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="text"
                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                    ::name="controlName + '.company_name'"
                    ::value="address.company_name"
                    :placeholder="trans('shop::app.checkout.onepage.address.company-name')"
                    ::id="controlName + '_company_name'"
                />
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.company_name.after') !!}

            <!-- First Name & Last Name Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- First Name -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_first_name'">
                        @lang('shop::app.checkout.onepage.address.first-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.first_name'"
                        ::value="address.first_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.first-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.first-name')"
                        ::id="controlName + '_first_name'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.first_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.first_name.after') !!}

                <!-- Last Name -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_last_name'">
                        @lang('shop::app.checkout.onepage.address.last-name')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.last_name'"
                        ::value="address.last_name"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.last-name')"
                        :placeholder="trans('shop::app.checkout.onepage.address.last-name')"
                        ::id="controlName + '_last_name'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.last_name'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.last_name.after') !!}
            </div>

            <!-- Email & Phone Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Email -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_email'">
                        @lang('shop::app.checkout.onepage.address.email')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="email"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.email'"
                        ::value="address.email"
                        rules="required|email"
                        :label="trans('shop::app.checkout.onepage.address.email')"
                        placeholder="email@example.com"
                        ::id="controlName + '_email'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.email'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.email.after') !!}

                <!-- Phone -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_phone'">
                        @lang('shop::app.checkout.onepage.address.phone')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.phone'"
                        ::value="address.phone"
                        rules="required|phone"
                        :label="trans('shop::app.checkout.onepage.address.phone')"
                        :placeholder="trans('shop::app.checkout.onepage.address.phone')"
                        ::id="controlName + '_phone'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.phone'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.phone.after') !!}
            </div>

            <!-- Vat ID (Billing only) -->
            <template v-if="controlName=='billing'">
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_vat_id'">
                        @lang('shop::app.checkout.onepage.address.vat-id')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.vat_id'"
                        ::value="address.vat_id"
                        :label="trans('shop::app.checkout.onepage.address.vat-id')"
                        :placeholder="trans('shop::app.checkout.onepage.address.vat-id')"
                        ::id="controlName + '_vat_id'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.vat_id'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.vat_id.after') !!}
            </template>

            <!-- Street Address -->
            <x-shop::form.control-group class="mb-0">
                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_address_0'">
                    @lang('shop::app.checkout.onepage.address.street-address')
                </x-shop::form.control-group.label>

                <x-shop::form.control-group.control
                    type="text"
                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                    ::name="controlName + '.address.[0]'"
                    ::value="address.address[0]"
                    rules="required|address"
                    :label="trans('shop::app.checkout.onepage.address.street-address')"
                    :placeholder="trans('shop::app.checkout.onepage.address.street-address')"
                    ::id="controlName + '_address_0'"
                />

                <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.address.[0]'" />

                @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                    @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                        <x-shop::form.control-group.control
                            type="text"
                            class="mt-2 w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                            ::name="controlName + '.address.[{{ $i }}]'"
                            rules="address"
                            :label="trans('shop::app.checkout.onepage.address.street-address')"
                            :placeholder="trans('shop::app.checkout.onepage.address.street-address')"
                            ::id="controlName + '_address_{{ $i }}'"
                        />

                        <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.address.[{{ $i }}]'" />
                    @endfor
                @endif
            </x-shop::form.control-group>

            {!! view_render_event('bagisto.shop.checkout.onepage.address.form.address.after') !!}

            <!-- Country & State Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- Country -->
                <x-shop::form.control-group class="mb-0" ::for="controlName + '_country'">
                    <x-shop::form.control-group.label class="{{ core()->isCountryRequired() ? 'required' : '' }} mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                        @lang('shop::app.checkout.onepage.address.country')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="select"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.country'"
                        ::value="address.country"
                        v-model="selectedCountry"
                        rules="{{ core()->isCountryRequired() ? 'required' : '' }}"
                        :label="trans('shop::app.checkout.onepage.address.country')"
                        :placeholder="trans('shop::app.checkout.onepage.address.country')"
                        ::id="controlName + '_country'"
                        @change="selectedState = ''"
                    >
                        <option value="">
                            @lang('shop::app.checkout.onepage.address.select-country')
                        </option>

                        <option
                            v-for="country in countries"
                            :value="country.code"
                        >
                            @{{ country.name }}
                        </option>
                    </x-shop::form.control-group.control>

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.country'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.country.after') !!}

                <!-- State -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="{{ core()->isStateRequired() ? 'required' : '' }} mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_state'">
                        @lang('shop::app.checkout.onepage.address.state')
                    </x-shop::form.control-group.label>

                    <template v-if="states">
                        <template v-if="haveStates">
                            <x-shop::form.control-group.control
                                type="select"
                                class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                ::name="controlName + '.state'"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                ::value="address.state"
                                v-model="selectedState"
                                :label="trans('shop::app.checkout.onepage.address.state')"
                                :placeholder="trans('shop::app.checkout.onepage.address.state')"
                                ::id="controlName + '_state'"
                            >
                                <option value="">
                                    @lang('shop::app.checkout.onepage.address.select-state')
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
                                class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                ::name="controlName + '.state'"
                                ::value="address.state"
                                v-model="selectedState"
                                rules="{{ core()->isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.checkout.onepage.address.state')"
                                :placeholder="trans('shop::app.checkout.onepage.address.state')"
                                ::id="controlName + '_state'"
                            />
                        </template>
                    </template>

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.state'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.state.after') !!}
            </div>

            <!-- City & Postcode Grid -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <!-- City -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_city'">
                        @lang('shop::app.checkout.onepage.address.city')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.city'"
                        ::value="address.city"
                        rules="required"
                        :label="trans('shop::app.checkout.onepage.address.city')"
                        :placeholder="trans('shop::app.checkout.onepage.address.city')"
                        ::id="controlName + '_city'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.city'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.city.after') !!}

                <!-- Postcode -->
                <x-shop::form.control-group class="mb-0">
                    <x-shop::form.control-group.label class="{{ core()->isPostCodeRequired() ? 'required' : '' }} mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]" ::for="controlName + '_postcode'">
                        @lang('shop::app.checkout.onepage.address.postcode')
                    </x-shop::form.control-group.label>

                    <x-shop::form.control-group.control
                        type="text"
                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                        ::name="controlName + '.postcode'"
                        ::value="address.postcode"
                        rules="{{ core()->isPostCodeRequired() ? 'required' : '' }}|postcode"
                        :label="trans('shop::app.checkout.onepage.address.postcode')"
                        :placeholder="trans('shop::app.checkout.onepage.address.postcode')"
                        ::id="controlName + '_postcode'"
                    />

                    <x-shop::form.control-group.error class="mt-1 text-xs text-red-500" ::name="controlName + '.postcode'" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.checkout.onepage.address.form.postcode.after') !!}
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                return {
                    selectedCountry: this.address.country,

                    selectedState: this.address.state,

                    countries: [],

                    states: null,
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
            }
        });
    </script>
@endPushOnce
