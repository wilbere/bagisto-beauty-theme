<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.index.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="profile" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-1 min-w-0 w-full">
        <div class="flex items-center justify-between border-b border-[#ede8dc] pb-5">
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] md:hidden shadow-xs transition-colors"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                </a>

                <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                    @lang('shop::app.customers.account.profile.index.title')
                </h1>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.before') !!}

            <a
                href="{{ route('shop.customers.account.profile.edit') }}"
                class="inline-flex items-center justify-center rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold tracking-wide text-white transition-all hover:bg-[#9e8464] shadow-xs active:scale-[0.99]"
            >
                @lang('shop::app.customers.account.profile.index.edit')
            </a>

            {!! view_render_event('bagisto.shop.customers.account.profile.edit_button.after') !!}
        </div>

        <!-- Profile Information Card -->
        <div class="mt-6 rounded-2xl border border-[#ede8dc] bg-white p-6 sm:p-8 shadow-xs">
            <div class="divide-y divide-[#ede8dc]">
                {!! view_render_event('bagisto.shop.customers.account.profile.first_name.before') !!}

                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-1 sm:gap-4 py-4.5 first:pt-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                        @lang('shop::app.customers.account.profile.index.first-name')
                    </p>

                    <p class="text-sm font-medium text-[#18181b]" v-pre>
                        {{ $customer->first_name }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.first_name.after') !!}

                {!! view_render_event('bagisto.shop.customers.account.profile.last_name.before') !!}

                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-1 sm:gap-4 py-4.5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                        @lang('shop::app.customers.account.profile.index.last-name')
                    </p>

                    <p class="text-sm font-medium text-[#18181b]" v-pre>
                        {{ $customer->last_name }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.last_name.after') !!}

                {!! view_render_event('bagisto.shop.customers.account.profile.gender.before') !!}

                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-1 sm:gap-4 py-4.5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                        @lang('shop::app.customers.account.profile.index.gender')
                    </p>

                    <p class="text-sm font-medium text-[#18181b]" v-pre>
                        {{ $customer->gender ?? '-' }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.gender.after') !!}

                {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.before') !!}

                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-1 sm:gap-4 py-4.5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                        @lang('shop::app.customers.account.profile.index.dob')
                    </p>

                    <p class="text-sm font-medium text-[#18181b]" v-pre>
                        {{ $customer->date_of_birth?->format('Y-m-d') ?? '-' }}
                    </p>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.date_of_birth.after') !!}

                {!! view_render_event('bagisto.shop.customers.account.profile.email.before') !!}

                <div class="grid grid-cols-1 sm:grid-cols-[1fr_2fr] gap-1 sm:gap-4 py-4.5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                        @lang('shop::app.customers.account.profile.index.email')
                    </p>

                    <p class="text-sm font-medium text-[#18181b]" v-pre>
                        {{ $customer->email }}
                    </p>
                </div>
                
                {!! view_render_event('bagisto.shop.customers.account.profile.email.after') !!}
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.delete.before') !!}

            <!-- Delete Profile Modal -->
            <div class="mt-8 border-t border-[#ede8dc] pt-6 flex justify-end">
                <x-shop::form action="{{ route('shop.customers.account.profile.destroy') }}">
                    <x-shop::modal>
                        <x-slot:toggle>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-white px-5 py-2 text-xs font-semibold text-red-600 transition-all hover:bg-red-50 hover:border-red-300 shadow-xs cursor-pointer"
                            >
                                <span class="icon-bin text-base"></span>
                                @lang('shop::app.customers.account.profile.index.delete-profile')
                            </button>
                        </x-slot>

                        <x-slot:header>
                            <h2 class="font-serif text-xl font-bold text-[#18181b]">
                                @lang('shop::app.customers.account.profile.index.enter-password')
                            </h2>
                        </x-slot>

                        <x-slot:content>
                            <x-shop::form.control-group class="mb-0!">
                                <x-shop::form.control-group.control
                                    type="password"
                                    name="password"
                                    class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-5 py-3 text-sm text-[#18181b] focus:border-[#9e8464] focus:bg-white focus:outline-hidden"
                                    rules="required"
                                    placeholder="Enter your password"
                                />

                                <x-shop::form.control-group.error
                                    class="text-left text-xs text-red-500 mt-1"
                                    control-name="password"
                                />
                            </x-shop::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-2.5 text-xs font-semibold text-white transition-all hover:bg-red-700 shadow-xs cursor-pointer"
                            >
                                @lang('shop::app.customers.account.profile.index.delete')
                            </button>
                        </x-slot>
                    </x-shop::modal>
                </x-shop::form>
            </div>

            {!! view_render_event('bagisto.shop.customers.account.profile.delete.after') !!}
        </div>
    </div>
</x-shop::layouts.account>
