<div class="w-full rounded-2xl border border-[#ede8dc] bg-white p-6 shadow-xs lg:sticky lg:top-28">
    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.before') !!}

    <h2
        class="font-editorial text-2xl font-semibold tracking-tight text-[#18181b]"
        role="heading"
        aria-level="2"
    >
        @lang('shop::app.checkout.cart.summary.cart-summary')
    </h2>

    {!! view_render_event('bagisto.shop.checkout.cart.summary.title.after') !!}

    <!-- Cart Totals List -->
    <div class="mt-6 space-y-4">
        <!-- Estimate Tax and Shipping -->
        @if (core()->getConfigData('sales.checkout.shopping_cart.estimate_shipping'))
            <template v-if="cart.have_stockable_items">
                @include('shop::checkout.cart.summary.estimate-shipping')
            </template>
        @endif

        <!-- Sub Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.before') !!}

        <template v-if="displayTax.subtotal == 'including_tax'">
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </span>

                <span class="font-semibold text-[#18181b]">
                    @{{ cart.formatted_sub_total_incl_tax }}
                </span>
            </div>
        </template>

        <template v-else-if="displayTax.subtotal == 'both'">
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </span>

                <div class="text-right">
                    <p class="font-semibold text-[#18181b]">
                        @{{ cart.formatted_sub_total_incl_tax }}
                    </p>

                    <p class="text-xs text-[#71717a]">
                        @lang('shop::app.checkout.cart.summary.excl-tax') @{{ cart.formatted_sub_total }}
                    </p>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.sub-total')
                </span>

                <span class="font-semibold text-[#18181b]">
                    @{{ cart.formatted_sub_total }}
                </span>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.sub_total.after') !!}

        <!-- Discount -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.before') !!}

        <template v-if="cart.discount_amount && parseFloat(cart.discount_amount) > 0">
            <!-- Single Source Simple Line -->
            <div
                class="flex items-center justify-between text-sm text-[#b54a4a]"
                v-if="parseFloat(cart.items_discount_amount || 0) <= 0 || parseFloat(cart.shipping_discount_amount || 0) <= 0"
            >
                <span>
                    @lang('shop::app.checkout.cart.summary.discount-amount')
                </span>

                <span class="font-semibold">
                    - @{{ cart.formatted_discount_amount }}
                </span>
            </div>

            <!-- Multi Source Expandable Breakdown -->
            <div
                class="rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-3 text-sm"
                v-else
            >
                <div
                    class="flex cursor-pointer items-center justify-between text-[#b54a4a]"
                    @click="cart.show_discount_breakdown = ! cart.show_discount_breakdown"
                >
                    <span>
                        @lang('shop::app.checkout.cart.summary.discount-amount')
                    </span>

                    <span class="flex items-center gap-1 font-semibold">
                        - @{{ cart.formatted_discount_amount }}
                        <span class="text-xs" :class="{'rotate-180': cart.show_discount_breakdown}">▼</span>
                    </span>
                </div>

                <div
                    class="mt-2 space-y-1 border-t border-[#ede8dc] pt-2 text-xs text-[#71717a]"
                    v-show="cart.show_discount_breakdown"
                >
                    <div class="flex justify-between">
                        <span>@lang('shop::app.checkout.cart.summary.items-discount')</span>
                        <span>- @{{ cart.formatted_items_discount_amount }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>@lang('shop::app.checkout.cart.summary.shipping-discount')</span>
                        <span>- @{{ cart.formatted_shipping_discount_amount }}</span>
                    </div>
                </div>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.discount_amount.after') !!}

        <!-- Apply Coupon -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.before') !!}

        @include('shop::checkout.coupon')

        {!! view_render_event('bagisto.shop.checkout.cart.summary.coupon.after') !!}

        <!-- Shipping Rates -->
        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.before') !!}
        
        <template v-if="displayTax.shipping == 'including_tax'">
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </span>

                <span class="font-semibold text-[#18181b]">
                    + @{{ cart.formatted_shipping_amount_incl_tax }}
                </span>
            </div>
        </template>

        <template v-else-if="displayTax.shipping == 'both'">
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </span>

                <div class="text-right">
                    <p class="font-semibold text-[#18181b]">
                        + @{{ cart.formatted_shipping_amount_incl_tax }}
                    </p>

                    <p class="text-xs text-[#71717a]">
                        @lang('shop::app.checkout.cart.summary.excl-tax') @{{ cart.formatted_shipping_amount }}
                    </p>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex items-center justify-between text-sm">
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.delivery-charges')
                </span>

                <span class="font-semibold text-[#18181b]">
                    + @{{ cart.formatted_shipping_amount }}
                </span>
            </div>
        </template>

        {!! view_render_event('bagisto.shop.checkout.onepage.summary.delivery_charges.after') !!}

        <!-- Taxes -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.before') !!}

        @php
            $showTaxBreakdown = (bool) core()->getConfigData('sales.taxes.shopping_cart.show_tax_breakdown');
        @endphp

        <div
            class="flex items-center justify-between text-sm"
            v-if="! cart.tax_total"
        >
            <span class="text-[#52525b]">
                @lang('shop::app.checkout.cart.summary.tax')
            </span>

            <span class="font-semibold text-[#18181b]">
                + @{{ cart.formatted_tax_total }}
            </span>
        </div>

        @if ($showTaxBreakdown)
            <div
                class="rounded-xl border border-[#ede8dc] bg-[#fdfbf7] p-3 text-sm"
                v-else
            >
                <button
                    type="button"
                    class="flex w-full cursor-pointer items-center justify-between text-left focus:outline-hidden"
                    @click="cart.show_taxes = ! cart.show_taxes"
                >
                    <span class="text-[#52525b]">
                        @lang('shop::app.checkout.cart.summary.tax')
                    </span>

                    <span class="flex items-center gap-1.5 font-semibold text-[#18181b]">
                        <template v-if="displayTax.subtotal === 'including_tax' || displayTax.subtotal === 'both'">
                            @{{ cart.formatted_tax_total }}
                            <span class="text-xs font-normal text-[#71717a]">
                                (@lang('shop::app.checkout.cart.summary.included'))
                            </span>
                        </template>

                        <template v-else>+ @{{ cart.formatted_tax_total }}</template>

                        <span class="text-xs" :class="{'rotate-180': cart.show_taxes}">▼</span>
                    </span>
                </button>

                <div
                    class="mt-2 space-y-1.5 border-t border-[#ede8dc] pt-2"
                    v-show="cart.show_taxes"
                >
                    <div
                        class="space-y-1"
                        v-for="taxLine in cart.applied_taxes_breakdown"
                    >
                        <p class="text-xs font-medium text-[#71717a]">
                            @{{ taxLine.rate }}
                        </p>

                        <div
                            class="flex items-center justify-between text-xs text-[#52525b]"
                            v-for="product in taxLine.items"
                        >
                            <span class="truncate pr-2">@{{ product.name }}</span>
                            <span class="shrink-0 whitespace-nowrap">
                                <template v-if="displayTax.subtotal === 'including_tax' || displayTax.subtotal === 'both'">@{{ product.tax_amount }}</template>
                                <template v-else>+ @{{ product.tax_amount }}</template>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div
                class="flex items-center justify-between text-sm"
                v-else
            >
                <span class="text-[#52525b]">
                    @lang('shop::app.checkout.cart.summary.tax')
                </span>

                <span class="font-semibold text-[#18181b]">
                    <template v-if="displayTax.subtotal === 'including_tax' || displayTax.subtotal === 'both'">
                        @{{ cart.formatted_tax_total }}
                        <span class="text-xs font-normal text-[#71717a]">
                            (@lang('shop::app.checkout.cart.summary.included'))
                        </span>
                    </template>

                    <template v-else>+ @{{ cart.formatted_tax_total }}</template>
                </span>
            </div>
        @endif

        {!! view_render_event('bagisto.shop.checkout.cart.summary.tax.after') !!}

        <!-- Cart Grand Total -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.before') !!}

        <div class="border-t border-[#ede8dc] pt-4">
            <div class="flex items-baseline justify-between">
                <span class="text-base font-semibold text-[#18181b]">
                    @lang('shop::app.checkout.cart.summary.grand-total')
                </span>

                <span class="font-editorial text-2xl font-bold text-[#18181b]">
                    @{{ cart.formatted_grand_total }}
                </span>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.grand_total.after') !!}

        <!-- Proceed To Checkout Button -->
        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.before') !!}

        <a
            href="{{ route('shop.checkout.onepage.index') }}"
            class="flex w-full items-center justify-center rounded-full bg-[#18181b] py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-[#9e8464] active:scale-[0.99]"
        >
            @lang('shop::app.checkout.cart.summary.proceed-to-checkout')
        </a>

        {!! view_render_event('bagisto.shop.checkout.cart.summary.proceed_to_checkout.after') !!}

        <!-- Trust & Security Badges -->
        <div class="mt-4 flex items-center justify-center gap-4 text-center text-[11px] text-[#71717a]">
            <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-[#9e8464]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                @lang('beauty::app.services.secure-payment')
            </span>
            <span>•</span>
            <span class="flex items-center gap-1">
                <svg class="h-3.5 w-3.5 text-[#9e8464]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @lang('beauty::app.services.safe-packaging')
            </span>
        </div>
    </div>
</div>
