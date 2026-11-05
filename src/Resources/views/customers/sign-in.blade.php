<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.login-form.page-title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.login-form.page-title')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.login-form.page-title')
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

            <!-- Login Form Card -->
            <div class="mx-auto w-full max-w-md rounded-3xl border border-[#ede8dc] bg-white p-7 shadow-xs sm:max-w-lg sm:p-10">
                {!! view_render_event('bagisto.shop.customers.login.logo.before') !!}
                {!! view_render_event('bagisto.shop.customers.login.logo.after') !!}

                <!-- Header Title & Subtitle -->
                <div class="text-center">
                    <h1 class="font-editorial text-3xl font-semibold tracking-tight text-[#18181b] sm:text-4xl">
                        @lang('shop::app.customers.login-form.title')
                    </h1>

                    <p class="mt-2 text-xs leading-relaxed text-[#71717a] sm:text-sm">
                        @lang('shop::app.customers.login-form.form-login-text')
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.login.before') !!}

                <div class="mt-8">
                    <x-shop::form :action="route('shop.customer.session.create')">
                        {!! view_render_event('bagisto.shop.customers.login_form_controls.before') !!}

                        <!-- Email -->
                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="required mb-1.5 block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                @lang('shop::app.customers.login-form.email')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="email"
                                class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                name="email"
                                rules="required|email"
                                value=""
                                :label="trans('shop::app.customers.login-form.email')"
                                placeholder="email@example.com"
                                :aria-label="trans('shop::app.customers.login-form.email')"
                                aria-required="true"
                            />

                            <x-shop::form.control-group.error control-name="email" class="mt-1 text-xs text-red-500" />
                        </x-shop::form.control-group>

                        <!-- Password -->
                        <x-shop::form.control-group class="mb-2">
                            <div class="mb-1.5 flex items-center justify-between">
                                <x-shop::form.control-group.label class="required mb-0! block text-xs font-semibold uppercase tracking-wider text-[#52525b]">
                                    @lang('shop::app.customers.login-form.password')
                                </x-shop::form.control-group.label>

                                <a
                                    href="{{ route('shop.customers.forgot_password.create') }}"
                                    class="text-xs font-medium text-[#9e8464] transition-colors hover:text-[#18181b] hover:underline"
                                >
                                    @lang('shop::app.customers.login-form.forgot-pass')
                                </a>
                            </div>

                            <x-shop::form.control-group.control
                                type="password"
                                class="w-full rounded-xl border border-[#ede8dc] bg-[#fdfbf7] px-4 py-3 text-sm text-[#18181b] transition-all placeholder:text-[#a1a1aa] focus:border-[#9e8464] focus:bg-white focus:outline-hidden focus:ring-1 focus:ring-[#9e8464]"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                value=""
                                :label="trans('shop::app.customers.login-form.password')"
                                :placeholder="trans('shop::app.customers.login-form.password')"
                                :aria-label="trans('shop::app.customers.login-form.password')"
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

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button
                                class="flex w-full cursor-pointer items-center justify-center rounded-full bg-[#18181b] py-3.5 px-8 text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-[#9e8464] active:scale-[0.99]"
                                type="submit"
                            >
                                @lang('shop::app.customers.login-form.button-title')
                            </button>
                        </div>

                        <!-- Social Login -->
                        <div class="mt-6 flex flex-col items-center justify-center">
                            {!! view_render_event('bagisto.shop.customers.login_form_controls.after') !!}
                        </div>
                    </x-shop::form>
                </div>

                {!! view_render_event('bagisto.shop.customers.login.after') !!}

                @if (
                    request()->cookie('enable-resend')
                    && request()->cookie('email-for-resend')
                )
                    <p class="mt-4 text-center text-xs text-[#71717a]">
                        <a
                            class="font-semibold text-[#9e8464] underline transition-colors hover:text-[#18181b]"
                            href="{{ route('shop.customers.resend.verification_email', urlencode(request()->cookie('email-for-resend'))) }}"
                        >
                            @lang('shop::app.customers.login-form.resend-verification')
                        </a>
                    </p>
                @endif

                <!-- Switcher to Register -->
                <div class="mt-8 border-t border-[#ede8dc] pt-6 text-center text-xs text-[#71717a] sm:text-sm">
                    <span>@lang('shop::app.customers.login-form.new-customer')</span>
                    <a
                        class="ml-1 font-semibold text-[#18181b] transition-colors hover:text-[#9e8464] hover:underline"
                        href="{{ route('shop.customers.register.index') }}"
                    >
                        @lang('shop::app.customers.login-form.create-your-account')
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>
