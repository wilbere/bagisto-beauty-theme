{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.before') !!}

<v-topbar>
    <!-- Shimmer Effect -->
    <div class="flex w-full items-center justify-between px-8 py-2 lg:px-12">
        <!-- Currencies -->
        <div class="flex w-24 items-center gap-2 py-1">
            <div class="shimmer h-4 w-12 rounded-sm" role="presentation"></div>
            <div class="shimmer h-4 w-4 rounded-sm" role="presentation"></div>
        </div>

        <!-- Offers -->
        <div class="shimmer h-4 w-64 rounded-sm py-1" role="presentation"></div>

        <!-- Locales -->
        <div class="flex w-28 items-center justify-end gap-2 py-1">
            <div class="shimmer h-4 w-4 rounded-xs" role="presentation"></div>
            <div class="shimmer h-4 w-16 rounded-sm" role="presentation"></div>
            <div class="shimmer h-4 w-4 rounded-sm" role="presentation"></div>
        </div>
    </div>
</v-topbar>

{!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-topbar-template"
    >
        <div class="flex w-full items-center justify-between px-8 py-1 lg:px-12">
            <!-- Left: Currency Switcher & Rate Badge -->
            <div class="flex items-center gap-3">
                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.currency_switcher.before') !!}

                <!-- Currency Switcher -->
                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'left' : 'right' }}">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="flex cursor-pointer items-center gap-1.5 py-1.5 text-xs font-medium text-[#62503d] transition-colors hover:text-[#18181b]"
                            @click="currencyToggler = ! currencyToggler"
                            aria-label="Seleccionar moneda"
                        >
                            <span v-pre>
                                {{ core()->getCurrentCurrency()->symbol . ' ' . core()->getCurrentCurrencyCode() }}
                            </span>

                            <span
                                class="text-sm"
                                :class="{'icon-arrow-up': currencyToggler, 'icon-arrow-down': ! currencyToggler}"
                                role="presentation"
                            >
                            </span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="journal-scroll max-h-125 p-0! rounded-lg shadow-lg border border-[#ede8dc] bg-[#fdfbf7]">
                        <v-currency-switcher></v-currency-switcher>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.currency_switcher.after') !!}
            </div>

            <!-- Center: Header Promo Offer -->
            <div class="text-center text-xs font-normal text-[#7f684e]">
                <span v-pre>{{ core()->getConfigData('general.content.header_offer.title') }}</span>
                
                @if(core()->getConfigData('general.content.header_offer.redirection_title'))
                    <a 
                        href="{{ core()->getConfigData('general.content.header_offer.redirection_link') ?: route('shop.home.index') }}" 
                        class="ml-1 font-semibold text-[#18181b] underline hover:text-[#9e8464] transition-colors"
                    >
                        {{ core()->getConfigData('general.content.header_offer.redirection_title') }}
                    </a>
                @endif
            </div>

            <!-- Right: Locales Switcher -->
            <div class="flex items-center gap-3">
                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.locale_switcher.before') !!}

                <!-- Locales Switcher -->
                <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                    <x-slot:toggle>
                        <!-- Dropdown Toggler -->
                        <button
                            type="button"
                            class="flex cursor-pointer items-center gap-1.5 py-1.5 text-xs font-medium text-[#62503d] transition-colors hover:text-[#18181b]"
                            @click="localeToggler = ! localeToggler"
                            aria-label="Seleccionar idioma"
                        >
                            <img
                                src="{{ ! empty(core()->getCurrentLocale()->logo_url)
                                        ? core()->getCurrentLocale()->logo_url
                                        : bagisto_asset('images/default-language.svg')
                                    }}"
                                class="h-3 w-auto rounded-xs object-cover"
                                alt="@lang('shop::app.components.layouts.header.desktop.top.default-locale')"
                                width="20"
                                height="14"
                            />
                            
                            <span v-pre>
                                {{ core()->getCurrentChannel()->locales()->orderBy('name')->where('code', app()->getLocale())->value('name') ?? core()->getCurrentLocale()->name }}
                            </span>

                            <span
                                class="text-sm"
                                :class="{'icon-arrow-up': localeToggler, 'icon-arrow-down': ! localeToggler}"
                                role="presentation"
                            ></span>
                        </button>
                    </x-slot>
                
                    <!-- Dropdown Content -->
                    <x-slot:content class="journal-scroll max-h-125 p-0! rounded-lg shadow-lg border border-[#ede8dc] bg-[#fdfbf7]">
                        <v-locale-switcher></v-locale-switcher>
                    </x-slot>
                </x-shop::dropdown>

                {!! view_render_event('bagisto.shop.components.layouts.header.desktop.top.locale_switcher.after') !!}
            </div>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-currency-switcher-template"
    >
        <div class="my-1 grid gap-0.5 overflow-auto max-md:my-0 sm:max-h-125 min-w-[130px]">
            <button
                type="button"
                class="w-full cursor-pointer px-4 py-2 text-xs font-medium text-[#18181b] hover:bg-[#ede8dc]/60 focus-visible:bg-[#ede8dc]/60 focus-visible:outline-hidden ltr:text-left rtl:text-right transition-colors"
                v-for="currency in currencies"
                :key="currency.code"
                :class="{'bg-[#ede8dc]/80 font-semibold text-[#9e8464]': currency.code == '{{ core()->getCurrentCurrencyCode() }}'}"
                @click="change(currency)"
            >
                @{{ currency.symbol + ' ' + currency.code }}
            </button>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-locale-switcher-template"
    >
        <div class="my-1 grid gap-0.5 overflow-auto max-md:my-0 sm:max-h-125 min-w-[140px]">
            <button
                type="button"
                class="flex w-full cursor-pointer items-center gap-2.5 px-4 py-2 text-xs font-medium text-[#18181b] hover:bg-[#ede8dc]/60 focus-visible:bg-[#ede8dc]/60 focus-visible:outline-hidden ltr:text-left rtl:text-right transition-colors"
                v-for="locale in locales"
                :key="locale.code"
                :class="{'bg-[#ede8dc]/80 font-semibold text-[#9e8464]': locale.code == '{{ app()->getLocale() }}'}"
                @click="change(locale)"                  
            >
                <img
                    :src="locale.logo_url || '{{ bagisto_asset('images/default-language.svg') }}'"
                    class="h-3 w-auto rounded-xs object-cover"
                    width="20"
                    height="14"
                />

                @{{ locale.name }}
            </button>
        </div>
    </script>

    <script type="module">
        app.component('v-topbar', {
            template: '#v-topbar-template',

            data() {
                return {
                    localeToggler: false,
                    currencyToggler: false,
                };
            },
        });

        app.component('v-currency-switcher', {
            template: '#v-currency-switcher-template',

            data() {
                return {
                    currencies: @json(core()->getCurrentChannel()->currencies),
                };
            },

            methods: {
                change(currency) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('currency', currency.code);
                    window.location.href = url.href;
                }
            }
        });

        app.component('v-locale-switcher', {
            template: '#v-locale-switcher-template',

            data() {
                return {
                    locales: @json(core()->getCurrentChannel()->locales()->orderBy('name')->get()),
                };
            },

            methods: {
                change(locale) {
                    let url = new URL(window.location.href);
                    url.searchParams.set('locale', locale.code);
                    window.location.href = url.href;
                }
            }
        });
    </script>
@endPushOnce
