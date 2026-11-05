<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="@lang('shop::app.customers.forgot-password.title')"/>
    <meta name="keywords" content="@lang('shop::app.customers.forgot-password.title')"/>
@endPush

<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.forgot-password.title')
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

            <!-- Forgot Password Card -->
            <div class="mx-auto w-full max-w-md rounded-3xl border border-[#ede8dc] bg-white p-7 shadow-xs sm:p-10">
                {!! view_render_event('bagisto.shop.customers.forget_password.logo.before') !!}
                {!! view_render_event('bagisto.shop.customers.forget_password.logo.after') !!}

                <!-- Header Icon & Title -->
                <div class="text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[#f7f4ee] text-[#9e8464]">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>

                    <h1 class="mt-4 font-editorial text-3xl font-semibold tracking-tight text-[#18181b]">
                        @lang('shop::app.customers.forgot-password.title')
                    </h1>

                    <p class="mt-2 text-xs leading-relaxed text-[#71717a] sm:text-sm">
                        @lang('shop::app.customers.forgot-password.forgot-password-text')
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.forget_password.before') !!}

                <div class="mt-8">
                    <x-shop::form :action="route('shop.customers.forgot_password.store')">
                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.before') !!}

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

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.email.after') !!}

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
                                @lang('shop::app.customers.forgot-password.submit')
                            </button>
                        </div>

                        {!! view_render_event('bagisto.shop.customers.forget_password_form_controls.after') !!}
                    </x-shop::form>
                </div>

                {!! view_render_event('bagisto.shop.customers.forget_password.after') !!}

                <!-- Back to Sign In Link -->
                <div class="mt-8 border-t border-[#ede8dc] pt-6 text-center text-xs text-[#71717a] sm:text-sm">
                    <a
                        class="group inline-flex items-center gap-1.5 font-semibold text-[#18181b] transition-colors hover:text-[#9e8464]"
                        href="{{ route('shop.customer.session.index') }}"
                    >
                        <span class="transition-transform group-hover:-translate-x-1">&larr;</span>
                        <span>@lang('shop::app.customers.forgot-password.sign-in-button')</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {!! \Webkul\Customer\Facades\Captcha::renderJS() !!}
    @endpush
</x-shop::layouts>
