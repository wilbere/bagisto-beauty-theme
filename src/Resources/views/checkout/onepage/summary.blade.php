<!-- Header -->
<div class="mb-5 flex items-center justify-between border-b border-[#ede8dc] pb-4">
    <h2 class="font-editorial text-xl font-semibold text-[#18181b]">
        @lang('shop::app.checkout.onepage.summary.cart-summary')
    </h2>

    <span class="rounded-full bg-[#fdfbf7] border border-[#ede8dc] px-3 py-1 text-xs font-medium text-[#71717a]">
        @{{ cart.items_count }} @lang('beauty::app.checkout.items')
    </span>
</div>

<!-- Cart Items List -->
<div class="divide-y divide-[#ede8dc] border-b border-[#ede8dc] pb-4 max-h-72 overflow-y-auto no-scrollbar">
    <div
        class="flex items-center gap-4 py-3"
        v-for="item in cart.items"
    >
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.before') !!}

        <img
            class="h-16 w-16 shrink-0 rounded-2xl border border-[#ede8dc] bg-[#fdfbf7] object-cover p-1"
            :src="item.base_image.small_image_url"
            :alt="item.base_image.alt"
            width="64"
            height="64"
        />

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_image.after') !!}

        <div class="flex-1 min-w-0">
            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.before') !!}

            <p class="truncate text-xs font-semibold text-[#18181b]">
                @{{ item.name }}
            </p>

            {!! view_render_event('bagisto.shop.checkout.onepage.summary.item_name.after') !!}

            <p class="mt-1 text-xs text-[#71717a]">
                <template v-if="displayTax.prices == 'including_tax'">
                    @lang('shop::app.checkout.onepage.summary.price-and-qty', ['price' => '@{{ item.formatted_price_incl_tax }}', 'qty' => '@{{ item.quantity }}'])
                </template>
                <template v-else-if="displayTax.prices == 'both'">
                    @lang('shop::app.checkout.onepage.summary.price-and-qty', ['price' => '@{{ item.formatted_price_incl_tax }}', 'qty' => '@{{ item.quantity }}'])
                </template>
                <template v-else>
                    @lang('shop::app.checkout.onepage.summary.price-and-qty', ['price' => '@{{ item.formatted_price }}', 'qty' => '@{{ item.quantity }}'])
                </template>
            </p>
        </div>

        <div class="text-right">
            <p class="text-xs font-bold text-[#18181b]">
                @{{ item.formatted_total }}
            </p>
        </div>
    </div>
</div>

<!-- Cart Totals Breakdown -->
<div class="mt-5 space-y-3">
    <!-- Sub Total -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.before') !!}

    <div class="flex justify-between text-xs">
        <span class="text-[#71717a]">@lang('shop::app.checkout.onepage.summary.sub-total')</span>
        <span class="font-semibold text-[#18181b]">
            <template v-if="displayTax.subtotal == 'including_tax'">
                @{{ cart.formatted_sub_total_incl_tax }}
            </template>
            <template v-else>
                @{{ cart.formatted_sub_total }}
            </template>
        </span>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.sub_total.after') !!}

    <!-- Discount Amount -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.before') !!}

    <div
        class="flex justify-between text-xs text-emerald-700"
        v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0"
    >
        <span>@lang('shop::app.checkout.onepage.summary.discount-amount')</span>
        <span class="font-semibold">- @{{ cart.formatted_discount_amount }}</span>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.discount_amount.after') !!}

    <!-- Apply Coupon Component -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.before') !!}

    <div class="py-2 border-y border-[#ede8dc]">
        @include('shop::checkout.coupon')
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.coupon.after') !!}

    <!-- Delivery Charges -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}

    <div class="flex justify-between text-xs">
        <span class="text-[#71717a]">@lang('shop::app.checkout.onepage.summary.delivery-charges')</span>
        <span class="font-semibold text-[#18181b]">
            <template v-if="parseFloat(cart.shipping_amount || 0) > 0">
                + @{{ cart.formatted_shipping_amount }}
            </template>
            <template v-else>
                <span class="text-emerald-700">Gratis</span>
            </template>
        </span>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

    <!-- Taxes -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.before') !!}

    <div
        class="flex justify-between text-xs"
        v-if="cart.tax_total && parseFloat(cart.tax_total) > 0"
    >
        <span class="text-[#71717a]">@lang('shop::app.checkout.onepage.summary.tax')</span>
        <span class="font-semibold text-[#18181b]">+ @{{ cart.formatted_tax_total }}</span>
    </div>

    {!! view_render_event('bagisto.shop.checkout.onepage.summary.tax.after') !!}

    <!-- Grand Total -->
    {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.before') !!}

    <div class="mt-4 border-t border-[#ede8dc] pt-4">
        <div class="flex items-baseline justify-between">
            <span class="text-sm font-semibold uppercase tracking-wider text-[#18181b]">
                @lang('shop::app.checkout.onepage.summary.grand-total')
            </span>

            <span class="font-editorial text-2xl font-bold text-[#18181b]">
                @{{ cart.formatted_grand_total }}
            </span>
        </div>

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.grand_total.after') !!}
    </div>
</div>
