{!! view_render_event('bagisto.shop.checkout.onepage.address.before') !!}

<div class="rounded-3xl border border-[#ede8dc] bg-white p-6 shadow-xs sm:p-8">
    <div class="mb-6 flex items-center justify-between border-b border-[#ede8dc] pb-4">
        <div class="flex items-center gap-3">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-[#18181b] text-xs font-semibold text-white">
                1
            </span>
            <h2 class="font-editorial text-2xl font-semibold text-[#18181b]">
                @lang('shop::app.checkout.onepage.address.title')
            </h2>
        </div>
    </div>

    <!-- If the customer is guest -->
    <template v-if="cart.is_guest">
        @include('shop::checkout.onepage.address.guest')
    </template>

    <!-- If the customer is logged in -->
    <template v-else>
        @include('shop::checkout.onepage.address.customer')
    </template>
</div>

{!! view_render_event('bagisto.shop.checkout.onepage.address.after') !!}
