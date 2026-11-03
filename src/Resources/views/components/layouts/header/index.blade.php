{!! view_render_event('bagisto.shop.layout.header.before') !!}

@if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1 )
    <div class="border-b border-[#eae5db]/60 bg-[#f7f5f0] text-xs max-lg:hidden">
        <x-shop::layouts.header.desktop.top />
    </div>
@endif

<header class="sticky top-0 z-40 border-b border-[#eae5db]/70 bg-[#fdfbf7]/90 backdrop-blur-md transition-all">
    <v-header-switcher>
        <!-- Desktop Header Shimmer -->
        <div class="flex flex-wrap max-lg:hidden">
            <div class="mx-auto flex h-20 w-full max-w-7xl items-center justify-between px-6 lg:px-8">
                <!-- Left: Logo & Categories -->
                <div class="flex items-center gap-x-10">
                    <span class="shimmer block h-8 w-36 rounded-md" role="presentation"></span>
                    <div class="flex items-center gap-6">
                        <span class="shimmer h-5 w-20 rounded-md" role="presentation"></span>
                        <span class="shimmer h-5 w-20 rounded-md" role="presentation"></span>
                        <span class="shimmer h-5 w-20 rounded-md" role="presentation"></span>
                    </div>
                </div>

                <!-- Right: Search & Actions -->
                <div class="flex items-center gap-x-8">
                    <span class="shimmer block h-10 w-64 rounded-full" role="presentation"></span>
                    <div class="flex items-center gap-x-5">
                        <span class="shimmer h-6 w-6 rounded-full" role="presentation"></span>
                        <span class="shimmer h-6 w-6 rounded-full" role="presentation"></span>
                        <span class="shimmer h-6 w-6 rounded-full" role="presentation"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Header Shimmer -->
        <div class="flex items-center justify-between px-4 py-4 lg:hidden">
            <div class="flex items-center gap-3">
                <span class="shimmer block h-6 w-6 rounded-sm" role="presentation"></span>
                <span class="shimmer block h-7 w-28 rounded-md" role="presentation"></span>
            </div>
            <div class="flex items-center gap-4">
                <span class="shimmer block h-6 w-6 rounded-full" role="presentation"></span>
                <span class="shimmer block h-6 w-6 rounded-full" role="presentation"></span>
            </div>
        </div>
    </v-header-switcher>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-header-switcher-template"
    >
        <div>
            <!-- Desktop Header -->
            <div class="max-lg:hidden">
                <x-shop::layouts.header.desktop.bottom />
            </div>

            <!-- Mobile Header -->
            <div class="lg:hidden">
                <x-shop::layouts.header.mobile />
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-header-switcher', {
            template: '#v-header-switcher-template',
        });
    </script>
@endPushOnce
