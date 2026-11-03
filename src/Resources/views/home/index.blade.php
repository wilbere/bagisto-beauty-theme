@php
    $channel = core()->getCurrentChannel();
@endphp

<!-- SEO Meta Content -->
@push ('meta')
    <meta
        name="title"
        content="{{ $channel->home_seo['meta_title'] ?? '' }}"
    />

    <meta
        name="description"
        content="{{ $channel->home_seo['meta_description'] ?? '' }}"
    />

    <meta
        name="keywords"
        content="{{ $channel->home_seo['meta_keywords'] ?? '' }}"
    />
@endPush

@push('scripts')
    @if(! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <!-- Page Title -->
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? '' }}
    </x-slot>

    @if (count($sections))
        <!-- Dynamic Storefront Sections configured in Admin Appearance -->
        @foreach ($sections as $section)
            @php
                $data = $section->options;

                $marks = ($preview ?? false)
                    && ! $section->getTypeInstance()?->rendersInLayout();
            @endphp

            @if ($marks)
                <div
                    data-section-id="{{ $section->id }}"
                    data-section-name="{{ $section->name }}"
                >
            @endif

            @switch ($section->type)
                @case (\Webkul\Theme\Enums\SectionTypeEnum::IMAGE_CAROUSEL->value)
                    <x-shop::carousel
                        :options="$section->getTypeInstance()?->sanitize((array) $data) ?? $data"
                        aria-label="{{ trans('shop::app.home.index.image-carousel') }}"
                    />
                    @break

                @case (\Webkul\Theme\Enums\SectionTypeEnum::STATIC_CONTENT->value)
                    @if (! empty($data['css']))
                        @push ('styles')
                            <style>
                                {!! bagisto_theme_storage()->resolveMarkup($data['css']) !!}
                            </style>
                        @endpush
                    @endif

                    @if (! empty($data['html']))
                        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                            {!! bagisto_theme_storage()->resolveMarkup($data['html']) !!}
                        </div>
                    @endif
                    @break

                @case (\Webkul\Theme\Enums\SectionTypeEnum::CATEGORY_CAROUSEL->value)
                    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                        <x-shop::categories.carousel
                            :title="$data['title'] ?? ''"
                            :src="route('shop.api.categories.index', array_merge((array) ($data['filters'] ?? []), $catalogQuery ?? []))"
                            :navigation-link="route('shop.home.index')"
                            aria-label="{{ trans('shop::app.home.index.categories-carousel') }}"
                        />
                    </div>
                    @break

                @case (\Webkul\Theme\Enums\SectionTypeEnum::PRODUCT_CAROUSEL->value)
                    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                        <x-shop::products.carousel
                            :title="$data['title'] ?? ''"
                            :src="route('shop.api.products.index', array_merge((array) ($data['filters'] ?? []), $catalogQuery ?? []))"
                            :navigation-link="route('shop.search.index', $data['filters'] ?? [])"
                            aria-label="{{ trans('shop::app.home.index.product-carousel') }}"
                        />
                    </div>
                    @break
            @endswitch

            @if ($marks)
                </div>
            @endif
        @endforeach
    @else
        <!-- Elegant Fallback Hero & Welcome Section when no sections are defined yet -->
        <div class="relative overflow-hidden bg-gradient-to-b from-[#f7f4ee] to-[#fdfbf7] py-20 lg:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="inline-block rounded-full bg-[#d4a373]/20 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-[#7f684e]">
                        @lang('beauty::app.home.season-badge')
                    </span>

                    <h1 class="mt-6 font-editorial text-4xl font-bold tracking-tight text-[#18181b] sm:text-6xl">
                        @lang('beauty::app.home.hero-title')
                    </h1>

                    <p class="mt-6 text-base text-[#52525b] leading-relaxed max-w-2xl mx-auto">
                        @lang('beauty::app.home.hero-description')
                    </p>

                    <div class="mt-10 flex items-center justify-center gap-4">
                        <a
                            href="{{ route('shop.search.index') }}"
                            class="rounded-full bg-[#18181b] px-8 py-3.5 text-xs font-semibold uppercase tracking-wider text-white shadow-xs transition-colors hover:bg-[#d4a373]"
                        >
                            @lang('beauty::app.home.explore-cta')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($preview ?? false)
        @include('shop::home.preview-bridge')
    @endif
</x-shop::layouts>
