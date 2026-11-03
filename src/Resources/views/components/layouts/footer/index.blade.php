{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('sectionRepository', \Webkul\Theme\Repositories\SectionRepository::class)

@php
    $channel = core()->getCurrentChannel();

    $section = $sectionRepository->findOneOfType(
        \Webkul\Theme\Enums\SectionTypeEnum::FOOTER_LINKS->value,
        $channel->id,
        $channel->theme,
        app()->getLocale()
    );

    $columns = collect($section?->getTypeInstance()?->sanitize((array) $section->options) ?? [])
        ->map(fn ($links) => array_filter((array) $links, fn ($link) => filled($link['title'] ?? null)))
        ->filter();
@endphp

<footer
    class="mt-16 border-t border-[#eae5db] bg-[#f7f5f0] text-[#18181b]"
    @if ($section && $sectionRepository->isPreviewing())
        data-section-id="{{ $section->id }}"
        data-section-name="{{ $section->name }}"
    @endif
>
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
            <!-- Brand & Newsletter Section (5 cols) -->
            <div class="lg:col-span-5">
                <span class="font-editorial text-2xl font-semibold tracking-tight text-[#18181b]">
                    {{ $channel->name ?? 'Beauty & Nails' }}
                </span>
                
                <p class="mt-3 max-w-sm text-sm text-[#52525b] leading-relaxed">
                    Cosmética profesional, esmaltes de alta pigmentación y productos exclusivos para artistas del nail art y salones de belleza.
                </p>

                {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.before') !!}

                @if (core()->getConfigData('customer.settings.newsletter.subscription'))
                    <div class="mt-8">
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">
                            Únete al club exclusivo
                        </p>
                        <p class="mt-1 text-sm text-[#52525b]">
                            Recibe lanzamientos de temporada, técnicas de manicura y ofertas privadas.
                        </p>

                        <x-shop::form
                            :action="route('shop.subscription.store')"
                            class="mt-4 flex max-w-md gap-x-2"
                        >
                            <x-shop::form.control-group class="mb-0! flex-1">
                                <x-shop::form.control-group.control
                                    type="email"
                                    name="email"
                                    rules="required|email"
                                    placeholder="Ingresa tu correo electrónico"
                                    class="w-full rounded-full border-[#d4a373]/40 bg-white px-5 py-2.5 text-sm placeholder:text-[#a1a1aa] focus:border-[#18181b] focus:ring-[#18181b]"
                                />
                            </x-shop::form.control-group>

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white transition-colors hover:bg-[#3f3f46] focus:outline-hidden"
                            >
                                Suscribirme
                            </button>
                        </x-shop::form>
                    </div>
                @endif

                {!! view_render_event('bagisto.shop.layout.footer.newsletter_subscription.after') !!}
            </div>

            <!-- Footer Dynamic Links (7 cols) -->
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-7">
                @if ($columns->count())
                    @foreach ($columns as $footerLinkSection)
                        <div>
                            <ul class="space-y-3" v-pre>
                                @foreach ($footerLinkSection as $link)
                                    <li>
                                        <a
                                            href="{{ url($link['url']) }}"
                                            class="text-sm text-[#52525b] transition-colors hover:text-[#18181b] hover:underline underline-offset-4"
                                        >
                                            {{ $link['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @else
                    {{-- Default Beauty Fallback Links if section is empty --}}
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">Colecciones</h4>
                        <ul class="mt-4 space-y-2.5 text-sm text-[#52525b]">
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Esmaltes Semipermanentes</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Geles & Acrílicos</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Tratamientos & Cuidado</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Herramientas de Nail Art</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">Atención</h4>
                        <ul class="mt-4 space-y-2.5 text-sm text-[#52525b]">
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Guía de Aplicación</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Preguntas Frecuentes</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Envíos y Seguimiento</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Políticas de Devolución</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-[#71717a]">Nosotros</h4>
                        <ul class="mt-4 space-y-2.5 text-sm text-[#52525b]">
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Filosofía de la Marca</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Para Profesionales</a></li>
                            <li><a href="{{ url('/') }}" class="hover:text-[#18181b]">Contacto</a></li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bottom Bar: Legal & Payment Methods -->
        <div class="mt-16 flex flex-col items-center justify-between gap-4 border-t border-[#eae5db] pt-8 sm:flex-row">
            <p class="text-xs text-[#71717a]">
                &copy; {{ date('Y') }} {{ $channel->name ?? 'Beauty & Nails' }}. Todos los derechos reservados.
            </p>

            <div class="flex items-center gap-x-4 text-xs text-[#71717a]">
                <span>Pago Seguro</span>
                <span>•</span>
                <span>Envíos Nacionales</span>
                <span>•</span>
                <span>Calidad Profesional Garantizada</span>
            </div>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
