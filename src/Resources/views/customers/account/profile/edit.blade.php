<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.edit.edit-profile')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="profile.edit" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-1 min-w-0 w-full">
        <div class="mb-6 flex items-center justify-between border-b border-[#ede8dc] pb-5">
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] shadow-xs transition-colors"
                    href="{{ route('shop.customers.account.profile.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                </a>

                <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                    @lang('shop::app.customers.account.profile.edit.edit-profile')
                </h1>
            </div>
        </div>
    
        {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

        <!-- Profile Edit Form -->
        <div class="rounded-2xl border border-[#ede8dc] bg-white p-6 sm:p-8 shadow-xs">
            <x-shop::form
                :action="route('shop.customers.account.profile.update')"
                enctype="multipart/form-data"
            >
                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}
        
                <!-- Image -->
                <x-shop::form.control-group class="mb-6">
                    <x-shop::form.control-group.control
                        type="image"
                        class="mb-0 rounded-2xl p-0! text-gray-700"
                        name="image[]"
                        :label="trans('Image')"
                        :is-multiple="false"
                        accepted-types="image/*"
                        :src="$customer->image_url"
                    />

                    <x-shop::form.control-group.error control-name="image[]" />
                </x-shop::form.control-group>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.image.after') !!}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <!-- First Name -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.first-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="first_name"
                            rules="required"
                            :value="old('first_name') ?? $customer->first_name"
                            :label="trans('shop::app.customers.account.profile.edit.first-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.first-name')"
                        />

                        <x-shop::form.control-group.error control-name="first_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.first_name.after') !!}

                    <!-- Last Name -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.last-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="last_name"
                            rules="required"
                            :value="old('last_name') ?? $customer->last_name"
                            :label="trans('shop::app.customers.account.profile.edit.last-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.last-name')"
                        />

                        <x-shop::form.control-group.error control-name="last_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.last_name.after') !!}

                    <!-- Email -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            rules="required|email"
                            :value="old('email') ?? $customer->email"
                            :label="trans('shop::app.customers.account.profile.edit.email')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.email')"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.email.after') !!}

                    <!-- Phone -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.phone')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            rules="required|phone"
                            :value="old('phone') ?? $customer->phone"
                            :label="trans('shop::app.customers.account.profile.edit.phone')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.phone')"
                        />

                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.phone.after') !!}

                    <!-- Gender -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="required text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.gender')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="select"
                            class="mb-3"
                            name="gender"
                            rules="required"
                            :value="old('gender') ?? $customer->gender"
                            :aria-label="trans('shop::app.customers.account.profile.edit.select-gender')"
                            :label="trans('shop::app.customers.account.profile.edit.gender')"
                        >
                            <option value="Other">
                                @lang('shop::app.customers.account.profile.edit.other')
                            </option>

                            <option value="Male">
                                @lang('shop::app.customers.account.profile.edit.male')
                            </option>

                            <option value="Female">
                                @lang('shop::app.customers.account.profile.edit.female')
                            </option>
                        </x-shop::form.control-group.control>

                        <x-shop::form.control-group.error control-name="gender" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.gender.after') !!}

                    <!-- DOB -->
                    <x-shop::form.control-group class="mb-4">
                        <x-shop::form.control-group.label class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            @lang('shop::app.customers.account.profile.edit.dob')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="date"
                            name="date_of_birth"
                            :value="old('date_of_birth') ?? $customer->date_of_birth?->format('Y-m-d')"
                            :label="trans('shop::app.customers.account.profile.edit.dob')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.dob')"
                        />

                        <x-shop::form.control-group.error control-name="date_of_birth" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.date_of_birth.after') !!}
                </div>

                <div class="mt-6 border-t border-[#ede8dc] pt-6">
                    <h3 class="font-serif text-lg font-bold text-[#18181b] mb-4">
                        @lang('shop::app.customers.account.profile.edit.change-password')
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                        <!-- Current Password -->
                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                                @lang('shop::app.customers.account.profile.edit.current-password')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="password"
                                name="current_password"
                                value=""
                                :label="trans('shop::app.customers.account.profile.edit.current-password')"
                                :placeholder="trans('shop::app.customers.account.profile.edit.current-password')"
                            />

                            <x-shop::form.control-group.error control-name="current_password" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.old_password.after') !!}

                        <!-- New Password -->
                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                                @lang('shop::app.customers.account.profile.edit.new-password')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="password"
                                name="new_password"
                                value=""
                                :label="trans('shop::app.customers.account.profile.edit.new-password')"
                                :placeholder="trans('shop::app.customers.account.profile.edit.new-password')"
                            />

                            <x-shop::form.control-group.error control-name="new_password" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password.after') !!}

                        <!-- New Password Confirmation -->
                        <x-shop::form.control-group class="mb-4">
                            <x-shop::form.control-group.label class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                                @lang('shop::app.customers.account.profile.edit.confirm-password')
                            </x-shop::form.control-group.label>

                            <x-shop::form.control-group.control
                                type="password"
                                name="new_password_confirmation"
                                rules="confirmed:@new_password"
                                value=""
                                :label="trans('shop::app.customers.account.profile.edit.confirm-password')"
                                :placeholder="trans('shop::app.customers.account.profile.edit.confirm-password')"
                            />

                            <x-shop::form.control-group.error control-name="new_password_confirmation" />
                        </x-shop::form.control-group>

                        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password_confirmation.after') !!}
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="my-4 flex select-none items-center gap-2.5">
                    <input
                        type="checkbox"
                        name="subscribed_to_news_letter"
                        id="is-subscribed"
                        class="peer sr-only"
                        @checked($customer->subscribed_to_news_letter)
                    />

                    <label
                        class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-[#a1a1aa] peer-checked:text-[#9e8464] peer-focus-visible:ring-2 peer-focus-visible:ring-[#9e8464] peer-focus-visible:outline-hidden rounded"
                        for="is-subscribed"
                    ></label>

                    <label
                        class="cursor-pointer select-none text-sm text-[#71717a]"
                        for="is-subscribed"
                    >
                        @lang('shop::app.customers.account.profile.edit.subscribe-to-newsletter')
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a
                        href="{{ route('shop.customers.account.profile.index') }}"
                        class="inline-flex items-center justify-center rounded-full border border-[#ede8dc] bg-white px-6 py-2.5 text-xs font-semibold text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] shadow-xs transition-all"
                    >
                        @lang('shop::app.customers.account.profile.edit.cancel')
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-full bg-[#18181b] px-8 py-2.5 text-xs font-semibold tracking-wide text-white transition-all hover:bg-[#9e8464] shadow-xs active:scale-[0.99] cursor-pointer"
                    >
                        @lang('shop::app.customers.account.profile.edit.save')
                    </button>
                </div>

                {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

            </x-shop::form>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}

    </div>
</x-shop::layouts.account>
