<!-- Coupon Vue Component -->
<v-coupon 
    :cart="cart"
    @coupon-applied="getCart"
    @coupon-removed="getCart"
>
</v-coupon>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-coupon-template"
    >
        <div class="flex items-center justify-between border-y border-[#ede8dc]/80 py-3 text-sm">
            <span class="font-medium text-[#52525b]">
                @{{ cart.coupon_code ? "@lang('shop::app.checkout.coupon.applied')" : "@lang('shop::app.checkout.coupon.discount')" }}
            </span>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.before') !!}

            <div class="flex items-center">
                <!-- Apply Coupon Modal & Form -->
                <x-shop::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, applyCoupon)">
                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.before') !!}

                        <x-shop::modal ref="couponModel">
                            <!-- Modal Toggler -->
                            <x-slot:toggle>
                                <button 
                                    type="button"
                                    class="inline-flex items-center gap-1.5 cursor-pointer text-xs font-semibold uppercase tracking-wider text-[#9e8464] transition-colors hover:text-[#18181b]"
                                    v-if="! cart.coupon_code"
                                >
                                    <span>@lang('shop::app.checkout.coupon.apply')</span>
                                    <span class="text-base leading-none">+</span>
                                </button>
                            </x-slot>

                            <!-- Modal Header -->
                            <x-slot:header class="p-6 pb-2">
                                <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                                    @lang('shop::app.checkout.coupon.apply')
                                </h2>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content class="px-6 py-4">
                                <x-shop::form.control-group class="mb-0!">
                                    <x-shop::form.control-group.label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                                        @lang('shop::app.checkout.coupon.discount')
                                    </x-shop::form.control-group.label>

                                    <x-shop::form.control-group.control
                                        type="text"
                                        class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-colors placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                        name="code"
                                        rules="required"
                                        :placeholder="trans('shop::app.checkout.coupon.enter-your-code')"
                                    />

                                    <x-shop::form.control-group.error
                                        class="mt-1.5 flex text-xs text-red-500"
                                        control-name="code"
                                    />
                                </x-shop::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer class="p-6 pt-2">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs text-[#71717a]">
                                            @lang('shop::app.checkout.coupon.subtotal')
                                        </p>
                                        <p class="font-editorial text-xl font-semibold text-[#18181b]">
                                            @{{ cart.formatted_sub_total }}
                                        </p>
                                    </div>

                                    <button
                                        type="submit"
                                        class="cursor-pointer rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="isStoring"
                                    >
                                        <span v-if="! isStoring">@lang('shop::app.checkout.coupon.button-title')</span>
                                        <span v-else>@lang('beauty::app.products.processing')</span>
                                    </button>
                                </div>
                            </x-slot>
                        </x-shop::modal>

                        {!! view_render_event('bagisto.shop.checkout.cart.coupon.coupon_form_controls.after') !!}
                    </form>
                </x-shop::form>

                <!-- Applied Coupon Tag Container -->
                <div
                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50/80 px-3 py-1 text-xs font-medium text-emerald-800"
                    v-if="cart.coupon_code"
                >
                    <span class="tracking-wide">@{{ cart.coupon_code }}</span>

                    <button
                        type="button"
                        class="flex h-4 w-4 cursor-pointer items-center justify-center rounded-full text-emerald-600 transition-colors hover:bg-emerald-200/60 hover:text-red-600"
                        title="@lang('shop::app.checkout.coupon.remove')"
                        aria-label="@lang('shop::app.checkout.coupon.remove')"
                        @click="destroyCoupon"
                    >
                        <span class="text-sm font-bold leading-none">&times;</span>
                    </button>
                </div>
            </div>

            {!! view_render_event('bagisto.shop.checkout.cart.coupon.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-coupon', {
            template: '#v-coupon-template',
            
            props: ['cart'],

            data() {
                return {
                    isStoring: false,
                }
            },

            methods: {
                applyCoupon(params, { resetForm }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('shop.api.checkout.cart.coupon.apply') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            this.$emit('coupon-applied');
                  
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$refs.couponModel.toggle();

                            resetForm();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            this.$refs.couponModel.toggle();

                            if ([400, 422].includes(error.response.request.status)) {
                                this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.message });

                                resetForm();

                                return;
                            }

                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                destroyCoupon() {
                    this.$axios.delete("{{ route('shop.api.checkout.cart.coupon.remove') }}", {
                            '_token': "{{ csrf_token() }}"
                        })
                        .then((response) => {
                            this.$emit('coupon-removed');

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => console.log(error));
                },
            }
        })
    </script>
@endPushOnce
