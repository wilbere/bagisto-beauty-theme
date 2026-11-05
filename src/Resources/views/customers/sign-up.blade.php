<!-- SEO Meta Content -->
@push('meta')
    <meta
        name="description"
        content="@lang('shop::app.customers.signup-form.page-title')"
    />

    <meta
        name="keywords"
        content="@lang('shop::app.customers.signup-form.page-title')"
    />
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.signup-form.page-title')
    </x-slot>

    <div class="min-h-[75vh] bg-[#fdfbf7] py-8 sm:py-12 md:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Top Navigation / Return Link -->
            <div class="mb-6 flex items-center justify-start">
                <a
                    href="{{ route('shop.home.index') }}"
                    class="group inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-[#71717a] transition-colors hover:text-[#9e8464]"
                >
                    <span class="transition-transform group-hover:-translate-x-1">&larr;</span>
                    <span>@lang('shop::app.checkout.cart.index.continue-shopping')</span>
                </a>
            </div>

            <!-- Sign Up Form Card -->
            <div class="mx-auto w-full max-w-lg rounded-3xl border border-[#ede8dc] bg-white p-7 shadow-xs sm:max-w-xl sm:p-10">
                {!! view_render_event('bagisto.shop.customers.sign-up.logo.before') !!}
                {!! view_render_event('bagisto.shop.customers.sign-up.logo.after') !!}

                <!-- Header Title & Subtitle -->
                <div class="text-center">
                    <h1 class="font-editorial text-3xl font-semibold tracking-tight text-[#18181b] sm:text-4xl">
                        @lang('shop::app.customers.signup-form.page-title')
                    </h1>

                    <p class="mt-2 text-xs leading-relaxed text-[#71717a] sm:text-sm">
                        @lang('shop::app.customers.signup-form.form-signup-text')
                    </p>
                </div>

                <div class="mt-8">
                    <x-shop::form :action="route('shop.customers.register.store')">
                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.before') !!}

                        <!-- Name Row (First Name & Last Name) -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- First Name -->
                            <x-shop::form.control-group class="mb-3">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.customers.signup-form.first-name')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    name="first_name"
                                    rules="required"
                                    :value="old('first_name')"
                                    :label="trans('shop::app.customers.signup-form.first-name')"
                                    :placeholder="trans('shop::app.customers.signup-form.first-name')"
                                    :aria-label="trans('shop::app.customers.signup-form.first-name')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="first_name" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.customers.signup_form.first_name.after') !!}

                            <!-- Last Name -->
                            <x-shop::form.control-group class="mb-3">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.customers.signup-form.last-name')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="text"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    name="last_name"
                                    rules="required"
                                    :value="old('last_name')"
                                    :label="trans('shop::app.customers.signup-form.last-name')"
                                    :placeholder="trans('shop::app.customers.signup-form.last-name')"
                                    :aria-label="trans('shop::app.customers.signup-form.last-name')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="last_name" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.customers.signup_form.last_name.after') !!}
                        </div>

                        <!-- Email -->
                        <x-shop::form.control-group class="mb-3">
                            <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                @lang('shop::app.customers.signup-form.email')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="email"
                                class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                name="email"
                                rules="required|email"
                                :value="old('email')"
                                :label="trans('shop::app.customers.signup-form.email')"
                                placeholder="email@example.com"
                                :aria-label="trans('shop::app.customers.signup-form.email')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="email" class="mt-1 text-xs text-red-500" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.signup_form.email.after') !!}

                        <!-- Password Row (Password & Confirm Password) -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Password -->
                            <x-shop::form.control-group class="mb-3">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.customers.signup-form.password')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="password"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    name="password"
                                    rules="required|min:6"
                                    :value="old('password')"
                                    :label="trans('shop::app.customers.signup-form.password')"
                                    :placeholder="trans('shop::app.customers.signup-form.password')"
                                    ref="password"
                                    :aria-label="trans('shop::app.customers.signup-form.password')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="password" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.customers.signup_form.password.after') !!}

                            <!-- Confirm Password -->
                            <x-shop::form.control-group class="mb-3">
                                <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.customers.signup-form.confirm-pass')
                                </x-shop::form.control-group.label>

                                <x-shop::form.control-group.control
                                    type="password"
                                    class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                    name="password_confirmation"
                                    rules="confirmed:@password"
                                    value=""
                                    :label="trans('shop::app.customers.signup-form.password')"
                                    :placeholder="trans('shop::app.customers.signup-form.confirm-pass')"
                                    :aria-label="trans('shop::app.customers.signup-form.confirm-pass')"
                                    aria-required="true"
                                />

                                <x-shop::form.control-group.error control-name="password_confirmation" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>

                            {!! view_render_event('bagisto.shop.customers.signup_form.password_confirmation.after') !!}
                        </div>

                        <!-- Captcha -->
                        @if (core()->getConfigData('customer.captcha.credentials.status'))
                            <x-shop::form.control-group class="mt-2">
                                {!! \Webkul\Customer\Facades\Captcha::render() !!}

                                <x-shop::form.control-group.error control-name="recaptcha_token" class="mt-1 text-xs text-red-500" />
                            </x-shop::form.control-group>
                        @endif

                        <!-- Newsletter Subscription Checkbox -->
                        @if (core()->getConfigData('customer.settings.create_new_account_options.news_letter'))
                            <div class="mt-2 mb-3 flex select-none items-center gap-2.5">
                                <input
                                    type="checkbox"
                                    name="is_subscribed"
                                    id="is-subscribed"
                                    class="h-4.5 w-4.5 cursor-pointer rounded border-[#ede8dc] text-[#18181b] accent-[#18181b] focus:ring-[#9e8464]"
                                />

                                <label
                                    class="cursor-pointer select-none text-xs text-[#52525b] transition-colors hover:text-[#18181b]"
                                    for="is-subscribed"
                                >
                                    @lang('shop::app.customers.signup-form.subscribe-to-newsletter')
                                </label>
                            </div>
                        @endif

                        {!! view_render_event('bagisto.shop.customers.signup_form.newsletter_subscription.after') !!}

                        <!-- GDPR Agreement -->
                        @if(
                            core()->getConfigData('general.gdpr.settings.enabled')
                            && core()->getConfigData('general.gdpr.agreement.enabled')
                        )
                            <div class="mb-3 flex select-none items-center gap-2 text-xs text-[#52525b]">
                                <x-shop::form.control-group.control
                                    type="checkbox"
                                    name="agreement"
                                    id="agreement"
                                    value="0"
                                    rules="required"
                                    for="agreement"
                                    class="h-4.5 w-4.5 cursor-pointer rounded border-[#ede8dc] text-[#18181b] accent-[#18181b] focus:ring-[#9e8464]"
                                />

                                <label
                                    class="cursor-pointer select-none text-xs text-[#52525b]"
                                    for="agreement"
                                    v-pre
                                >
                                    {{ core()->getConfigData('general.gdpr.agreement.agreement_label') }}
                                </label>

                                @if (core()->getConfigData('general.gdpr.agreement.agreement_content'))
                                    <span
                                        class="cursor-pointer font-medium text-[#9e8464] underline hover:text-[#18181b]"
                                        @click="$refs.termsModal.open()"
                                    >
                                        @lang('shop::app.customers.signup-form.click-here')
                                    </span>
                                @endif
                            </div>

                            <x-shop::form.control-group.error control-name="agreement" class="mt-1 text-xs text-red-500" />
                        @endif

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button
                                class="flex w-full cursor-pointer items-center justify-center rounded-full bg-[#18181b] py-3.5 px-8 text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-[#9e8464] active:scale-[0.99]"
                                type="submit"
                            >
                                @lang('shop::app.customers.signup-form.button-title')
                            </button>
                        </div>

                        <!-- Social Login -->
                        <div class="mt-6 flex flex-col items-center justify-center">
                            {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                        </div>

                        {!! view_render_event('bagisto.shop.customers.signup_form_controls.after') !!}
                    </x-shop::form>
                </div>

                <!-- Switcher to Login -->
                <div class="mt-8 border-t border-[#ede8dc] pt-6 text-center text-xs text-[#71717a] sm:text-sm">
                    <span>@lang('shop::app.customers.signup-form.account-exists')</span>
                    <a
                        class="ml-1 font-semibold text-[#18181b] transition-colors hover:text-[#9e8464] hover:underline"
                        href="{{ route('shop.customer.session.index') }}"
                    >
                        @lang('shop::app.customers.signup-form.sign-in-button')
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush

    <!-- Terms & Conditions Modal -->
    <x-shop::modal ref="termsModal">
        <x-slot:toggle></x-slot>

        <x-slot:header class="p-6 pb-2">
            <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                @lang('shop::app.customers.signup-form.terms-conditions')
            </h2>
        </x-slot>

        <x-slot:content class="p-6 pt-2 text-sm text-[#52525b] leading-relaxed">
            <div class="max-h-96 overflow-y-auto">
                {!! core()->getConfigData('general.gdpr.agreement.agreement_content') !!}
            </div>
        </x-slot>
    </x-shop::modal>
</x-shop::layouts>
