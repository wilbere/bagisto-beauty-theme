@inject ('reviewHelper', \Webkul\Product\Helpers\Review::class)
@inject ('productViewHelper', \Webkul\Product\Helpers\View::class)

@php
    $totalRatings = $reviewHelper->getTotalRating($product);
    $totalReviews = $reviewHelper->getTotalReviews($product);
    $avgRatings = $reviewHelper->getAverageRating($product);
    $percentageRatings = $reviewHelper->getPercentageRating($product);
    $customAttributeValues = $productViewHelper->getAdditionalData($product);
    $attributeData = collect($customAttributeValues)->filter(fn ($item) => ! empty($item['value']));
@endphp

<!-- SEO Meta Content -->
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>
    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {!! app(\Webkul\Product\Helpers\SEO::class)->getProductJsonLd($product) !!}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $product->name }}" />
    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />
    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />
    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        @if (core()->getConfigData('general.general.breadcrumbs.shop'))
            <div class="mb-6 text-xs text-[#71717a]">
                <x-shop::breadcrumbs name="product" :entity="$product" />
            </div>
        @endif

        <!-- Main Product Vue Container -->
        <v-product>
            <x-shop::shimmer.products.view />
        </v-product>
    </div>

    <!-- Editorial Product Information Tabs -->
    <div class="border-t border-[#eae5db]/60 bg-[#f7f5f0]/30 py-16">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <x-shop::tabs position="center" ref="productTabs">
                <!-- Description Tab -->
                <x-shop::tabs.item
                    id="description-tab"
                    :title="trans('shop::app.products.view.description')"
                    :is-selected="true"
                >
                    <div class="prose prose-zinc max-w-none py-8 text-sm text-[#52525b] leading-relaxed">
                        {!! $product->description !!}
                    </div>
                </x-shop::tabs.item>

                <!-- Technical / Attributes Tab -->
                @if (count($attributeData))
                    <x-shop::tabs.item
                        id="additional-info-tab"
                        :title="trans('shop::app.products.view.additional-information')"
                        :is-selected="false"
                    >
                        <div class="py-8">
                            <dl class="divide-y divide-[#eae5db]/60">
                                @foreach ($customAttributeValues as $customAttributeValue)
                                    @if (! empty($customAttributeValue['value']))
                                        <div class="grid grid-cols-3 py-3 text-sm">
                                            <dt class="font-medium text-[#18181b]">{{ $customAttributeValue['label'] }}</dt>
                                            <dd class="col-span-2 text-[#52525b]">{{ $customAttributeValue['value'] }}</dd>
                                        </div>
                                    @endif
                                @endforeach
                            </dl>
                        </div>
                    </x-shop::tabs.item>
                @endif

                <!-- Reviews Tab -->
                <x-shop::tabs.item
                    id="reviews-tab"
                    :title="trans('shop::app.products.view.review')"
                    :is-selected="false"
                >
                    <div class="py-8">
                        @include('shop::products.view.reviews')
                    </div>
                </x-shop::tabs.item>
            </x-shop::tabs>
        </div>
    </div>

    <!-- Related & Cross-selling Products Carousel -->
    <v-product-associations></v-product-associations>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-template"
        >
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="is_buy_now" v-model="is_buy_now">

                    <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 items-start">
                        <!-- Left Column: Gallery Media (7 cols) -->
                        <div class="lg:col-span-7">
                            @include('shop::products.view.gallery')
                        </div>

                        <!-- Right Column: Sticky Product Buy Box (5 cols) -->
                        <div class="lg:col-span-5 lg:sticky lg:top-28">
                            <!-- Ratings Row -->
                            @if ($totalRatings)
                                <div
                                    class="mb-3 flex items-center gap-2 cursor-pointer"
                                    role="button"
                                    tabindex="0"
                                    @click="scrollToReview"
                                >
                                    <x-shop::products.ratings
                                        class="text-xs"
                                        :average="$avgRatings"
                                        :total="$totalRatings"
                                        ::rating="true"
                                    />
                                    <span class="text-xs text-[#71717a] hover:underline">({{ $totalRatings }} @lang('beauty::app.products.opinions'))</span>
                                </div>
                            @endif

                            <!-- Product Title -->
                            <h1 class="font-editorial text-3xl font-bold tracking-tight text-[#18181b] sm:text-4xl" v-pre>
                                {{ $product->name }}
                            </h1>

                            <!-- Price Display -->
                            <div class="mt-4 flex items-baseline gap-3">
                                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-[#18181b]">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </div>

                                @if (\Webkul\Tax\Facades\Tax::isInclusiveTaxProductPrices())
                                    <span class="text-xs text-[#71717a]">
                                        (@lang('shop::app.products.view.tax-inclusive'))
                                    </span>
                                @endif
                            </div>

                            <!-- Stock Status Indicator -->
                            <div class="mt-3 flex items-center gap-2">
                                @if ($product->isSaleable(1))
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-medium text-emerald-700">En stock listo para despacho</span>
                                @else
                                    <span class="inline-flex h-2 w-2 rounded-full bg-rose-500"></span>
                                    <span class="text-xs font-medium text-rose-700">Agotado temporalmente</span>
                                @endif
                            </div>

                            <!-- Short Description / Highlights -->
                            @if ($product->short_description)
                                <div class="mt-4 text-sm text-[#52525b] leading-relaxed">
                                    {!! $product->short_description !!}
                                </div>
                            @endif

                            <!-- Configurable Variant Swatches (Colors / Sizes) -->
                            <div class="mt-6">
                                @include('shop::products.view.types.simple')
                                @include('shop::products.view.types.configurable')
                                @include('shop::products.view.types.grouped')
                                @include('shop::products.view.types.bundle')
                                @include('shop::products.view.types.downloadable')
                                @include('shop::products.view.types.booking')
                            </div>

                            <!-- Quantity & Action Buttons -->
                            <div class="mt-8 space-y-4">
                                <div class="flex items-center gap-4">
                                    <!-- Quantity Selector -->
                                    <x-shop::quantity-changer
                                        name="quantity"
                                        :value="1"
                                    />

                                    <!-- Add to Cart Primary Button -->
                                    <button
                                        type="submit"
                                        id="pdp-primary-add-to-cart"
                                        class="flex-1 rounded-full bg-[#18181b] py-3.5 px-6 text-xs font-semibold uppercase tracking-widest text-white shadow-xs transition-all hover:bg-[#9e8464] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="!{{ $product->isSaleable(1) ? 'true' : 'false' }} || isStoring.addToCart"
                                        @click="is_buy_now = 0"
                                    >
                                        <span v-if="isStoring.addToCart">@lang('beauty::app.products.adding-to-cart')</span>
                                        <span v-else>@lang('shop::app.products.view.add-to-cart')</span>
                                    </button>

                                    <!-- Wishlist Button -->
                                    @if (core()->getConfigData('customer.settings.wishlist.wishlist_option'))
                                        <button
                                            type="button"
                                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-[#ede8dc] bg-[#fdfbf7] text-lg text-[#18181b] transition-all hover:border-[#9e8464] hover:text-[#9e8464]"
                                            :class="isWishlist ? 'text-red-500 border-red-200 bg-red-50/50' : ''"
                                            @click="addToWishlist"
                                            aria-label="@lang('shop::app.products.view.add-to-wishlist')"
                                        >
                                            <span :class="isWishlist ? 'icon-heart-fill' : 'icon-heart'"></span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Buy Now Button -->
                                @if (core()->getConfigData('catalog.products.product_view_page.buy_now_button_display'))
                                    <button
                                        type="submit"
                                        class="w-full rounded-full border border-[#18181b] bg-white py-3 text-xs font-semibold uppercase tracking-widest text-[#18181b] transition-all hover:bg-[#18181b] hover:text-white disabled:cursor-not-allowed disabled:opacity-50"
                                        :disabled="!{{ $product->isSaleable(1) ? 'true' : 'false' }} || isStoring.buyNow"
                                        @click="is_buy_now = 1"
                                    >
                                        <span v-if="isStoring.buyNow">@lang('beauty::app.products.processing')</span>
                                        <span v-else>@lang('shop::app.products.view.buy-now')</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Trust Badges List -->
                            <div class="mt-8 border-t border-[#ede8dc] pt-6">
                                <div class="grid grid-cols-2 gap-3.5 text-xs text-[#52525b]">
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#ede8dc]/60 text-xs font-bold text-[#9e8464]">✓</span>
                                        <span class="font-medium text-[#18181b]">@lang('beauty::app.services.pro-grade')</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#ede8dc]/60 text-xs font-bold text-[#9e8464]">✓</span>
                                        <span class="font-medium text-[#18181b]">@lang('beauty::app.services.cruelty-free')</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#ede8dc]/60 text-xs font-bold text-[#9e8464]">✓</span>
                                        <span class="font-medium text-[#18181b]">@lang('beauty::app.services.safe-packaging')</span>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#ede8dc]/60 text-xs font-bold text-[#9e8464]">✓</span>
                                        <span class="font-medium text-[#18181b]">@lang('beauty::app.services.support')</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Mobile Buy Bar -->
                    <div
                        id="pdp-sticky-mobile-bar"
                        class="fixed bottom-0 inset-x-0 z-40 border-t border-[#ede8dc] bg-[#fdfbf7]/95 px-4 py-3 shadow-xl backdrop-blur-md transition-all duration-300 translate-y-full opacity-0 pointer-events-none lg:hidden"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-medium text-[#18181b]" v-pre>{{ $product->name }}</p>
                                <div class="text-sm font-bold text-[#18181b]">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </div>
                            </div>

                            <button
                                type="submit"
                                class="shrink-0 rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#d4a373]"
                                :disabled="!{{ $product->isSaleable(1) ? 'true' : 'false' }} || isStoring.addToCart"
                                @click="is_buy_now = 0"
                            >
                                <span v-if="isStoring.addToCart">...</span>
                                <span v-else>@lang('shop::app.products.view.add-to-cart')</span>
                            </button>
                        </div>
                    </div>
                </form>
            </x-shop::form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        isWishlist: false,
                        isCustomer: Boolean("{{ auth()->guard('customer')->check() }}"),
                        is_buy_now: 0,
                        isStoring: {
                            addToCart: false,
                            buyNow: false,
                        },
                    };
                },

                mounted() {
                    this.checkWishlistStatus();
                    this.initStickyMobileBar();
                },

                methods: {
                    initStickyMobileBar() {
                        const primaryBtn = document.getElementById('pdp-primary-add-to-cart');
                        const stickyBar = document.getElementById('pdp-sticky-mobile-bar');
                        if (! primaryBtn || ! stickyBar) return;

                        const observer = new IntersectionObserver(([entry]) => {
                            if (! entry.isIntersecting) {
                                stickyBar.classList.remove('translate-y-full', 'opacity-0', 'pointer-events-none');
                                stickyBar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
                            } else {
                                stickyBar.classList.add('translate-y-full', 'opacity-0', 'pointer-events-none');
                                stickyBar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
                            }
                        }, { threshold: 0.1 });

                        observer.observe(primaryBtn);
                    },
                    checkWishlistStatus() {
                        if (this.isCustomer) {
                            this.$axios.get('{{ route('shop.api.customers.account.wishlist.index') }}')
                                .then(response => {
                                    const wishlistItems = response.data.data || [];
                                    this.isWishlist = Boolean(wishlistItems.find(item => item.product.id == "{{ $product->id }}")?.product?.is_wishlist);
                                })
                                .catch(() => {});
                        }
                    },
                    addToCart(params) {
                        const isBuyNow = this.is_buy_now;

                        if (isBuyNow) {
                            this.isStoring.buyNow = true;
                        } else {
                            this.isStoring.addToCart = true;
                        }

                        let formData = new FormData(this.$refs.formData);

                        this.$axios.post('{{ route('shop.api.checkout.cart.store') }}', formData)
                            .then((response) => {
                                if (response.data.data) {
                                    if (isBuyNow) {
                                        window.location.href = '{{ route('shop.checkout.onepage.index') }}';
                                        return;
                                    }

                                    this.$emitter.emit('update-mini-cart', response.data.data);
                                    window.beautyEmitter?.emit('cart:updated', response.data.data);
                                    window.beautyEmitter?.emit('cart:open');
                                    this.$emitter.emit('cart:open');

                                    this.$emitter.emit('add-flash', {
                                        type: 'success',
                                        message: response.data.message,
                                    });
                                } else {
                                    this.$emitter.emit('add-flash', {
                                        type: 'warning',
                                        message: response.data.message,
                                    });
                                }

                                this.isStoring.addToCart = false;
                                this.isStoring.buyNow = false;
                            })
                            .catch((error) => {
                                this.isStoring.addToCart = false;
                                this.isStoring.buyNow = false;

                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response.data.message,
                                });
                            });
                    },

                    addToWishlist() {
                        this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                            product_id: "{{ $product->id }}",
                        })
                        .then((response) => {
                            this.isWishlist = ! this.isWishlist;

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message,
                            });
                        })
                        .catch(() => {
                            window.location.href = '{{ route('shop.customer.session.index') }}';
                        });
                    },

                    scrollToReview() {
                        let reviewTab = document.getElementById('reviews-tab');
                        if (reviewTab) {
                            reviewTab.scrollIntoView({ behavior: 'smooth' });
                            reviewTab.click();
                        }
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts>
