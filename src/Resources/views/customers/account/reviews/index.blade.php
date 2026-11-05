<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.reviews.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="reviews" />
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
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] md:hidden shadow-xs transition-colors"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                </a>

                <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                    @lang('shop::app.customers.account.reviews.title')
                </h1>
            </div>
        </div>

        <!-- Reviews Vue Component -->
        <v-product-reviews>
            <!-- Reviews Shimmer Effect -->
            <x-shop::shimmer.customers.account.reviews :count="4" />
        </v-product-reviews>
    </div>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-product-reviews-template"
        >
            <div>
                <!-- Reviews Shimmer Effect -->
                <template v-if="isLoading">
                    <x-shop::shimmer.customers.account.reviews :count="4" />
                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.before', ['reviews' => $reviews]) !!}

                <!-- Reviews Information -->
                <template v-else>
                    @if (! $reviews->isEmpty())
                        <!-- Review Information -->
                        <div class="mt-6 flex flex-col gap-4">
                            @foreach($reviews as $review)
                                @php
                                    $productBaseImage = product_image()->getProductBaseImage($review->product);
                                @endphp

                                <a
                                    href="{{ route('shop.product_or_category.index', $review->product->url_key) }}"
                                    id="{{ $review->product_id }}"
                                    aria-label="{{ $review->title }}"
                                    class="block"
                                >
                                    <div class="rounded-2xl border border-[#ede8dc] bg-white p-5 sm:p-6 shadow-xs transition-all hover:border-[#9e8464] flex flex-col sm:flex-row gap-5 items-start">
                                        {!! view_render_event('bagisto.shop.customers.account.reviews.image.before', ['reviews' => $reviews]) !!}

                                        <x-shop::media.images.lazy
                                            class="h-20 w-20 sm:h-24 sm:w-24 shrink-0 rounded-xl object-cover border border-[#ede8dc]"
                                            src="{{ $productBaseImage['medium_image_url'] }}"
                                            alt="{{ $productBaseImage['alt'] }}"
                                        />

                                        {!! view_render_event('bagisto.shop.customers.account.reviews.image.after', ['reviews' => $reviews]) !!}

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-start justify-between gap-2">
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.before', ['reviews' => $reviews]) !!}

                                                <h3 class="font-serif text-lg font-bold text-[#18181b]" v-pre>
                                                    {{ $review->title }}
                                                </h3>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.title.after', ['reviews' => $reviews]) !!}
        
                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.before', ['reviews' => $reviews]) !!}

                                                <div class="flex items-center gap-0.5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span class="icon-star-fill text-lg {{ $review->rating >= $i ? 'text-[#9e8464]' : 'text-[#ede8dc]' }}"></span>
                                                    @endfor
                                                </div>

                                                {!! view_render_event('bagisto.shop.customers.account.reviews.rating.after', ['reviews' => $reviews]) !!}
                                            </div>
        
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.before', ['reviews' => $reviews]) !!}

                                            <p class="mt-1 text-xs font-normal text-[#71717a]" v-pre>
                                                {{ $review->created_at }}
                                            </p>
        
                                            {!! view_render_event('bagisto.shop.customers.account.reviews.created_at.after', ['reviews' => $reviews]) !!}

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.before', ['reviews' => $reviews]) !!}

                                            <p class="mt-3 text-sm leading-relaxed text-[#52525b]" v-pre>
                                                {{ $review->comment }}
                                            </p>

                                            {!! view_render_event('bagisto.shop.customers.account.reviews.comment.after', ['reviews' => $reviews]) !!}
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $reviews->links() }}
                            </div>
                        </div>
                    @else
                        <!-- Review Empty Page -->
                        <div class="m-auto grid w-full place-content-center items-center justify-items-center py-24 text-center">
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fdfbf7] border border-[#ede8dc] text-[#9e8464] mb-4">
                                <span class="icon-star-fill text-3xl"></span>
                            </div>

                            <h3 class="font-serif text-xl font-bold text-[#18181b]">
                                @lang('shop::app.customers.account.reviews.empty-review')
                            </h3>

                            <p class="mt-1 text-sm text-[#71717a] max-w-sm">
                                Aún no has escrito ninguna reseña de tus compras.
                            </p>
                        </div>
                    @endif
                </template>

                {!! view_render_event('bagisto.shop.customers.account.reviews.list.after', ['reviews' => $reviews]) !!}

            </div>
        </script>

        <script type="module">
            app.component("v-product-reviews", {
                template: '#v-product-reviews-template',

                data() {
                    return {
                        isLoading: true,
                    };
                },

                mounted() {
                    this.get();
                },

                methods: {
                    get() {
                        this.$axios.get("{{ route('shop.customers.account.reviews.index') }}")
                            .then(response => {
                                this.isLoading = false;
                            })
                            .catch(error => {});
                    },
                },
            });
        </script>
    @endpushOnce
</x-shop::layouts.account>
