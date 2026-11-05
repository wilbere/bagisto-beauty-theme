<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.downloadable-products.name')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="downloadable-products" />
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
                    @lang('shop::app.customers.account.downloadable-products.name')
                </h1>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.before') !!}

        <!-- For Desktop View -->
        <div class="max-md:hidden">
            <x-shop::datagrid
                :src="route('shop.customers.account.downloadable_products.index')"
                :shimmerColumns="5"
            />
        </div>

        <!-- For Mobile View -->
        <div class="hidden max-md:block">
            <x-shop::datagrid :src="route('shop.customers.account.downloadable_products.index')">
                <!-- Datagrid Header -->
                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <div class="hidden"></div>
                </template>

                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-shop::shimmer.customers.account.downloadable-products />
                    </template>

                    <template v-else>
                        <div class="grid gap-4">
                            <template
                                v-for="record in available.records"
                                v-if="available.records.length"
                            >
                                <div class="rounded-2xl border border-[#ede8dc] bg-white p-5 shadow-xs transition-all hover:border-[#9e8464]">
                                    <div class="flex items-start justify-between">
                                        <div class="text-sm font-semibold text-[#18181b]">
                                            <p>@lang('shop::app.customers.account.downloadable-products.orderId'): #@{{ record.increment_id }}</p>

                                            <p class="mt-1 text-xs font-normal text-[#71717a]">
                                                @{{ record.created_at }}
                                            </p>
                                        </div>

                                        <p v-html="record.status"></p>
                                    </div>
            
                                    <div class="mt-4 border-t border-[#ede8dc] pt-3 text-xs font-normal">
                                        <div
                                            class="text-sm font-semibold text-[#9e8464]"
                                            v-html="record.product_name"
                                        >
                                        </div>

                                        <p class="mt-2 text-[#71717a]">
                                            <span>@lang('Remaining Downloads'):</span>
                                            <span class="font-medium text-[#18181b]">@{{ record.remaining_downloads }}</span>
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <template v-else>
                                <p class="text-center text-sm text-[#71717a] py-8">
                                    @{{ available.records.length }} @lang('shop::app.customers.account.downloadable-products.records-found')
                                </p>
                            </template>
                        </div>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.downloadable_products.list.after') !!}
    </div>
</x-shop::layouts.account>
