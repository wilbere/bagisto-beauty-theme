<x-shop::layouts
    :has-header="true"
    :has-feature="false"
    :has-footer="true"
>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.checkout.success.thanks')
    </x-slot>

    <div class="min-h-[70vh] bg-[#fdfbf7] py-12 sm:py-16">
        <div class="mx-auto max-w-xl px-4 sm:px-6">
            <div class="rounded-3xl border border-[#ede8dc] bg-white p-8 text-center shadow-xs sm:p-12">
                {{ view_render_event('bagisto.shop.checkout.success.image.before', ['order' => $order]) }}

                <!-- Success Icon -->
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#fdfbf7] border border-[#ede8dc] text-[#9e8464] shadow-xs">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>

                {{ view_render_event('bagisto.shop.checkout.success.image.after', ['order' => $order]) }}

                <!-- Title -->
                <h1 class="font-editorial text-3xl font-semibold text-[#18181b] sm:text-4xl">
                    @lang('shop::app.checkout.success.thanks')
                </h1>

                <!-- Order ID Badge -->
                <div class="my-5 inline-flex items-center gap-2 rounded-full border border-[#ede8dc] bg-[#fdfbf7] px-4 py-1.5 text-xs font-semibold text-[#18181b]">
                    <span>Pedido:</span>
                    @if (auth()->guard('customer')->user())
                        <a class="text-[#9e8464] underline transition-colors hover:text-[#18181b]" href="{{ route('shop.customers.account.orders.view', $order->id) }}">
                            #{{ $order->increment_id }}
                        </a>
                    @else
                        <span class="text-[#9e8464]">#{{ $order->increment_id }}</span>
                    @endif
                </div>

                <!-- Info Message -->
                <p class="mx-auto max-w-md text-sm leading-relaxed text-[#71717a]">
                    @if (! empty($order->checkout_message))
                        {!! nl2br(e($order->checkout_message)) !!}
                    @else
                        @lang('shop::app.checkout.success.info')
                    @endif
                </p>

                {{ view_render_event('bagisto.shop.checkout.success.continue-shopping.before', ['order' => $order]) }}

                <!-- Continue Shopping CTA -->
                <div class="mt-8">
                    <a
                        href="{{ route('shop.home.index') }}"
                        class="inline-flex cursor-pointer items-center justify-center rounded-full bg-[#18181b] px-9 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#9e8464]"
                    >
                        @lang('shop::app.checkout.cart.index.continue-shopping')
                    </a>
                </div>

                {{ view_render_event('bagisto.shop.checkout.success.continue-shopping.after', ['order' => $order]) }}
            </div>
        </div>
    </div>
</x-shop::layouts>
