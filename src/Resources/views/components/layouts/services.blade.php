{!! view_render_event('bagisto.shop.layout.features.before') !!}

@inject('sectionRepository', \Webkul\Theme\Repositories\SectionRepository::class)

@php
    $channel = core()->getCurrentChannel();

    $sections = $sectionRepository->findAllOfType(
        \Webkul\Theme\Enums\SectionTypeEnum::SERVICES_CONTENT->value,
        $channel->id,
        $channel->theme,
        app()->getLocale()
    );
@endphp

@if ($sections->count())
    @foreach ($sections as $section)
        @continue (empty($section->options['services']))

        <div
            class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8"
            v-pre
            @if ($sectionRepository->isPreviewing())
                data-section-id="{{ $section->id }}"
                data-section-name="{{ $section->name }}"
            @endif
        >
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($section->options['services'] as $service)
                    <div class="flex flex-col items-center text-center p-6 rounded-2xl border border-[#eae5db]/60 bg-white shadow-xs transition-shadow hover:shadow-md">
                        <span
                            class="{{ $service['service_icon'] }} mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-[#f7f4ee] text-xl text-[#18181b]"
                            role="presentation"
                        ></span>

                        <h4 class="font-editorial text-base font-semibold text-[#18181b]">
                            {{ $service['title'] }}
                        </h4>

                        <p class="mt-1 text-xs text-[#52525b] leading-relaxed">
                            {{ $service['description'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@else
    {{-- High-end Beauty Guarantees Bar --}}
    <div class="border-y border-[#eae5db]/60 bg-[#f7f5f0]/50 py-10 my-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#d4a373] shadow-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">@lang('beauty::app.services.pro-grade')</h4>
                        <p class="text-xs text-[#71717a]">@lang('beauty::app.services.pro-grade-sub')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#d4a373] shadow-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">@lang('beauty::app.services.safe-packaging')</h4>
                        <p class="text-xs text-[#71717a]">@lang('beauty::app.services.safe-packaging-sub')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#d4a373] shadow-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">@lang('beauty::app.services.fast-dispatch')</h4>
                        <p class="text-xs text-[#71717a]">@lang('beauty::app.services.fast-dispatch-sub')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-[#d4a373] shadow-xs">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#18181b]">@lang('beauty::app.services.secure-payment')</h4>
                        <p class="text-xs text-[#71717a]">@lang('beauty::app.services.secure-payment-sub')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{!! view_render_event('bagisto.shop.layout.features.after') !!}
