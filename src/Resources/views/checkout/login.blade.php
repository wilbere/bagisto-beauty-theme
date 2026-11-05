<!-- Checkout Login Vue JS Component -->
<v-checkout-login>
    <div class="flex items-center">
        <button
            type="button"
            class="cursor-pointer text-xs font-semibold uppercase tracking-wider text-[#9e8464] transition-colors hover:text-[#18181b]"
        >
            @lang('shop::app.checkout.login.title')
        </button>
    </div>
</v-checkout-login>

@pushOnce('scripts')
    {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}

    <script
        type="text/x-template"
        id="v-checkout-login-template"
    >
        <div>
            <div class="flex items-center">
                <button
                    type="button"
                    class="cursor-pointer text-xs font-semibold uppercase tracking-wider text-[#9e8464] transition-colors hover:text-[#18181b]"
                    @click="$refs.loginModel.open()"
                >
                    @lang('shop::app.checkout.login.title')
                </button>
            </div>

            <!-- Login Form -->
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                {!! view_render_event('bagisto.shop.checkout.login.before') !!}

                <!-- Login form -->
                <form @submit="handleSubmit($event, login)">
                    {!! view_render_event('bagisto.shop.checkout.login.form_controls.before') !!}

                    <!-- Login modal -->
                    <x-shop::modal ref="loginModel">
                        <!-- Modal Header -->
                        <x-slot:header class="p-6 pb-2">
                            <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                                @lang('shop::app.checkout.login.title')
                            </h2>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content class="px-6 py-4">
                            <!-- Email -->
                            <x-shop::form.control-group class="mb-4">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.checkout.login.email')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="email"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('shop::app.checkout.login.email')"
                                    placeholder="email@example.com"
                                    :aria-label="trans('shop::app.checkout.login.email')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="email" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            <!-- Password -->
                            <x-shop::form.control-group class="mb-2">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.checkout.login.password')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="password"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('shop::app.checkout.login.password')"
                                    :placeholder="trans('shop::app.checkout.login.password')"
                                    :aria-label="trans('shop::app.checkout.login.password')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="password" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            <!-- Captcha -->
                            @if (core()->getConfigData('customer.captcha.credentials.status'))
                                <x-shop::form.control-group class="mt-4">
                                    {!! \Webkul\Customer\Facades\Captcha::render() !!}

                                    <x-shop::form.control-group.error control-name="recaptcha_token" class="mt-1 text-xs text-red-500" />
                                </x-shop::form.control-group>
                            @endif
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer class="p-6 pt-2">
                            <div class="flex items-center justify-end gap-4">
                                <button
                                    type="submit"
                                    class="cursor-pointer rounded-full bg-[#18181b] px-7 py-3 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464] disabled:cursor-not-allowed disabled:opacity-50"
                                    :disabled="isStoring"
                                >
                                    <span v-if="! isStoring">@lang('shop::app.checkout.login.title')</span>
                                    <span v-else>@lang('beauty::app.products.processing')</span>
                                </button>
                            </div>
                        </x-slot>
                    </x-shop::modal>

                    {!! view_render_event('bagisto.shop.checkout.login.form_controls.after') !!}
                </form>
            </x-shop::form>

            {!! view_render_event('bagisto.shop.checkout.login.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-login', {
            template: '#v-checkout-login-template',

            data() {
                return {
                    isStoring: false,
                }
            },

            methods: {
                login(params, {
                    resetForm,
                    setErrors
                }) {
                    this.isStoring = true;

                    const captchaResponse = document.querySelector('[name="recaptcha_token"]')?.value

                    params['recaptcha_token'] = captchaResponse;

                    this.$axios.post("{{ route('shop.api.customers.session.create') }}", params)
                        .then((response) => {
                            this.isStoring = false;

                            window.location.reload();
                        })
                        .catch((error) => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);

                                return;
                            }

                            this.$emitter.emit('add-flash', {
                                type: 'error',
                                message: error.response.data.message
                            });
                        });
                },
            }
        })
    </script>
@endPushOnce
